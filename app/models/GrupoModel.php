<?php
class GrupoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar grupos con filtros por nivel y grado
    public function listarGrupos($nivel = '', $grado = ''): array {
        $sql = "SELECT 
                    g.id,
                    g.nivel,
                    g.grado,
                    g.letra,
                    c.nombre AS nombre_ciclo,
                    COUNT(DISTINCT aga.alumno_id) AS total_alumnos
                FROM Grupo g
                LEFT JOIN CicloEscolar c ON g.ciclo_escolar_id = c.id
                LEFT JOIN Alumno_Grupo aga ON g.id = aga.grupo_id
                LEFT JOIN Alumno a ON a.id = aga.alumno_id
                WHERE 1=1";

        $params = [];

        if (!empty($nivel)) {
            $sql .= " AND g.nivel = :nivel";
            $params[':nivel'] = $nivel;
        }

        if (!empty($grado)) {
            $sql .= " AND g.grado = :grado";
            $params[':grado'] = $grado;
        }

        $sql .= " GROUP BY g.id, g.nivel, g.grado, g.letra, c.nombre ORDER BY g.nivel, g.grado, g.letra";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registrar grupo
    public function registrarGrupo($nivel, $grado, $letra, $id_ciclo) {
        // Verificar no duplicar grupos
        $verificarsql = "SELECT COUNT(*) FROM Grupo WHERE nivel = :nivel AND grado = :grado AND letra = :letra AND ciclo_escolar_id = :ciclo_escolar_id";
        $verificarStmt = $this->pdo->prepare($verificarsql);
        $verificarStmt->execute([
            ':nivel' => $nivel,
            ':grado' => $grado,
            ':letra' => $letra,
            ':ciclo_escolar_id' => $id_ciclo
        ]);
        
        if ($verificarStmt->fetchColumn() > 0) {
            throw new Exception("El grupo ya existe en el mismo ciclo escolar.");
        }

        // Registrar nuevo grupo
        $insertSql = "INSERT INTO Grupo (nivel, grado, letra, ciclo_escolar_id) VALUES (:nivel, :grado, :letra, :ciclo_escolar_id)";
        $stmt = $this->pdo->prepare($insertSql);
        $stmt->execute([
            ':nivel' => $nivel,
            ':grado' => $grado,
            ':letra' => $letra,
            ':ciclo_escolar_id' => $id_ciclo
        ]);

        // Retornar el ID del grupo recién creado
        return $this->pdo->lastInsertId();
    }

    // Contar alumnos sin grupo
    public function contarAlumnosSinGrupo() {
        $sql = "SELECT COUNT(*) AS total FROM Alumno a 
                WHERE NOT EXISTS (
                    SELECT 1 FROM Alumno_Grupo ag
                    WHERE ag.alumno_id = a.id
                )";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // Contar alumnos sin grupo por nivel y grado
    public function contarAlumnosSinGrupoPorNivelGrado($nivel, $grado) {
        $sql = "SELECT COUNT(*) FROM Alumno a
                WHERE a.nivel = :nivel AND a.grado = :grado
                AND NOT EXISTS (
                    SELECT 1 FROM Alumno_Grupo ag
                    WHERE ag.alumno_id = a.id
                )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nivel' => $nivel,
            ':grado' => $grado
        ]);
        return $stmt->fetchColumn();
    }

    // Listar alumnos sin grupo filtrando por nivel y grado
    public function listarAlumnosSinGrupoPorNivelGrado($nivel, $grado) {
        $sql = "SELECT a.id, a.nombre, a.apellido_p, a.apellido_m
                FROM Alumno a
                WHERE a.nivel = :nivel AND a.grado = :grado
                AND NOT EXISTS (
                    SELECT 1 FROM Alumno_Grupo ag
                    WHERE ag.alumno_id = a.id
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nivel' => $nivel,
            ':grado' => $grado
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener datos de un grupo específico
    public function obtenerGrupoPorId($id_grupo): ?array {
        $sql = "SELECT * FROM Grupo WHERE id_grupo = :id_grupo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_grupo' => $id_grupo]);
        $grupo = $stmt->fetch(PDO::FETCH_ASSOC);
        return $grupo ?: null; // Devuelve null si no encontró el grupo
    }

    // Listar alumnos asignados a un grupo
    public function listarAlumnosAsignados($id_grupo) {
        $sql = "SELECT a.id_alumno, a.nombre, a.apellido_p, a.apellido_m, a.nivel, a.grado
                FROM Alumno a
                INNER JOIN Alumno_Grupo aga ON a.id = aga.alumno_id
                WHERE aga.grupo_id = :id_grupo
                ORDER BY a.apellido_p, a.apellido_m, a.nombre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_grupo' => $id_grupo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Quitar alumnos del grupo
    public function eliminarAlumnosDeGrupo($id_grupo, array $alumnos_ids): void {
        $sql = "DELETE FROM Alumno_Grupo 
                WHERE grupo_id = :id_grupo AND alumno_id = :id_alumno";
        $stmt = $this->pdo->prepare($sql);
        foreach ($alumnos_ids as $id_alumno) {
            $stmt->execute([
                ':id_grupo' => $id_grupo,
                ':id_alumno' => $id_alumno
            ]);
        }
    }

// Asignar nuevos alumnos al grupo (evita duplicados correctamente)
    public function asignarAlumnosAGrupo($id_grupo, array $alumnos, $id_ciclo): void {
        foreach ($alumnos as $id_alumno) {
            // Verificar si ya existe asignación
            $check = $this->pdo->prepare("SELECT 1 FROM Alumno_Grupo WHERE grupo_id = :grupo_id AND alumno_id = :alumno_id");
            $check->execute([
                ':grupo_id' => $id_grupo,
                ':alumno_id' => $id_alumno
            ]);

            if (!$check->fetch()) {
                $stmt = $this->pdo->prepare("INSERT INTO Alumno_Grupo (alumno_id, grupo_id) VALUES (:alumno_id, :grupo_id)");
                $stmt->execute([
                    ':alumno_id' => $id_alumno,
                    ':grupo_id' => $id_grupo
                ]);
            }
        }
    }
}

class CicloEscolarModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar ciclos escolares
    public function listarCiclosEscolares() {
        $sql = "SELECT id, nombre FROM CicloEscolar ORDER BY fecha_inicio DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo ciclo escolar ejecutando procedimiento SQL
    public function crearCicloEscolar() {
        $sql = "SELECT crear_ciclo_escolar()";
        return $this->pdo->query($sql)->fetchColumn();
    }
        
}

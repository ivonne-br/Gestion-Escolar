<?php
class GrupoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar grupos con filtros por nivel y grado
    public function listarGrupos($nivel = '', $grado = ''): array {
        $sql = "SELECT 
                    g.id_grupo,
                    g.nivel,
                    g.grado,
                    g.letra,
                    c.nombre_ciclo AS nombre_ciclo,
                    COUNT(DISTINCT aga.id_alumno) AS total_alumnos
                FROM Grupo g
                LEFT JOIN CicloEscolar c ON g.id_ciclo = c.id_ciclo
                LEFT JOIN AsignacionGrupoAlumno aga ON g.id_grupo = aga.id_grupo
                LEFT JOIN Alumno a ON a.id_alumno = aga.id_alumno
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

        $sql .= " GROUP BY g.id_grupo, g.nivel, g.grado, g.letra, c.nombre_ciclo ORDER BY g.nivel, g.grado, g.letra";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registrar grupo
    public function registrarGrupo($nivel, $grado, $letra, $id_ciclo) {
        // Verificar no duplicar grupos
        $verificarsql = "SELECT COUNT(*) FROM Grupo WHERE nivel = :nivel AND grado = :grado AND letra = :letra AND id_ciclo = :id_ciclo";
        $verificarStmt = $this->pdo->prepare($verificarsql);
        $verificarStmt->execute([
            ':nivel' => $nivel,
            ':grado' => $grado,
            ':letra' => $letra,
            ':id_ciclo' => $id_ciclo
        ]);
        
        if ($verificarStmt->fetchColumn() > 0) {
            throw new Exception("El grupo ya existe en el mismo ciclo escolar.");
        }

        // Registrar nuevo grupo
        $insertSql = "INSERT INTO Grupo (nivel, grado, letra, id_ciclo) VALUES (:nivel, :grado, :letra, :id_ciclo)";
        $stmt = $this->pdo->prepare($insertSql);
        $stmt->execute([
            ':nivel' => $nivel,
            ':grado' => $grado,
            ':letra' => $letra,
            ':id_ciclo' => $id_ciclo
        ]);

        // Retornar el ID del grupo recién creado
        return $this->pdo->lastInsertId();
    }

    // Contar alumnos sin grupo
    public function contarAlumnosSinGrupo() {
        $sql = "SELECT COUNT(*) AS total FROM Alumno a 
                WHERE NOT EXISTS (
                    SELECT 1 FROM AsignacionGrupoAlumno ag
                    WHERE ag.id_alumno = a.id_alumno
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
                    SELECT 1 FROM AsignacionGrupoAlumno ag
                    WHERE ag.id_alumno = a.id_alumno
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
        $sql = "SELECT a.id_alumno, a.nombre, a.apellido_p, a.apellido_m
                FROM Alumno a
                WHERE a.nivel = :nivel AND a.grado = :grado
                AND NOT EXISTS (
                    SELECT 1 FROM AsignacionGrupoAlumno ag
                    WHERE ag.id_alumno = a.id_alumno
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
                INNER JOIN AsignacionGrupoAlumno aga ON a.id_alumno = aga.id_alumno
                WHERE aga.id_grupo = :id_grupo
                ORDER BY a.apellido_p, a.apellido_m, a.nombre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_grupo' => $id_grupo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Quitar alumnos del grupo
    public function eliminarAlumnosDeGrupo($id_grupo, array $alumnos_ids): void {
        $sql = "DELETE FROM AsignacionGrupoAlumno 
                WHERE id_grupo = :id_grupo AND id_alumno = :id_alumno";
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
            $check = $this->pdo->prepare("SELECT 1 FROM AsignacionGrupoAlumno WHERE id_grupo = :id_grupo AND id_alumno = :id_alumno");
            $check->execute([
                ':id_grupo' => $id_grupo,
                ':id_alumno' => $id_alumno
            ]);

            if (!$check->fetch()) {
                // Insertar si no existe
                $stmt = $this->pdo->prepare("INSERT INTO AsignacionGrupoAlumno (id_grupo, id_alumno, id_ciclo) VALUES (:id_grupo, :id_alumno, :id_ciclo)");
                $stmt->execute([
                    ':id_grupo' => $id_grupo,
                    ':id_alumno' => $id_alumno,
                    ':id_ciclo' => $id_ciclo
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
        $sql = "SELECT id_ciclo, nombre_ciclo FROM CicloEscolar ORDER BY fecha_inicio DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo ciclo escolar ejecutando procedimiento SQL
    public function crearCicloEscolar() {
        $sql = "SELECT crear_ciclo_escolar()";
        return $this->pdo->query($sql)->fetchColumn();
    }
        
}

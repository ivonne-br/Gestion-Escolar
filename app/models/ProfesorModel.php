<?php
class ProfesorModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar profesores con asignaciones a grupos y materias, con filtros por nivel, materia y estado
    public function listarProfesoresConAsignaciones($nivel = '', $materia = '', $estado = '') {
        $sql = "
            SELECT
                p.id_profesor,
                p.nombre,
                p.apellido_p,
                p.apellido_m,
                STRING_AGG(DISTINCT m.nombre, ', ') AS materias,
                STRING_AGG(DISTINCT g.nivel || ' ' || g.grado || g.letra, ', ') AS grupos
            FROM Profesor p
            LEFT JOIN ProfesorGrupoMateria pgm ON pgm.id_profesor = p.id_profesor
            LEFT JOIN Materia m ON m.id_materia = pgm.id_materia
            LEFT JOIN Grupo g ON g.id_grupo = pgm.id_grupo
            WHERE 1=1
        ";

        $params = [];

        if (!empty($nivel)) {
            $sql .= " AND g.nivel = :nivel";
            $params[':nivel'] = $nivel;
        }

        if (!empty($materia)) {
            $sql .= " AND m.nombre ILIKE :materia";
            $params[':materia'] = "%$materia%";
        }

        // Estado dinámico según si tiene grupo
        $sql .= "
            GROUP BY p.id_profesor, p.nombre, p.apellido_p, p.apellido_m
            ORDER BY p.apellido_p, p.apellido_m, p.nombre
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agregar campo "estado" manualmente
        foreach ($results as &$row) {
            $row['estado'] = !empty($row['grupos']) ? 'Activo' : 'Inactivo';
        }

        return $results;
    }

    // Detalle de un profesor por ID
    public function obtenerPorId($id) {
        $sql = "SELECT id_profesor, nombre, apellido_p, apellido_m, correo, especialidad FROM Profesor WHERE id_profesor = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener grupos asignados a un profesor
    public function obtenerGruposPorProfesor($idProfesor) {
    $sql = "
        SELECT 
            g.letra AS nombre,
            g.letra,
            g.grado,
            g.nivel,
            m.nombre AS materia
        FROM ProfesorGrupoMateria pgm
        INNER JOIN Grupo g ON g.id_grupo = pgm.id_grupo
        INNER JOIN Materia m ON m.id_materia = pgm.id_materia
        WHERE pgm.id_profesor = :id_profesor
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_profesor' => $idProfesor]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registrar profesor
    public function registrarProfesor($nombre, $apellido_p, $apellido_m, $especialidad) {
        $sql = "SELECT registrar_profesor(:nombre, :apellido_p, :apellido_m, :especialidad)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido_p' => $apellido_p,
            ':apellido_m' => $apellido_m,
            ':especialidad' => $especialidad  
        ]);
        return $stmt->fetchColumn();
    }

    // Eliminar profesor
    public function eliminar($id) {
        $sql = "DELETE FROM Profesor WHERE id_profesor = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Actualizar profesor < modificar
    public function actualizar($id, $nombre, $ap, $am) {
        $correo = strtolower($ap . '.' . $am . '.' . $id . '@cal.mx');

        $sql = "UPDATE Profesor
                SET nombre = :nombre,
                    apellido_p = :ap,
                    apellido_m = :am,
                    correo = :correo
                WHERE id_profesor = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':ap' => $ap,
            ':am' => $am,
            ':correo' => $correo,
            ':id' => $id
        ]);
    }

    public function obtenerGruposYMateriasPorProfesor($idProfesor) {
        $sql = "
            SELECT 
                pgm.id_grupo,
                g.nivel,
                g.grado,
                g.letra AS nombre,
                pgm.id_materia,
                m.nombre AS materia
            FROM ProfesorGrupoMateria pgm
            INNER JOIN Grupo g ON g.id_grupo = pgm.id_grupo
            INNER JOIN Materia m ON m.id_materia = pgm.id_materia
            WHERE pgm.id_profesor = :id_profesor
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_profesor' => $idProfesor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTareasPorProfesor($idProfesor) {
        $sql = "
            SELECT t.id_tarea, t.nombre, t.fecha_entrega
            FROM Tarea t
            JOIN ProfesorGrupoMateria pgm ON pgm.id_grupo = t.id_grupo AND pgm.id_materia = t.id_materia
            WHERE pgm.id_profesor = :id_profesor
            ORDER BY t.fecha_entrega DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_profesor' => $idProfesor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener detalles de una tarea por ID
    public function obtenerDetalleTarea($idTarea) {
        $sql = "
            SELECT t.*, m.nombre AS materia_nombre, g.nivel, g.grado, g.letra
            FROM Tarea t
            JOIN Materia m ON t.id_materia = m.id_materia
            JOIN Grupo g ON t.id_grupo = g.id_grupo
            WHERE t.id_tarea = :id_tarea
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_tarea' => $idTarea]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener alumnos con su calificación (si existe) para una tarea
    public function obtenerAlumnosConCalificacion($idTarea, $idGrupo) {
        $sql = "
            SELECT a.id_alumno, a.nombre, a.apellido_p, a.apellido_m,
                ct.calificacion, ct.observaciones
            FROM asignaciongrupoalumno aga
            JOIN alumno a ON aga.id_alumno = a.id_alumno
            LEFT JOIN calificaciontarea ct ON ct.id_alumno = a.id_alumno AND ct.id_tarea = :id_tarea
            WHERE aga.id_grupo = :id_grupo
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_tarea' => $idTarea,
            ':id_grupo' => $idGrupo
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar tarea
    public function actualizarTarea($id_tarea, $nombre, $descripcion, $fecha_entrega) {
        $sql = " UPDATE Tarea
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    fecha_entrega = :fecha_entrega
                WHERE id_tarea = :id_tarea";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_tarea' => $id_tarea,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':fecha_entrega' => $fecha_entrega
        ]);
    }

    // Eliminar tarea
    public function eliminarTarea($id_tarea) {
        $sql = "DELETE FROM Tarea WHERE id_tarea = :id_tarea";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_tarea' => $id_tarea]);
    }

    // Registrar nueva tarea
    public function registrarTarea($nombre, $descripcion, $fecha_entrega, $id_grupo, $id_materia) {
        $sql = "
            INSERT INTO Tarea (nombre, descripcion, fecha_entrega, id_grupo, id_materia)
            VALUES (:nombre, :descripcion, :fecha_entrega, :id_grupo, :id_materia)
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':fecha_entrega' => $fecha_entrega,
            ':id_grupo' => $id_grupo,
            ':id_materia' => $id_materia
        ]);
    }

    public function obtenerNombreAlumno($id_alumno) {
        $sql = "SELECT nombre, apellido_p, apellido_m FROM Alumno WHERE id_alumno = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id_alumno]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerTareasYCalificacionesPorAlumno($id_alumno, $id_materia) {
        $sql = "SELECT t.nombre AS tarea, c.calificacion
                FROM CalificacionTarea c
                JOIN Tarea t ON c.id_tarea = t.id_tarea
                WHERE c.id_alumno = :id_alumno AND t.id_materia = :id_materia";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_alumno' => $id_alumno, ':id_materia' => $id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularPromedioTareas($id_alumno, $id_materia) {
        $sql = "SELECT AVG(c.calificacion) AS promedio
                FROM CalificacionTarea c
                JOIN Tarea t ON c.id_tarea = t.id_tarea
                WHERE c.id_alumno = :id_alumno AND t.id_materia = :id_materia";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_alumno' => $id_alumno, ':id_materia' => $id_materia]);
        return $stmt->fetchColumn();
    }

    public function obtenerAlumnosPorGrupo($id_grupo) {
        $sql = "
            SELECT a.id_alumno, a.nombre, a.apellido_p, a.apellido_m
            FROM AsignacionGrupoAlumno aga
            JOIN Alumno a ON aga.id_alumno = a.id_alumno
            WHERE aga.id_grupo = :id_grupo
            ORDER BY a.apellido_p, a.apellido_m, a.nombre
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_grupo' => $id_grupo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function guardarCalificacionFinal($id_alumno, $id_grupo, $id_materia, $calificacion_final, $observaciones) {
        $sql = "
            INSERT INTO CalificacionFinal (id_alumno, id_grupo, id_materia, calificacion, observaciones)
            VALUES (:id_alumno, :id_grupo, :id_materia, :calificacion, :observaciones)
            ON CONFLICT (id_alumno, id_grupo, id_materia)
            DO UPDATE SET calificacion = EXCLUDED.calificacion, observaciones = EXCLUDED.observaciones
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_alumno' => $id_alumno,
            ':id_grupo' => $id_grupo,
            ':id_materia' => $id_materia,
            ':calificacion' => $calificacion_final,
            ':observaciones' => $observaciones
        ]);
    }

    // Obtener tareas por grupo y materia
    public function obtenerTareasPorGrupoMateria($id_grupo, $id_materia) {
        $sql = "
            SELECT id_tarea, nombre, descripcion, fecha_entrega
            FROM Tarea
            WHERE id_grupo = :id_grupo AND id_materia = :id_materia
            ORDER BY fecha_entrega ASC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_grupo' => $id_grupo,
            ':id_materia' => $id_materia
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registrar calificación de tarea
    public function registrarCalificacion($id_tarea, $id_alumno, $calificacion, $observaciones) {
        $sql = "
            INSERT INTO CalificacionTarea (id_tarea, id_alumno, calificacion, observaciones)
            VALUES (:id_tarea, :id_alumno, :calificacion, :observaciones)
            ON CONFLICT (id_tarea, id_alumno)
            DO UPDATE SET calificacion = EXCLUDED.calificacion, observaciones = EXCLUDED.observaciones
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_tarea' => $id_tarea,
            ':id_alumno' => $id_alumno,
            ':calificacion' => $calificacion,
            ':observaciones' => $observaciones
        ]);
    }


    public function obtenerBoletaPorAlumno($id_alumno) {
        $sql = "SELECT * FROM vista_boleta_alumno WHERE id_alumno = :id_alumno";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_alumno' => $id_alumno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Obtener el ID del ciclo escolar actual (el último insertado)
    public function obtenerCicloActualId() {
        $sql = "SELECT id_ciclo FROM CicloEscolar ORDER BY fecha_inicio DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Obtener calificación final por ciclo y periodo manualmente
    public function obtenerCalificacionFinalPorPeriodo($id_alumno, $id_materia, $id_ciclo, $periodo) {
        $sql = "
            SELECT calif_final
            FROM CalificacionMateria
            WHERE id_alumno = :id_alumno
              AND id_materia = :id_materia
              AND id_ciclo = :id_ciclo
              AND id_periodo = :periodo
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_alumno' => $id_alumno,
            ':id_materia' => $id_materia,
            ':id_ciclo' => $id_ciclo,
            ':periodo' => $periodo
        ]);
        return $stmt->fetchColumn();
    }

    // Calcular promedio final de una materia en un ciclo escolar para un alumno
    public function calcularPromedioFinalPorCiclo($id_alumno, $id_materia, $id_ciclo) {
        $sql = "
            SELECT ROUND(AVG(cm.calif_final), 2) AS promedio
            FROM CalificacionMateria cm
            WHERE cm.id_alumno = :id_alumno
              AND cm.id_materia = :id_materia
              AND cm.id_ciclo = :id_ciclo
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_alumno' => $id_alumno,
            ':id_materia' => $id_materia,
            ':id_ciclo' => $id_ciclo
        ]);
        return $stmt->fetchColumn();
    }

    // Registrar calificación por periodo (incluyendo calificación final si se usa un id_periodo especial como 4)
    public function registrarCalificacionPorPeriodo($id_alumno, $id_materia, $id_ciclo, $id_periodo, $calificacion, $observaciones) {
        $sql = "
            INSERT INTO CalificacionMateria (id_alumno, id_materia, id_ciclo, id_periodo, calif_final, observaciones)
            VALUES (:id_alumno, :id_materia, :id_ciclo, :id_periodo, :calificacion, :observaciones)
            ON CONFLICT (id_alumno, id_materia, id_ciclo, id_periodo)
            DO UPDATE SET calif_final = EXCLUDED.calif_final, observaciones = EXCLUDED.observaciones
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_alumno' => $id_alumno,
            ':id_materia' => $id_materia,
            ':id_ciclo' => $id_ciclo,
            ':id_periodo' => $id_periodo,
            ':calificacion' => $calificacion,
            ':observaciones' => $observaciones
        ]);
    }
}
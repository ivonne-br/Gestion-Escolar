<?php
session_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/ProfesorModel.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: /GestionEscolar/public/login.php");
    exit;
}

$profesorId = $_SESSION['usuario'];
$db = new Database();
$pdo = $db->conectar();
$model = new ProfesorModel($pdo);

$request_uri = $_SERVER['REQUEST_URI'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($request_uri, '/calificar_tarea_detalle') !== false) {
        if (!isset($_GET['id_tarea']) || empty($_GET['id_tarea'])) {
            echo "<h2>⚠️ Falta el parámetro 'id_tarea'.</h2>";
            echo "<a href='/GestionEscolar/public/profesores/calificar_tarea'>⬅ Volver</a>";
            exit;
        }

        $id_tarea = $_GET['id_tarea'];

        $tarea = $model->obtenerDetalleTarea($id_tarea);
        if (!$tarea) {
            echo "<h2>⚠️ Tarea no encontrada.</h2>";
            echo "<a href='/GestionEscolar/public/profesores/calificar_tarea'>⬅ Volver</a>";
            exit;
        }

        $alumnos = $model->obtenerAlumnosConCalificacion($id_tarea, $tarea['id_grupo']);

        $entregados = [];
        $no_entregados = [];
        foreach ($alumnos as $alumno) {
            if ($alumno['calificacion'] !== null) {
                $entregados[] = $alumno;
            } else {
                $no_entregados[] = $alumno;
            }
        }

        // Mostrar la vista de detalle
        require __DIR__ . '/../views/profesores/calificar_tarea_detalle.php';
        exit;

    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($request_uri, '/calificar_tarea') !== false) {
        $sql = "
            SELECT t.id_tarea, t.nombre, t.fecha_entrega,
                   g.nivel, g.grado, g.letra,
                   m.nombre AS materia_nombre
            FROM Tarea t
            JOIN Grupo g ON g.id_grupo = t.id_grupo
            JOIN Materia m ON m.id_materia = t.id_materia
            JOIN ProfesorGrupoMateria pgm ON pgm.id_grupo = g.id_grupo AND pgm.id_materia = m.id_materia
            WHERE pgm.id_profesor = :id_profesor
            ORDER BY t.fecha_entrega DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_profesor' => $profesorId]);
        $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agregar links útiles
        foreach ($tareas as &$tarea) {
            $tarea['link'] = "/GestionEscolar/public/profesores/calificar_tarea_detalle?id_tarea=" . $tarea['id_tarea'];
        }

        require_once __DIR__ . '/../views/profesores/calificar_tarea.php';
        exit;

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($request_uri, '/tareas/calificar') !== false) {
        if (!isset($_POST['id_tarea']) || empty($_POST['id_tarea']) || !isset($_POST['calificacion'])) {
            echo "<p style='color:red;'>Faltan datos para registrar calificaciones.</p>";
            exit;
        }

        $id_tarea = $_POST['id_tarea'];
        $calificaciones = $_POST['calificacion'];
        $observaciones = $_POST['observaciones'] ?? [];

        foreach ($calificaciones as $id_alumno => $calificacion) {
            $obs = $observaciones[$id_alumno] ?? null;

            $stmt = $pdo->prepare("
                INSERT INTO CalificacionTarea (id_tarea, id_alumno, calificacion, observaciones)
                VALUES (:id_tarea, :id_alumno, :calificacion, :observaciones)
                ON CONFLICT (id_tarea, id_alumno)
                DO UPDATE SET calificacion = EXCLUDED.calificacion, observaciones = EXCLUDED.observaciones
            ");

            $stmt->execute([
                ':id_tarea' => $id_tarea,
                ':id_alumno' => $id_alumno,
                ':calificacion' => $calificacion,
                ':observaciones' => $obs
            ]);
        }

        echo "<p style='color:green;'>✅ Calificaciones registradas correctamente.</p>";
        echo "<a href='/GestionEscolar/public/profesores/calificar_tarea_detalle?id_tarea=$id_tarea'>⬅ Volver a la tarea</a>";
        exit;

    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($request_uri, '/tareas/editar') !== false) {
        if (!isset($_GET['id_tarea']) || empty($_GET['id_tarea'])) {
            echo "<h2>⚠️ Falta el parámetro 'id_tarea'.</h2>";
            echo "<a href='/GestionEscolar/public/profesores/calificar_tarea'>⬅ Volver</a>";
            exit;
        }

        $id_tarea = $_GET['id_tarea'];
        $tarea = $model->obtenerDetalleTarea($id_tarea);

        if (!$tarea) {
            echo "<h2>⚠️ Tarea no encontrada.</h2>";
            echo "<a href='/GestionEscolar/public/profesores/calificar_tarea'>⬅ Volver</a>";
            exit;
        }

        require_once __DIR__ . '/../views/profesores/editar_tarea.php';
        exit;

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($request_uri, '/tareas/editar') !== false) {
        if (!isset($_POST['id_tarea'], $_POST['nombre'], $_POST['fecha_entrega'])) {
            echo "<p style='color:red;'>Faltan datos para editar la tarea.</p>";
            exit;
        }

        $id_tarea = $_POST['id_tarea'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? null;
        $fecha_entrega = $_POST['fecha_entrega'];

        $resultado = $model->actualizarTarea($id_tarea, $nombre, $descripcion, $fecha_entrega);
        if ($resultado) {
            echo "<p style='color:green;'>✅ Tarea actualizada correctamente.</p>";
        } else {
            echo "<p style='color:red;'>⚠️ Error al actualizar la tarea.</p>";
        }

        echo "<a href='/GestionEscolar/public/profesores/calificar_tarea'>⬅ Volver a Listado de Tareas</a>";
        exit;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($request_uri, '/tareas/eliminar') !== false) {
        if (!isset($_POST['id_tarea'])) {
            echo "<p style='color:red;'>Falta el ID de la tarea para eliminar.</p>";
            exit;
        }
        
        $id_tarea = $_POST['id_tarea'];
        $resultado = $model->eliminarTarea($id_tarea);
        if ($resultado) {
            echo "<p style='color:green;'>✅ Tarea eliminada correctamente.</p>";
        } else {
            echo "<p style='color:red;'>⚠️ Error al eliminar la tarea.</p>";
        }

        echo "<a href='/GestionEscolar/public/profesores/calificar_tarea'>⬅ Volver a Listado de Tareas</a>";
        exit;

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($request_uri, '/tareas/guardar') !== false) {
        if (!isset($_POST['nombre'], $_POST['descripcion'], $_POST['fecha_entrega'], $_POST['id_grupo'], $_POST['id_materia'])) {
            echo "<p style='color:red;'>Faltan datos para registrar la tarea.</p>";
            exit;
        }

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $id_grupo = $_POST['id_grupo'];
        $id_materia = $_POST['id_materia'];

        $resultado = $model->registrarTarea($nombre, $descripcion, $fecha_entrega, $id_grupo, $id_materia);
        if ($resultado) {
            echo "<p style='color:green;'>✅ Tarea registrada correctamente.</p>";
        } else {
            echo "<p style='color:red;'>⚠️ Error al registrar la tarea.</p>";
        }

        echo "<a href='/GestionEscolar/public/profesores/calificar_tarea'>⬅ Volver a Listado de Tareas</a>";
        exit;
    }
}
catch (PDOException $e) {
    echo "<p style='color:red;'>Error de base de datos: " . $e->getMessage() . "</p>";
    exit;
} catch (Exception $e) {
    echo "<p style='color:red;'>Error inesperado: " . $e->getMessage() . "</p>";
    exit;
}
?>
        
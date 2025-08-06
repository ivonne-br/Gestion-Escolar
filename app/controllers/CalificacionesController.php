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

$request_uri = $_SERVER['REQUEST_URI'] ?? '';

// Mostrar calificaciones de un alumno
if (strpos($request_uri, '/profesores/calificaciones/alumno') !== false &&
    isset($_GET['id_alumno'], $_GET['id_grupo'], $_GET['id_materia'])) {
    
    $id_alumno = $_GET['id_alumno'];
    $id_grupo = $_GET['id_grupo'];
    $id_materia = $_GET['id_materia'];

    $nombreAlumno = $model->obtenerNombreAlumno($id_alumno);
    $tareas = $model->obtenerTareasYCalificacionesPorAlumno($id_alumno, $id_materia);
    $promedio = $model->calcularPromedioTareas($id_alumno, $id_materia);

    $id_ciclo = $model->obtenerCicloActualId(); // fallback method

    $calificacionesPorPeriodo = [
        '1er Periodo' => $model->obtenerCalificacionFinalPorPeriodo($id_alumno, $id_materia, $id_ciclo, 1),
        '2do Periodo' => $model->obtenerCalificacionFinalPorPeriodo($id_alumno, $id_materia, $id_ciclo, 2),
        '3er Periodo' => $model->obtenerCalificacionFinalPorPeriodo($id_alumno, $id_materia, $id_ciclo, 3),
    ];
    $promedioGeneral = $model->calcularPromedioFinalPorCiclo($id_alumno, $id_materia, $id_ciclo);

    $mensajeExito = isset($_GET['exito']) ? "✅ Calificaciones registradas correctamente." : null;

    require_once __DIR__ . '/../views/profesores/alumno_calificaciones.php';
    exit;
}

// Mostrar alumnos del grupo y materia seleccionados
if (strpos($request_uri, '/profesores/calificaciones') !== false &&
    isset($_GET['id_grupo'], $_GET['id_materia'])) {

    $datos = $model->obtenerGruposYMateriasPorProfesor($profesorId);
    $alumnos = $model->obtenerAlumnosPorGrupo($_GET['id_grupo']);

    require __DIR__ . '/../views/profesores/calificaciones_selector.php';
    exit;
}

// Mostrar formulario de selección de grupo y materia
if (strpos($request_uri, '/profesores/calificaciones') !== false) {
    $datos = $model->obtenerGruposYMateriasPorProfesor($profesorId);
    require_once __DIR__ . '/../views/profesores/calificaciones_selector.php';
    exit;
}

// Guardar calificación final con rúbrica
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    strpos($request_uri, '/profesores/calificaciones/final') !== false) {

    $idAlumno = $_POST['id_alumno'] ?? null;
    $idGrupo = $_POST['id_grupo'] ?? null;
    $idMateria = $_POST['id_materia'] ?? null;
    $rubrica = $_POST['rubrica'] ?? null;

    if ($idAlumno && $idGrupo && $idMateria && $rubrica !== null) {
        $idCiclo = $model->obtenerCicloActualId();
        $errores = [];

        foreach ([1, 2, 3] as $periodo) {
            $valor = $_POST["calif_p$periodo"] ?? null;
            if ($valor !== null && $valor !== '') {
                $ok = $model->registrarCalificacionPorPeriodo($idAlumno, $idMateria, $idCiclo, $periodo, $valor, $rubrica);
                if (!$ok) $errores[] = "Periodo $periodo";
            }
        }

        if (isset($_POST['final']) && $_POST['final'] !== '') {
            $model->guardarCalificacionFinal($idAlumno, $idGrupo, $idMateria, $_POST['final'], $rubrica);
        }

        // Redirige a la misma vista del alumno con éxito
        header("Location: /GestionEscolar/public/profesores/calificaciones/alumno?id_alumno=$idAlumno&id_grupo=$idGrupo&id_materia=$idMateria&exito=1");
        exit;
    }
    exit;
}

if (strpos($request_uri, '/profesores/boleta') !== false && isset($_GET['id_alumno'])) {
    $id_alumno = $_GET['id_alumno'];
    $boleta = $model->obtenerBoletaPorAlumno($id_alumno);
    require_once __DIR__ . '/../views/profesores/boleta.php';
    exit;
}

// Si no se reconoce la ruta
http_response_code(404);
echo "Página no encontrada.";

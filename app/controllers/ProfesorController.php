<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/ProfesorModel.php';

$db = new Database();
$pdo = $db->conectar();
$model = new ProfesorModel($pdo);

// Actualizar profesor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_profesor'], $_POST['nombre'])) {
    $id = $_POST['id_profesor'];
    $nombre = $_POST['nombre'];
    $ap = $_POST['apellido_p'];
    $am = $_POST['apellido_m'];

    if ($model->actualizar($id, $nombre, $ap, $am)) {
        echo "<p>✅ Profesor actualizado correctamente.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error al actualizar profesor.</p>";
    }
    echo "<a href='/GestionEscolar/public/profesores/index'>⬅ Volver al listado</a>";
    exit;
}

// Eliminar profesor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_profesor']) && !isset($_POST['nombre'])) {
    $id = $_POST['id_profesor'];
    if ($model->eliminar($id)) {
        header('Location: /GestionEscolar/public/profesores/index');
        exit;
    } else {
        echo "<p style='color:red;'>❌ No se pudo eliminar el profesor.</p>";
        echo "<a href='/GestionEscolar/public/profesores/index'>Volver</a>";
        exit;
    }
}

// Registrar nuevo profesor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_generado = $model->registrarProfesor(
            $_POST['nombre'],
            $_POST['apellido_p'],
            $_POST['apellido_m'],
            $_POST['especialidad']
        );

        echo "<h2>Profesor registrado exitosamente</h2>";
        echo "<p><strong>ID generado:</strong> $id_generado</p>";
        echo "<p>";
        echo "<a href='/GestionEscolar/public/profesores/opciones'><button>⬅ Volver a Profesores</button></a> ";
        echo "<a href='/GestionEscolar/public/profesores/formulario'><button>➕ Agregar Otro Profesor</button></a>";
        echo "</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error al registrar profesor: " . $e->getMessage() . "</p>";
    }
}

// Listar con filtros
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['listar'])) {
    $nivel = $_GET['nivel'] ?? '';
    $materia = $_GET['materia'] ?? '';
    $estado = $_GET['estado'] ?? '';

    $profesores = $model->listarProfesoresConAsignaciones($nivel, $materia, $estado);
    include __DIR__ . '/../views/profesores/index.php';
    exit;
}

/*
// Mostrar grupos asignados al profesor
if ($_GET['r'] === 'profesores/grupos') {
    session_start();
    if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
        header("Location: /GestionEscolar/public/login.php");
        exit;
    }

    $idProfesor = $_SESSION['usuario'];
    $grupos = $model->obtenerGruposPorProfesor($idProfesor);

    include __DIR__ . '/../public/profesores/grupos';
    exit;
}
*/

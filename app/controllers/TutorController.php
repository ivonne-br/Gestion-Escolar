<?php
require_once __DIR__ . '/../models/TutorModel.php';
require_once __DIR__ . '/../../config/database.php';

$db = new Database();
$pdo = $db->conectar();
$tutorModel = new TutorModel($pdo);

// Actualizar tutor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_tutor'], $_POST['nombre'])) {
    $id = $_POST['id_tutor'];
    $nombre = $_POST['nombre'];
    $apellido_p = $_POST['apellido_p'];
    $apellido_m = $_POST['apellido_m'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    if ($tutorModel->actualizar($id, $nombre, $apellido_p, $apellido_m, $correo, $telefono)) {
        echo "<p>✅ Tutor actualizado correctamente.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error al actualiz
        ar tutor.</p>";
    }
    echo "<a href='/GestionEscolar/public/tutores/index'>⬅ Volver al listado</a>";
    exit;
}

// Eliminar tutor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_tutor']) && !isset($_POST['nombre'])) {
    $id = $_POST['id_tutor'];
    if ($tutorModel->eliminar($id)) {
        header('Location: /GestionEscolar/public/tutores/index');
        exit;
    } else {
        echo "<p style='color:red;'>❌ No se pudo eliminar el tutor.</p>";
        echo "<a href='/GestionEscolar/public/tutores/index'>Volver</a>";
        exit;
    }
}

// Registrar nuevo tutor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_generado = $tutorModel->registrar(
            $_POST['nombre'],
            $_POST['apellido_p'],
            $_POST['apellido_m'],
            $_POST['correo'],
            $_POST['telefono']
        );

        echo "<h2>Tutor registrado exitosamente</h2>";
        echo "<p><strong>ID generado:</strong> $id_generado</p>";
        echo "<p>";
        echo "<a href='/GestionEscolar/public/tutores/index'><button>⬅ Volver a Listado Tutores</button></a> ";
        echo "<a href='/GestionEscolar/public/tutores/formulario'><button>➕ Agregar Otro Tutor</button></a>";
        echo "<a href='/GestionEscolar/public/alumnos/formulario?id_tutor=$id_generado'><button>👨‍🎓 Agregar Alumno</button></a>";
        echo "</p>";
        
        exit;

    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error al registrar tutor: " . $e->getMessage() . "</p>";
    }
}

// Listar con filtros
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['listar'])) {
    $buscar = $_GET['buscar'] ?? '';
    $tutores = !empty($buscar)
        ? $tutorModel->buscarPorNombreApellido($buscar)
        : $tutorModel->listarTodosPorId();
    
    include __DIR__ . '/../views/tutores/index.php';
} else {
    $tutores = $tutorModel->listarTodosPorId();
    include __DIR__ . '/../views/tutores/index.php';
}
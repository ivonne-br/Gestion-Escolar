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

        echo '<!DOCTYPE html>
  <html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Registro Exitoso</title>
    <link rel="stylesheet" href="/GestionEscolar/public/css/estilo.css">
    <link rel="stylesheet" href="/GestionEscolar/public/css/formulario_admin.css">
  </head>
  <body>';

        echo '
  <div style="max-width: 600px; margin: 2em auto; font-family: sans-serif;">
    <div style="padding: 1em; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; text-align: center; margin-bottom: 1.5em;">
        ✅ Tutor registrado con éxito.<br>ID generado: <strong>' . htmlspecialchars($id_generado) . '</strong>
    </div>
    <div class="botones-registro" style="display: flex; gap: 1em; align-items: center;">
      <a href="/GestionEscolar/public/tutores/index" class="boton-volver">⬅ Volver a Listado Tutores</a>
      <a href="/GestionEscolar/public/tutores/formulario" class="boton-volver">+ Agregar Otro Tutor</a>
      <a href="/GestionEscolar/public/alumnos/formulario?id_tutor=' . urlencode($id_generado) . '" class="boton-volver">👨‍🎓 Agregar Alumno</a>
    </div>
  </div>';

        echo '</body></html>';
        exit;

    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error al registrar tutor: " . htmlspecialchars($e->getMessage()) . "</p>";
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
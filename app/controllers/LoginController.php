<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

$db = new Database();
$conn = $db->conectar();

$model = new UsuarioModel($conn);
$datos = $model->buscarUsuario($usuario);

if ($datos && password_verify($contrasena, $datos['contrasena'])) {
    $_SESSION['usuario'] = $usuario;
    $_SESSION['rol'] = $datos['rol'];

    // Redirigir según el rol
    switch ($datos['rol']) {
        case 'administrador':
            header('Location: /GestionEscolar/app/views/administradores/dashboard.php');
            exit;
        case 'alumno':
            header('Location: /GestionEscolar/app/views/alumnos/dashboard.php');
            exit;
        case 'profesor':
            header('Location: /GestionEscolar/app/views/profesores/dashboard.php');
            exit;
        case 'tutor':
            header('Location: /GestionEscolar/app/views/tutores/dashboard.php');
            exit;
        default:
            echo "Rol no reconocido.";
    }
} else {
    echo "<div style='color: red; text-align: center;'>❌ Credenciales inválidas.</div>";
}
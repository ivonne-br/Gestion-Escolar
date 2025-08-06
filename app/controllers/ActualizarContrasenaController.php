<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    if (!$id_usuario || !$contrasena) {
        echo "Faltan datos.";
        exit;
    }

    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $db = new Database();
    $conn = $db->conectar();
    $model = new UsuarioModel($conn);

    if ($model->actualizarContrasena($id_usuario, $contrasena_hash)) {
        echo "Contraseña actualizada con éxito.";
        echo "<br><a href='/GestionEscolar/public/auth/login'>Ir al login</a>";
    } else {
        echo "Error al actualizar la contraseña.";
    }
} else {
    echo "Acceso no permitido.";
}
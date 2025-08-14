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
        echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>Contraseña actualizada</title>
            <link rel='stylesheet' href='/GestionEscolar/public/css/estilo.css'>
        </head>
        <body class='fondo-escolar'>
            <div style='display: flex; flex-direction: column; align-items: center;'>
                <div style='padding: 1em; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; font-family: sans-serif; max-width: 500px; text-align: center;'>
                    ✅ Contraseña actualizada con éxito.
                </div>
                <a href='/GestionEscolar/public/auth/login' class='btn-link' style='margin-top: 20px; text-decoration: none;'>Ir al login</a>
            </div>
        </body>
        </html>";
    } else {
        echo "Error al actualizar la contraseña.";
    }
} else {
    $id_usuario = $_GET['id_usuario'] ?? '';
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Actualizar Contraseña</title>
        <link rel='stylesheet' href='/GestionEscolar/public/css/estilo.css'>
    </head>
    <body class='fondo-escolar'>
        <h2 class='titulo'>Establecer o actualizar contraseña</h2>
        <div class='container'>
            <form method='POST' action='/GestionEscolar/public/usuarios/actualizar_contrasena'>
                <div class='form-row'>
                    <label for='id_usuario'>ID de Usuario:</label>
                    <input type='text' id='id_usuario' name='id_usuario' required value='" . htmlspecialchars($id_usuario) . "'>
                </div>
                <div class='form-row'>
                    <label for='contrasena'>Nueva Contraseña:</label>
                    <input type='password' id='contrasena' name='contrasena' required>
                </div>
                <button type='submit' class='btn-link'>Guardar Contraseña</button>
            </form>
        </div>
    </body>
    </html>";
}
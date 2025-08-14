<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/AdministradorModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido_p = $_POST['apellido_p'] ?? '';
    $apellido_m = $_POST['apellido_m'] ?? '';

    if (empty($nombre) || empty($apellido_p) || empty($apellido_m)) {
        die("Nombre y apellidos son obligatorios.");
    }

    $db = new Database();
    $conn = $db->conectar();

    $model = new AdministradorModel($conn);
    $id = $model->registrar($nombre, $apellido_p, $apellido_m);

    if ($id) {
        echo "
        <div style='padding: 1em; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; font-family: sans-serif; max-width: 500px; margin: 2em auto; text-align: center;'>
            ✅ Administrador registrado con éxito.<br>ID generado: <strong>$id</strong>
        </div>
        <div style='text-align: center; margin-top: 20px;'>
            <a href='/GestionEscolar/public/usuarios/form_actualizar_contrasena?id_usuario=" . urlencode($id) . "' class='btn-link' style='
                display: inline-block;
                padding: 10px 30px;
                background-color: #2959AE;
                color: white;
                border-radius: 25px;
                text-decoration: none;
                font-size: 16px;
                font-family: sans-serif;
            '>Establecer contraseña</a>
        </div>
        ";
    } else {
        die("Error al registrar administrador.");
    }
}
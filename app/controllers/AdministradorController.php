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
// agregar verificación para evitar inyección SQL???
    $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
    $apellido_p = htmlspecialchars($apellido_p, ENT_QUOTES, 'UTF-8');
    $apellido_m = htmlspecialchars($apellido_m, ENT_QUOTES, 'UTF-8');
// ------------------

    $db = new Database();
    $conn = $db->conectar();

    $model = new AdministradorModel($conn);
    $id = $model->registrar($nombre, $apellido_p, $apellido_m);

    if ($id) {
        echo "<p>Administrador registrado con ID: <strong>$id</strong></p>";
        echo "<a href='/GestionEscolar/public/usuarios/form_actualizar_contrasena?id_usuario=" . urlencode($id) . "'>➡ Establecer contraseña</a>";
        exit;
        // echo "Administrador registrado con ID: $id";
    } else {
        echo "Error al registrar administrador.";
    }
}
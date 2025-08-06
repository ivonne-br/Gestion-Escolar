<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit();
} else {
    header('Location: login.php');
    exit();
}
?>

<form method="POST" action="../app/controllers/LoginController.php">
  Usuario: <input type="text" name="usuario"><br>
  Contraseña: <input type="password" name="contrasena"><br>
  <button type="submit">Ingresar</button>
</form>


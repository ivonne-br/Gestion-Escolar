<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>

<h2>Bienvenido <?php echo $_SESSION['usuario']; ?> (<?php echo $_SESSION['rol']; ?>)</h2>
<p>Panel de control.</p>
<a href="logout.php">Cerrar sesión</a>


<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'tutor') {
    header("Location: ../../../../public/login.php");
    exit;
}
?>

<h2>Bienvenido, <?php echo $_SESSION['usuario']; ?> (Tutor)</h2>
<ul>
  <li><a href="/GestionEscolar/public/****/opciones">Información del alumno</a></li>
  <li><a href="/GestionEscolar/public/****/opciones">Tareas</a></li>
  <li><a href="/GestionEscolar/public/****/opciones">Calificaiones</a></li>
  <li><a href="../../../../public/logout.php">Cerrar sesión</a></li>
</ul>
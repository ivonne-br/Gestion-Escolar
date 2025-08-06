<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../../../../public/login.php");
    exit;
}
?>

<h2>Bienvenido, <?php echo $_SESSION['usuario']; ?> (Administrador)</h2>

<ul>
  <li><a href="/GestionEscolar/public/profesores/opciones">Profesores</a></li>
  <li><a href="/GestionEscolar/public/alumnos/tutor_alumno">Tutores y Alumnos</a></li>
  <li><a href="/GestionEscolar/public/grupos/opciones">Grupos</a></li>
  <li><a href="../../../../public/logout.php">Cerrar sesión</a></li>
</ul>
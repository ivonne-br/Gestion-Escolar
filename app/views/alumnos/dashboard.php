<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'alumno') {
    header("Location: ../../../../public/login.php");
    exit;
}
?>

<h2>Bienvenido, <?php echo $_SESSION['usuario']; ?> (Alumno)</h2>

<ul>
  <li><a href="/GestionEscolar/public/alumnos/horario">Horario</a></li>
  <li><a href="/GestionEscolar/public/alumnos/tareas">Tareas</a></li>
  <li><a href="/GestionEscolar/public/alumnos/calificaciones">Calificaciones</a></li>
  <li><a href="../../../../public/logout.php">Cerrar sesión</a></li>
</ul>
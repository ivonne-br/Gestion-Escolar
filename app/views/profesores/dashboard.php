<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: ../../../../public/login.php");
    exit;
}
?>

<h2>Bienvenido, <?php echo $_SESSION['usuario']; ?> (Profesor)</h2>

<ul>
  <li><a href="/GestionEscolar/public/profesores/grupos">Mis grupos</a></li>
  <li><a href="/GestionEscolar/public/profesores/tareas">Tareas</a></li>
  <li><a href="/GestionEscolar/public/profesores/calificaciones">Calificaciones</a></li>
  <li><a href="../../../../public/logout.php">Cerrar sesión</a></li>
</ul>
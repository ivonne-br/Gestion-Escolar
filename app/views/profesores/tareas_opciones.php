<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: /GestionEscolar/public/auth/login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Opciones - Profesores</title>
</head>
<body>
  <h2>Profesores</h2>
  <ul>
    <li><a href="/GestionEscolar/public/profesores/tareas_formulario">Asignar Tareas</a></li>
    <li><a href="/GestionEscolar/public/profesores/calificar_tarea">Calificar Tarea</a></li>
  </ul>

  <br>
  <a href="/GestionEscolar/public/profesores/dashboard">⬅ Volver al Dashboard</a>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /GestionEscolar/public/auth/login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Opciones - Tutores</title>
</head>
<body>
  <h2>Tutores</h2>
  <ul>
    <li><a href="/GestionEscolar/public/tutores/index">Ver Tutores</a></li>
    <li><a href="/GestionEscolar/public/tutores/formulario">Registrar Tutor</a></li>
  </ul>
  <br>
  <a href="/GestionEscolar/public/alumnos/tutor_alumno">⬅ Volver al Tutores y Alumnos</a>
  <br>
  <a href="/GestionEscolar/public/administradores/dashboard">⬅ Volver al Dashboard</a>
</body>
</html>
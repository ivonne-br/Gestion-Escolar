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
  <title>Opciones - Tutores y Alumnos</title>
</head>
<body>
  <h2>Tutores y Alumnos</h2>
  <ul>
    <li><a href="/GestionEscolar/public/tutores/opciones">Tutores</a></li>
    <li><a href="/GestionEscolar/public/alumnos/opciones">Alumnos</a></li>
  </ul>
  <br>
  <a href="/GestionEscolar/public/administradores/dashboard">⬅ Volver al Dashboard</a>
</body>
</html>
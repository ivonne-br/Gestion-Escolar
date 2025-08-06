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
  <title>Opciones - Profesores</title>
</head>
<body>
  <h2>Profesores</h2>
  <ul>
    <li><a href="/GestionEscolar/public/profesores/index">Ver Profesores</a></li>
    <li><a href="/GestionEscolar/public/profesores/formulario">Registrar Profesor</a></li>
  </ul>

  <br>
  <a href="/GestionEscolar/public/administradores/dashboard">⬅ Volver al Dashboard</a>
</body>
</html>
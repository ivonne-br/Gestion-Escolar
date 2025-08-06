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
    <title>Opciones - Grupos</title>
</head>
<body>
    <h2>Grupos</h2>
    <ul>
        <li><a href="/GestionEscolar/public/grupos/index">Ver Grupos</a></li>
        <li><a href="/GestionEscolar/public/grupos/formulario">Registrar Grupo</a></li>
        <li><a href="/GestionEscolar/public/grupos/asignar_profesor">Asignar Profesor a Grupo</a></li>
        <li><a href="/GestionEscolar/public/grupos/crear_ciclo">Crear Ciclo Escolar</a></li>
    </ul>
    <br>
    <a href="/GestionEscolar/public/administradores/dashboard">⬅ Volver al Dashboard</a>
</body>
</html>
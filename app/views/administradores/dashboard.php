<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../../../../public/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
    <link rel="stylesheet" href="/GestionEscolar/public/css/estilo.css">
    <link rel="stylesheet" href="/GestionEscolar/public/css/dashboard.css">
</head>
<body>
    <div class="header">
        <img src="/GestionEscolar/public/img/logo_colegio.png" alt="Logo Colegio" class="header-logo">
        <div class="header-text">
            <div class="header-title">Colegio Abraham Lincoln</div>
            <div class="header-subtitle">fundado en 1971</div>
        </div>
    </div>

    <div class="usuario-info">
        <div class="usuario-texto">
            <?php echo $_SESSION['nombre_completo']; ?><br>
            <?php echo $_SESSION['id_usuario']; ?>
        </div>
        <img src="/GestionEscolar/public/img/usuario_icono.png" alt="Perfil" class="usuario-icono">
    </div>

    <div class="menu-panel">
        <a class="menu-opcion" href="/GestionEscolar/public/grupos/opciones">Grupos</a>
        <a class="menu-opcion" href="/GestionEscolar/public/profesores/opciones">Profesores</a>
        <a class="menu-opcion" href="/GestionEscolar/public/alumnos/tutor_alumno">Tutores y Alumnos</a>
    </div>
</body>
</html>
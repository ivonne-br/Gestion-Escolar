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
    <link rel="stylesheet" href="/GestionEscolar/public/css/estilo.css">
    <link rel="stylesheet" href="/GestionEscolar/public/css/grupos.css">
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

    <div class="wrapper">
            <div class="menu-panel full-width">
                <a class="menu-opcion" href="/GestionEscolar/public/grupos/index">Ver Grupos</a>
                <a class="menu-opcion" href="/GestionEscolar/public/grupos/formulario">Registrar Grupo</a>
                <a class="menu-opcion" href="/GestionEscolar/public/grupos/asignar_profesor">Asignar Profesor a Grupo</a>
                <a class="menu-opcion" href="/GestionEscolar/public/grupos/crear_ciclo">Crear Ciclo Escolar</a>
            </div>
            <div class="botones-acciones" style="display: flex; flex-direction: column; gap: 10px;">
                <a href="/GestionEscolar/public/administradores/dashboard" class="boton-volver">⬅ Volver al Dashboard</a>
            </div>
    </div>
</body>
</html>
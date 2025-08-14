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
    <a href="/GestionEscolar/public/tutores/index" class="menu-opcion">👨‍🏫 Ver Tutores</a>
    <a href="/GestionEscolar/public/tutores/formulario" class="menu-opcion">➕ Registrar Tutor</a>
  </div>
  <div class="botones-acciones" style="display: flex; flex-direction: column; gap: 10px;">
    <a href="/GestionEscolar/public/alumnos/tutor_alumno" class="boton-volver">⬅ Volver a Tutores y Alumnos</a>
    <a href="/GestionEscolar/public/administradores/dashboard" class="boton-volver">⬅ Volver al Dashboard</a>
  </div>
</body>
</html>
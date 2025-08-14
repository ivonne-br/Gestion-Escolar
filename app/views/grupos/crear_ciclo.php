<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Ciclo Escolar</title>
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
<div class="crear-ciclo-container">
  <h3 class="titulo">🗓 Crear Nuevo Ciclo Escolar</h3>
  <form method="POST" action="/GestionEscolar/public/grupos/crear_ciclo" onsubmit="return confirm('¿Estás seguro de crear un nuevo ciclo escolar?')">
      <label class="form-label">Fecha de inicio:</label>
      <input class="form-input" type="date" name="fecha_inicio" required>

      <label class="form-label">Fecha de fin:</label>
      <input class="form-input" type="date" name="fecha_fin" required>

      <button class="form-button" type="submit" name="crear_ciclo">➕ Crear Ciclo Escolar</button>
  </form>
  <a class="volver-enlace" href="/GestionEscolar/public/grupos/opciones">⬅ Volver al Grupos</a>
  <hr>
</div>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tutores y Alumnos</title>
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
          <?php
          session_start();
          echo isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : '';
          echo "<br>";
          echo isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : '';
          ?>
      </div>
      <img src="/GestionEscolar/public/img/usuario_icono.png" alt="Perfil" class="usuario-icono">
  </div>

  <div class ="wrapper">
      <div class="menu-panel full-width">
          <a class="menu-opcion" href="/GestionEscolar/public/tutores/opciones">Tutores</a>
          <a class="menu-opcion" href="/GestionEscolar/public/alumnos/opciones">Alumnos</a>
      </div>
      <div class="botones-acciones" style="display: flex; flex-direction: column; gap: 10px;">
          <a href="/GestionEscolar/public/administradores/dashboard" class="boton-volver">⬅ Volver al Dashboard</a>
      </div>
  </div>
</body>
</html>
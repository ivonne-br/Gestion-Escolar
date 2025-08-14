<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Registrar Administrador</title>
  <link rel="stylesheet" href="/GestionEscolar/public/css/estilo.css">
  <link rel="stylesheet" href="/GestionEscolar/public/css/formulario_admin.css">
</head>
<body>
  <div class="header">
    <img src="/GestionEscolar/public/img/logo_colegio.png" alt="Logo Colegio" class="header-logo">
    <div class="header-text">
      <div class="header-title">Colegio Abraham Lincoln</div>
      <div class="header-subtitle">fundado en 1971</div>
    </div>
  </div>
  <div class="formulario-background">
    <h2 class="titulo">Registrar Administrador</h2>
    <div class="container">
      <form action="/GestionEscolar/public/administradores/registrar" method="POST">
        <div class="form-content">
          <div class="form-row">
            <label for="nombre">Nombre(s):</label>
            <input type="text" id="nombre" name="nombre" required>
          </div>
          <div class="form-row">
            <label for="apellido_p">Apellido Paterno:</label>
            <input type="text" id="apellido_p" name="apellido_p" required>
          </div>
          <div class="form-row">
            <label for="apellido_m">Apellido Materno:</label>
            <input type="text" id="apellido_m" name="apellido_m">
          </div>
        </div>
        <button type="submit">Registrar</button>
      </form>
    </div>
  </div>
</body>
</html>
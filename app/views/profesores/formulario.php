</html>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Registrar Profesor</title>
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

  <div class="formulario-background">
    <h2 class="titulo">Registrar Profesor</h2>
    <div class="container">
      <form action="/GestionEscolar/public/profesores/registrar" method="POST">
        <div class="form-content">
          <div class="form-row">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
          </div>
          <div class="form-row">
            <label for="apellido_p">Apellido Paterno:</label>
            <input type="text" id="apellido_p" name="apellido_p" required>
          </div>
          <div class="form-row">
            <label for="apellido_m">Apellido Materno:</label>
            <input type="text" id="apellido_m" name="apellido_m" required>
          </div>
          <div class="form-row">
            <label for="especialidad">Especialidad:</label>
            <select id="especialidad" name="especialidad" required>
              <option value="">-- Selecciona una opción --</option>
              <option value="Español">Español</option>
              <option value="Matemáticas">Matemáticas</option>
              <option value="Ciencias">Ciencias</option>
              <option value="Geografía">Geografía</option>
              <option value="Historia">Historia</option>
              <option value="Formación Cívica y Ética">Formación Cívica y Ética</option>
              <option value="Artes">Artes</option>
              <option value="Educación Física">Educación Física</option>
              <option value="Inglés">Inglés</option>
              <option value="Tecnología">Tecnología</option>
            </select>
          </div>
        </div>
        <button type="submit">Registrar</button>
      </form>

      <div class="enlaces-volver">
        <a href="/GestionEscolar/public/profesores/opciones" class="boton-volver">⬅ Volver a Profesores</a>
      </div>
    </div>
  </div>
</body>
</html>
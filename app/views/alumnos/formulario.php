<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Alumno</title>
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
    <h2 class="titulo">Registrar Alumno</h2>
    <div class="container">
      <form action="/GestionEscolar/public/alumnos/registrar" method="POST">
        <input type="hidden" name="accion" value="registrar">
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
            <label for="curp">CURP:</label>
            <input type="text" id="curp" name="curp" maxlength="18" required>
          </div>
          <div class="form-row">
            <label for="buscar_tutor">Buscar Tutor:</label>
            <input type="text" id="buscar_tutor" placeholder="Escribe nombre o apellido">
          </div>
          <div class="form-row">
            <label for="lista_tutores">Selecciona Tutor:</label>
            <select name="id_tutor" id="lista_tutores" required>
              <option value="">-- Selecciona un tutor --</option>
            </select>
          </div>
          <div class="form-row">
            <label for="nivel">Nivel:</label>
            <select name="nivel" id="nivel" required>
              <option value="">-- Selecciona nivel --</option>
              <option value="Primaria">Primaria</option>
              <option value="Secundaria">Secundaria</option>
            </select>
          </div>
          <div class="form-row">
            <label for="grado">Grado:</label>
            <select name="grado" id="grado" required>
              <option value="">-- Selecciona grado --</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
            </select>
          </div>
        </div>
        <button type="submit">Registrar</button>
      </form>

      <div class="enlaces-volver">
        <a href="/GestionEscolar/public/alumnos/opciones" class="boton-volver">⬅ Volver a Alumnos</a>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('buscar_tutor').addEventListener('input', function () {
      const filtro = this.value.toLowerCase();
      const lista = document.getElementById('lista_tutores');

      fetch('/GestionEscolar/public/obtener_tutores.php')
        .then(response => response.json())
        .then(data => {
          lista.innerHTML = '<option value="">-- Selecciona un tutor --</option>';

          data.forEach(tutor => {
            const nombreCompleto = (tutor.nombre + ' ' + tutor.apellido_p + ' ' + tutor.apellido_m).toLowerCase();

            if (nombreCompleto.includes(filtro)) {
              const option = document.createElement('option');
              option.value = tutor.id_tutor;
              option.textContent = tutor.nombre + ' ' + tutor.apellido_p + ' ' + tutor.apellido_m;
              lista.appendChild(option);
            }
          });
        })
        .catch(err => {
          console.error('Error al obtener tutores:', err);
        });
    });
  </script>
</body>
</html>
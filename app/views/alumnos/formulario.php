<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Registrar Alumno</title>
</head>
<body>
  <h2>Formulario - Alumno</h2>
  <form action="/GestionEscolar/public/alumnos/registrar" method="POST">
    <input type="hidden" name="accion" value="registrar">

    <label>Nombre:</label>
    <input type="text" name="nombre" required><br><br>

    <label>Apellido Paterno:</label>
    <input type="text" name="apellido_p" required><br><br>

    <label>Apellido Materno:</label>
    <input type="text" name="apellido_m" required><br><br>

    <label>CURP:</label>
    <input type="text" name="curp" maxlength="18" required><br><br>

    <label>Buscar Tutor:</label>
    <input type="text" id="buscar_tutor" placeholder="Escribe nombre o apellido"><br><br>

    <label>Selecciona Tutor:</label>
    <select name="id_tutor" id="lista_tutores" required>
    <?php
    $id_tutor = $_GET['id_tutor'] ?? '';
    echo '<option value="">-- Selecciona un tutor --</option>';
    ?>
    <?php
    if ($id_tutor) {
        require_once __DIR__ . '/../../../config/database.php';
        require_once __DIR__ . '/../../models/TutorModel.php';
        $db = new Database();
        $conn = $db->conectar();
        $model = new TutorModel($conn);
        $tutor = $model->obtenerPorId($id_tutor);
        if ($tutor) {
            $nombreCompleto = $tutor['nombre'] . ' ' . $tutor['apellido_p'] . ' ' . $tutor['apellido_m'];
            echo "<option value='$id_tutor' selected>$nombreCompleto ($id_tutor)</option>";
        }
    }
    ?>
    </select><br><br>

    <label>Nivel:</label>
    <select name="nivel" required>
        <option value="">-- Selecciona nivel --</option>
        <option value="Primaria">Primaria</option>
        <option value="Secundaria">Secundaria</option>
    </select>
    <br><br>

    <label>Grado:</label>
    <select name="grado" required>
        <option value="">-- Selecciona grado --</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
    </select>
    <br><br>

    <button type="submit">Registrar</button>
    <br><br>
    <a href="/GestionEscolar/public/alumnos/opciones">⬅ Volver a Alumnos</a>
    <br>
  </form>

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
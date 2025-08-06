<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Registrar Profesor</title>
</head>
<body>
  <h2>Formulario - Profesor</h2>
  <form action="/GestionEscolar/public/profesores/registrar" method="POST">
    <label>Nombre:</label>
    <input type="text" name="nombre" required><br><br>

    <label>Apellido Paterno:</label>
    <input type="text" name="apellido_p" required><br><br>

    <label>Apellido Materno:</label>
    <input type="text" name="apellido_m" required><br><br>

    <label>Especialidad:</label>
    <select name="especialidad" required>
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
    </select><br><br>

    <button type="submit">Registrar</button>
  </form>
  <br>
  <a href="/GestionEscolar/public/profesores/opciones">⬅ Volver a Profesores</a>

</body>
</html>
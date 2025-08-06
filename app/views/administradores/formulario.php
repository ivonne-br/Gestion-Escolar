<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Registrar Administrador</title>
</head>
<body>
  <h2>Formulario - Administrador</h2>
  <form action="/GestionEscolar/public/administradores/registrar" method="POST">
    <label>Nombre:</label>
    <input type="text" name="nombre" required><br><br>

    <label>Apellido Paterno:</label>
    <input type="text" name="apellido_p" required><br><br>

    <label>Apellido Materno:</label>
    <input type="text" name="apellido_m"><br><br>

    <button type="submit">Registrar</button>
  </form>
</body>
</html>


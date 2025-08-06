<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Formulario - Tutor</title>
</head>
<body>
  <h2>Registrar Tutor</h2>
<form action="/GestionEscolar/app/controllers/TutorController.php" method="POST">    
    Nombre(s): <input type="text" name="nombre" required><br><br>
    Apellido Paterno: <input type="text" name="apellido_p" required><br><br>
    Apellido Materno: <input type="text" name="apellido_m"><br><br>
    Correo: <input type="email" name="correo" required><br><br>
    Teléfono: <input type="text" name="telefono" required><br><br>
    <button type="submit">Registrar</button>
  </form>
  <br>
  <a href="/GestionEscolar/public/tutores/opciones">⬅ Volver a Tutores</a>
  <br>
  <a href="/GestionEscolar/public/administradores/dashboard">⬅ Volver al Dashboard</a>
</body>
</html>
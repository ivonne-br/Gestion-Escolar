<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
</head>
<body>
  <h2>Iniciar Sesión</h2>
<form method="POST" action="/GestionEscolar/public/auth/procesar_login">
    <label for="usuario">ID Usuario:</label><br>
    <input type="text" name="usuario" id="usuario" required><br><br>

    <label for="contrasena">Contraseña:</label><br>
    <input type="password" name="contrasena" id="contrasena" required><br><br>

    <button type="submit">Entrar</button>
  </form>
</body>
</html>

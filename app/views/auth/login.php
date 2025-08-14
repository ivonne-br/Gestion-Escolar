<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="/GestionEscolar/public/css/login.css">
</head>
<body>
  <div class="login-container">
    <img src="/GestionEscolar/public/img/logo_colegio.png" alt="Logo Colegio" class="logo">
    
    <div class="titulo">Iniciar Sesión</div>

    <form method="POST" action="/GestionEscolar/public/auth/procesar_login">
      <input type="text" name="usuario" placeholder="ID Usuario" required>
      <input type="password" name="contrasena" placeholder="Contraseña" required>
      <button type="submit">Ingresar</button>
    </form>
  </div>
</body>
</html>

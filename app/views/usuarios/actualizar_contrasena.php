<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Contraseña</title>
</head>
<body>
    <h2>Establecer o actualizar contraseña</h2>
    <form method="POST" action="/GestionEscolar/public/usuarios/actualizar_contrasena">
        <label for="id_usuario">ID de Usuario:</label><br>
        <input type="text" id="id_usuario" name="id_usuario" required><br><br>

        <label for="contrasena">Nueva Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena" required><br><br>

        <button type="submit">Guardar Contraseña</button>
    </form>
</body>
</html>
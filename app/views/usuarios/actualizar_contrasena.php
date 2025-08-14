<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Contraseña</title>
    <link rel="stylesheet" href="/GestionEscolar/public/css/estilo.css">
</head>
<body class="fondo-escolar">
    <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
        <h2 class="titulo">Establecer contraseña</h2>
        <div class="container">
            <form method="POST" action="/GestionEscolar/public/usuarios/actualizar_contrasena">
                <div class="form-row">
                    <label for="id_usuario">ID de Usuario:</label>
                    <input type="text" id="id_usuario" name="id_usuario" required value="<?php echo isset($_GET['id_usuario']) ? htmlspecialchars($_GET['id_usuario']) : ''; ?>">
                </div>

                <div class="form-row">
                    <label for="contrasena">Nueva Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>

                <button type="submit" class="btn-link" style="margin-top: 25px;">Guardar Contraseña</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/TutorModel.php';

$id = $_GET['id'] ?? '';
if (!$id) {
    echo "ID de tutor no proporcionado.";
    exit;
}

$db = new Database();
$conn = $db->conectar();
$model = new TutorModel($conn);
$tutor = $model->obtenerPorId($id);

if (!$tutor) {
    echo "Tutor no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tutor</title>
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
        <h2 class="titulo">✏️ Editar Tutor</h2>
        <div class="container">
            <form method="POST" action="/GestionEscolar/public/tutores/actualizar">
                <input type="hidden" name="id_tutor" value="<?php echo htmlspecialchars($tutor['id']); ?>">

                <div class="form-content">
                    <div class="form-row">
                        <label>Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($tutor['nombre']); ?>" required>
                    </div>
                    <div class="form-row">
                        <label>Apellido Paterno:</label>
                        <input type="text" id="apellido_p" name="apellido_p" value="<?php echo htmlspecialchars($tutor['apellido_paterno']); ?>" required>
                    </div>
                    <div class="form-row">
                        <label>Apellido Materno:</label>
                        <input type="text" id="apellido_m" name="apellido_m" value="<?php echo htmlspecialchars($tutor['apellido_materno']); ?>">
                    </div>
                    <div class="form-row">
                        <label>Correo:</label>
                        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($tutor['correo']); ?>" required>
                    </div>
                    <div class="form-row">
                        <label>Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($tutor['telefono']); ?>" required>
                    </div>
                </div>

                <button type="submit">💾 Actualizar</button>
            </form>

            <form method="POST" action="/GestionEscolar/public/tutores/eliminar" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este tutor y todos sus datos relacionados? Esta acción no se puede deshacer.')">
                <input type="hidden" name="id_tutor" value="<?php echo htmlspecialchars($tutor['id']); ?>">
                <button type="submit" style="color: red;" title="Eliminar tutor permanentemente">🗑️ Eliminar Tutor</button>
            </form>

            <div class="enlaces-volver">
                <a href="/GestionEscolar/public/tutores/opciones" class="boton-volver">⬅ Volver a Tutores</a>
                <a href="/GestionEscolar/public/tutores/index" class="boton-volver">⬅ Volver a Listado de Tutores</a>
            </div>
        </div>
    </div>
</body>
</html>
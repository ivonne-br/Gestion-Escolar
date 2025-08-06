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
</head>
<body>
    <h2>✏️ Editar Tutor</h2>
    <form method="POST" action="/GestionEscolar/public/tutores/actualizar">
        <input type="hidden" name="id_tutor" value="<?php echo htmlspecialchars($tutor['id_tutor']); ?>">

        <label>Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($tutor['nombre']); ?>" required>

        <label>Apellido Paterno:</label><br>
        <input type="text" id="apellido_p" name="apellido_p" value="<?php echo htmlspecialchars($tutor['apellido_p']); ?>" required>

        <label>Apellido Materno:</label><br>
        <input type="text" id="apellido_m" name="apellido_m" value="<?php echo htmlspecialchars($tutor['apellido_m']); ?>">

        <label>Correo:</label><br>
        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($tutor['correo']); ?>" required>

        <label>Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($tutor['telefono']); ?>" required>

        <button type="submit">💾 Actualizar</button>
    </form>

    <form method="POST" action="/GestionEscolar/public/tutores/eliminar" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este tutor y todos sus datos relacionados? Esta acción no se puede deshacer.')">
        <input type="hidden" name="id_tutor" value="<?php echo htmlspecialchars($tutor['id_tutor']); ?>">
        <button type="submit" style="color: red;" title="Eliminar tutor permanentemente">🗑️ Eliminar Tutor</button>
    </form>

    <br>
    <a href="/GestionEscolar/public/tutores/opciones">⬅ Volver a Tutores</a><br>
    <br>
    <a href="/GestionEscolar/public/tutores/index">⬅ Volver a Listado de Tutores</a>
</body>
</html>
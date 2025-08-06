<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/AlumnoModel.php';
require_once __DIR__ . '/../../models/TutorModel.php';

$id = $_GET['id'] ?? '';
if (!$id) {
    echo "ID de alumno no proporcionado.";
    exit;
}

$db = new Database();
$conn = $db->conectar();
$alumnoModel = new AlumnoModel($conn);
$tutorModel = new TutorModel($conn);

$alumno = $alumnoModel->obtenerPorId($id);
if (!$alumno) {
    echo "Alumno no encontrado.";
    exit;
}

$tutores = $tutorModel->listarTodos(); // para select de tutores
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Alumno</title>
</head>
<body>
    <h2>✏️ Editar Alumno</h2>
    <form method="POST" action="/GestionEscolar/public/alumnos/actualizar">
        <input type="hidden" name="id_alumno" value="<?= htmlspecialchars($alumno['id_alumno']) ?>">
        <input type="hidden" name="accion" value="actualizar">

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= htmlspecialchars($alumno['nombre']) ?>" required><br>

        <label>Apellido Paterno:</label><br>
        <input type="text" name="apellido_p" value="<?= htmlspecialchars($alumno['apellido_p']) ?>" required><br>

        <label>Apellido Materno:</label><br>
        <input type="text" name="apellido_m" value="<?= htmlspecialchars($alumno['apellido_m']) ?>"><br>

        <label>CURP:</label><br>
        <input type="text" name="curp" value="<?= htmlspecialchars($alumno['curp']) ?>" required><br>

        <label>Tutor:</label><br>
        <select name="id_tutor" required>
            <?php foreach ($tutores as $tutor): ?>
                <option value="<?= $tutor['id_tutor'] ?>" <?= $tutor['id_tutor'] === $alumno['id_tutor'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tutor['nombre'] . ' ' . $tutor['apellido_p'] . ' ' . $tutor['apellido_m']) ?> (<?= $tutor['id_tutor'] ?>)
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">💾 Actualizar</button>
    </form>

    <form method="POST" action="/GestionEscolar/public/alumnos/actualizar" onsubmit="return confirm('¿Seguro que deseas eliminar este alumno? Esta acción no se puede deshacer.')">
        <input type="hidden" name="id_alumno" value="<?= htmlspecialchars($alumno['id_alumno']) ?>">
        <input type="hidden" name="accion" value="eliminar">
        <button type="submit" style="color: red;">🗑️ Eliminar Alumno</button>
    </form>

    <br>
    <a href="/GestionEscolar/public/alumnos/index">⬅ Volver al Listado de Alumnos</a>
</body>
</html>
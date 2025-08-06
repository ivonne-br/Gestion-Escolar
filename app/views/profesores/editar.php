<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/ProfesorModel.php';

$id = $_GET['id'] ?? '';
if (!$id) {
    echo "ID de profesor no proporcionado.";
    exit;
}

$db = new Database();
$conn = $db->conectar();
$model = new ProfesorModel($conn);
$profesor = $model->obtenerPorId($id);

if (!$profesor) {
    echo "Profesor no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Profesor</title>
</head>
<body>
    <h2>Editar Profesor</h2>
    <form method="POST" action="/GestionEscolar/public/profesores/actualizar">
        <input type="hidden" name="id_profesor" value="<?php echo htmlspecialchars($profesor['id_profesor']); ?>">

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($profesor['nombre']); ?>" required><br><br>

        <label>Apellido Paterno:</label><br>
        <input type="text" name="apellido_p" value="<?php echo htmlspecialchars($profesor['apellido_p']); ?>" required><br><br>

        <label>Apellido Materno:</label><br>
        <input type="text" name="apellido_m" value="<?php echo htmlspecialchars($profesor['apellido_m']); ?>"><br><br>

        <label>Especialización:</label><br>
        <select name="especializacion" required>
            <option value="">-- Selecciona una opción --</option>
            <?php
            $materias = [
                "Español",
                "Matemáticas",
                "Ciencias",
                "Geografía",
                "Historia",
                "Formación Cívica y Ética",
                "Artes",
                "Educación Física",
                "Inglés",
                "Tecnología"
            ];
            foreach ($materias as $materia) {
                $selected = ($profesor['especializacion'] === $materia) ? 'selected' : '';
                echo "<option value=\"$materia\" $selected>$materia</option>";
            }
            ?>
        </select><br><br>

        <button type="submit">Actualizar</button>
    </form>

    <form method="POST" action="/GestionEscolar/public/profesores/eliminar" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este profesor y todos sus datos relacionados? Esta acción no se puede deshacer.')">
        <input type="hidden" name="id_profesor" value="<?php echo htmlspecialchars($profesor['id_profesor']); ?>">
        <button type="submit" style="color: red;">🗑️ Eliminar Profesor</button>
    </form>

    <br>
    <a href="/GestionEscolar/public/profesores/index">⬅ Volver a Listado de Profesores</a>
    <br>
    <a href="/GestionEscolar/public/profesores/opciones">⬅ Volver a Profesores</a>

</body>
</html>
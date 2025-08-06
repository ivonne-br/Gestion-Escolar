<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: /GestionEscolar/public/login.php");
    exit;
}
if (isset($_SESSION['tarea_asignada']) && $_SESSION['tarea_asignada']) {
    echo "<p style='color:green;'>✅ Tarea asignada correctamente.</p>";
    unset($_SESSION['tarea_asignada']);
}

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/ProfesorModel.php';

$db = new Database();
$pdo = $db->conectar();
$model = new ProfesorModel($pdo);

$idProfesor = $_SESSION['usuario'];

// Obtener grupos y materias con sus IDs asignados al profesor
$gruposMaterias = $model->obtenerGruposYMateriasPorProfesor($idProfesor);

// Para llenar los selects sin duplicar materias
$materias = [];
foreach ($gruposMaterias as $gm) {
    if (!isset($materias[$gm['id_materia']])) {
        $materias[$gm['id_materia']] = $gm['materia'];
    }
}
?>

<h2>Asignar Tarea</h2>

<form action="/GestionEscolar/public/profesores/tareas/guardar" method="POST">
    <label for="nombre">Nombre de la tarea:</label><br />
    <input type="text" id="nombre" name="nombre" required><br /><br />

    <label for="descripcion">Descripción:</label><br />
    <textarea id="descripcion" name="descripcion" rows="4" cols="50"></textarea><br /><br />

    <label for="fecha_entrega">Fecha de entrega:</label><br />
    <input type="date" id="fecha_entrega" name="fecha_entrega" required><br /><br />

    <label for="id_materia">Materia:</label><br />
    <select id="id_materia" name="id_materia" required>
        <option value="">Seleccione una materia</option>
        <?php foreach ($materias as $id => $nombre): ?>
            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($nombre) ?></option>
        <?php endforeach; ?>
    </select><br /><br />

    <label for="id_grupo">Grupo:</label><br />
    <select id="id_grupo" name="id_grupo" required>
        <option value="">Seleccione un grupo</option>
        <?php foreach ($gruposMaterias as $gm): ?>
            <option value="<?= htmlspecialchars($gm['id_grupo']) ?>">
                <?= htmlspecialchars("{$gm['nivel']} {$gm['grado']} {$gm['nombre']}") ?>
            </option>
        <?php endforeach; ?>
    </select><br /><br />

    <button type="submit">Asignar Tarea</button>
</form>
<br>
<a href="/GestionEscolar/public/profesores/tareas">⬅ Volver a Tareas</a>
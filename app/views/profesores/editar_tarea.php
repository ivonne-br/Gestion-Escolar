<?php
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: /GestionEscolar/public/login.php");
    exit;
}

if (!isset($_GET['id_tarea']) || empty($_GET['id_tarea'])) {
    echo "<h2>⚠️ Falta el parámetro 'id_tarea'.</h2>";
    echo "<a href='/GestionEscolar/public/profesores/calificar_tarea'>⬅ Volver</a>";
    exit;
}

$id_tarea = $_GET['id_tarea'];
?>

<h2>Editar Tarea</h2>

<form action="/GestionEscolar/public/profesores/tareas/editar" method="POST">
    <input type="hidden" name="id_tarea" value="<?= htmlspecialchars($tarea['id_tarea']) ?>">
    <label for="nombre">Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($tarea['nombre']) ?>" required><br><br>

    <label for="descripcion">Descripción:</label><br>
    <textarea id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($tarea['descripcion'] ?? '') ?></textarea><br><br>

    <label for="fecha_entrega">Fecha de entrega:</label><br>
    <input type="date" id="fecha_entrega" name="fecha_entrega" value="<?= htmlspecialchars($tarea['fecha_entrega']) ?>" required><br><br>

    <button type="submit">Guardar Cambios</button>
</form>

<form action="/GestionEscolar/public/profesores/tareas/eliminar" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar esta tarea?');" style="margin-top:15px;">
    <input type="hidden" name="id_tarea" value="<?= htmlspecialchars($tarea['id_tarea']) ?>">
    <button type="submit" style="background-color: red; color: white;">Eliminar Tarea</button>
</form>

<br>
<a href="/GestionEscolar/public/profesores/calificar_tarea">⬅ Volver</a>
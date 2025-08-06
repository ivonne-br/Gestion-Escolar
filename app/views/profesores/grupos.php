<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/ProfesorModel.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: ../../../../public/login.php");
    exit;
}

$db = new Database();
$conn = $db->conectar();
$model = new ProfesorModel($conn);

// Obtener ID del profesor desde sesión
$idProfesor = $_SESSION['usuario'];

// Obtener los grupos asignados
$grupos = $model->obtenerGruposPorProfesor($idProfesor);
?>

<h2>👨‍🏫 Mis Grupos Asignados</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Grupo</th>
            <th>Grado</th>
            <th>Nivel</th>
            <th>Materia</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($grupos)): ?>
    <p>No tienes grupos asignados actualmente.</p>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    <?php else: ?>
        <?php foreach ($grupos as $grupo): ?>
            <tr>
                <td><?= htmlspecialchars($grupo['nombre']) ?></td>
                <td><?= htmlspecialchars($grupo['grado']) ?></td>
                <td><?= htmlspecialchars($grupo['nivel']) ?></td>
                <td><?= htmlspecialchars($grupo['materia']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<br>
<a href="/GestionEscolar/public/profesores/dashboard">⬅ Volver a Dashboard</a>
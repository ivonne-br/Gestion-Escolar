<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/TutorModel.php';

$db = new Database();
$conn = $db->conectar();
$model = new TutorModel($conn);

$buscar = $_GET['buscar'] ?? '';
$tutores = !empty($buscar)
    ? $model->buscarPorNombreApellido($buscar)
    : $model->listarTodosPorId();
?>


<h2>📋 Listado de Tutores</h2>
<form id="filtro-form" method="GET" action="/GestionEscolar/public/tutores/index">
    <label for="buscar">Nombre o Apellido:</label>
    <input type="text" name="buscar" id="buscar" placeholder="Buscar por nombre o apellido" value="<?= htmlspecialchars($buscar) ?>">
    <button type="submit">🔍 Buscar</button>
    <a href="/GestionEscolar/public/tutores/index" style="margin-left: 10px;">❌ Limpiar</a>
</form>
<br>

<a href="/GestionEscolar/public/tutores/formulario">➕ Agregar Tutor</a>
<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($tutores)): ?>
            <?php foreach ($tutores as $tutor): ?>
                <tr>
                    <td><?= htmlspecialchars($tutor['id_tutor']) ?></td>
                    <td><?= htmlspecialchars($tutor['nombre'] . ' ' . $tutor['apellido_p'] . ' ' . $tutor['apellido_m']); ?></td>
                    <td><?= htmlspecialchars($tutor['correo']); ?></td>
                    <td><?= htmlspecialchars($tutor['telefono']); ?></td>
                    <td><a href="/GestionEscolar/public/tutores/editar?id=<?= urlencode($tutor['id_tutor']); ?>">✏️</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center;">No hay tutores registrados todavía.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<br>
<a href="/GestionEscolar/public/tutores/opciones">⬅ Volver a Tutores</a>
<br>
<a href="/GestionEscolar/public/administradores/dashboard">⬅ Volver al Dashboard</a>

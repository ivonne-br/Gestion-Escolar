<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/AlumnoModel.php';
require_once __DIR__ . '/../../models/TutorModel.php';

$db = new Database();
$conn = $db->conectar();
$model = new AlumnoModel($conn);
$tutorModel = new TutorModel($conn);

$buscar = $_GET['buscar'] ?? '';
$alumnos = !empty($buscar)
    ? $model->buscarPorNombreApellido($buscar)
    : $model->listarTodos();
?>

<h2>📋 Listado de Alumnos</h2>
<form id="filtro-form" method="GET" action="/GestionEscolar/public/alumnos/index">
    <label for="buscar">Nombre o Apellido:</label>
    <input type="text" name="buscar" id="buscar" placeholder="Buscar por nombre o apellido" value="<?= htmlspecialchars($buscar) ?>">
    <button type="submit">🔍 Buscar</button>
    <a href="/GestionEscolar/public/alumnos/index" style="margin-left: 10px;">❌ Limpiar</a>
</form>
<br>

<a href="/GestionEscolar/public/alumnos/formulario">➕ Agregar Alumno</a>
<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>CURP</th>
            <th>Correo</th>
            <th>Nivel</th>
            <th>Grado</th>
            <th>Grupo</th>
            <th>Tutor</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($alumnos)): ?>
            <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td><?= htmlspecialchars($alumno['id_alumno']) ?></td>
                    <td><?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido_p'] . ' ' . $alumno['apellido_m']) ?></td>
                    <td><?= htmlspecialchars($alumno['curp']) ?></td>
                    <td><?= htmlspecialchars($alumno['correo']) ?></td>
                    <td><?= htmlspecialchars($alumno['nivel']) ?></td>
                    <td><?= htmlspecialchars($alumno['grado']) ?></td>
                    <td><?= htmlspecialchars($alumno['grupo'] ?? 'Grupo no asignado') ?></td>
                    <td>
                        <?php
                        $tutor = $tutorModel->obtenerPorId($alumno['id_tutor']);
                        echo $tutor
                            ? htmlspecialchars($tutor['nombre'] . ' ' . $tutor['apellido_p'] . ' ' . $tutor['apellido_m']) . ' (' . $tutor['id_tutor'] . ')'
                            : 'No asignado';
                        ?>
                    </td>
                    <td><a href="/GestionEscolar/public/alumnos/editar?id=<?= urlencode($alumno['id_alumno']); ?>">✏️</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" style="text-align:center;">No hay alumnos registrados todavía.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<br>
<a href="/GestionEscolar/public/alumnos/opciones">⬅ Volver a Alumnos</a>
<br>
<a href="/GestionEscolar/public/administradores/dashboard">⬅ Volver al Dashboard</a>
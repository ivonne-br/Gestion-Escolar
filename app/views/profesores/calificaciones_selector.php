<?php
$grupos = [];
$materiasPorGrupo = [];

foreach ($datos as $row) {
    $idGrupo = $row['id_grupo'];
    $idMateria = $row['id_materia'];

    $grupos[$idGrupo] = $row['nivel'] . ' ' . $row['grado'] . ' ' . $row['nombre'];

    if (!isset($materiasPorGrupo[$idGrupo])) {
        $materiasPorGrupo[$idGrupo] = [];
    }

    // Evita duplicados
    if (!isset($materiasPorGrupo[$idGrupo][$idMateria])) {
        $materiasPorGrupo[$idGrupo][$idMateria] = $row['materia'];
    }
}
?>

<?php if (isset($_GET['id_grupo'], $_GET['id_materia'])): ?>
    <?php
        $id_grupo = $_GET['id_grupo'];
        $id_materia = $_GET['id_materia'];
        $grupoNombre = $grupos[$id_grupo] ?? 'Grupo desconocido';
        $materiaNombre = $materiasPorGrupo[$id_grupo][$id_materia] ?? 'Materia desconocida';
        $alumnos = $model->obtenerAlumnosPorGrupo($id_grupo);
    ?>
    <h2><?= htmlspecialchars($grupoNombre) ?> - <?= htmlspecialchars($materiaNombre) ?></h2>
<?php else: ?>
    <h2>Seleccionar Grupo y Materia</h2>
    <form method="GET" action="/GestionEscolar/public/profesores/calificaciones">
        <label for="id_grupo">Grupo:</label>
        <select name="id_grupo" id="id_grupo" required>
            <option value="">Seleccione un grupo</option>
            <?php foreach ($grupos as $id => $nombre): ?>
                <option value="<?= $id ?>" <?= (isset($_GET['id_grupo']) && $_GET['id_grupo'] == $id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($nombre) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="id_materia">Materia:</label>
        <select name="id_materia" id="id_materia" required>
            <option value="">Seleccione una materia</option>
            <?php
            $grupoSeleccionado = $_GET['id_grupo'] ?? null;
            if ($grupoSeleccionado && isset($materiasPorGrupo[$grupoSeleccionado])):
                foreach ($materiasPorGrupo[$grupoSeleccionado] as $id => $nombre): ?>
                    <option value="<?= $id ?>" <?= (isset($_GET['id_materia']) && $_GET['id_materia'] == $id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($nombre) ?>
                    </option>
            <?php endforeach;
            endif; ?>
        </select>

        <button type="submit">Ver Alumnos</button>
    </form>
    <br>
    <a href="/GestionEscolar/public/profesores/dashboard">⬅ Volver a Dashboard</a>
<?php endif; ?>

<?php if (isset($alumnos)): ?>
    <?php if ($alumnos): ?>
        <h3>Lista de Alumnos</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Ver Calificaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno): ?>
                    <tr>
                        <td><?= $alumno['nombre'] . ' ' . $alumno['apellido_p'] . ' ' . $alumno['apellido_m'] ?></td>
                        <td>
                            <a href="/GestionEscolar/public/profesores/calificaciones/alumno?id_alumno=<?= $alumno['id_alumno'] ?>&id_grupo=<?= $id_grupo ?>&id_materia=<?= $id_materia ?>">Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="/GestionEscolar/public/profesores/calificaciones">⬅ Volver (a seleccionar grupo y marteria)</a>

    <?php else: ?>
        <p>No hay alumnos asignados a este grupo.</p>
    <?php endif; ?>
<?php endif; ?>

<script>
    const materiasPorGrupo = <?= json_encode($materiasPorGrupo) ?>;

    document.getElementById('id_grupo').addEventListener('change', function() {
        const grupoId = this.value;
        const materiaSelect = document.getElementById('id_materia');

        // Limpiar opciones anteriores
        materiaSelect.innerHTML = '<option value="">Seleccione una materia</option>';

        if (materiasPorGrupo[grupoId]) {
            for (const [id, nombre] of Object.entries(materiasPorGrupo[grupoId])) {
                const option = document.createElement('option');
                option.value = id;
                option.textContent = nombre;
                materiaSelect.appendChild(option);
            }
        }
    });

    // Disparar cambio si ya hay grupo seleccionado (para mantener estado tras submit)
    window.addEventListener('DOMContentLoaded', function() {
        const grupoId = document.getElementById('id_grupo').value;
        if (grupoId) {
            const event = new Event('change');
            document.getElementById('id_grupo').dispatchEvent(event);
        }
    });
</script>
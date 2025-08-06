<h2>Tareas para Calificar</h2>
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Grupo / Materia</th>
            <th>Fecha de Entrega</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tareas as $tarea): ?>
            <tr>
                <td><?= htmlspecialchars(isset($tarea['id_tarea']) ? $tarea['id_tarea'] : '') ?></td>
                <td><?= htmlspecialchars(isset($tarea['nombre']) ? $tarea['nombre'] : '') ?></td>
                <td>
                    <?= htmlspecialchars(
                        (isset($tarea['nivel']) ? $tarea['nivel'] : '') . ' ' .
                        (isset($tarea['grado']) ? $tarea['grado'] : '') . ' ' .
                        (isset($tarea['letra']) ? $tarea['letra'] : '') . ' / ' .
                        (isset($tarea['materia_nombre']) ? $tarea['materia_nombre'] : '')
                    ) ?>
                </td>
                <td><?= htmlspecialchars(isset($tarea['fecha_entrega']) ? $tarea['fecha_entrega'] : '') ?></td>
                <td>
                    <form action="/GestionEscolar/public/profesores/calificar_tarea_detalle" method="GET" style="display:inline;">
                        <input type="hidden" name="id_tarea" value="<?= htmlspecialchars(isset($tarea['id_tarea']) ? $tarea['id_tarea'] : '') ?>">
                        <button type="submit">Calificar</button>
                    </form>

                    <form action="/GestionEscolar/public/profesores/tareas/editar" method="GET" style="display:inline;">
                        <input type="hidden" name="id_tarea" value="<?= htmlspecialchars(isset($tarea['id_tarea']) ? $tarea['id_tarea'] : '') ?>">
                        <button type="submit">Editar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    <br>
    <a href="/GestionEscolar/public/profesores/tareas">⬅ Volver a Tareas</a>
<h2>Calificaciones de <?= $nombreAlumno['nombre'] . ' ' . $nombreAlumno['apellido_p'] . ' ' . $nombreAlumno['apellido_m'] ?></h2>

<?php if (isset($_GET['exito']) && $_GET['exito'] === '1'): ?>
    <p style="color: green;"><strong>✅ Calificaciones registradas correctamente.</strong></p>
<?php endif; ?>

<?php if (count($tareas) > 0): ?>
<fieldset>
    <legend>Listado de Tareas</legend>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Tarea</th>
                <th>Calificación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tareas as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['tarea']) ?></td>
                <td><?= $t['calificacion'] !== null ? $t['calificacion'] : '—' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>

<p><strong>Promedio:</strong> <?= number_format($promedio, 2) ?></p>

<?php else: ?>
    <p>No hay tareas registradas para este alumno en esta materia.</p>
<?php endif; ?>

<?php if (isset($calificacionesPorPeriodo) && count($calificacionesPorPeriodo) > 0): ?>
<fieldset>
    <legend>Calificaciones por Periodo</legend>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Periodo</th>
                <th>Calificación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($calificacionesPorPeriodo as $periodo => $calif): ?>
            <tr>
                <td><?= htmlspecialchars($periodo) ?></td>
                <td><?= $calif !== null ? $calif : '—' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>
<?php endif; ?>

<fieldset>
    <legend>Calificaciones Manuales</legend>
    <?php if (isset($_GET['exito']) && $_GET['exito'] == '1'): ?>
        <p style="color: green;"><strong>✅ Calificaciones registradas correctamente.</strong></p>
    <?php endif; ?>
    <form method="POST" action="/GestionEscolar/public/profesores/calificaciones/final">
        <input type="hidden" name="id_alumno" value="<?= $id_alumno ?>">
        <input type="hidden" name="id_grupo" value="<?= $id_grupo ?>">
        <input type="hidden" name="id_materia" value="<?= $id_materia ?>">

        <label for="calif_p1">Calificación 1er Periodo:</label>
        <input id="calif_p1" type="number" step="0.1" name="calif_p1"
            value="<?= isset($calificacionesPorPeriodo['1er Periodo']) ? $calificacionesPorPeriodo['1er Periodo'] : '' ?>"><br><br>

        <label for="calif_p2">Calificación 2do Periodo:</label>
        <input id="calif_p2" type="number" step="0.1" name="calif_p2"
            value="<?= isset($calificacionesPorPeriodo['2do Periodo']) ? $calificacionesPorPeriodo['2do Periodo'] : '' ?>"><br><br>

        <label for="calif_p3">Calificación 3er Periodo:</label>
        <input id="calif_p3" type="number" step="0.1" name="calif_p3"
            value="<?= isset($calificacionesPorPeriodo['3er Periodo']) ? $calificacionesPorPeriodo['3er Periodo'] : '' ?>"><br><br>

        <label for="final">Calificación Final:</label>
        <input id="final" type="number" step="0.1" name="final"
            value="<?= isset($calificacionFinal) ? $calificacionFinal : '' ?>"><br><br>

        <label for="rubrica">Retroalimentación:</label><br>
        <textarea id="rubrica" name="rubrica" rows="4" cols="50" required><?= isset($retroalimentacion) ? htmlspecialchars($retroalimentacion) : '' ?></textarea><br><br>

        <button type="submit">Guardar calificaciones</button>
    </form>
</fieldset>

<br>
<a href="/GestionEscolar/public/profesores/boleta?id_alumno=<?= $id_alumno ?>">🧾 Ver Boleta del Alumno</a>
<br>
<a href="/GestionEscolar/public/profesores/calificaciones?id_grupo=<?= $id_grupo ?>&id_materia=<?= $id_materia ?>">⬅ Volver a la lista de alumnos</a>

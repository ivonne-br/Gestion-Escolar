<h2>✅ Profesores asignados correctamente a materias según especialidad.</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID Profesor</th>
            <th>Materia</th>
            <th>Grado</th>
            <th>Nivel</th>
            <th>Grupo (Letra)</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($asignaciones)): ?>
        <?php foreach ($asignaciones as $asignacion): ?>
            <tr>
                <td><?= htmlspecialchars($asignacion['id_profesor']) ?></td>
                <td><?= htmlspecialchars($asignacion['nombre_materia']) ?></td>
                <td><?= htmlspecialchars($asignacion['grado']) ?></td>
                <td><?= htmlspecialchars($asignacion['nivel']) ?></td>
                <td><?= htmlspecialchars($asignacion['letra']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">No se realizaron asignaciones.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<br>
<a href="/GestionEscolar/public/grupos/opciones">
    <button>⬅ Volver a Grupos</button>
</a>

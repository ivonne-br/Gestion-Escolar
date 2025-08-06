<?php if (!isset($boleta) || empty($boleta)): ?>
    <h2>No hay datos de boleta disponibles para este alumno.</h2>
<?php else: ?>
    <h2>Boleta de Calificaciones</h2>
    <p><strong>Alumno:</strong> <?= htmlspecialchars($boleta['nombre_alumno']) ?></p>
    <p><strong>Grupo:</strong> <?= htmlspecialchars($boleta['grupo']) ?></p>
    <p><strong>Ciclo Escolar:</strong> <?= htmlspecialchars($boleta['ciclo']) ?></p>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Materia</th>
                <th>1er Periodo</th>
                <th>2do Periodo</th>
                <th>3er Periodo</th>
                <th>Promedio Final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($boleta['materias'] as $materia): ?>
                <tr>
                    <td><?= htmlspecialchars($materia['nombre']) ?></td>
                    <td><?= $materia['calif_1p'] ?? '—' ?></td>
                    <td><?= $materia['calif_2p'] ?? '—' ?></td>
                    <td><?= $materia['calif_3p'] ?? '—' ?></td>
                    <td><?= $materia['calif_final'] ?? '—' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<br>
<a href="/GestionEscolar/public/profesores/calificaciones">⬅ Volver a Calificaciones</a>

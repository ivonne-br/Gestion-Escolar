<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista Previa de Asignación de Grupos</title>
</head>
<body>
    <h2>📄 Vista Previa de Asignación de Grupos</h2>

    <?php if (!empty($gruposAsignados)): ?>
        <form method="POST" action="/GestionEscolar/public/grupos/asignar_final">
            <input type="hidden" name="nivel" value="<?= htmlspecialchars($nivel) ?>">
            <input type="hidden" name="grado" value="<?= htmlspecialchars($grado) ?>">
            <input type="hidden" name="id_ciclo" value="<?= htmlspecialchars($idCiclo) ?>">

            <?php foreach ($gruposAsignados as $letra => $alumnos): ?>
                <h3>Grupo <?= htmlspecialchars($grado) ?>°<?= htmlspecialchars($letra) ?> (<?= htmlspecialchars($nivel) ?>)</h3>
                <ol>
                    <?php foreach ($alumnos as $alumno): ?>
                        <li>
                            <?= htmlspecialchars($alumno['apellido_p'] . ' ' . $alumno['apellido_m'] . ' ' . $alumno['nombre']) ?>
                            <input type="hidden" name="asignaciones[<?= $letra ?>][]" value="<?= $alumno['id_alumno'] ?>">
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endforeach; ?>

            <button type="submit">✅ Confirmar Asignación Final</button>
        </form>
    <?php else: ?>
        <p>No hay alumnos sin grupo disponibles para este nivel y grado.</p>
    <?php endif; ?>

    <br>
    <a href="/GestionEscolar/public/grupos/formulario"><button>⬅ Volver al Formulario</button></a>
</body>
</html>
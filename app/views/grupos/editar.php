<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <?php if (isset($grupo) && $grupo): ?>
        <title>Editar Grupo <?= htmlspecialchars($grupo['letra']) ?></title>
    <?php else: ?>
        <title>Editar Grupo - No encontrado</title>
    <?php endif; ?>
</head>
<body>
    <h2>Editar Grupo: <?php echo isset($grupo) ? htmlspecialchars($grupo['nivel'] . ' ' . $grupo['grado'] . ' ' . $grupo['letra']) : 'No encontrado'; ?></h2>
    <p><strong>Ciclo escolar:</strong> <?php echo isset($grupo['id_ciclo']) ? htmlspecialchars($grupo['id_ciclo']) : 'N/D'; ?></p>

    <form method="POST" action="/GestionEscolar/public/grupos/actualizar">
        <input type="hidden" name="id_grupo" value="<?= isset($grupo['id_grupo']) ? htmlspecialchars($grupo['id_grupo']) : '' ?>">
        <input type="hidden" name="id_ciclo" value="<?= isset($grupo['id_ciclo']) ? htmlspecialchars($grupo['id_ciclo']) : '' ?>">
        
        <fieldset>
            <legend>Alumnos asignados al grupo</legend>
            <?php if (empty($alumnosAsignados)): ?>
                <p>No hay alumnos asignados a este grupo.</p>
            <?php else: ?>
                <label>Seleccione los alumnos que desea quitar:</label>
                <select name="alumnos_eliminar[]" multiple>
                    <?php foreach ($alumnosAsignados as $alumno): ?>
                        <option value="<?= htmlspecialchars($alumno['id_alumno']) ?>">
                            <?= htmlspecialchars($alumno['apellido_p'] . ' ' . $alumno['apellido_m'] . ', ' . $alumno['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </fieldset>

        <fieldset>
            <legend>Alumnos disponibles para agregar</legend>
            <?php if (empty($alumnosDisponibles)): ?>
                <p>No hay alumnos disponibles sin grupo para este nivel y grado.</p>
            <?php else: ?>
                <label>Seleccione los alumnos que desea agregar:</label>
                <select name="alumnos_agregar[]" multiple>
                    <?php foreach ($alumnosDisponibles as $alumno): ?>
                        <option value="<?= htmlspecialchars($alumno['id_alumno']) ?>">
                            <?= htmlspecialchars($alumno['apellido_p'] . ' ' . $alumno['apellido_m'] . ', ' . $alumno['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </fieldset>

        <button type="submit" class="btn">Guardar cambios</button>
    </form>

    <br>
    <a href="/GestionEscolar/public/grupos/index">⬅ Volver a lista de grupos</a>
</body>
</html>
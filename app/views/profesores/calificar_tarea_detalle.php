<?php
if (!isset($tarea)) $tarea = [];
if (!isset($entregados)) $entregados = [];
if (!isset($no_entregados)) $no_entregados = [];
?>

<h2>Calificar tarea: <?= $tarea && isset($tarea['nombre']) ? htmlspecialchars($tarea['nombre']) : 'No disponible' ?></h2>
<p><strong>Descripción:</strong> <?= $tarea && isset($tarea['descripcion']) ? htmlspecialchars($tarea['descripcion']) : 'No disponible' ?></p>
<p>
  <strong>Materia:</strong> <?= $tarea && isset($tarea['materia_nombre']) ? htmlspecialchars($tarea['materia_nombre']) : 'No disponible' ?> |
  <strong>Grupo:</strong> <?= $tarea && isset($tarea['nivel'], $tarea['grado'], $tarea['letra']) ? 
    htmlspecialchars($tarea['nivel'] . ' ' . $tarea['grado'] . ' ' . $tarea['letra']) : 'No disponible' ?>
</p>

<label for="filtro">Buscar alumno:</label>
<input type="text" id="filtro" placeholder="Nombre o Apellido..." onkeyup="filtrarAlumnos()" />
<br /><br />

<script>
    function filtrarAlumnos() {
        const input = document.getElementById('filtro').value.toLowerCase();
        const filas = document.querySelectorAll('tbody tr');
        filas.forEach(fila => {
            const nombre = fila.children[1].textContent.toLowerCase();
            fila.style.display = nombre.includes(input) ? '' : 'none';
        });
    }
</script>

<form action="/GestionEscolar/public/profesores/tareas/calificar" method="POST">
    <input type="hidden" name="id_tarea" value="<?= isset($tarea['id_tarea']) ? htmlspecialchars($tarea['id_tarea']) : '' ?>" />

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre Completo</th>
                <th>Calificación</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = 1;
            foreach (array_merge($entregados, $no_entregados) as $alumno): ?>
                <tr>
                    <td><?= $index++ ?></td>
                    <td><?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido_p'] . ' ' . $alumno['apellido_m']) ?></td>
                    <td>
                        <?php if ($alumno['calificacion'] !== null): ?>
                            <?= htmlspecialchars($alumno['calificacion']) ?>
                        <?php else: ?>
                            <input type="number" step="0.01" min="0" max="10" name="calificacion[<?= htmlspecialchars($alumno['id_alumno']) ?>]" />
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($alumno['observaciones'])): ?>
                            <?= htmlspecialchars($alumno['observaciones']) ?>
                        <?php else: ?>
                            <input type="text" name="observaciones[<?= htmlspecialchars($alumno['id_alumno']) ?>]" placeholder="Observaciones" />
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br />
    <button type="submit">Registrar Calificaciones</button>
</form>
<br>
<a href="/GestionEscolar/public/profesores/calificar_tarea">⬅ Volver a Listado de Tareas</a>
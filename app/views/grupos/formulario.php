<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Asignar Grupos</title>
</head>
<body>
    <h2>Asignar Grupos</h2>

    <form method="GET" action="/GestionEscolar/public/grupos/formulario">
        <label>Nivel:</label>
        <select name="nivel" required>
            <option value="">-- Selecciona un nivel --</option>
            <option value="Primaria" <?= (isset($nivel) && $nivel === 'Primaria') ? 'selected' : '' ?>>Primaria</option>
            <option value="Secundaria" <?= (isset($nivel) && $nivel === 'Secundaria') ? 'selected' : '' ?>>Secundaria</option>
        </select><br><br>

        <label>Grado:</label>
        <select name="grado" required>
            <option value="">-- Selecciona un grado --</option>
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <option value="<?= $i ?>" <?= (isset($grado) && $grado == $i) ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select><br><br>

        <button type="submit">🔍 Filtrar</button>
    </form>
    <br>

    <?php if (!empty($nivel) && !empty($grado) && is_numeric($alumnosSinGrupo)): ?>
        <p><strong>Alumnos sin grupo:</strong> <?= $alumnosSinGrupo ?> alumno(s)</p>

    <form method="POST" action="/GestionEscolar/public/grupos/preview_asignacion">
        <input type="hidden" name="nivel" value="<?= htmlspecialchars($nivel ?? '') ?>">
        <input type="hidden" name="grado" value="<?= htmlspecialchars($grado ?? '') ?>">

        <label>Número de Grupos:</label>
        <select name="num_grupos" required>
            <option value="">-- Número de grupos (ej. 3 para A, B, C) --</option>
            <?php for ($i = 1; $i <= 7; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?> grupo(s)</option>
            <?php endfor; ?>
        </select><br><br>

        <label>Ciclo Escolar:</label>
        <select name="id_ciclo" required>
            <option value="">-- Selecciona un ciclo escolar --</option>
            <?php foreach ($ciclos as $c): ?>
                <option value="<?= $c['id_ciclo'] ?>"><?= htmlspecialchars($c['nombre_ciclo']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">📄 Previsualizar Asignación</button>
    </form>
    <?php endif; ?>

    <br>
    <a href="/GestionEscolar/public/grupos/opciones">⬅ Volver a Grupos</a>
</body>
</html>
<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/GrupoModel.php';

$db = new Database();
$conn = $db->conectar();
$model = new GrupoModel($conn);

// Obtener filtros
$nivel = $_GET['nivel'] ?? '';
$grado = $_GET['grado'] ?? '';

// Listar grupos con filtros aplicados
$grupos = $model->listarGrupos($nivel, $grado);
if (!$grupos) {
    $grupos = [];
}
?>

<h2>📘 Listado de Grupos</h2>

<form method="GET" action="/GestionEscolar/public/grupos/index">
    <label for="nivel">Nivel:</label>
    <select name="nivel" id="nivel">
        <option value="">-- Todos --</option>
        <option value="Primaria" <?= $nivel === 'Primaria' ? 'selected' : '' ?>>Primaria</option>
        <option value="Secundaria" <?= $nivel === 'Secundaria' ? 'selected' : '' ?>>Secundaria</option>
    </select>

    <label for="grado">Grado:</label>
    <select name="grado" id="grado">
        <option value="">-- Todos --</option>
        <?php for ($i = 1; $i <= 6; $i++): ?>
            <option value="<?= $i ?>" <?= $grado == $i ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>

    <button type="submit" form=filtro-form>🔍 Filtrar</button>
    <a href="/GestionEscolar/public/grupos/index" style="margin-left: 10px;">❌ Limpiar Filtros</a>
</form>

<br>
<a href="/GestionEscolar/public/grupos/formulario">➕ Asignar Grupo</a>
<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nivel</th>
            <th>Grado</th>
            <th>Letra</th>
            <th>Ciclo Escolar</th>
            <th>Número de Alumnos</th>
            <th>Editar</th>
            <th>Detalle</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($grupos)): ?>
            <?php foreach ($grupos as $grupo): ?>
                <tr>
                    <td><?= htmlspecialchars($grupo['id_grupo'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($grupo['nivel'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($grupo['grado'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($grupo['letra'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($grupo['nombre_ciclo'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($grupo['total_alumnos'] ?? '0'); ?></td>
                    <td>
                        <a href="/GestionEscolar/public/grupos/editar?id=<?= urlencode($grupo['id_grupo']); ?>">✏️ Editar</a>
                    </td>
                    <td>
                        <a href="/GestionEscolar/public/grupos/detalle?id=<?= urlencode($grupo['id_grupo']); ?>"><button>🔍 Detalle</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" style="text-align: center;">No se encontraron grupos.</td>   
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<br>
<a href="/GestionEscolar/public/grupos/opciones">⬅ Volver a Grupos</a>

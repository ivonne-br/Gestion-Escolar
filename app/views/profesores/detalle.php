<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/ProfesorModel.php';

if (!isset($_GET['id'])) {
    echo "<p style='color:red;'>❌ ID de profesor no especificado.</p>";
    echo "<a href='/GestionEscolar/public/profesores/index'>⬅ Volver al listado</a>";
    exit;
}

$id = $_GET['id'];
$db = new Database();
$pdo = $db->conectar();
$model = new ProfesorModel($pdo);

$profesor = $model->obtenerPorId($id);

if (!$profesor) {
    echo "<p style='color:red;'>❌ Profesor no encontrado.</p>";
    echo "<a href='/GestionEscolar/public/profesores/index'>⬅ Volver al listado</a>";
    exit;
}
?>

<h2>📘 Detalle del Profesor</h2>

<ul>
  <li><strong>ID:</strong> <?= htmlspecialchars($profesor['id_profesor']) ?></li>
  <li><strong>Nombre:</strong> <?= htmlspecialchars($profesor['nombre']) ?></li>
  <li><strong>Apellido Paterno:</strong> <?= htmlspecialchars($profesor['apellido_p']) ?></li>
  <li><strong>Apellido Materno:</strong> <?= htmlspecialchars($profesor['apellido_m']) ?></li>
  <li><strong>Correo:</strong> <?= htmlspecialchars($profesor['correo']) ?></li>
<li><strong>Especialización:</strong> <?= htmlspecialchars($profesor['especialidad'] ?? 'No especificada') ?></li>
</ul>

<?php
$gruposAsignados = $model->obtenerGruposPorProfesor($id);
?>

<h3>👥 Grupos Asignados</h3>
<?php if (!empty($gruposAsignados)): ?>
    <ul>
        <?php foreach ($gruposAsignados as $grupo): ?>
            <li>
                <strong>Nivel:</strong> <?= !empty($grupo['nivel']) ? htmlspecialchars($grupo['nivel']) : 'No especificado' ?><br>
                <strong>Grado:</strong> <?= !empty($grupo['grado']) ? htmlspecialchars($grupo['grado']) : 'No especificado' ?><br>
                <strong>Letra:</strong> <?= !empty($grupo['letra']) ? htmlspecialchars($grupo['letra']) : 'No especificada' ?><br>
                <strong>Materia:</strong> <?= !empty($grupo['materia']) ? htmlspecialchars($grupo['materia']) : 'No especificada' ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Este profesor no tiene grupos asignados.</p>
<?php endif; ?>

<br>
<a href="/GestionEscolar/public/profesores/index">⬅ Volver al listado</a>
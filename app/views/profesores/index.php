<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/ProfesorModel.php';

$db = new Database();
$conn = $db->conectar();
$model = new ProfesorModel($conn);

// Obtener filtros
$nivel = $_GET['nivel'] ?? '';
$materia = $_GET['materia'] ?? '';
$estado = $_GET['estado'] ?? '';
// Obtener profesores con filtros aplicados
$profesores = $model->listarProfesoresConAsignaciones($nivel, $materia, $estado);
if (!$profesores) {
    $profesores = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listado - Profesores</title>
    <link rel="stylesheet" href="/GestionEscolar/public/css/estilo.css">
    <link rel="stylesheet" href="/GestionEscolar/public/css/index_grupos.css">
</head>
<body>
    <div class="header">
        <img src="/GestionEscolar/public/img/logo_colegio.png" alt="Logo Colegio" class="header-logo">
        <div class="header-text">
            <div class="header-title">Colegio Abraham Lincoln</div>
            <div class="header-subtitle">fundado en 1971</div>
        </div>
    </div>
    <div class="usuario-info">
        <div class="usuario-texto">
            <?= $_SESSION['nombre_completo'] ?? '' ?><br>
            <?= $_SESSION['id_usuario'] ?? '' ?>
        </div>
        <img src="/GestionEscolar/public/img/usuario_icono.png" alt="Perfil" class="usuario-icono">
    </div>
    <main>
        <h2>📘 Listado de Profesores</h2>
        <div class="contenedor-dos-columnas">
            <div class="panel-lateral">
                <form id="filtro-form" class="formulario-filtro" method="GET" action="/GestionEscolar/public/profesores/index">
                    <label for="nivel">Nivel:</label>
                    <select name="nivel" id="nivel">
                        <option value="">-- Todos --</option>
                        <option value="Primaria" <?= $nivel === 'Primaria' ? 'selected' : '' ?>>Primaria</option>
                        <option value="Secundaria" <?= $nivel === 'Secundaria' ? 'selected' : '' ?>>Secundaria</option>
                    </select>

                    <label for="estado">Estado:</label>
                    <select name="estado" id="estado">
                        <option value="">-- Todos --</option>
                        <option value="Activo" <?= isset($_GET['estado']) && $_GET['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="Inactivo" <?= isset($_GET['estado']) && $_GET['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>

                    <label for="materia">Materia:</label>
                    <select name="materia" id="materia">
                        <option value="">-- Selecciona una opción --</option>
                        <option value="Español" <?= $materia === 'Español' ? 'selected' : '' ?>>Español</option>
                        <option value="Matemáticas" <?= $materia === 'Matemáticas' ? 'selected' : '' ?>>Matemáticas</option>
                        <option value="Ciencias" <?= $materia === 'Ciencias' ? 'selected' : '' ?>>Ciencias</option>
                        <option value="Geografía" <?= $materia === 'Geografía' ? 'selected' : '' ?>>Geografía</option>
                        <option value="Historia" <?= $materia === 'Historia' ? 'selected' : '' ?>>Historia</option>
                        <option value="Formación Cívica y Ética" <?= $materia === 'Formación Cívica y Ética' ? 'selected' : '' ?>>Formación Cívica y Ética</option>
                        <option value="Artes" <?= $materia === 'Artes' ? 'selected' : '' ?>>Artes</option>
                        <option value="Educación Física" <?= $materia === 'Educación Física' ? 'selected' : '' ?>>Educación Física</option>
                        <option value="Inglés" <?= $materia === 'Inglés' ? 'selected' : '' ?>>Inglés</option>
                        <option value="Tecnología" <?= $materia === 'Tecnología' ? 'selected' : '' ?>>Tecnología</option>
                    </select>
                    <button type="submit" form="filtro-form">🔍 Filtrar</button>
                    <a href="/GestionEscolar/public/profesores/index" style="margin-left: 10px;">❌ Limpiar Filtros</a>
                </form>
                <div class="botones-acciones">
                    <a href="/GestionEscolar/public/profesores/formulario" class="boton-agregar">+ Agregar Profesor</a>
                </div>
            </div>
            <div class="panel-principal">
                <br>
                <table border="1" cellpadding="8" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Materia</th>
                            <th>Grupo(s)</th>
                            <th>Nivel</th>
                            <th>Estado</th>
                            <th>Editar</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($profesores)): ?>
                        <?php foreach ($profesores as $profesor): ?>
                        <tr>
                            <td><?= htmlspecialchars($profesor['id_profesor']) ?></td>
                            <td><?= htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellido_p'] . ' ' . $profesor['apellido_m']) ?></td>
                            <td><?= htmlspecialchars($profesor['materias'] ?? 'Sin asignar') ?></td>
                            <td><?= htmlspecialchars($profesor['grupos'] ?? 'Sin asignar') ?></td>
                            <td><?= htmlspecialchars($profesor['nivel'] ?? 'N/A') ?></td>
                            <td><?= !empty($profesor['grupos']) ? 'Activo' : 'Inactivo' ?></td>
                            <td><a href="/GestionEscolar/public/profesores/editar?id=<?= urlencode($profesor['id_profesor']) ?>">✏️</a></td>
                            <td><a href="/GestionEscolar/public/profesores/detalle?id=<?= urlencode($profesor['id_profesor']) ?>"><button>📘 Ver Detalle</button></a></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="sin-resultados">No se encontraron profesores.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                <br>
                <div class="botones-acciones" style="margin-top: 20px;">
                    <a href="/GestionEscolar/public/profesores/opciones" class="boton-volver">⬅ Volver a Profesores</a>
                    <a href="/GestionEscolar/public/administradores/dashboard" class="boton-volver">⬅ Volver al Dashboard</a>
                </div>
            </div>
        </div> <!-- contenedor-dos-columnas -->
    </main>
</body>
</html>

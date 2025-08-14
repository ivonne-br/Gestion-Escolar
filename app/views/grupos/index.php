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

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listado - Grupos</title>
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
            <?php
            session_start();
            echo isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : '';
            echo "<br>";
            echo isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : '';
            ?>
        </div>
        <img src="/GestionEscolar/public/img/usuario_icono.png" alt="Perfil" class="usuario-icono">
    </div>

    <main>
        <h2>📘 Listado de Grupos</h2>
        <div class="contenedor-dos-columnas" style="display: flex; gap: 30px;">
            <div class="panel-lateral" style="flex: 1; display: flex; flex-direction: column; gap: 20px;">
                <form method="GET" action="/GestionEscolar/public/grupos/index" class="formulario-filtro">
                    <label for="nivel">Nivel:</label>
                    <select name="nivel" id="nivel">
                        <option value="">-- Todos --</option>
                        <option value="Primaria" <?= $nivel === 'Primaria' ? 'selected' : '' ?>>Primaria</option>
                        <option value="Secundaria" <?= $nivel === 'Secundaria' ? 'selected' : '' ?>>Secundaria</option>
                    </select>

                    <br><br>
                    <label for="grado">Grado:</label>
                    <select name="grado" id="grado">
                        <option value="">-- Todos --</option>
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <option value="<?= $i ?>" <?= $grado == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>

                    <button type="submit" class="boton-filtrar">🔍 Filtrar</button>
                    <br><br>
                    <a href="/GestionEscolar/public/grupos/index" class="boton-limpiar">❌ Limpiar Filtros</a>
                </form>
                <div class="botones-acciones" style="display: flex; flex-direction: column; gap: 1px;">
                    <a href="/GestionEscolar/public/grupos/formulario" class="boton-asignar">➕ Asignar Grupo</a>
                    <a href="/GestionEscolar/public/grupos/opciones" class="boton-volver">⬅ Volver a Grupos</a>
                </div>
            </div>

            <div class="panel-principal" style="flex: 3;">
                <table class="grupo-tabla">
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
                                        <a href="/GestionEscolar/public/grupos/editar?id=<?= urlencode($grupo['id_grupo']); ?>" class="boton-editar">✏️ Editar</a>
                                    </td>
                                    <td>
                                        <a href="/GestionEscolar/public/grupos/detalle?id=<?= urlencode($grupo['id_grupo']); ?>" class="boton-detalle">🔍 Detalle</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="sin-resultados">No se encontraron grupos.</td>   
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
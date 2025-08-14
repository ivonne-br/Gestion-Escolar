<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/TutorModel.php';

$db = new Database();
$conn = $db->conectar();
$model = new TutorModel($conn);

$buscar = $_GET['buscar'] ?? '';
$tutores = !empty($buscar)
    ? $model->buscarPorNombreApellido($buscar)
    : $model->listarConDatosDeUsuario();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listado - Tutores</title>
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
        <h2>📘 Listado de Tutores</h2>
        <div class="contenedor-dos-columnas">
            <div class="panel-lateral">
                <form method="GET" action="/GestionEscolar/public/tutores/index" class="formulario-filtro">
                    <label for="buscar">Buscar:</label>
                    <input type="text" name="buscar" id="buscar" placeholder="Nombre o Apellido" value="<?= htmlspecialchars($buscar) ?>">
                    <button type="submit">🔍 Buscar</button>
                    <a href="/GestionEscolar/public/tutores/index" style="margin-left: 10px;">❌ Limpiar</a>
                </form>
                <div class="botones-acciones">
                    <a href="/GestionEscolar/public/tutores/formulario" class="boton-agregar">+ Agregar Tutor</a>
                </div>
            </div>
            <div class="panel-principal">
                <table class="grupo-table" border="1" cellpadding="8" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tutores)): ?>
                            <?php foreach ($tutores as $tutor): ?>
                                <tr>
                                    <td><?= isset($tutor['id_tutor']) ? htmlspecialchars($tutor['id_tutor']) : '' ?></td>
                                    <td><?= isset($tutor['nombre'], $tutor['apellido_paterno'], $tutor['apellido_materno']) ? htmlspecialchars($tutor['nombre'] . ' ' . $tutor['apellido_paterno'] . ' ' . $tutor['apellido_materno']) : '' ?></td>
                                    <td><?= isset($tutor['correo']) ? htmlspecialchars($tutor['correo']) : '' ?></td>
                                    <td><?= isset($tutor['telefono']) ? htmlspecialchars($tutor['telefono']) : '' ?></td>
                                    <td>
                                      <?php if (isset($tutor['id_tutor'])): ?>
                                        <a href="/GestionEscolar/public/tutores/editar?id=<?= urlencode($tutor['id_tutor']); ?>">✏️</a>
                                      <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">No hay tutores registrados todavía.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="botones-acciones" style="margin-top: 20px;">
                <a href="/GestionEscolar/public/tutores/opciones" class="boton-volver">⬅ Volver a Tutores</a>
                <a href="/GestionEscolar/public/administradores/dashboard" class="boton-volver">⬅ Volver al Dashboard</a>
            </div>
        </div>
    </main>
</body>
</html>
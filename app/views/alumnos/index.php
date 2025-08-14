<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/AlumnoModel.php';
require_once __DIR__ . '/../../models/TutorModel.php';

$db = new Database();
$conn = $db->conectar();
$model = new AlumnoModel($conn);
$tutorModel = new TutorModel($conn);

$buscar = $_GET['buscar'] ?? '';
$alumnos = !empty($buscar)
    ? $model->buscarPorNombreApellido($buscar)
    : $model->listarTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado - Alumnos</title>
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
        <h2>📘 Listado de Alumnos</h2>
        <div class="contenedor-dos-columnas">
            <div class="panel-lateral">
                <form method="GET" action="/GestionEscolar/public/alumnos/index" class="formulario-filtro">
                    <label for="buscar">Buscar:</label>
                    <input type="text" name="buscar" id="buscar" placeholder="Nombre o Apellido" value="<?= htmlspecialchars($buscar) ?>">
                    <button type="submit">🔍 Buscar</button>
                    <a href="/GestionEscolar/public/alumnos/index" style="margin-left: 10px;">❌ Limpiar</a>
                </form>
                <div class="botones-acciones">
                    <a href="/GestionEscolar/public/alumnos/formulario" class="boton-agregar">➕ Agregar Alumno</a>
                </div>
            </div>
            <div class="panel-principal">
                <table class="grupo-table" border="1" cellpadding="8" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>CURP</th>
                            <th>Correo</th>
                            <th>Nivel</th>
                            <th>Grado</th>
                            <th>Grupo</th>
                            <th>Tutor</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($alumnos)): ?>
                            <?php foreach ($alumnos as $alumno): ?>
                                <tr>
                                    <td><?= htmlspecialchars($alumno['id_alumno']) ?></td>
                                    <td><?= htmlspecialchars($alumno['nombre_usuario'] . ' ' . $alumno['apellido_paterno'] . ' ' . $alumno['apellido_materno']) ?></td>
                                    <td><?= htmlspecialchars($alumno['curp']) ?></td>
                                    <td><?= htmlspecialchars($alumno['correo_usuario']) ?></td>
                                    <td><?= htmlspecialchars($alumno['nivel']) ?></td>
                                    <td><?= htmlspecialchars($alumno['grado']) ?></td>
                                    <td><?= htmlspecialchars($alumno['grupo'] ?? 'Grupo no asignado') ?></td>
                                    <td>
                                        <?php
                                        if (isset($alumno['id_tutor'])) {
                                            $tutor = $tutorModel->obtenerPorId($alumno['id_tutor']);
                                            echo $tutor
                                                ? htmlspecialchars($tutor['nombre'] . ' ' . $tutor['apellido_p'] . ' ' . $tutor['apellido_m']) . ' (' . $tutor['id_tutor'] . ')'
                                                : 'No asignado';
                                        } else {
                                            echo 'No asignado';
                                        }
                                        ?>
                                    </td>
                                    <td><a href="/GestionEscolar/public/alumnos/editar?id=<?= urlencode($alumno['id_alumno']); ?>">✏️</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align:center;">No hay alumnos registrados todavía.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="botones-acciones" style="margin-top: 20px;">
                    <a href="/GestionEscolar/public/alumnos/opciones" class="boton-volver">⬅ Volver a Alumnos</a>
                    <a href="/GestionEscolar/public/administradores/dashboard" class="boton-volver">⬅ Volver al Dashboard</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
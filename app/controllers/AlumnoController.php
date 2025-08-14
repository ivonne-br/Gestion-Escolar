<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/AlumnoModel.php';

echo '
<head>
    <meta charset="UTF-8">
    <title>Listado - Alumnos</title>
    <link rel="stylesheet" href="/GestionEscolar/public/css/estilo.css">
    <link rel="stylesheet" href="/GestionEscolar/public/css/index_grupos.css">
</head>
';

echo '
<div class="header">
    <img src="/GestionEscolar/public/img/logo_colegio.png" alt="Logo Colegio" class="header-logo">
    <div class="header-text">
        <div class="header-title">Colegio Abraham Lincoln</div>
        <div class="header-subtitle">fundado en 1971</div>
    </div>
</div>
<div class="usuario-info">
    <div class="usuario-texto">'
        . (isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : '') . '<br>' .
        (isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : '') .
    '</div>
    <img src="/GestionEscolar/public/img/usuario_icono.png" alt="Perfil" class="usuario-icono">
</div>
';

$db = new Database();
$conn = $db->conectar();
$alumnoModel = new AlumnoModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si estamos en /alumnos/registrar
    if ($_SERVER['REQUEST_URI'] === '/GestionEscolar/public/alumnos/registrar') {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido_p = trim($_POST['apellido_p'] ?? '');
        $apellido_m = trim($_POST['apellido_m'] ?? '');
        $curp = trim($_POST['curp'] ?? '');
        $id_tutor = trim($_POST['id_tutor'] ?? '');
        $nivel = trim($_POST['nivel'] ?? '');
        $grado = trim($_POST['grado'] ?? '');

        if ($nombre === '' || $apellido_p === '' || $curp === '' || $id_tutor === '' || $nivel === '' || $grado === '') {
            die('Por favor completa todos los campos obligatorios para registrar el alumno.');
        }

        if (!preg_match('/^TU\d+$/', $id_tutor)) {
            die('El ID del tutor debe tener el formato correcto (por ejemplo, TU034).');
        }

        $curp_pattern = '/^[A-Z]{4}\d{6}[HM][A-Z]{5}[0-9A-Z]{2}$/i';
        if (!preg_match($curp_pattern, $curp)) {
            die('El CURP no tiene un formato válido.');
        }

        try {
            $id = $alumnoModel->registrar($nombre, $apellido_p, $apellido_m, $curp, $grado, $nivel, $id_tutor);

            if ($id) {
                echo '<div style="padding: 1em; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; font-family: sans-serif; max-width: 500px; margin: 2em auto; text-align: center;">
                        ✅ Alumno registrado con éxito.<br>ID generado: <strong>' . htmlspecialchars($id) . '</strong>
                      </div>';

                echo '<div class="botones-acciones" style="display: flex; flex-direction: column; align-items: center; margin-top: 20px; gap: 15px;">
                        <a href="/GestionEscolar/public/alumnos/index" class="boton-volver">🔙 Volver al listado de alumnos</a>
                        <a href="/GestionEscolar/public/alumnos/formulario?id_tutor=' . urlencode($id_tutor) . '" class="boton-volver">➕ Agregar otro alumno para este tutor</a>
                        <a href="/GestionEscolar/public/tutores/index" class="boton-volver">👨‍🏫 Ver tutores</a>
                      </div>';
                exit;
            } else {
                throw new Exception("No se pudo registrar el alumno.");
            }
        } catch (PDOException $e) {
            echo "<div style='padding: 1em; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; font-family: sans-serif; max-width: 500px; margin: 2em auto; text-align: center;'>
                    ❌ " . htmlspecialchars($e->getMessage()) . "
                  </div>";
        }

        exit;
    }

    // Si es eliminar o actualizar, mantenemos la lógica anterior:
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'eliminar':
            if (!empty($_POST['id_alumno'])) {
                $id = $_POST['id_alumno'];
                if ($alumnoModel->eliminar($id)) {
                    header('Location: /GestionEscolar/public/alumnos/index');
                    exit;
                } else {
                    echo "<p style='color:red;'>❌ Error al eliminar alumno.</p>";
                    echo "<a href='/GestionEscolar/public/alumnos/index'><button>⬅ Volver al Listado</button></a>";
                    exit;
                }
            } else {
                die('ID de alumno no proporcionado para eliminar.');
            }
            break;

        case 'actualizar':
            if (!empty($_POST['id_alumno'])) {
                $id = $_POST['id_alumno'];
                $nombre = trim($_POST['nombre'] ?? '');
                $apellido_p = trim($_POST['apellido_p'] ?? '');
                $apellido_m = trim($_POST['apellido_m'] ?? '');
                $curp = trim($_POST['curp'] ?? '');
                $id_tutor = trim($_POST['id_tutor'] ?? '');

                if ($nombre === '' || $apellido_p === '' || $curp === '' || $id_tutor === '') {
                    die('Por favor completa todos los campos obligatorios para actualizar el alumno.');
                }

                if (!preg_match('/^TU\d+$/', $id_tutor)) {
                    die('El ID del tutor debe tener el formato correcto (por ejemplo, TU034).');
                }

                $curp_pattern = '/^[A-Z]{4}\d{6}[HM][A-Z]{5}[0-9A-Z]{2}$/i';
                if (!preg_match($curp_pattern, $curp)) {
                    die('El CURP no tiene un formato válido.');
                }

                if ($alumnoModel->actualizar($id, $nombre, $apellido_p, $apellido_m, $curp, $id_tutor)) {
                    echo "<div style='padding:1em;background:#d4edda;color:#155724;border:1px solid #c3e6cb;border-radius:5px;font-family:sans-serif;text-align:center;'>";
                    echo "✅ Alumno actualizado con éxito.";
                    echo "</div>";
                } else {
                    echo "<p style='color:red;'>❌ Error al actualizar alumno.</p>";
                }
                echo "<a href='/GestionEscolar/public/alumnos/index'><button>⬅ Volver al Listado</button></a>";
                exit;
            } else {
                die('ID de alumno no proporcionado para actualizar.');
            }
            break;
    }
}

// Listar alumnos (con búsqueda opcional)
$buscar = $_GET['buscar'] ?? '';

if (!empty($buscar)) {
    $alumnos = $alumnoModel->buscarPorNombreApellido($buscar);
} else {
    $alumnos = $alumnoModel->listarTodos();
}

include __DIR__ . '/../views/alumnos/index.php';
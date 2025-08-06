<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/GrupoModel.php';
require_once __DIR__ . '/../models/CicloEscolarModel.php';

$db = new Database();
$pdo = $db->conectar();
$grupoModel = new GrupoModel($pdo);
$cicloModel = new CicloEscolarModel($pdo);

$ciclos = $cicloModel->listarCiclosEscolares();
$alumnosSinGrupo = $grupoModel->contarAlumnosSinGrupo();

// Ruta: formulario grupos
if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '/grupos/formulario') !== false) {
    $nivel = $_GET['nivel'] ?? '';
    $grado = $_GET['grado'] ?? '';
    $alumnosSinGrupo = '';

    if ($nivel && $grado) {
        $alumnosSinGrupo = $grupoModel->contarAlumnosSinGrupoPorNivelGrado($nivel, (int)$grado);
    }

    require_once __DIR__ . '/../views/grupos/formulario.php';
    exit;
}

// Ruta: asignar alumnos final
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/GestionEscolar/public/grupos/asignar_final') {
    $nivel = $_POST['nivel'] ?? '';
    $grado = $_POST['grado'] ?? '';
    $idCiclo = $_POST['id_ciclo'] ?? '';
    $asignaciones = $_POST['asignaciones'] ?? [];

    if (!$nivel || !$grado || !$idCiclo || empty($asignaciones)) {
        echo "<p style='color:red;'>Faltan datos para realizar la asignación.</p>";
        echo "<a href='/GestionEscolar/public/grupos/formulario'><button>⬅ Volver</button></a>";
        exit;
    }

    // Crear grupos si no existen y mapear letra => id_grupo
    $letras = array_keys($asignaciones);
    $grupoIds = [];

    foreach ($letras as $letra) {
        $stmt = $pdo->prepare("SELECT id_grupo FROM Grupo WHERE nivel = :nivel AND grado = :grado AND letra = :letra AND id_ciclo = :id_ciclo");
        $stmt->execute([
            ':nivel' => $nivel,
            ':grado' => $grado,
            ':letra' => $letra,
            ':id_ciclo' => $idCiclo
        ]);
        $id_grupo = $stmt->fetchColumn();

        if (!$id_grupo) {
            $stmtInsert = $pdo->prepare("INSERT INTO Grupo (nivel, grado, letra, id_ciclo) VALUES (:nivel, :grado, :letra, :id_ciclo)");
            $stmtInsert->execute([
                ':nivel' => $nivel,
                ':grado' => $grado,
                ':letra' => $letra,
                ':id_ciclo' => $idCiclo
            ]);
            $id_grupo = $pdo->lastInsertId();
        }

        $grupoIds[$letra] = $id_grupo;
    }

    // Asignar alumnos a grupos
    foreach ($asignaciones as $letra => $alumnos) {
        $id_grupo = $grupoIds[$letra];
        foreach ($alumnos as $id_alumno) {
            $check = $pdo->prepare("SELECT 1 FROM AsignacionGrupoAlumno WHERE id_alumno = :id_alumno");
            $check->execute([':id_alumno' => $id_alumno]);
            if (!$check->fetch()) {
                $stmtAsignar = $pdo->prepare("INSERT INTO AsignacionGrupoAlumno (id_alumno, id_grupo, id_ciclo) VALUES (:id_alumno, :id_grupo, :id_ciclo)");
                $stmtAsignar->execute([
                    ':id_alumno' => $id_alumno,
                    ':id_grupo' => $id_grupo,
                    ':id_ciclo' => $idCiclo
                ]);
            }
        }
    }

    echo "<h2>✅ Alumnos asignados correctamente a sus grupos.</h2>";
    echo "<a href='/GestionEscolar/public/grupos/index'><button>⬅ Ver Grupos</button></a>";
    exit;
}

// Ruta: listar grupos
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/GestionEscolar/public/grupos/index') {
    $nivel = $_GET['nivel'] ?? '';
    $grado = $_GET['grado'] ?? '';
    
    $grupos = $grupoModel->listarGrupos($nivel, $grado);
    include __DIR__ . '/../views/grupos/index.php';
    exit;
}

// Ruta: registrar grupo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/GestionEscolar/public/grupos/registrar') {
    try {
        $id_generado = $grupoModel->registrarGrupo(
            $_POST['nivel'],
            $_POST['grado'],
            $_POST['letra'],
            $_POST['id_ciclo']
        );

        echo "<h2>Grupo registrado exitosamente</h2>";
        echo "<p><strong>ID generado:</strong> $id_generado</p>";
        echo "<p>";
        echo "<a href='/GestionEscolar/public/grupos/index'><button>⬅ Volver a Grupos</button></a> ";
        echo "<a href='/GestionEscolar/public/grupos/formulario'><button>➕ Asignar Otro Grupo</button></a>";
        echo "</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error al registrar grupo: " . $e->getMessage() . "</p>";
    }
    exit;
}

// Ruta: crear ciclo escolar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/GestionEscolar/public/grupos/crear_ciclo') {
    try {
        $pdo->query("SELECT crear_ciclo_escolar();");
        echo "<h2>✅ Ciclo escolar creado exitosamente</h2>";
        echo "<a href='/GestionEscolar/public/grupos/opciones'><button>⬅ Volver a Grupos</button></a>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error al crear ciclo escolar: " . $e->getMessage() . "</p>";
        echo "<a href='/GestionEscolar/public/grupos/opciones'><button>⬅ Volver a Grupos</button></a>";
    }
    exit;
}

// Ruta: previsualizar asignacion grupos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/GestionEscolar/public/grupos/preview_asignacion') {
    $nivel = $_POST['nivel'] ?? '';
    $grado = $_POST['grado'] ?? '';
    $numGrupos = intval($_POST['num_grupos'] ?? 0);
    $idCiclo = intval($_POST['id_ciclo'] ?? 0);

    if ($nivel && $grado && $numGrupos > 0 && $idCiclo > 0) {
        $alumnos = $grupoModel->listarAlumnosSinGrupoPorNivelGrado($nivel, $grado);

        usort($alumnos, function($a, $b) {
            return strcmp(strtoupper($a['apellido_p']), strtoupper($b['apellido_p'])) 
                ?: strcmp(strtoupper($a['apellido_m']), strtoupper($b['apellido_m'])) 
                ?: strcmp(strtoupper($a['nombre']), strtoupper($b['nombre']));
        });

        $gruposAsignados = [];
        $letras = range('A', 'Z');
        foreach ($alumnos as $index => $alumno) {
            $grupoIndex = $index % $numGrupos;
            $letraGrupo = $letras[$grupoIndex];
            $gruposAsignados[$letraGrupo][] = $alumno;
        }

        require_once __DIR__ . '/../views/grupos/preview_asignacion.php';
        exit;
    } else {
        echo "<p style='color:red;'>Datos incompletos para generar la vista previa.</p>";
        echo "<a href='/GestionEscolar/public/grupos/formulario'><button>⬅ Volver</button></a>";
        exit;
    }
}

// Ruta: editar grupo GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '/grupos/editar') !== false) {
    $id_grupo = $_GET['id'] ?? null;
    if (!$id_grupo) {
        echo "ID de grupo no especificado.";
        exit;
    }

    $grupo = $grupoModel->obtenerGrupoPorId($id_grupo);
    if (!$grupo) {
        echo "<p style='color:red;'>Grupo con ID $id_grupo no encontrado.</p>";
        exit;
    }
    $alumnosAsignados = $grupoModel->listarAlumnosAsignados($id_grupo);
    $alumnosDisponibles = $grupoModel->listarAlumnosSinGrupoPorNivelGrado($grupo['nivel'], $grupo['grado']);
    $ciclos = $cicloModel->listarCiclosEscolares();

    require_once __DIR__ . '/../views/grupos/editar.php';
    exit;
}

// Ruta: eliminar grupo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/GestionEscolar/public/grupos/eliminar') {
    $id_grupo = $_POST['id_grupo'] ?? null;
    if (!$id_grupo) {
        echo "ID de grupo no especificado.";
        exit;
    }

    try {
        $grupoModel->eliminarGrupo($id_grupo);
        echo "<h2>Grupo eliminado exitosamente.</h2>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error al eliminar grupo: " . $e->getMessage() . "</p>";
    }
    
    echo "<a href='/GestionEscolar/public/grupos/index'><button>⬅ Ver Grupos</button></a>";
    exit;
}

// Ruta: actualizar grupo (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/GestionEscolar/public/grupos/actualizar') {
    $id_grupo = $_POST['id_grupo'] ?? null;
    $id_ciclo = $_POST['id_ciclo'] ?? null;
    $alumnosAgregar = $_POST['alumnos_agregar'] ?? [];
    $alumnosEliminar = $_POST['alumnos_eliminar'] ?? [];

    if (!$id_grupo || !$id_ciclo) {
        echo "<p style='color:red;'>Datos incompletos para actualizar el grupo.</p>";
        exit;
    }

    require_once __DIR__ . '/../models/GrupoModel.php';

    if (!empty($alumnosEliminar)) {
        $grupoModel->eliminarAlumnosDeGrupo($id_grupo, $alumnosEliminar);
    }

    if (!empty($alumnosAgregar)) {
        $grupoModel->asignarAlumnosAGrupo($id_grupo, $alumnosAgregar, $id_ciclo);
    }

    echo "<h2>✅ Cambios guardados exitosamente.</h2>";
    echo "<a href='/GestionEscolar/public/grupos/index'><button>⬅ Volver a lista de grupos</button></a>";
    exit;
}
?>
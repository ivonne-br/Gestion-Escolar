<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/ProfesorGrupoModel.php';
require_once __DIR__ . '/../models/ProfesorModel.php';

session_start();

$database = new Database();
$pdo = $database->conectar();

if (isset($_GET['r']) && $_GET['r'] === 'profesores/asignar_resumen') {
    try {
        // Ejecutar función almacenada que retorna las asignaciones
        $sql = "SELECT * FROM asignar_profesores_a_materias_por_especialidad_con_retorno()";
        $stmt = $pdo->query($sql);
        $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargar la vista y pasarle los datos para mostrar el resumen
        require_once __DIR__ . '/../views/grupos/asignar_resumen.php';
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error al asignar profesores: " . $e->getMessage() . "</p>";
        echo "<br><a href='/GestionEscolar/public/grupos/opciones'><button>⬅ Volver a Grupos</button></a>";
    }
    exit;
}

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($request_uri === '/GestionEscolar/public/profesores/grupos') {
    if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
        header("Location: /GestionEscolar/public/login.php");
        exit;
    }

    $model = new ProfesorModel($pdo);
    $idProfesor = $_SESSION['usuario'];
    $grupos = $model->obtenerGruposPorProfesor($idProfesor);

    require_once __DIR__ . '/../views/profesores/grupos.php';
    exit;
}
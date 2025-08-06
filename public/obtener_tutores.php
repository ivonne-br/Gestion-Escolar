<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

try {
    $pdo = (new Database())->conectar();
    $stmt = $pdo->query("SELECT id_tutor, nombre, apellido_p, apellido_m FROM Tutor ORDER BY apellido_p");
    $tutores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tutores);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener tutores', 'detalle' => $e->getMessage()]);
}
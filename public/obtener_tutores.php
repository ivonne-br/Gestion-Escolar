<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

try {
    $pdo = (new Database())->conectar();
    $stmt = $pdo->query("SELECT 
      t.usuario_id AS id_tutor, 
      u.nombre, 
      u.apellido_paterno AS apellido_p, 
      u.apellido_materno AS apellido_m 
    FROM Tutor t 
    JOIN Usuario u ON t.usuario_id = u.id 
    ORDER BY u.apellido_paterno");
    $tutores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tutores);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener tutores', 'detalle' => $e->getMessage()]);
}
?>
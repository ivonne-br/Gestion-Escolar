<?php
$host = 'localhost';
$dbname = 'scholar_management'; 
$user = 'ivonne'; 
$pass = 'ajolote';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa";
} catch (PDOException $e) {
    die("Error al conectar a PostgreSQL: " . $e->getMessage());
}
?>
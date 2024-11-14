<?php
// Configuración de CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET");

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "marketing_turistico");

if ($conn->connect_error) {
    die(json_encode(["mensaje" => "Conexión fallida: " . $conn->connect_error]));
}

// Obtener todas las campañas
$sql = "SELECT id, titulo, descripcion, destino FROM campanas";
$result = $conn->query($sql);

$campanas = [];
while ($row = $result->fetch_assoc()) {
    $campanas[] = $row;
}

echo json_encode($campanas);

// Cerrar la conexión
$conn->close();
?>

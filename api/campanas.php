<?php
// Configuración de CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "marketing_turistico");

if ($conn->connect_error) {
    die(json_encode(["mensaje" => "Conexión fallida: " . $conn->connect_error]));
}

// Verificar que la solicitud sea POST para crear una campaña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    // Validar que los datos necesarios estén presentes
    if (isset($data->titulo) && isset($data->descripcion) && isset($data->destino)) {
        $titulo = $data->titulo;
        $descripcion = $data->descripcion;
        $destino = $data->destino;

        // Insertar la nueva campaña en la base de datos
        $stmt = $conn->prepare("INSERT INTO campanas (titulo, descripcion, destino) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $titulo, $descripcion, $destino);

        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Campaña creada con éxito"]);
        } else {
            echo json_encode(["mensaje" => "Error al crear campaña"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["mensaje" => "Datos incompletos"]);
    }
} else {
    echo json_encode(["mensaje" => "Método de solicitud no permitido"]);
}

// Cerrar la conexión
$conn->close();
?>

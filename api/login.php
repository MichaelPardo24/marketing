<?php
// Permitir solicitudes desde cualquier origen (puedes restringirlo a dominios específicos en producción)
header("Access-Control-Allow-Origin: *");  // Cambia '*' por tu dominio en producción para mayor seguridad
header("Content-Type: application/json; charset=UTF-8");

// Permitir cabeceras específicas, incluyendo Content-Type y Authorization
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Permitir métodos HTTP específicos (GET, POST, PUT, DELETE, etc.)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");

// Verifica si es una solicitud OPTIONS (solicitud preflight) y termina allí si es necesario
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Si la solicitud es OPTIONS, solo devolvemos una respuesta con las cabeceras CORS
    exit(0);
}

$conn = new mysqli("localhost", "root", "", "marketing_turistico");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $email = $data->email;
    $password = $data->password;

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo json_encode(["mensaje" => "Login exitoso", "usuario_id" => $user['id']]);
        } else {
            echo json_encode(["mensaje" => "Contraseña incorrecta"]);
        }
    } else {
        echo json_encode(["mensaje" => "Usuario no encontrado"]);
    }
}

$conn->close();
?>

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

// Si la solicitud es de tipo GET, se consultan los usuarios
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener todos los usuarios
    $sql = "SELECT id, nombre, email FROM usuarios";
    $result = $conn->query($sql);

    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    // Devolver los usuarios en formato JSON
    echo json_encode($usuarios);
} 

// Si la solicitud es de tipo POST, se inserta un nuevo usuario
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    // Verificar que los datos necesarios estén presentes
    if (isset($data->nombre) && isset($data->email) && isset($data->password)) {
        $nombre = $data->nombre;
        $email = $data->email;
        $password = password_hash($data->password, PASSWORD_BCRYPT); // Encriptar la contraseña

        // Validar si el email ya existe en la base de datos
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo json_encode(["mensaje" => "El correo electrónico ya está registrado"]);
        } else {
            // Insertar el nuevo usuario en la base de datos
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $email, $password);

            if ($stmt->execute()) {
                echo json_encode(["mensaje" => "Usuario creado con éxito"]);
            } else {
                echo json_encode(["mensaje" => "Error al crear usuario"]);
            }

            $stmt->close();
        }
    } else {
        echo json_encode(["mensaje" => "Datos incompletos"]);
    }
} else {
    echo json_encode(["mensaje" => "Método de solicitud no permitido"]);
}

// Cerrar la conexión
$conn->close();
?>

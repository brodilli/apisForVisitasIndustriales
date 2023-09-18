<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';
$conn = conectarDb();

$data = json_decode(file_get_contents("php://input"));

if ($data !== null && isset($data->correo) && isset($data->contraseña)) {
    $correo = $data->correo;
    $contraseña = $data->contraseña;

    try {
        $stmt = $conn->prepare("SELECT * FROM `usuario` WHERE `correo` = :correo AND `contraseña` = :contrasena");
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contrasena', $contraseña);

        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rs) {
                http_response_code(200);
                echo json_encode(array("data" => $rs, "Status" => "200"));
            } else {
                http_response_code(404);
                echo json_encode(array("error" => "Usuario no encontrado", "Status" => "404"));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Error en la consulta SQL", "Status" => "500"));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array("error" => $e->getMessage(), "Status" => "500"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Los datos de inicio de sesión son incorrectos.", "Status" => "400"));
}
?>

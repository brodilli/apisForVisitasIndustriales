<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conectar.php';
$conn = conectarDb();

$data = json_decode(file_get_contents("php://input"));

if ($data !== null && isset($data->correo) && isset($data->contraseña)) {
    $correo = $data->correo;
    $contraseña = $data->contraseña;
    // Resto del código
} else {
    // Manejar el caso en el que los datos no están presentes o son incorrectos
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Los datos de inicio de sesión son incorrectos."));
}


try {
    $stmt = $conn->prepare("SELECT * FROM `usuario` WHERE `correo` = :correo AND `contraseña` = :contraseña");
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contraseña', $contraseña);

    if ($stmt->execute()) {
        $rs = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rs) {
            http_response_code(200);
            $outp = json_encode($rs);
            echo $outp;
        } else {
            http_response_code(404);
            $outp = '{"error":"Usuario no encontrado"}';
            echo $outp;
        }
    } else {
        http_response_code(500);
        $outp = '{"error":"Error en la consulta SQL"}';
        echo $outp;
    }
} catch (PDOException $e) {
    http_response_code(500);
    $outp = '{"error":"' . $e->getMessage() . '"}';
    echo $outp;
}
?>

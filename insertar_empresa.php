<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';

$con = conectarDb();

$data = json_decode(file_get_contents('php://input'));

$tipoUser = isset($data->tipoUser) ? $data->tipoUser : null;
$nombres = isset($data->nombres) ? $data->nombres : null;
$apellidoP = isset($data->apellidoP) ? $data->apellidoP : null;
$apellidoM = isset($data->apellidoM) ? $data->apellidoM : null;
$correo = isset($data->correo) ? $data->correo : null;
$contraseña = isset($data->contraseña) ? $data->contraseña : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        $tipoUser !== null &&
        $nombres !== null &&
        $apellidoP !== null &&
        $apellidoM !== null &&
        $correo !== null &&
        $contraseña !== null
    ) {
        try {
            $stmt = $con->prepare("SELECT COUNT(*) FROM `usuario` WHERE `correo` = :correo");
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            $nums = $stmt->fetchColumn();

            if ($nums > 0) {
                http_response_code(400); // Bad Request
                echo json_encode(array('isOk' => 'existe', 'msj' => 'Correo ya registrado'));
            } else {
                $stmt = $con->prepare("INSERT INTO `usuario` (`tipoUser`, `nombres`, `apellidoP`, `apellidoM`, `correo`, `contraseña`) 
                    VALUES (:tipoUser, :nombres, :apellidoP, :apellidoM, :correo, :contraseña)");

                $stmt->bindParam(':tipoUser', $tipoUser);
                $stmt->bindParam(':nombres', $nombres);
                $stmt->bindParam(':apellidoP', $apellidoP);
                $stmt->bindParam(':apellidoM', $apellidoM);
                $stmt->bindParam(':correo', $correo);
                $stmt->bindParam(':contraseña', $contraseña);

                if ($stmt->execute()) {
                    http_response_code(200);
                    echo json_encode(array('isOk' => 'true', 'msj' => 'Registro exitoso'));
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(array('isOk' => 'false', 'msj' => 'Error en la base de datos: ' . $stmt->errorInfo()));
                }
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('isOk' => 'false', 'msj' => 'Error en la base de datos: ' . $e->getMessage()));
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(array('isOk' => 'false', 'msj' => 'Faltan campos en la solicitud.'));
    }
}

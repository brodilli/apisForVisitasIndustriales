<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';

// Establecer la conexión con la base de datos usando PDO
$conexion = conectarDb();

$data = json_decode(file_get_contents('php://input'));

// Verificar el método de solicitud
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si los campos requeridos no están vacíos
    if (
        isset($data->tipoUser) && !empty($data->tipoUser) &&
        isset($data->nombres) && !empty($data->nombres) &&
        isset($data->apellidoP) && !empty($data->apellidoP) &&
        isset($data->apellidoM) && !empty($data->apellidoM) &&
        isset($data->correo) && !empty($data->correo) &&
        isset($data->contraseña) && !empty($data->contraseña)
    ) {
        $tipoUser = $data->tipoUser;
        $nombres = $data->nombres;
        $apellidoP = $data->apellidoP;
        $apellidoM = $data->apellidoM;
        $correo = $data->correo;
        $contraseña = $data->contraseña;
        $numSesion = 0;

        // Preparar la consulta para verificar si el correo ya está registrado
        $stmt = $conexion->prepare("SELECT * FROM `usuario` WHERE `correo` = :correo");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        $nums = $stmt->rowCount();

        if ($nums > 0) {
            http_response_code(400); // Bad Request
            echo json_encode(array('isOk' => 'existe', 'msj' => 'Correo ya registrado'));
        } else {
            // Preparar la consulta para insertar un nuevo registro
            $stmt = $conexion->prepare("INSERT INTO `usuario` (`tipoUser`, `nombres`, `apellidoP`, `apellidoM`, `correo`, `contraseña`, `numSesion`) 
                                        VALUES (:tipoUser, :nombres, :apellidoP, :apellidoM, :correo, :contraseña, :numSesion)");
            $stmt->bindParam(':tipoUser', $tipoUser);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidoP', $apellidoP);
            $stmt->bindParam(':apellidoM', $apellidoM);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':contraseña', $contraseña);
            $stmt->bindParam(':numSesion', $numSesion);

            
            $debugInfo = $stmt->debugDumpParams();
var_dump($debugInfo);

            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(array('isOk' => 'true', 'msj' => 'Registro exitoso'));
            } else {
                http_response_code(500); // Internal Server Error
                $errorInfo = $stmt->errorInfo();
                echo json_encode(array('isOk' => 'false', 'msj' => 'Error en la base de datos: ' . implode(" ", $errorInfo)));
            }
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(array('isOk' => 'false', 'msj' => 'Faltan campos en la solicitud.'));
    }
}
?>

<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';
$con = conectarDb();

if (!$con) {
    http_response_code(500);
    echo json_encode(array('isOk' => false, 'msj' => 'Error en la conexión a la base de datos: ' . mysqli_connect_error()));
    die();
}

$data = json_decode(file_get_contents('php://input'));

$tipoUser = isset($data->tipoUser) ? $data->tipoUser : null;
$nombres = isset($data->nombres) ? $data->nombres : null;
$apellidoP = isset($data->apellidoP) ? $data->apellidoP : null;
$apellidoM = isset($data->apellidoM) ? $data->apellidoM : null;
$correo = isset($data->correo) ? $data->correo : null;
$contraseña = isset($data->contraseña) ? $data->contraseña : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !empty($tipoUser) &&
        !empty($nombres) &&
        !empty($apellidoP) &&
        !empty($apellidoM) &&
        !empty($correo) &&
        !empty($contraseña)
    ) {
        $sqlQuery = "INSERT INTO `usuario` (`tipoUser`, `nombres`, `apellidoP`, `apellidoM`, `correo`, `contraseña`) 
            VALUES ('$tipoUser', '$nombres', '$apellidoP', '$apellidoM', '$correo', '$contraseña')";

        try {
            if ($con->query($sqlQuery) === TRUE) {
                http_response_code(200);
                echo json_encode(array('isOk' => true, 'msj' => 'Registro exitoso'));
            } else {
                http_response_code(500);
                echo json_encode(array('isOk' => false, 'msj' => 'Error en la base de datos: ' . $con->error));
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array('isOk' => false, 'msj' => 'Error en el servidor: ' . $e->getMessage()));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('isOk' => false, 'msj' => 'Faltan campos en la solicitud.'));
    }
    mysqli_close($con);
}
?>

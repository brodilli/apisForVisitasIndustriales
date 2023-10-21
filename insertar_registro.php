<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';
$con = conectarDb();

$data = json_decode(file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar que los campos requeridos estén presentes en la solicitud
    if (isset($data->tipoUser, $data->nombres, $data->apellidoP, $data->apellidoM, $data->correo, $data->contraseña)) {
        $tipoUser = $data->tipoUser;
        $nombres = $data->nombres;
        $apellidoP = $data->apellidoP;
        $apellidoM = $data->apellidoM;
        $correo = $data->correo;
        $contraseña = $data->contraseña;

        // Verificar si el correo ya está registrado
        $result = mysqli_query($con, "SELECT * FROM `usuario` WHERE `correo` = '" . $correo . "'");
        $nums = mysqli_num_rows($result);

        if ($nums > 0) {
            echo json_encode(array('isOk' => 'existe', 'msj' => 'Correo ya registrado'));
        } else {
            // Insertar el nuevo registro
            $sqlQuery = "INSERT INTO `usuario` (`tipoUser`, `nombres`, `apellidoP`, `apellidoM`, `correo`, `contraseña`) 
             VALUES ('$tipoUser', '$nombres', '$apellidoP', '$apellidoM', '$correo', '$contraseña')";

            if ($con->query($sqlQuery) === TRUE) {
                http_response_code(200);
                echo json_encode(array('isOk' => 'true', 'msj' => 'Registro exitoso'));
            } else {
                http_response_code(500);
                echo json_encode(array('isOk' => 'false', 'msj' => 'Error en la base de datos: ' . $con->error));
            }
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(array('isOk' => 'false', 'msj' => 'Faltan campos en la solicitud.'));
    }
    mysqli_close($con);
}
?>

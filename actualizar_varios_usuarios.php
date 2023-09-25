<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';

$conexion = conectarDb();
$dataObject = json_decode(file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = isset($dataObject->id_usuario) ? $dataObject->id_usuario : null;
    $tipoUser = isset($dataObject->tipoUser) ? $dataObject->tipoUser : null;
    $nombres = isset($dataObject->nombres) ? $dataObject->nombres : null;
    $apellidoP = isset($dataObject->apellidoP) ? $dataObject->apellidoP : null;
    $apellidoM = isset($dataObject->apellidoM) ? $dataObject->apellidoM : null;
    $correo = isset($dataObject->correo) ? $dataObject->correo : null;
    $contraseña = isset($dataObject->contraseña) ? $dataObject->contraseña : null;

    if ($id_usuario !== null) {
        $actualizacion = "UPDATE `usuario` SET 
            `nombres`='$nombres',
            `tipoUser`='$tipoUser',
            `apellidoP`='$apellidoP',
            `apellidoM`='$apellidoM',
            `correo`='$correo',
            `contraseña`='$contraseña'
            WHERE id_usuario = $id_usuario";

        $resultadoActualizacion = mysqli_query($conexion, $actualizacion);

        if ($resultadoActualizacion) {
            echo json_encode(array('isOk' => true, 'msj' => 'Registro editado de forma exitosa.'));
        } else {
            echo json_encode(array('isOk' => false, 'msj' => $conexion->error));
        }
    } else {
        echo json_encode(array('isOk' => false, 'msj' => 'Falta el parámetro id_usuario en la solicitud.'));
    }
} else {
    echo json_encode(array('isOk' => false, 'msj' => 'Método de solicitud no permitido.'));
}
?>

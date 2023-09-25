<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';

function actualizarUsuario($conexion, $dataObject) {
    if (isset($dataObject->id_usuario)) {
        $id_usuario = $dataObject->id_usuario;
        $tipoUser = isset($dataObject->tipoUser) ? $dataObject->tipoUser : null;
        $nombres = isset($dataObject->nombres) ? $dataObject->nombres : null;
        $apellidoP = isset($dataObject->apellidoP) ? $dataObject->apellidoP : null;
        $apellidoM = isset($dataObject->apellidoM) ? $dataObject->apellidoM : null;
        $correo = isset($dataObject->correo) ? $dataObject->correo : null;
        $contraseña = isset($dataObject->contraseña) ? $dataObject->contraseña : null;

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
            return array('isOk' => true, 'msj' => 'Registro editado de forma exitosa.');
        } else {
            return array('isOk' => false, 'msj' => $conexion->error);
        }
    } else {
        return array('isOk' => false, 'msj' => 'Falta el parámetro id_usuario en la solicitud.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = conectarDb();
    $dataObject = json_decode(file_get_contents('php://input'));
    $response = actualizarUsuario($conexion, $dataObject);
    echo json_encode($response);
} else {
    echo json_encode(array('isOk' => false, 'msj' => 'Método de solicitud no permitido.'));
}
?>

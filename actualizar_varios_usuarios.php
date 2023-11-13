<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

        try {
            // Utilice consultas preparadas para evitar la inyección SQL
            $stmt = $conexion->prepare("UPDATE `usuario` SET 
                `nombres`=?, `tipoUser`=?, `apellidoP`=?, `apellidoM`=?, `correo`=?, `contraseña`=?
                WHERE id_usuario = ?");
            $stmt->bind_param("ssssssi", $nombres, $tipoUser, $apellidoP, $apellidoM, $correo, $contraseña, $id_usuario);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return array('isOk' => true, 'msj' => 'Registro editado de forma exitosa.');
            } else {
                return array('isOk' => false, 'msj' => 'No se realizó ninguna edición en el registro.');
            }
        } catch (Exception $e) {
            return array('isOk' => false, 'msj' => 'Error en la solicitud: ' . $e->getMessage());
        }
    } else {
        return array('isOk' => false, 'msj' => 'Falta el parámetro id_usuario en la solicitud.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = conectarDb();
    $dataObject = json_decode(file_get_contents('php://input'));
    
    // Agregar autenticación y autorización aquí si es necesario
    
    $response = actualizarUsuario($conexion, $dataObject);
    echo json_encode($response);
} else {
    echo json_encode(array('isOk' => false, 'msj' => 'Método de solicitud no permitido.'));
}
?>

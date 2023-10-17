<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';

$conexion = conectarDb();
$dataObject = json_decode(file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($dataObject->id_usuario) && isset($dataObject->numSesion)) {
        $id_usuario = $dataObject->id_usuario;
        $numSesion = $dataObject->numSesion;

        $actualizacion = "UPDATE `usuario` SET `numSesion` = :numSesion WHERE id_usuario = :id_usuario";

        $stmt = $conexion->prepare($actualizacion);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':numSesion', $numSesion);

        if ($stmt->execute()) {
            echo json_encode(array('isOk' => true, 'msj' => 'Registro editado de forma exitosa.'));
        } else {
            echo json_encode(array('isOk' => false, 'msj' => 'Error al editar el registro: ' . $stmt->errorInfo()));
        }
    } else {
        echo json_encode(array('isOk' => false, 'msj' => 'Datos faltantes en la solicitud.'));
    }
}
?>

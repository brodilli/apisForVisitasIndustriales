<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';

$conexion = conectarDb();
$dataObject = json_decode(file_get_contents("php://input"));

$id_usuario = isset($dataObject->id_usuario) ? $dataObject->id_usuario : null;
$numSesion = isset($dataObject->numSesion) ? $dataObject->numSesion : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($id_usuario !== null && $numSesion !== null) {
        try {
            $actualizacion = "UPDATE `usuario` SET `numSesion` = :numSesion WHERE `id_usuario` = :id_usuario";
            
            $stmt = $conexion->prepare($actualizacion);
            $stmt->bindParam(':numSesion', $numSesion, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                echo json_encode(array('isOk' => true, 'msj' => 'Registro editado de forma exitosa.'));
            } else {
                echo json_encode(array('isOk' => false, 'msj' => 'Error al editar el registro.'));
            }
        } catch (PDOException $e) {
            echo json_encode(array('isOk' => false, 'msj' => 'Error en la base de datos: ' . $e->getMessage()));
        }
    } else {
        echo json_encode(array('isOk' => false, 'msj' => 'Faltan parámetros obligatorios.'));
    }
} else {
    echo json_encode(array('isOk' => false, 'msj' => 'Método no permitido.'));
}
?>

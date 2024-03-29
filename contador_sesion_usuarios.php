<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';

$conexion = conectarDb();
$dataObject = json_decode(file_get_contents('php://input'));

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
                // Consulta para obtener nombres y tipoUser
                $consultaDatos = "SELECT nombres, tipoUser FROM usuario WHERE id_usuario = :id_usuario";
                $stmtDatos = $conexion->prepare($consultaDatos);
                $stmtDatos->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmtDatos->execute();
                $datos = $stmtDatos->fetch(PDO::FETCH_ASSOC);
            
                http_response_code(200);
                echo json_encode(array(
                    'isOk' => true,
                    'msj' => 'Registro editado de forma exitosa.',
                    'nombres' => $datos['nombres'],
                    'id_usuario' => $id_usuario,
                    'numSesion' => $numSesion,
                    'tipoUser' => $datos['tipoUser']
                ));            
            } else {
                http_response_code(500);
                echo json_encode(array('isOk' => false, 'msj' => 'Error al editar el registro.'));
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('isOk' => false, 'msj' => 'Error en la base de datos: ' . $e->getMessage()));
        }
    } else {
        echo json_encode(array('isOk' => false, 'msj' => 'Datos faltantes en la solicitud.'));
    }
}
?>

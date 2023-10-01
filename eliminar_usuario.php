<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';

$conexion = conectarDb();
$dataObject = json_decode(file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($dataObject->id_usuario)) {
        $id_usuario = $dataObject->id_usuario;
        
        try {
            $eliminar = "DELETE FROM `usuario` WHERE id_usuario = :id_usuario";
            
            $stmt = $conexion->prepare($eliminar);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(array('isOk' => true, 'msj' => 'Registro eliminado de forma exitosa.'));
            } else {
                http_response_code(500);
                echo json_encode(array('isOk' => false, 'msj' => 'Error al eliminar el registro.'));
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('isOk' => false, 'msj' => 'Error en la base de datos: ' . $e->getMessage()));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('isOk' => false, 'msj' => 'Falta el parámetro id_usuario en la solicitud.'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('isOk' => false, 'msj' => 'Método no permitido.'));
}
?>

<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

require 'conectar.php';

$conexion = conectarDb();
$dataObject = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($dataObject->id_usuario, $dataObject->contraseña, $dataObject->numTelefono)) {
        $id_usuario = $dataObject->id_usuario;
        $contraseña = $dataObject->contraseña;
        $numTelefono = $dataObject->numTelefono;

        try {
            $actualizacion = "UPDATE `usuario` SET `contraseña` = :contrasena, `numTelefono` = :telefono WHERE `id_usuario` = :id";

            $stmt = $conexion->prepare($actualizacion);
            $stmt->bindParam(':contrasena', $contraseña, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $numTelefono, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(array('isOk' => true, 'msj' => 'Registro editado de forma exitosa.'));
            } else {
                echo json_encode(array('isOk' => false, 'msj' => 'Error al editar el registro.'));
            }
        } catch (PDOException $e) {
            echo json_encode(array('isOk' => false, 'msj' => 'Error en la base de datos: ' . $e->getMessage()));
        }
    } else {
        echo json_encode(array('isOk' => false, 'msj' => 'Faltan parámetros en la solicitud.'));
    }
} else {
    echo json_encode(array('isOk' => false, 'msj' => 'Método de solicitud no permitido.'));
}
?>

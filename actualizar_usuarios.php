<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php'; // Asegúrate de que conectar.php establezca la conexión PDO correctamente
$conexion = conectarDb();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $dataObject = json_decode(file_get_contents("php://input"));

        $id_usuario = $dataObject->id_usuario;
        $nombres = $dataObject->nombres;
        $apellidoP = $dataObject->apellidoP;
        $apellidoM = $dataObject->apellidoM;
        $correo = $dataObject->correo;
        $contraseña = $dataObject->contraseña;
        $numTelefono = $dataObject->numTelefono;

        // Consulta SQL preparada para actualizar los datos
        $sqlQuery = "UPDATE usuario SET 
            nombres = :nombres, 
            apellidoP = :apellidoP,
            apellidoM = :apellidoM,
            correo = :correo,
            contraseña = :contraseña,
            numTelefono = :numTelefono
            WHERE id_usuario = :id_usuario";

        $stmt = $conexion->prepare($sqlQuery);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidoP', $apellidoP);
        $stmt->bindParam(':apellidoM', $apellidoM);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contraseña', $contraseña);
        $stmt->bindParam(':numTelefono', $numTelefono);
        $stmt->bindParam(':id_usuario', $id_usuario);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array('isOk' => true, 'msj' => 'Registro editado de forma exitosa.'));
        } else {
            throw new Exception("Error en la consulta");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array('isOk' => false, 'msj' => 'Error en la solicitud: ' . $e->getMessage()));
    }
}
?>

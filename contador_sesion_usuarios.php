<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conectar.php';

try {
    $pdo = conectarDb(); // Reemplaza esta línea con la función que crea la conexión PDO

    $dataObject = json_decode(file_get_contents("php://input"));

    $id_usuario = $dataObject->id_usuario;
    $numSesion = $dataObject->numSesion;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $stmt = $pdo->prepare("UPDATE `usuario` SET `numSesion` = :numSesion WHERE `id_usuario` = :id_usuario");
        $stmt->bindParam(':numSesion', $numSesion, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(array('isOk' => true, 'msj' => 'Registro editado de forma exitosa.'));
        } else {
            echo json_encode(array('isOk' => false, 'msj' => 'No se encontraron registros para actualizar.'));
        }
    }
} catch (PDOException $e) {
    echo json_encode(array('isOk' => false, 'msj' => $e->getMessage()));
}
?>

<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';
$con = conectarDb();

$data = json_decode(file_get_contents('php://input'));

$tipoUser = isset($data->tipoUser) ? $data->tipoUser : null;
$nombres = isset($data->nombres) ? $data->nombres : null;
$apellidoP = isset($data->apellidoP) ? $data->apellidoP : null;
$apellidoM = isset($data->apellidoM) ? $data->apellidoM : null;
$correo = isset($data->correo) ? $data->correo : null;
$contraseña = isset($data->contraseña) ? $data->contraseña : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        $tipoUser !== null &&
        $nombres !== null &&
        $apellidoP !== null &&
        $apellidoM !== null &&
        $correo !== null &&
        $contraseña !== null
    ) {
        try {
            $result = mysqli_query($con, "SELECT * FROM `usuario` WHERE `correo` = '".$correo."'");
            $nums = mysqli_num_rows($result);

            if ($nums > 0) {
                http_response_code(400);
                echo json_encode(array('isOk' => 'existe', 'msj' => 'Correo ya registrado'));
            } else {
                $sqlQuery = "INSERT INTO `usuario`(`tipoUser`, `nombres`, `apellidoP`, `apellidoM`, `correo`, `contraseña`)
                    VALUES (:tipoUser, :nombres, :apellidoP, :apellidoM, :correo, :contraseña)";
                
                $stmt = $con->prepare($sqlQuery);
                $stmt->bindParam(':tipoUser', $tipoUser);
                $stmt->bindParam(':nombres', $nombres);
                $stmt->bindParam(':apellidoP', $apellidoP);
                $stmt->bindParam(':apellidoM', $apellidoM);
                $stmt->bindParam(':correo', $correo);
                $stmt->bindParam(':contraseña', $contraseña);

                if ($stmt->execute()) {
                    http_response_code(200);
                    echo json_encode(array('isOk' => true, 'msj' => 'Registro exitoso'));
                } else {
                    http_response_code(500);
                    echo json_encode(array('isOk' => false, 'msj' => $con->error));
                }
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('isOk' => false, 'msj' => 'Error en la base de datos: ' . $e->getMessage()));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('isOk' => false, 'msj' => 'Faltan parámetros obligatorios.'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('isOk' => false, 'msj' => 'Método no permitido.'));
}
?>

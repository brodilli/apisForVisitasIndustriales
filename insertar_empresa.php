<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';
$con = conectarDb();

$data = json_decode(file_get_contents('php://input'));

$nombre_empresa = isset($data->nombre_empresa) ? $data->nombre_empresa : null;
$lugar = isset($data->lugar) ? $data->lugar : null;
$nombre_contacto = isset($data->nombre_contacto) ? $data->nombre_contacto : null;
$correo_contacto = isset($data->correo_contacto) ? $data->correo_contacto : null;
$telefono_contacto = isset($data->telefono_contacto) ? $data->telefono_contacto : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        $nombre_empresa !== null &&
        $lugar !== null &&
        $nombre_contacto !== null &&
        $correo_contacto !== null &&
        $telefono_contacto !== null
    ) {
        try {
            $sqlQuery = "INSERT INTO `empresa`(`nombre_empresa`, `lugar`, `nombre_contacto`, `correo_contacto`, `telefono_contacto`)
                VALUES (:nombre_empresa, :lugar, :nombre_contacto, :correo_contacto, :telefono_contacto)";
            
            $stmt = $con->prepare($sqlQuery);
            $stmt->bindParam(':nombre_empresa', $nombre_empresa);
            $stmt->bindParam(':lugar', $lugar);
            $stmt->bindParam(':nombre_contacto', $nombre_contacto);
            $stmt->bindParam(':correo_contacto', $correo_contacto);
            $stmt->bindParam(':telefono_contacto', $telefono_contacto);

            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(array('isOk' => true, 'msj' => 'Registro exitoso'));
            } else {
                http_response_code(500);
                echo json_encode(array('isOk' => false, 'msj' => $con->error));
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

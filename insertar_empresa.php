<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';
$con = conectarDB();

if (!$con) {
    http_response_code(500);
    echo json_encode(array('isOk' => false, 'msj' => 'Error en la conexión a la base de datos: ' . mysqli_connect_error()));
    die();
}

$data = json_decode(file_get_contents('php://input'));

$nombre_empresa = isset($data->nombre_empresa) ? $data->nombre_empresa : null;
$lugar = isset($data->lugar) ? $data->lugar : null;
$nombre_contacto = isset($data->nombre_contacto) ? $data->nombre_contacto : null;
$correo_contacto = isset($data->correo_contacto) ? $data->correo_contacto : null;
$telefono_contacto = isset($data->telefono_contacto) ? $data->telefono_contacto : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !empty($nombre_empresa) &&
        !empty($lugar) &&
        !empty($nombre_contacto) &&
        !empty($correo_contacto) &&
        !empty($telefono_contacto)
    ) {
        // Log de datos recibidos
        error_log('Received data: ' . print_r($data, true));

        // Consulta preparada para evitar inyección SQL
        $sqlQuery = "INSERT INTO `empresa` (`nombre_empresa`, `lugar`, `nombre_contacto`, `correo_contacto`, `telefono_contacto`) 
            VALUES (:nombre_empresa, :lugar, :nombre_contacto, :correo_contacto, :telefono_contacto)";

        try {
            $stmt = $con->prepare($sqlQuery);
            $stmt->bindParam(':nombre_empresa', $nombre_empresa);
            $stmt->bindParam(':lugar', $lugar);
            $stmt->bindParam(':nombre_contacto', $nombre_contacto);
            $stmt->bindParam(':correo_contacto', $correo_contacto);
            $stmt->bindParam(':telefono_contacto', $telefono_contacto);

            $stmt->execute();

            http_response_code(200);
            echo json_encode(array('isOk' => true, 'msj' => 'Registro exitoso'));
        } catch (Exception $e) {
            // Log de errores
            error_log('Error en el servidor: ' . $e->getMessage());

            http_response_code(500);
            echo json_encode(array('isOk' => false, 'msj' => 'Error en el servidor: ' . $e->getMessage()));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('isOk' => false, 'msj' => 'Faltan campos en la solicitud.'));
    }

    mysqli_close($con);
}
?>

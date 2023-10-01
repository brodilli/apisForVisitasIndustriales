<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';
$con = conectarDb();

$data = json_decode(file_get_contents('php://input'));

$id_usuario = isset($data->id_usuario) ? $data->id_usuario : null;
$id_carrera = isset($data->id_carrera) ? $data->id_carrera : null;
$semestre = isset($data->semestre) ? $data->semestre : null;
$grupo = isset($data->grupo) ? $data->grupo : null;
$asignatura = isset($data->asignatura) ? $data->asignatura : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        $id_usuario !== null &&
        $id_carrera !== null &&
        $semestre !== null &&
        $grupo !== null &&
        $asignatura !== null
    ) {
        // Verificar el número de solicitudes existentes
        $sqlQuery = "SELECT COUNT(*) AS total FROM solicitud_visita WHERE id_usuario='$id_usuario' AND semestre = '$semestre' AND grupo = '$grupo' AND id_carrera = '$id_carrera' AND asignatura = '$asignatura'";
        $resultado = $con->query($sqlQuery);

        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            $totalSolicitudes = $fila['total'];
            
            http_response_code(200);
            echo json_encode(array('total' => $totalSolicitudes));
        } else {
            http_response_code(500);
            echo json_encode(array('isOk' => false, 'msj' => 'Error en la base de datos: ' . $con->error));
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

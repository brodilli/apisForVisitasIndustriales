<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';
$con = conectarDb();

$data = json_decode(file_get_contents('php://input'));

$id_visita = isset($data->id_visita) ? $data->id_visita : null;
$id_vehiculo = isset($data->id_vehiculo) ? $data->id_vehiculo : null;
$fecha = isset($data->fecha) ? $data->fecha : null;
$horaSalida = isset($data->horaSalida) ? $data->horaSalida : null;
$horaLlegada = isset($data->horaLlegada) ? $data->horaLlegada : null;
$empresa = isset($data->empresa) ? $data->empresa : null;
$lugar = isset($data->lugar) ? $data->lugar : null;
$docente = isset($data->docente) ? $data->docente : null;
$numAlumnos = isset($data->numAlumnos) ? $data->numAlumnos : null;
$color = isset($data->color) ? $data->color : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        $id_visita !== null &&
        $id_vehiculo !== null &&
        $fecha !== null &&
        $horaSalida !== null &&
        $horaLlegada !== null &&
        $empresa !== null &&
        $lugar !== null &&
        $docente !== null &&
        $numAlumnos !== null &&
        $color !== null
    ) {
        try {
            $sqlQuery = "INSERT INTO agenda (id_visita, id_vehiculo, fecha, horaSalida, horaLlegada, empresa, lugar, docente, numAlumnos, color) 
                    VALUES (:id_visita, :id_vehiculo, :fecha, :horaSalida, :horaLlegada, :empresa, :lugar, :docente, :numAlumnos, :color)";

            $stmt = $con->prepare($sqlQuery);
            $stmt->bindParam(':id_visita', $id_visita);
            $stmt->bindParam(':id_vehiculo', $id_vehiculo);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':horaSalida', $horaSalida);
            $stmt->bindParam(':horaLlegada', $horaLlegada);
            $stmt->bindParam(':empresa', $empresa);
            $stmt->bindParam(':lugar', $lugar);
            $stmt->bindParam(':docente', $docente);
            $stmt->bindParam(':numAlumnos', $numAlumnos);
            $stmt->bindParam(':color', $color);

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

<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';
$con = conectarDb();

$data = json_decode(file_get_contents('php://input'));

$id_usuario = $data->id_usuario ?? null;
$id_carrera = $data->id_carrera ?? null;
$id_empresa = $data->id_empresa ?? null;
$semestre = $data->semestre ?? null;
$grupo = $data->grupo ?? null;
$objetivo = $data->objetivo ?? null;
$fecha = $data->fecha ?? null;
$horaSalida = $data->horaSalida ?? null;
$horaLlegada = $data->horaLlegada ?? null;
$num_alumnos = $data->num_alumnos ?? null;
$num_alumnas = $data->num_alumnas ?? null;
$asignatura = $data->asignatura ?? null;
$acompanante = $data->acompanante ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        $id_usuario !== null &&
        $id_carrera !== null &&
        $id_empresa !== null &&
        $semestre !== null &&
        $grupo !== null &&
        $objetivo !== null &&
        $fecha !== null &&
        $horaSalida !== null &&
        $horaLlegada !== null &&
        $num_alumnos !== null &&
        $num_alumnas !== null &&
        $asignatura !== null &&
        $acompanante !== null
    ) {
        $sqlQuery = "INSERT INTO `solicitud_visita`(`id_usuario`, `id_carrera`, `id_empresa`, `semestre`, `grupo`, `objetivo`, `fecha`, `horaSalida`, `horaLlegada`, `num_alumnos`, `num_alumnas`, `asignatura`, `acompanante`)
                    VALUES (:id_usuario, :id_carrera, :id_empresa, :semestre, :grupo, :objetivo, :fecha, :horaSalida, :horaLlegada, :num_alumnos, :num_alumnas, :asignatura, :acompanante)";
                
        $stmt = $con->prepare($sqlQuery);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_carrera', $id_carrera);
        $stmt->bindParam(':id_empresa', $id_empresa);
        $stmt->bindParam(':semestre', $semestre);
        $stmt->bindParam(':grupo', $grupo);
        $stmt->bindParam(':objetivo', $objetivo);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':horaSalida', $horaSalida);
        $stmt->bindParam(':horaLlegada', $horaLlegada);
        $stmt->bindParam(':num_alumnos', $num_alumnos);
        $stmt->bindParam(':num_alumnas', $num_alumnas);
        $stmt->bindParam(':asignatura', $asignatura);
        $stmt->bindParam(':acompanante', $acompanante);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array('isOk' => true, 'msj' => 'Registro exitoso'));
        } else {
            http_response_code(500);
            echo json_encode(array('isOk' => false, 'msj' => $con->error));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('isOk' => false, 'msj' => 'Faltan parámetros obligatorios'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('isOk' => false, 'msj' => 'Método no permitido.'));
}
?>

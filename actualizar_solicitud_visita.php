<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';

// Conectar a la base de datos
$con = conectarDb();

// Obtener datos de la solicitud POST
$data = json_decode(file_get_contents("php://input"));

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_visita = $data->id_visita ?? null;
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
    $estatus = $data->estatus ?? null;
    $comentarios = $data->comentarios ?? null;

    if (
        $id_visita && $id_carrera && $id_empresa && $semestre && $grupo &&
        $objetivo && $fecha && $horaSalida && $horaLlegada && $num_alumnos &&
        $num_alumnas && $asignatura && $estatus && $comentarios
    ) {
        // Prepared statement para evitar inyección SQL
        $sqlQuery = $con->prepare("UPDATE solicitud_visita SET 
            id_carrera = ?,
            id_empresa = ?,
            semestre = ?,
            grupo = ?,
            objetivo = ?,
            fecha = ?,
            horaSalida = ?,
            horaLlegada = ?,
            num_alumnos = ?,
            num_alumnas = ?,
            asignatura = ?,
            estatus = ?,
            comentarios = ?
            WHERE id_visita = ?");

        $sqlQuery->bind_param('iiissssssssssi', $id_carrera, $id_empresa, $semestre, $grupo, $objetivo, $fecha, $horaSalida, $horaLlegada, $num_alumnos, $num_alumnas, $asignatura, $estatus, $comentarios, $id_visita);
        $sqlQuery->execute();

        if ($sqlQuery->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(array('isOk' => true, 'msj' => 'Registro actualizado exitosamente'));
        } else {
            http_response_code(400);
            echo json_encode(array('isOk' => false, 'msj' => 'No se pudo actualizar el registro'));
        }

        // Cerrar la consulta y la conexión
        $sqlQuery->close();
        mysqli_close($con);
    } else {
        http_response_code(400);
        echo json_encode(array('isOk' => false, 'msj' => 'Datos insuficientes o inválidos'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('isOk' => false, 'msj' => 'Método no permitido'));
}
?>

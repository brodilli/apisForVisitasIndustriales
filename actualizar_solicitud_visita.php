<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require 'conectar.php';
$con = conectarDb();

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $requiredFields = ['id_visita', 'id_carrera', 'id_empresa', 'semestre', 'grupo', 'objetivo', 'fecha', 'horaSalida', 'horaLlegada', 'num_alumnos', 'num_alumnas', 'asignatura', 'estatus', 'comentarios'];

        foreach ($requiredFields as $field) {
            if (!property_exists($data, $field)) {
                throw new Exception("El campo '$field' es obligatorio.");
            }
        }

        // Utiliza consultas preparadas para prevenir inyección SQL
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

        $sqlQuery->bind_param('iiissssssssssi', $data->id_carrera, $data->id_empresa, $data->semestre, $data->grupo, $data->objetivo, $data->fecha, $data->horaSalida, $data->horaLlegada, $data->num_alumnos, $data->num_alumnas, $data->asignatura, $data->estatus, $data->comentarios, $data->id_visita);
        $sqlQuery->execute();

        if ($sqlQuery->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(['isOk' => true, 'msj' => 'Registro actualizado exitosamente']);
        } else {
            http_response_code(400);
            echo json_encode(['isOk' => false, 'msj' => 'No se pudo actualizar el registro']);
        }

        $sqlQuery->close();
        mysqli_close($con);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['isOk' => false, 'msj' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['isOk' => false, 'msj' => 'Método no permitido']);
}
?>
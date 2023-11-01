<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conectar.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = ['id_visita', 'id_carrera', 'id_empresa', 'semestre', 'grupo', 'objetivo', 'fecha', 'horaSalida', 'horaLlegada', 'num_alumnos', 'num_alumnas', 'asignatura', 'estatus', 'comentarios'];

    if (isset($data) && allRequiredFieldsExist($data, $requiredFields)) {
        try {
            $con = conectarDb();

            $sql = "UPDATE solicitud_visita SET 
                id_carrera = :id_carrera,
                id_empresa = :id_empresa,
                semestre = :semestre,
                grupo = :grupo,
                objetivo = :objetivo,
                fecha = :fecha,
                horaSalida = :horaSalida,
                horaLlegada = :horaLlegada,
                num_alumnos = :num_alumnos,
                num_alumnas = :num_alumnas,
                asignatura = :asignatura,
                estatus = :estatus,
                comentarios = :comentarios
                WHERE id_visita = :id_visita";

            $query = $con->prepare($sql);

            $query->execute([
                ':id_carrera' => $data['id_carrera'],
                ':id_empresa' => $data['id_empresa'],
                ':semestre' => $data['semestre'],
                ':grupo' => $data['grupo'],
                ':objetivo' => $data['objetivo'],
                ':fecha' => $data['fecha'],
                ':horaSalida' => $data['horaSalida'],
                ':horaLlegada' => $data['horaLlegada'],
                ':num_alumnos' => $data['num_alumnos'],
                ':num_alumnas' => $data['num_alumnas'],
                ':asignatura' => $data['asignatura'],
                ':estatus' => $data['estatus'],
                ':comentarios' => $data['comentarios'],
                ':id_visita' => $data['id_visita']
            ]);

            if ($query->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(['isOk' => true, 'msj' => 'Registro actualizado exitosamente']);
            } else {
                http_response_code(400);
                echo json_encode(['isOk' => false, 'msj' => 'No se pudo actualizar el registro']);
            }

            $con = null; // Cerrar la conexión
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['isOk' => false, 'msj' => 'Error interno del servidor: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['isOk' => false, 'msj' => 'Datos insuficientes o inválidos']);
    }
}

function allRequiredFieldsExist($data, $requiredFields) {
    return count(array_intersect(array_keys($data), $requiredFields)) === count($requiredFields);
}
?>

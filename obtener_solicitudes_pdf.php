<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"));
$id_usuario = $data->id_usuario;
$id_carrera = $data->id_carrera;
$semestre = $data->semestre;
$grupo = $data->grupo;
$asignatura = $data->asignatura;

$sql = "SELECT solicitud_visita.id_visita, empresa.nombre_empresa, empresa.lugar, usuario.id_usuario, usuario.nombres, usuario.apellidoP, 
        usuario.apellidoM, solicitud_visita.fecha, solicitud_visita.horaSalida, solicitud_visita.horaLlegada, solicitud_visita.estatus, solicitud_visita.id_empresa, solicitud_visita.asignatura, solicitud_visita.objetivo, solicitud_visita.grupo, 
        solicitud_visita.semestre, solicitud_visita.num_alumnos, solicitud_visita.num_alumnas, solicitud_visita.comentarios, carrera.nombre_carrera 
        FROM solicitud_visita 
        INNER JOIN empresa ON solicitud_visita.id_empresa = empresa.id_empresa 
        INNER JOIN usuario ON solicitud_visita.id_usuario = usuario.id_usuario 
        INNER JOIN carrera ON solicitud_visita.id_carrera = carrera.id_carrera
        WHERE usuario.id_usuario = :id_usuario 
        AND solicitud_visita.semestre = :semestre 
        AND solicitud_visita.grupo = :grupo 
        AND solicitud_visita.id_carrera = :id_carrera 
        AND solicitud_visita.asignatura = :asignatura";

include "conectar.php";

try {
    $pdo = conectarDB(); // Debe devolver una instancia PDO

    if (!$pdo) {
        throw new Exception('Error en la conexión a la base de datos.');
    }

    $statement = $pdo->prepare($sql);
    $statement->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $statement->bindParam(':semestre', $semestre, PDO::PARAM_STR);
    $statement->bindParam(':grupo', $grupo, PDO::PARAM_STR);
    $statement->bindParam(':id_carrera', $id_carrera, PDO::PARAM_INT);
    $statement->bindParam(':asignatura', $asignatura, PDO::PARAM_STR);

    if (!$statement->execute()) {
        throw new Exception('Error en la consulta.');
    }

    $arreglo = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (empty($arreglo)) {
        http_response_code(404);
        echo json_encode(array('message' => 'No se encontraron registros.'));
    } else {
        http_response_code(200);
        echo json_encode($arreglo);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('error' => $e->getMessage()));
}
?>

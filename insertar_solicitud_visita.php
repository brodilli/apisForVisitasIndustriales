<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conectar.php';
$con = conectarDb();

// Obtiene los datos en formato JSON
$data = json_decode(file_get_contents("php://input"));

// Verifica que los campos necesarios estén presentes
if (
    isset($data->id_usuario, $data->id_carrera, $data->id_empresa, $data->semestre, $data->grupo, $data->objetivo, $data->fecha, $data->horaSalida, $data->horaLlegada, $data->num_alumnos, $data->num_alumnas, $data->asignatura, $data->acompanante)
) {
    // Obtén los valores del objeto JSON
    $id_usuario = $data->id_usuario;
    $id_carrera = $data->id_carrera;
    $id_empresa = $data->id_empresa;
    $semestre = $data->semestre;
    $grupo = $data->grupo;
    $objetivo = $data->objetivo;
    $fecha = $data->fecha;
    $horaSalida = $data->horaSalida;
    $horaLlegada = $data->horaLlegada;
    $num_alumnos = $data->num_alumnos;
    $num_alumnas = $data->num_alumnas;
    $asignatura = $data->asignatura;
    $acompanante = $data->acompanante;

    try {
        // Consulta para contar el número de solicitudes existentes
        $sqlQuery = "SELECT COUNT(*) AS total FROM solicitud_visita WHERE id_usuario = ? AND semestre = ? AND grupo = ? AND id_carrera = ? AND asignatura = ?";
        $stmt = $con->prepare($sqlQuery);
        $stmt->execute([$id_usuario, $semestre, $grupo, $id_carrera, $asignatura]);
        $totalSolicitudes = $stmt->fetchColumn();

        if ($totalSolicitudes <= 4) {
            // Preparar la consulta para insertar una nueva solicitud
            $insertQuery = "INSERT INTO solicitud_visita (id_usuario, id_carrera, id_empresa, semestre, grupo, objetivo, fecha, horaSalida, horaLlegada, num_alumnos, num_alumnas, asignatura, acompanante) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($insertQuery);

            // Ejecutar la consulta de inserción
            $success = $stmt->execute([$id_usuario, $id_carrera, $id_empresa, $semestre, $grupo, $objetivo, $fecha, $horaSalida, $horaLlegada, $num_alumnos, $num_alumnas, $asignatura, $acompanante]);

            if ($success) {
                http_response_code(200);
                echo json_encode(['isOk' => true, 'msj' => 'Registro exitoso']);
            } else {
                echo json_encode(['isOk' => false, 'msj' => 'Error al insertar la solicitud']);
            }
        } else {
            http_response_code(406);
            echo json_encode('Excedido el límite de solicitudes');
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Datos insuficientes']);
}
?>

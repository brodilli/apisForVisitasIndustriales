<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php';
$con = conectarDb();

$data = json_decode(file_get_contents('php://input'));

$id_usuario = isset($data->id_usuario) ? $data->id_usuario : null;
$id_carrera = isset($data->id_carrera) ? $data->id_carrera : null;
$id_empresa = isset($data->id_empresa) ? $data->id_empresa : null;
$semestre = isset($data->semestre) ? $data->semestre : null;
$grupo = isset($data->grupo) ? $data->grupo : null;
$objetivo = isset($data->objetivo) ? $data->objetivo : null;
$fecha = isset($data->fecha) ? $data->fecha : null;
$horaSalida = isset($data->horaSalida) ? $data->horaSalida : null;
$horaLlegada = isset($data->horaLlegada) ? $data->horaLlegada : null;
$num_alumnos = isset($data->num_alumnos) ? $data->num_alumnos : null;
$num_alumnas = isset($data->num_alumnas) ? $data->num_alumnas : null;
$asignatura = isset($data->asignatura) ? $data->asignatura : null;
$acompanante = isset($data->acompanante) ? $data->acompanante : null;

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
        // Verificar el límite de 4 solicitudes
        $sqlQuery = "SELECT COUNT(*) AS total FROM solicitud_visita WHERE id_usuario='$id_usuario' AND semestre = '$semestre' AND grupo = '$grupo' AND id_carrera = '$id_carrera' AND asignatura = '$asignatura'";
        $resultado = $con->query($sqlQuery);

        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            $totalSolicitudes = $fila['total'];

            if ($totalSolicitudes <= 4) {
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
                http_response_code(406);
                echo json_encode(array('isMore' => "406", 'msj' => 'No se puede registrar más de 4 solicitudes'));
            }
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

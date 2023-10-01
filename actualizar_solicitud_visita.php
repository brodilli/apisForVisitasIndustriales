<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php'; // Asegúrate de que conectar.php establezca la conexión PDO correctamente

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $data = json_decode(file_get_contents("php://input"));

        $id_visita = $data->id_visita;
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
        $estatus = $data->estatus;
        $comentarios = $data->comentarios;

        // Consulta SQL preparada para actualizar los datos
        $sqlQuery = "UPDATE solicitud_visita SET 
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

        $stmt = $con->prepare($sqlQuery);
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
        $stmt->bindParam(':estatus', $estatus);
        $stmt->bindParam(':comentarios', $comentarios);
        $stmt->bindParam(':id_visita', $id_visita);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array('isOk' => true, 'msj' => 'Registro exitoso'));
        } else {
            throw new Exception("Error en la consulta");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array('isOk' => false, 'msj' => 'Error en la solicitud: ' . $e->getMessage()));
    }
}
?>

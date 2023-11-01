<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conectar.php';

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $con = conectarDbPDO(); // Esta función debería manejar la conexión con PDO

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

            $query = $con->prepare($sqlQuery);
            $query->bindParam(':id_carrera', $id_carrera);
            $query->bindParam(':id_empresa', $id_empresa);
            $query->bindParam(':semestre', $semestre);
            $query->bindParam(':grupo', $grupo);
            $query->bindParam(':objetivo', $objetivo);
            $query->bindParam(':fecha', $fecha);
            $query->bindParam(':horaSalida', $horaSalida);
            $query->bindParam(':horaLlegada', $horaLlegada);
            $query->bindParam(':num_alumnos', $num_alumnos);
            $query->bindParam(':num_alumnas', $num_alumnas);
            $query->bindParam(':asignatura', $asignatura);
            $query->bindParam(':estatus', $estatus);
            $query->bindParam(':comentarios', $comentarios);
            $query->bindParam(':id_visita', $id_visita);

            $query->execute();

            if ($query->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(array('isOk' => true, 'msj' => 'Registro actualizado exitosamente'));
            } else {
                http_response_code(400);
                echo json_encode(array('isOk' => false, 'msj' => 'No se pudo actualizar el registro'));
            }

            $query = null; // Liberar los recursos
            $con = null; // Cerrar la conexión

        } else {
            http_response_code(400);
            echo json_encode(array('isOk' => false, 'msj' => 'Datos insuficientes o inválidos'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('isOk' => false, 'msj' => 'Error interno del servidor: ' . $e->getMessage()));
    }
}
?>

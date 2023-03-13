<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conectar.php';
$con=conectarDb();

$data = json_decode(file_get_contents("php://input"));

$id_usuario= $data-> id_usuario;
$id_carrera= $data-> id_carrera;
$id_empresa= $data-> id_empresa;
$semestre = $data-> semestre;
$grupo = $data-> grupo;
$objetivo= $data-> objetivo;
$fecha= $data-> fecha;
$num_alumnos= $data-> num_alumnos;
$num_alumnas= $data-> num_alumnas;
$asignatura= $data-> asignatura;


if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $sqlQuery =("INSERT INTO `solicitud_visita`(`id_usuario`,`id_carrera`, `id_empresa`,`semestre`, `grupo`,`objetivo`, `fecha`, `num_alumnos`, `num_alumnas`, `asignatura`)
                                        VALUES ('".$id_usuario."','".$id_carrera."','".$id_empresa."','".$semestre."','".$grupo."','".$objetivo."', '".$fecha."','".$num_alumnos."', '".$num_alumnas."', '".$asignatura."' )");
 
        if ($con->query($sqlQuery) === TRUE) {
            http_response_code(200);
            echo json_encode(array('isOk'=>'true','msj'=>'Registro exitoso'));
            } else {
                echo json_encode(array('isOk'=>'false','msj'=>$con->error)); 
            }
            mysqli_close($con);
}

 ?>
<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conectar.php';
$con=conectarDb();

$data = json_decode(file_get_contents("php://input"));

$id_visita = $data-> id_visita;
$id_vehiculo = $data-> id_vehiculo;
$fecha = $data-> fecha;
$horaSalida = $data-> horaSalida;
$horaLlegada = $data-> horaLlegada;
$empresa = $data-> empresa;
$lugar = $data-> lugar;
$docente = $data-> docente;
$numAlumnos = $data-> numAlumnos;
$color= $data-> color;


if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $sqlQuery =("INSERT INTO agenda (id_visita, id_vehiculo, fecha, horaSalida, horaLlegada, empresa, lugar, docente, numAlumnos, color) 
                    VALUES ('$id_visita', '$id_vehiculo', '$fecha', '$horaSalida', '$horaLlegada', '$empresa', '$lugar', '$docente', '$numAlumnos', '$color')");
    
 
        if ($con->query($sqlQuery) === TRUE) {
            http_response_code(200);
            echo json_encode(array('isOk'=>'true','msj'=>'Registro exitoso'));
            } else {
                echo json_encode(array('isOk'=>'false','msj'=>$con->error)); 
            }
            mysqli_close($con);
}

 ?>
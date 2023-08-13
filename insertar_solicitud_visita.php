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
$horaSalida= $data-> horaSalida;
$horaLlegada= $data-> horaLlegada;
$num_alumnos= $data-> num_alumnos;
$num_alumnas= $data-> num_alumnas;
$asignatura= $data-> asignatura;
$acompanante= $data-> acompanante;


$sqlQuery = "SELECT COUNT(*) AS total FROM solicitud_visita WHERE id_usuario='$id_usuario' AND semestre = '$semestre' AND grupo = '$grupo' AND id_carrera = '$id_carrera' AND asignatura = '$asignatura'";
// Ejecutar la consulta SQL
// Aquí debes usar tu método de conexión y ejecución de consultas a la base de datos
$resultado = $con->query($sqlQuery);

if ($resultado) {
    
    // Obtener el número de solicitudes existentes
    $fila = $resultado->fetch_assoc();
    $totalSolicitudes = $fila['total'];

    // Verificar si se excede el límite de 4 solicitudes
    if ($totalSolicitudes <= 4) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
            $sqlQuery =("INSERT INTO `solicitud_visita`(`id_usuario`,`id_carrera`, `id_empresa`,`semestre`, `grupo`,`objetivo`, `fecha`, `horaSalida`,`horaLlegada`, `num_alumnos`, `num_alumnas`, `asignatura`, `acompanante`)
                                                VALUES ('".$id_usuario."','".$id_carrera."','".$id_empresa."','".$semestre."','".$grupo."','".$objetivo."', '".$fecha."','".$horaSalida."','".$horaLlegada."','".$num_alumnos."', '".$num_alumnas."', '".$asignatura."' , '".$acompanante."' )");
        
                if ($con->query($sqlQuery) === TRUE) {
                    http_response_code(200);
                    echo json_encode(array('isOk'=>'true','msj'=>'Registro exitoso'));
                    // echo json_decode("num"=>$totalSolicitudes)
                } else {
                    echo json_encode(array('isOk'=>'false','msj'=>$con->error)); 
                }
                mysqli_close($con);
        }
        
        // Aquí puedes agregar cualquier otra acción o mensaje de error que desees mostrar
    } else {
        echo json_encode(array('isMore'=>"406",'msj'=>'No se puede registrar más de 4 solicitudes'));
        
    }

}

 ?>
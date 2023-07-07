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
$semestre = $data-> semestre;
$grupo = $data-> grupo;
$asignatura= $data-> asignatura;


$sqlQuery = "SELECT COUNT(*) AS total FROM solicitud_visita WHERE id_usuario='$id_usuario' AND semestre = '$semestre' AND grupo = '$grupo' AND id_carrera = '$id_carrera' AND asignatura = '$asignatura'";
// Ejecutar la consulta SQL
// Aquí debes usar tu método de conexión y ejecución de consultas a la base de datos
$resultado = $con->query($sqlQuery);
if ($resultado) {
    // Obtener el número de solicitudes existentes
    $fila = $resultado->fetch_assoc();
    $totalSolicitudes = $fila['total'];
    echo $totalSolicitudes;
}
    else {
        echo "404";
}

 ?>
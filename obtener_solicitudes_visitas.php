<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data = json_decode(file_get_contents("php://input"));
$rango = $data -> rango;

$mesActual = date('n'); // Obtiene el número del mes actual (1-12)
$anoActual = date('Y'); // Obtiene el año actual
if ($rango == "1"){
    $sql = "SELECT solicitud_visita.id_visita, empresa.nombre_empresa, empresa.lugar, usuario.nombres, usuario.apellidoP, 
usuario.apellidoM, usuario.id_usuario, solicitud_visita.fecha, solicitud_visita.horaSalida, solicitud_visita.horaLlegada, solicitud_visita.estatus, solicitud_visita.id_empresa, solicitud_visita.asignatura, solicitud_visita.objetivo, solicitud_visita.grupo, 
solicitud_visita.semestre, solicitud_visita.num_alumnos, solicitud_visita.id_carrera, solicitud_visita.num_alumnas, solicitud_visita.comentarios, carrera.nombre_carrera 
FROM solicitud_visita 
INNER JOIN empresa on solicitud_visita.id_empresa = empresa.id_empresa 
INNER JOIN usuario on solicitud_visita.id_usuario = usuario.id_usuario 
INNER JOIN carrera on solicitud_visita.id_carrera = carrera.id_carrera";

if ($rango === "1" && $mesActual >= 1 && $mesActual <= 6) {
    // Agrega la condición para enero a junio del año actual
    $sql .= " WHERE MONTH(solicitud_visita.fecha) BETWEEN 1 AND 6 AND YEAR(solicitud_visita.fecha) = $anoActual";
}
if ($rango === "1" && $mesActual >= 7 && $mesActual <= 12) {
    // Agrega la condición para julio a diciembre del año actual
    $sql .= " WHERE MONTH(solicitud_visita.fecha) BETWEEN 7 AND 12 AND YEAR(solicitud_visita.fecha) = $anoActual";
}
}else{
    $sql= "SELECT solicitud_visita.id_visita, empresa.nombre_empresa, empresa.lugar, usuario.nombres, usuario.apellidoP, 
    usuario.apellidoM, usuario.id_usuario, solicitud_visita.fecha, solicitud_visita.horaSalida,solicitud_visita.horaLlegada, solicitud_visita.estatus, solicitud_visita.id_empresa, solicitud_visita.asignatura, solicitud_visita.objetivo, solicitud_visita.grupo, 
    solicitud_visita.semestre, solicitud_visita.num_alumnos, solicitud_visita.id_carrera, solicitud_visita.num_alumnas, solicitud_visita.comentarios,carrera.nombre_carrera FROM solicitud_visita 
    INNER JOIN empresa on solicitud_visita.id_empresa = empresa.id_empresa 
    INNER JOIN usuario on solicitud_visita.id_usuario = usuario.id_usuario 
    INNER JOIN carrera on solicitud_visita.id_carrera = carrera.id_carrera;";
}

include "conectar.php";
//sleep(1);
function desconectar($conexion){

    $close = mysqli_close($conexion);
        
        if($close){
            echo '';
        }else{
            echo 'Ha sucedido un error inexperado en la conexión de la base de datos';
        }

    return $close;
}

function obtenerArreglo($sql){
    //Creamos la conexion con la funcion anterior
  $conexion = conectarDB();

    //generamos la consulta

        mysqli_set_charset($conexion, "utf8"); //formato de datos utf8

    if(!$resultado = mysqli_query($conexion, $sql)) die(); //si la conexión cancelar programa

    $arreglo = array(); //creamos un array

    //guardamos en un array todos los datos de la consulta
    $i=0;

    while($row = mysqli_fetch_assoc($resultado))
    {
        $arreglo[$i] = $row;
        $i++;
    }

    desconectar($conexion); //desconectamos la base de datos

    return $arreglo; //devolvemos el array
}

        $r = obtenerArreglo($sql);
        echo json_encode($r);

?>
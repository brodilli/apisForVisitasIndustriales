<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Content-Type: text/html; charset=utf-8");
$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"));
$id_visita = $data -> id_visita;


$sql= "SELECT COUNT(*) AS count FROM `agenda` WHERE id_visita = '$id_visita'";
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
    $conexion = conectarDB();

    mysqli_set_charset($conexion, "utf8");

    if(!$resultado = mysqli_query($conexion, $sql)) die();

    $row = mysqli_fetch_assoc($resultado);
    desconectar($conexion);

    return $row;
}

$r = obtenerArreglo($sql);
echo json_encode($r);
?>
<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Content-Type: application/json; charset=utf-8");
$method = $_SERVER['REQUEST_METHOD'];
$sql = "SELECT * FROM `usuario` ORDER BY id_usuario ASC ";
include "conectar.php";

function desconectar($conexion){
    $close = mysqli_close($conexion);
    if ($close) {
        return true;
    } else {
        return "Ha sucedido un error inexperado en la conexión de la base de datos: " . mysqli_error($conexion);
    }
}

function obtenerArreglo($sql){
    $conexion = conectarDB();
    if (mysqli_connect_error()) {
        die("Error de conexión a la base de datos: " . mysqli_connect_error());
    } else {
        echo "Conexión exitosa a la base de datos.";
    }

    mysqli_set_charset($conexion, "utf8");

    if (!$resultado = mysqli_query($conexion, $sql)) {
        return "Error en la consulta SQL: " . mysqli_error($conexion);
    }

    $arreglo = array();

    while ($row = mysqli_fetch_assoc($resultado)) {
        $arreglo[] = $row;
    }

    $error = desconectar($conexion);
    if ($error === true) {
        return $arreglo;
    } else {
        return "Error al desconectar la base de datos: " . $error;
    }
}

$data = obtenerArreglo($sql);

if (is_array($data)) {
    echo json_encode($data);
} else {
    echo json_encode(array("error" => $data));
}
?>

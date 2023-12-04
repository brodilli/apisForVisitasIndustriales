<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Content-Type: application/json; charset=utf-8");

$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"));
$id_visita = $data->id_visita;

// Evitar la inyección de SQL usando consultas preparadas
$sql = "SELECT COUNT(*) AS count FROM `agenda` WHERE id_visita = ?";
include "conectar.php";

function desconectar($conexion)
{
    $close = mysqli_close($conexion);

    if ($close) {
        echo '';
    } else {
        echo 'Ha sucedido un error inexperado en la conexión de la base de datos';
    }

    return $close;
}

function obtenerArreglo($sql, $id_visita)
{
    $conexion = conectarDB();
    mysqli_set_charset($conexion, "utf8");

    // Utilizar consultas preparadas para evitar la inyección de SQL
    $stmt = mysqli_prepare($conexion, $sql);

    if (!$stmt) {
        die('Error en la preparación de la consulta: ' . mysqli_error($conexion));
    }

    // Vincular parámetros
    mysqli_stmt_bind_param($stmt, "s", $id_visita);

    // Ejecutar la consulta
    mysqli_stmt_execute($stmt);

    // Obtener el resultado
    $resultado = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultado);

    // Desconectar la base de datos
    desconectar($conexion);

    return $row;
}

$r = obtenerArreglo($sql, $id_visita);
echo json_encode($r);
?>

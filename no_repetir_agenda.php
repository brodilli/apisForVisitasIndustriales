<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Content-Type: application/json; charset=utf-8");

$data = json_decode(file_get_contents("php://input"));
$id_visita = $data->id_visita;

include "conectar.php";

function desconectar($conexion)
{
    $conexion = null; // Cerramos la conexión PDO
}

function obtenerArreglo($sql, $id_visita)
{
    $conexion = conectarDB();

    try {
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_visita', $id_visita, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
        return null;
    } finally {
        desconectar($conexion);
    }
}

$sql = "SELECT COUNT(*) AS count FROM `agenda` WHERE id_visita = :id_visita";
$r = obtenerArreglo($sql, $id_visita);
echo json_encode($r);
?>

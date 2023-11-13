<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Content-Type: application/json; charset=UTF-8');
$method = $_SERVER['REQUEST_METHOD'];
$sql = "SELECT * FROM `agenda` ORDER BY id_agenda ASC";
include "conectar.php";

function obtenerArreglo($pdo, $sql)
{
    try {
        $statement = $pdo->query($sql);

        if (!$statement) {
            throw new Exception('Error en la consulta.');
        }

        $arreglo = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $arreglo;
    } catch (Exception $e) {
        return array('error' => $e->getMessage());
    }
}

try {
    $pdo = conectarDB(); // Función conectarDB debería devolver una instancia PDO

    if (!$pdo) {
        throw new Exception('Error en la conexión a la base de datos.');
    }

    $arreglo = obtenerArreglo($pdo, $sql);

    if (isset($arreglo['error'])) {
        http_response_code(500);
    } else {
        http_response_code(200);
    }

    echo json_encode($arreglo);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('error' => $e->getMessage()));
}
?>

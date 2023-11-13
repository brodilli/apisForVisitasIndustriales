<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

include 'conectar.php';

function obtenerUsuarios() {
    $conexion = conectarDB();

    $sql = 'SELECT * FROM usuario ORDER BY id_usuario ASC';

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die('Error en la consulta SQL: ' . $conexion->errorInfo()[2]);
    }

    $stmt->execute();

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $usuarios;
}

try {
    $usuarios = obtenerUsuarios();
    echo json_encode(array('status' => 200, 'data' => $usuarios));
} catch (Exception $e) {
    echo json_encode(array('status' => 500, 'error' => $e->getMessage()));
}
?>

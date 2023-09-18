<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
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
    echo json_encode($usuarios);
} catch (Exception $e) {
    echo json_encode(array('error' => $e->getMessage()));
}
?>

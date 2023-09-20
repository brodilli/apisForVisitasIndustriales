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
    $response = array('status' => 200, 'data' => $usuarios);
} catch (Exception $e) {
    $response = array('status' => 500, 'error' => $e->getMessage());
}

// Ahora, asegurémonos de que solo se devuelva JSON
echo json_encode($response);
?>

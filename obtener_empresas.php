<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Content-Type: application/json; charset=utf-8');

include 'conectar.php';

function obtenerEmpresas() {
    $conexion = conectarDB();

    $sql = 'SELECT * FROM empresa ORDER BY id_empresa ASC';

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        throw new Exception('Error en la consulta SQL: ' . $conexion->errorInfo()[2]);
    }

    $stmt->execute();

    $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $empresas;
}

try {
    $empresas = obtenerEmpresas();
    if (is_array($empresas)) {
        http_response_code(200); // Éxito, código 200
        echo json_encode(array('status' => 200, 'data' => $empresas));
    } else {
        http_response_code(500); // Error del servidor, código 500
        echo json_encode(array('status' => 500, 'error' => 'Error al obtener las empresas'));
    }
} catch (Exception $e) {
    http_response_code(500); // Error del servidor, código 500
    echo json_encode(array('status' => 500, 'error' => $e->getMessage()));
}
?>

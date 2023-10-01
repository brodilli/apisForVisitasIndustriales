<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Origin, X-Requested-With, Authorization');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=utf-8');

include 'conectar.php';

function obtenerEmpresas() {
    $conexion = conectarDB();

    $sql = 'SELECT * FROM empresa ORDER BY id_empresa ASC';

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    try {
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return array('error' => 'Error en la consulta SQL: ' . $e->getMessage());
    } finally {
        $conexion = null; // Cerramos la conexión
    }
}

$data = json_decode(file_get_contents("php://input"));
$rango = $data->rango;

try {
    $empresas = obtenerEmpresas();
    
    if (is_array($empresas)) {
        http_response_code(200); // Éxito, código 200
        echo json_encode(array('status' => 200, 'data' => $empresas));
    } else {
        http_response_code(500); // Error del servidor, código 500
        echo json_encode(array('status' => 500, 'error' => $empresas['error']));
    }
} catch (Exception $e) {
    http_response_code(500); // Error del servidor, código 500
    echo json_encode(array('status' => 500, 'error' => $e->getMessage()));
}
?>

<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Origin, X-Requested-With, Authorization');
header('Content-Type: application/json; charset=utf-8');

require 'conectar.php';

function obtenerEmpresas() {
    $conexion = conectarDB();

    $sql = 'SELECT * FROM empresa ORDER BY id_empresa ASC';

    mysqli_set_charset($conexion, 'utf8'); // Formato de datos utf8

    if (!$resultado = mysqli_query($conexion, $sql)) {
        return array('error' => 'Error en la consulta SQL: ' . mysqli_error($conexion));
    }

    $empresas = array();

    while ($row = mysqli_fetch_assoc($resultado)) {
        $empresas[] = $row;
    }

    mysqli_close($conexion);

    return $empresas;
}

try {
    $empresas = obtenerEmpresas();
    if (is_array($empresas)) {
        http_response_code(200); // Éxito, código 200
        echo json_encode($empresas);
    } else {
        http_response_code(500); // Error del servidor, código 500
        echo json_encode($empresas);
    }
} catch (Exception $e) {
    http_response_code(500); // Error del servidor, código 500
    echo json_encode(array('error' => $e->getMessage()));
}
?>

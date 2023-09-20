<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Content-Type: application/json; charset=utf-8');

include 'conectar.php';

function obtenerEmpresas() {
    $conexion = conectarDB();

    $sql = 'SELECT * FROM empresa ORDER BY id_empresa ASC';

    mysqli_set_charset($conexion, 'utf8'); // Formato de datos utf8

    if (!$resultado = mysqli_query($conexion, $sql)) {
        die('Error en la consulta SQL: ' . mysqli_error($conexion));
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
    echo json_encode($empresas);
} catch (Exception $e) {
    echo json_encode(array('error' => $e->getMessage()));
}
?>

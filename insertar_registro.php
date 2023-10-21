<?php
// Establece los encabezados CORS para permitir solicitudes desde cualquier origen.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

require 'conectar.php'; // Asegúrate de que este archivo exista y contenga la función conectarDb.

$con = conectarDb(); // Asegúrate de que esta función esté definida y devuelva una conexión a la base de datos.

$data = json_decode(file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoUser = isset($data->tipoUser) ? $data->tipoUser : null;
    $nombres = isset($data->nombres) ? $data->nombres : null;
    $apellidoP = isset($data->apellidoP) ? $data->apellidoP : null;
    $apellidoM = isset($data->apellidoM) ? $data->apellidoM : null;
    $correo = isset($data->correo) ? $data->correo : null;
    $contraseña = isset($data->contraseña) ? $data->contraseña : null;

    if (
        $tipoUser !== null &&
        $nombres !== null &&
        $apellidoP !== null &&
        $apellidoM !== null &&
        $correo !== null &&
        $contraseña !== null
    ) {
        // Evita la inyección de SQL utilizando consultas preparadas.
        $sqlQuery = "INSERT INTO `usuario` (`tipoUser`, `nombres`, `apellidoP`, `apellidoM`, `correo`, `contraseña`) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sqlQuery);
        $stmt->bind_param("ssssss", $tipoUser, $nombres, $apellidoP, $apellidoM, $correo, $contraseña);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array('isOk' => true, 'msj' => 'Registro exitoso'));
        } else {
            http_response_code(500);
            echo json_encode(array('isOk' => false, 'msj' => 'Error en la base de datos: ' . $stmt->error));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('isOk' => false, 'msj' => 'Faltan parámetros obligatorios.'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('isOk' => false, 'msj' => 'Método no permitido.'));
}

mysqli_close($con); // Cierra la conexión a la base de datos después de su uso.
?>

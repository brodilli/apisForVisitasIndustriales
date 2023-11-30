<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    $servidor = "localhost";
    $usuario = "visitas";
    $password = "Myp@ssw0";
    $bd = "visitas_industriales";

    $dsn = "mysql:host=$servidor;dbname=$bd;charset=utf8mb4";
    $opciones = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $usuario, $password, $opciones);

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $data = json_decode(file_get_contents("php://input"));
        $id_visita = $data->id_visita;

        $sql = "DELETE FROM solicitud_visita WHERE id_visita = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_visita]);

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            http_response_code(200);
            echo json_encode(['isOk' => true, 'msj' => 'Registro eliminado exitosamente']);
        } else {
            http_response_code(400);
            echo json_encode(['isOk' => false, 'msj' => 'No se pudo eliminar el registro']);
        }
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['isOk' => false, 'msj' => 'Error interno del servidor: ' . $e->getMessage()]);
}
?>

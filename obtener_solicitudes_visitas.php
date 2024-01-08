<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=utf-8");

include "conectar.php"; // Asegúrate de incluir el archivo de conexión apropiado

function obtenerSolicitudes($rango) {
    try {
        $pdo = conectarDb(); // Conexión a la base de datos usando PDO

        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');

        // Ajustar la consulta según el valor de la variable de rango
        $sql = "SELECT solicitud_visita.id_visita, empresa.nombre_empresa, empresa.lugar, usuario.nombres, usuario.apellidoP, 
        usuario.apellidoM, usuario.id_usuario, solicitud_visita.fecha, solicitud_visita.horaSalida, solicitud_visita.horaLlegada, solicitud_visita.estatus, solicitud_visita.id_empresa, solicitud_visita.asignatura, solicitud_visita.objetivo, solicitud_visita.grupo, 
        solicitud_visita.semestre, solicitud_visita.num_alumnos, solicitud_visita.id_carrera, solicitud_visita.num_alumnas, solicitud_visita.comentarios, carrera.nombre_carrera 
        FROM solicitud_visita 
        INNER JOIN empresa ON solicitud_visita.id_empresa = empresa.id_empresa 
        INNER JOIN usuario ON solicitud_visita.id_usuario = usuario.id_usuario 
        INNER JOIN carrera ON solicitud_visita.id_carrera = carrera.id_carrera";

        if ($rango == 1) {
            $sql .= " WHERE YEAR(solicitud_visita.fecha) = YEAR(:fecha) AND (MONTH(solicitud_visita.fecha) >= 1 AND MONTH(solicitud_visita.fecha) <= 6)";
        }

        $stmt = $pdo->prepare($sql);

        // Asignar valor a parámetro :fecha
        $stmt->bindParam(':fecha', $fecha_actual, PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cerrar la conexión y devolver los resultados
        $pdo = null;
        return $result;
    } catch (PDOException $e) {
        // Manejo de errores
        http_response_code(500); // Error interno del servidor
        echo json_encode(["error" => "Hubo un problema al procesar la solicitud."]);
        exit();
    }
}

// Obtener el valor de la variable de rango, si no se proporciona, establecer en 2 por defecto
$rango = isset($_GET['rango']) ? $_GET['rango'] : 2;

// Llamada a la función para obtener los datos
$solicitudes = obtenerSolicitudes($rango);

// Devolver los datos como JSON
echo json_encode($solicitudes);
?>

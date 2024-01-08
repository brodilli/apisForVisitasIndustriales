<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=utf-8");

include "conectar.php";

function obtenerSolicitudes($rango) {
    try {
        $pdo = conectarDb();

        $mes_actual = date('n');

        $sqlGeneral = "SELECT solicitud_visita.id_visita, empresa.nombre_empresa, empresa.lugar, usuario.nombres, usuario.apellidoP, 
            usuario.apellidoM, usuario.id_usuario, solicitud_visita.fecha, solicitud_visita.fecha_creacion, solicitud_visita.horaSalida, solicitud_visita.horaLlegada, solicitud_visita.estatus, solicitud_visita.id_empresa, solicitud_visita.asignatura, solicitud_visita.objetivo, solicitud_visita.grupo, 
            solicitud_visita.semestre, solicitud_visita.num_alumnos, solicitud_visita.id_carrera, solicitud_visita.num_alumnas, solicitud_visita.comentarios, carrera.nombre_carrera 
            FROM solicitud_visita 
            INNER JOIN empresa ON solicitud_visita.id_empresa = empresa.id_empresa 
            INNER JOIN usuario ON solicitud_visita.id_usuario = usuario.id_usuario 
            INNER JOIN carrera ON solicitud_visita.id_carrera = carrera.id_carrera";

        $sqlSemestre = "SELECT solicitud_visita.id_visita, empresa.nombre_empresa, empresa.lugar, usuario.nombres, usuario.apellidoP, 
            usuario.apellidoM, usuario.id_usuario, solicitud_visita.fecha, solicitud_visita.fecha_creacion, solicitud_visita.horaSalida, solicitud_visita.horaLlegada, solicitud_visita.estatus, solicitud_visita.id_empresa, solicitud_visita.asignatura, solicitud_visita.objetivo, solicitud_visita.grupo, 
            solicitud_visita.semestre, solicitud_visita.num_alumnos, solicitud_visita.id_carrera, solicitud_visita.num_alumnas, solicitud_visita.comentarios, carrera.nombre_carrera 
            FROM solicitud_visita 
            INNER JOIN empresa ON solicitud_visita.id_empresa = empresa.id_empresa 
            INNER JOIN usuario ON solicitud_visita.id_usuario = usuario.id_usuario 
            INNER JOIN carrera ON solicitud_visita.id_carrera = carrera.id_carrera 
            WHERE";

        // Recuperar datos del cuerpo de la solicitud JSON
        $data = json_decode(file_get_contents('php://input'));

        // Recuperar el valor de rango
        $rango = isset($data->rango) ? $data->rango : 2;

        // echo "Valor de \$rango: " . $rango . "<br>";

        if ($rango == 1) {
            if ($mes_actual >= 1 && $mes_actual <= 7) {
                $sqlSemestre .= " MONTH(solicitud_visita.fecha_creacion) >= 1 AND MONTH(solicitud_visita.fecha_creacion) <= 7";
            } else {
                $sqlSemestre .= " MONTH(solicitud_visita.fecha_creacion) >= 8 AND MONTH(solicitud_visita.fecha_creacion) <= 12";
            }

            // echo "Consulta SQL con filtro de semestre: " . $sqlSemestre . "<br>";

            $stmt = $pdo->prepare($sqlSemestre);
        } else {
            // echo "Consulta SQL general: " . $sqlGeneral . "<br>";

            $stmt = $pdo->prepare($sqlGeneral);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;
        return $result;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Hubo un problema al procesar la solicitud."]);
        exit();
    }
}

$rango = 2; // Valor predeterminado

// Llamada a la función para obtener los datos
$solicitudes = obtenerSolicitudes($rango);

// Devolver los datos como JSON
echo json_encode($solicitudes);
?>

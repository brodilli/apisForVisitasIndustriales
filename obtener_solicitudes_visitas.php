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
        echo "Valor de \$mes_actual: " . $mes_actual . "<br>";

        // Consulta general sin filtro
        $sqlGeneral = "SELECT solicitud_visita.id_visita, empresa.nombre_empresa, empresa.lugar, usuario.nombres, usuario.apellidoP, 
            usuario.apellidoM, usuario.id_usuario, solicitud_visita.fecha, solicitud_visita.fecha_creacion, solicitud_visita.horaSalida, solicitud_visita.horaLlegada, solicitud_visita.estatus, solicitud_visita.id_empresa, solicitud_visita.asignatura, solicitud_visita.objetivo, solicitud_visita.grupo, 
            solicitud_visita.semestre, solicitud_visita.num_alumnos, solicitud_visita.id_carrera, solicitud_visita.num_alumnas, solicitud_visita.comentarios, carrera.nombre_carrera 
            FROM solicitud_visita 
            INNER JOIN empresa ON solicitud_visita.id_empresa = empresa.id_empresa 
            INNER JOIN usuario ON solicitud_visita.id_usuario = usuario.id_usuario 
            INNER JOIN carrera ON solicitud_visita.id_carrera = carrera.id_carrera";

        // Consulta con filtro por semestre
        $sqlSemestre = "SELECT solicitud_visita.id_visita, empresa.nombre_empresa, empresa.lugar, usuario.nombres, usuario.apellidoP, 
            usuario.apellidoM, usuario.id_usuario, solicitud_visita.fecha, solicitud_visita.fecha_creacion, solicitud_visita.horaSalida, solicitud_visita.horaLlegada, solicitud_visita.estatus, solicitud_visita.id_empresa, solicitud_visita.asignatura, solicitud_visita.objetivo, solicitud_visita.grupo, 
            solicitud_visita.semestre, solicitud_visita.num_alumnos, solicitud_visita.id_carrera, solicitud_visita.num_alumnas, solicitud_visita.comentarios, carrera.nombre_carrera 
            FROM solicitud_visita 
            INNER JOIN empresa ON solicitud_visita.id_empresa = empresa.id_empresa 
            INNER JOIN usuario ON solicitud_visita.id_usuario = usuario.id_usuario 
            INNER JOIN carrera ON solicitud_visita.id_carrera = carrera.id_carrera 
            WHERE";

        echo "Valor de \$rango: " . $rango . "<br>";

        if ($rango == 1) {
            // Ajustar la consulta para el filtro de semestre según el valor de $mes_actual
            if ($mes_actual >= 1 && $mes_actual <= 7) {
                $sqlSemestre .= " MONTH(solicitud_visita.fecha_creacion) >= 1 AND MONTH(solicitud_visita.fecha_creacion) <= 7";
            } else {
                $sqlSemestre .= " MONTH(solicitud_visita.fecha_creacion) >= 8 AND MONTH(solicitud_visita.fecha_creacion) <= 12";
            }

            // Ejecutar consulta con filtro de semestre
            $stmt = $pdo->prepare($sqlSemestre);
        } else {
            // Ejecutar consulta general sin filtro
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

// Obtener el valor de la variable de rango desde datos POST
$rango = isset($_POST['rango']) ? $_POST['rango'] : 2;
echo "Valor de \$rango: " . $rango . "<br>";

$solicitudes = obtenerSolicitudes($rango);

echo json_encode($solicitudes);
?>

<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Content-Type: text/html; charset=utf-8");
$method = $_SERVER['REQUEST_METHOD'];

function conectarDB(){
    $servidor = "localhost";
    $usuario = "visitas";
    $password = "Myp@ssw0";
    $bd = "visitas_industriales";

    try {
        $conexion = new PDO("mysql:host=$servidor;dbname=$bd;charset=utf8", $usuario, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        echo 'Ha sucedido un error inesperado en la conexión de la base de datos: ' . $e->getMessage();
        exit;
    }
}
?>
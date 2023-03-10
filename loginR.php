<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conectar.php';
$conn = conectarDb();

$data = json_decode(file_get_contents("php://input"));

$correo = $data->correo;
$contraseña = $data->contraseña;

$result = $conn->prepare("SELECT * FROM `usuario` WHERE `correo`=':correo' and `contraseña`=':contraseña'");
$result->bindParam(':correo', $correo);
$result->bindParam(':contraseña', $contraseña);
$result->execute();
$resultado = $result -> get_result();


if($result->execute() === true){
    http_response_code(200);
    echo json_encode(["success"=>1,"msg"=>"Usuario logueado"]);
}else{
    http_response_code(404);
    echo json_encode(["success"=>0,"msg"=>"Usuario o contraseña incorrectos"]);
}

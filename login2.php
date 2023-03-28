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

$result = mysqli_query($conn, "SELECT * FROM `usuario` WHERE `correo`='".$correo."' and `contraseña`='".$contraseña."'");

$nums = mysqli_num_rows($result);
$rs = mysqli_fetch_array($result);


// if()){
//     $result->bind_param(':correo', $data[$correo]);
 
//     $result->execute();
//     $resultado = $result -> get_result();
// }

if($nums > 0){
    http_response_code(200);
    $outp = "";

    $outp .= '{"correo":"'  . $rs["correo"] . '",';
    $outp .= '"id_usuario":"'   . $rs["id_usuario"]        . '",';
    $outp .= '"contraseña":"'   . $rs["contraseña"]        . '",';
    $outp .= '"nombres":"'   . $rs["nombres"]        . '",';
    $outp .= '"apellidoP":"'   . $rs["apellidoP"]        . '",';
    $outp .= '"apellidoM":"'   . $rs["apellidoM"]        . '",';
    $outp .= '"tipoUser":"'   . $rs["tipoUser"]        . '",';
    $outp .= '"Status":"200"}';

    echo $outp;

}else{
    http_response_code(202);
    $outp = "";
    $outp .= '{"Status":"202"}';
    echo $outp;
    
}

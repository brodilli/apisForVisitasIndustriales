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

$result = mysqli_query($conn, "SELECT * FROM `usuario` WHERE `correo`='".$correo."'");

$nums = mysqli_num_rows($result);
$rs = mysqli_fetch_array($result);


// if()){
//     $result->bind_param(':correo', $data[$correo]);
 
//     $result->execute();
//     $resultado = $result -> get_result();
// }

if($nums > 0){
    http_response_code(200);
    

    $contraseña= "123456";
     
    mail($correo, "Recuperar contraseña", "Su contraseña es: ".$contraseña);

}else{
    http_response_code(202);
    $outp = "";
    $outp .= '{"Status":"202"}';
    echo $outp;
    
}

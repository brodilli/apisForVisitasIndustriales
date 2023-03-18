<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conectar.php';
$con=conectarDb();

$data = json_decode(file_get_contents("php://input"));
$tipoUser = $data-> tipoUser;
$nombres= $data-> nombres;
$apellidoP= $data-> apellidoP;
$apellidoM= $data-> apellidoM;
$correo= $data-> correo;
$contraseña= $data-> contraseña;


$result = mysqli_query($con, "SELECT * FROM `usuario` WHERE `correo`='".$correo."'");
$nums = mysqli_num_rows($result);
$rs = mysqli_fetch_array($result);
if($nums > 0){
    echo json_encode(array('isOk'=>'existe','msj'=>'Correo ya registrado'));
}
else{
    if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
        $sqlQuery =("INSERT INTO `usuario`(`tipoUser`,`nombres`,`apellidoP`, `apellidoM`, `correo`, `contraseña`)
                VALUES ('".$tipoUser."','".$nombres."','".$apellidoP."', '".$apellidoM."', '".$correo."', '".$contraseña."' )");
     
            if ($con->query($sqlQuery) === TRUE) {
                http_response_code(200);
                echo json_encode(array('isOk'=>'true','msj'=>'Registro exitoso'));
                } else {
                    echo json_encode(array('isOk'=>'false','msj'=>$con->error)); 
                }
                mysqli_close($con);
    }
}

 ?>
<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require 'conectar.php';
$conexion=conectarDb();
$dataObject = json_decode(file_get_contents("php://input"));

$id_usuario = $dataObject-> id_usuario;
$numSesion = $dataObject-> numSesion;

if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
  $actualizacion = "UPDATE `usuario` SET 
		`numSesion`='$numSesion'
		WHERE id_usuario = $id_usuario";
   
   $resultadoActualizacion = mysqli_query($conexion, $actualizacion); 

   if($resultadoActualizacion)
   {
    echo json_encode(array('isOk'=>true,'msj'=>'Registro editado de forma exitosa.'));
   }
   else
   {
    echo json_encode(array('isOk'=>false,'msj'=>$conexion->error)); 
   }
}
?>
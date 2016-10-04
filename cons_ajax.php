<?php
//ini_set('display_errors', '1');
include('./includes/encabezado.php');
include('./cons_general.php');


$func = $_POST['funcion'];
if(isset($_POST['v1'])){$v1=$_POST['v1'];}else{$v1='';}

$res['data']=$func($_POST['id'],$v1);

//print_r($res);
$res['res']='exito';
//print_r($_POST);
//print_r($res);
//ini_set('display_errors', '1');
//print_r($res);
$salida=json_encode($res);
if($salida==''){
	echo "Error al codificar a Json";
	print_r($res);
}
echo $salida;


?>
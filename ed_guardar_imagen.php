<?php
/**
* ed_agrega_imagen.php
*
* aplicación para guardar y registrar en la base correspondiente un archivo generado por el cliente
 * 
 *  
* @package    	Plataforma Colectiva de Información Territorial: UBATIC2014
* @subpackage 	actividad
* @author     	Universidad de Buenos Aires
* @author     	<mario@trecc.com.ar>
* @author    	http://www.uba.ar/
* @author    	http://www.trecc.com.ar/recursos/proyectoubatic2014.htm
* @author		based on TReCC SA Procesos Participativos Urbanos, development. www.trecc.com.ar/recursos
* @copyright	2015 Universidad de Buenos Aires
* @copyright	esta aplicación se desarrollo sobre una publicación GNU 2014 TReCC SA
* @license    	http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 (GPL-3.0)
* Este archivo es parte de TReCC(tm) paneldecontrol y de sus proyectos hermanos: baseobra(tm), TReCC(tm) intraTReCC  y TReCC(tm) Procesos Participativos Urbanos.
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los términos de la "GNU General Public License" 
* publicada por la Free Software Foundation, version 3
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser útil, eficiente, predecible y transparente
* pero SIN NINGUNA GARANTÍA; sin siquiera la garantía implícita de
* CAPACIDAD DE MERCANTILIZACIÓN o utilidad para un propósito particular.
* Consulte la "GNU General Public License" para más detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aquí: <http://www.gnu.org/licenses/>.
* 
*
*/

include('./includes/encabezado.php');
ini_set('display_errors', '0');


if(count($_POST)>0){
	$Entrada=$_POST;
}else{
	$Entrada=$_GET;
}


	$Tabla = $Entrada['tabla'];
	$Id = $Entrada['id'];
	$Accion = $Entrada['accion'];
	

	$Salida = $Entrada['salida'];
	if($Salida==''){$Salida="indice_contenidos";}
	$Salidaid = $Entrada['salidaid'];
	if($Salidaid==''){$Salidaid=$_POST['id_p_PROproyectos'];}	
	$Salidatabla = $Entrada['salidatabla'];	
	

	$Base = 'estadisticas';
		
	
	foreach($Entrada as $k => $v){// estas variables son pasadas por als aplicaciones comunes manteniendose.
		if(substr($k,0,5)=='PASAR'){
			$PASAR[$k]=$v;
		}
	}
	
	
	


$Log=array();


function terminar($Log){
	$Log['tx'][]= "saliendo";
	echo json_encode($Log);
	exit;
}

if($_POST['id']<1){
	$Log['tx'][]="funcion llamada incorrectamente, falta id de la medicion: ".$_POST['idUbic'];

}
  

//echo json_encode(print_r($_POST,true));
//print_r($_FILES);
$Hoy=date("Y-m-d");

/*
$nombre = $_FILES['upload']['name'];
$Log['data']['nombreorig']=$nombre;
$b = explode(".",$nombre);
$extO =$b[(count($b)-1)];
$ext = strtolower($extO);
$nombreSinExt=str_replace(".".$extO, "", $nombre);
*/

$path='./imagenes/';
if(!file_exists($path)){
	$Log['tx'][]="creando carpeta $path";mkdir($path, 0777, true);chmod($path, 0777);	
}

if(!isset($_POST['carpeta'])){$_POST['carpeta']='general';}

$path .= str_replace("/", "", $_POST['carpeta']).'/';
if(!file_exists($path)){
	$Log['tx'][]="creando carpeta $path";mkdir($path, 0777, true);chmod($path, 0777);	
}

$nombre=$_POST['id'];
$nuevonombre= $path.$nombre.".png";

if(strlen($_POST['imagen'])<50){
	$Log['tx'][]="funcion llamada incorrectamente, falta informacion de la imagen: ".$_POST['imagen'];
	terminar($Log);
}

$img = $_POST['imagen'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);
//saving
$fileName = $nuevonombre;
file_put_contents($fileName, $fileData);


/*
$base64 = $_POST['imagen'];
$imageBlob = base64_decode($base64);
$imagick = new Imagick();

$imagick->readImageBlob($imageBlob);
$imagick->setImageFormat ("jpeg");
file_put_contents ($nuevonombre."png", $imagick);
//header("Content-Type: image/png");
//echo $imagick;	
//imagepng($imagick , $nuevonombre."png");
	*/

	
terminar($Log);


	
?>
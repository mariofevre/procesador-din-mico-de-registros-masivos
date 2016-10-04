<?php
/**
* ed_agrega_adjunto.php
*
* aplicación para guardar y registrar en la base correspondiente un archivo subido por el usuario
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
include('./cons_general.php');

$Log['data']=array();
function terminar($Log){
	echo json_encode($Log);
	exit;	
}

$Hoy=date("Y-m-d");
$ID=$_POST['id'];
if($ID<1){
	$Log['tx'][]= "la aplicacion de carga fue llamada incorrectametne, falta variable id.";
	$Log['res']='err';
	terminar($Log);
}
$idstring=str_pad($ID, 5,0,STR_PAD_LEFT);

$nombre = $_FILES['upload']['name'];

$Log['data']['nombreorig']=$nombre;
$b = explode(".",$nombre);
$ext = strtolower($b[(count($b)-1)]);
if($ext!='csv'){
	$Log['tx'][]= "error en la extensión del archivo, solo se acepta formato csv.";
	$Log['res']='err';
	terminar($Log);
}

$pathDocs='./documentos/';
if(!file_exists($pathDocs)){
	$Log['tx'][]= "creando carpeta $pathDocs";mkdir($pathDocs, 0777, true);chmod($pathDocs, 0777);	
}


$pathDocs.=$idstring.'/';
if(!file_exists($pathDocs)){
	$Log['tx'][]= "creando carpeta $pathDocs";mkdir($pathDocs, 0777, true);chmod($pathDocs, 0777);	
}

$rGuarda=$pathDocs.'tabla_'.$idstring.'.csv';
if (!copy($_FILES['upload']['tmp_name'], $rGuarda)) {
    $Log['tx'][] ="Error al copiar temp_".$rGuarda;
	$Log['res']='err';
	terminar($Log);	
}else{
	$Log['res']='exito';
	$Log['tx'][]=  "archivo guardado. ";
	terminar($Log);		
}

	
?>

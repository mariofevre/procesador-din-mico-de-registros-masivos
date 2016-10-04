<?php
/**
* ed_cargar_tabla.php
*
* pasa los campos y registros regisros de un CSV a una tabla
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
ini_set('display_errors', '1');
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

$pathDoc='./documentos/'.$idstring.'/tabla_'.$idstring.'.csv';

if(!file_exists($pathDoc)){
    $Log['tx'][] ="Error, el archivo: $pathDoc  . No existe.";
	$Log['res']='err';
	terminar($Log);	
}



$data = file($pathDoc);

$Largos=array();
foreach($data as $fila => $fdata){
	$e=explode(";",$fdata);
	
	foreach($e as $k => $val){
		if(!isset($Largos[$k])){$Largos[$k]=0;}
		$Largos[$k]=max($Largos[$k],strlen($val));
	}	
}

$tabla="CONT_".$idstring;
$base='estadisticas';


$query="DROP TABLE $base.$tabla";
mysql_query($query,$Conec1);
$Log['tx'][] =mysql_error($Conec1);
    
$query="CREATE TABLE `estadisticas`.`$tabla` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`))";
mysql_query($query,$Conec1);
$Log['tx'][] =mysql_error($Conec1);

$err=mysql_error($Conec1);	

if($err!=''){	
	$Log['tx'][]="Error al crear tabla:". $err;	
	$Log['tx'][]=$query;
	$Log['res'] ='err';
	terminar($Log);	
}  	
	
	
$encabezados=explode(";",$data[0]);

$cont=1;

foreach($encabezados as $k => $dato){
	
	$largo=$Largos[$k];
	
	if($largo<80){$tipo = "VARCHAR(100)";}
	elseif($largo<450){$tipo = "VARCHAR(500)";}
	else{$tipo = "TEXT";}
	
	$cstr="c".str_pad($cont, 3,0,STR_PAD_LEFT);
	$d=str_replace("'", "`", $dato);
	$d=str_replace('"', "`", $d);
	$query="
	ALTER TABLE 
	`estadisticas`.`".$tabla."` 
	ADD COLUMN `".$cstr."` $tipo NULL COMMENT '".$d."'";
	mysql_query($query,$Conec1);
	$Log['tx'][] =mysql_error($Conec1);
	
	$err=mysql_error($Conec1);	
	if($err!=''){
		$Log['tx'][]="Error al crear campo:". $err;
		$Log['tx'][]=$query;
		$Log['res'] ='err';
		terminar($Log);	
	}		
	$cont++;
}
$Log['data']['campos']=($cont-1);

$r=0;
unset($data[0]);
foreach($data as $fila){
	$f=str_replace("'", " ", $fila);
	$f=str_replace('"', "`", $f);
	$f=explode(';',$f);
	
	$cont=1;
	$sets='';
	
	foreach($f as $dato){
		$cstr="c".str_pad($cont, 3,0,STR_PAD_LEFT);
		$cont++;
		$sets.="`".$cstr."`='".$dato."', ";
	}
	$sets=substr($sets,0,-2);

	$query="
	insert INTO 
	`estadisticas`.`".$tabla."` 
	SET
	$sets";
	mysql_query($query,$Conec1);
		
	$err=mysql_error($Conec1);	
	if($err!=''){
		$Log['tx'][]="Error al cargar datos:". $err;
		$Log['tx'][]=$query;
		$Log['res'] ='err';
		terminar($Log);	
	}
	$r++;		
}
$Log['data']['registros']=$r;


$Log['res'] ='exito';
terminar($Log);	
?>

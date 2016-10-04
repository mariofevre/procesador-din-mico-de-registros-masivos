<?php
/**
* consultas.php
*
* reliza consultas a la base de datos  
* 
* @package    	TReCC(tm) redsustentable.
* @subpackage 	
* @author     	TReCC SA
* @author     	<mario@trecc.com.ar> <trecc@trecc.com.ar>
* @author    	www.trecc.com.ar  
* @copyright	2015 TReCC SA
* @license    	http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 (GPL-3.0)
* trabajo derivado de agrega_f.php copyright: 2010 TReCC SA (GPL-3.0)
* Este archivo es parte de TReCC(tm) paneldecontrol y de sus proyectos hermanos: baseobra(tm) y TReCC(tm) intraTReCC.
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los términos de la "GNU General Public License" 
* publicada por la Free Software Foundation, version 3
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser útil, eficiente, predecible y transparente
* pero SIN NIGUNA GARANTÍA; sin siquiera la garantía implícita de
* CAPACIDAD DE MERCANTILIZACIÓN o utilidad para un propósito particular.
* Consulte la "GNU General Public License" para más detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aquí: <http://www.gnu.org/licenses/>.
*/
//ini_set('display_errors', '1');

function consultaProyectoConf($IDproy){
	global $Conec1;
	
	$query="
		SELECT `PROproyectos`.`id`,
		    `PROproyectos`.`descripcion`,
		    `PROproyectos`.`campoident`
		FROM `estadisticas`.`PROproyectos`
		WHERE id='".$IDproy."'
	";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	$resultado=mysql_fetch_assoc($consulta);
	
	return $resultado;		
}


function consultaProyectos(){
	global $Conec1;
	
	$query="SELECT `PROproyectos`.`id`,
    `PROproyectos`.`descripcion`
	FROM `estadisticas`.`PROproyectos`
	";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	while($row=mysql_fetch_assoc($consulta)){
		$tabla="CONT_".str_pad($row['id'],5,"0",STR_PAD_LEFT);		
		$Contenidos[$tabla]['proyecto']=$row['descripcion'];
		$Contenidos[$tabla]['id']=$row['id'];
	}			
	
	$query="
		SHOW TABLES FROM estadisticas";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	while($row=mysql_fetch_assoc($consulta)){
		if(substr($row['Tables_in_estadisticas'],0,5)=='CONT_'){
			$Contenidos[$row['Tables_in_estadisticas']]['estado']='creada';
		}		
	}

	foreach($Contenidos as $nomtabla => $nodata){
		$columnas=0;
		$query="
		SHOW COLUMNS FROM ".$nomtabla."
		";
		$consulta=mysql_query($query,$Conec1);
		echo mysql_error($Conec1);
		while($row=mysql_fetch_assoc($consulta)){
			$columnas++;			
		}
		$Contenidos[$nomtabla]['columnas']=$columnas;
		
		$query="
		SELECT count(*) as num FROM ".$nomtabla.";
		";
		$consulta=mysql_query($query,$Conec1);
		echo mysql_error($Conec1);
		while($row=mysql_fetch_assoc($consulta)){
			$Contenidos[$nomtabla]['registros']=$row['num'];			
		}
		$Contenidos[$nomtabla]['columnas']=$columnas;		
	}
		
	$result['Contenidos']=$Contenidos;

	return $result;
		
}




function consultaPropEstructura($ID){
	global $Conec1;
	
	$query="
		SHOW TABLES FROM estadisticas";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	while($row=mysql_fetch_assoc($consulta)){
		if(substr($row['Tables_in_estadisticas'],0,5)=='CONT_'){
			$Contenidos[$row['Tables_in_estadisticas']]=array();
		}		
	}

	foreach($Contenidos as $nomtabla => $nodata){
		$columnas=0;
		$query="
		SHOW COLUMNS FROM ".$nomtabla."
		";
		$consulta=mysql_query($query,$Conec1);
		echo mysql_error($Conec1);
		while($row=mysql_fetch_assoc($consulta)){
			$columnas++;			
		}
		$Contenidos[$nomtabla]['columnas']=$columnas;
		
		$query="
		SELECT count(*) as num FROM ".$nomtabla.";
		";
		$consulta=mysql_query($query,$Conec1);
		echo mysql_error($Conec1);
		while($row=mysql_fetch_assoc($consulta)){
			$Contenidos[$nomtabla]['registros']=$row['num'];			
		}
		$Contenidos[$nomtabla]['columnas']=$columnas;
		
	}
		
	$result['Contenidos']=$Contenidos;

	return $result;		
}

function consultaCampos($IDproy,$Idcampo){
	//echo "hola2;"; print_r($Idcampo);
	global $Conec1;
	//,$Idcampo define un array de campos haiblidatos para consulta o seleccion
	
	$Campos = array(); //array de salida de resultado de campos
	$idstring=str_pad($IDproy, 5,0,STR_PAD_LEFT);
	$nomtabla="CONT_".$idstring;
	
	$query="
		SHOW FULL COLUMNS FROM ".$nomtabla."
		";
		$consulta=mysql_query($query,$Conec1);
		echo mysql_error($Conec1);
		while($row=mysql_fetch_assoc($consulta)){
			//echo "Hola";
			if($row['Field']=='id'){continue;}
			if(isset($Idcampo)){
				if(is_array($Idcampo)){
					//echo "son: ".count($Idcampo);
					//print_r($Idcampo);
					//echo  $row['Field'];
					if(!isset($Idcampo[$row['Field']])){continue;}//si fue definido, omite los campos que no figuren en lista.
				}	
			}	

			$Campos[$row['Field']]['Comment']=utf8_encode($row['Comment']);	
			$Campos[$row['Field']]['Field']=$row['Field'];
		}
		
		//echo $query;
	return	$Campos;
		
}

function consultaDepuracion($ID,$v1){
	global $Conec1;

	$query="SELECT `DEPacciones`.`id`,
	    `DEPacciones`.`id_p_PROproyectos`,
	    `DEPacciones`.`campos`,
	    `DEPacciones`.`procesamiento`,
	    `DEPacciones`.`variable1`,
	    `DEPacciones`.`variable2`,
	    `DEPacciones`.`orden`
	FROM `estadisticas`.`DEPacciones`
	WHERE id_p_PROproyectos='".$ID."'
	ORDER BY orden asc
	";
	//echo $query;
	$consulta=mysql_query($query,$Conec1);
	//echo mysql_num_rows($consulta);
	if(mysql_error($Conec1)!=''){
		return mysql_error($Conec1);
	}
	
	while($row=mysql_fetch_assoc($consulta)){
		
		foreach($row as $k => $v){
			$reg[$k]=utf8_encode($v);		
		}	
		$Tabla[]=$reg;
		unset($reg);
	}	
	
	return $Tabla;		
}
		
		
		
function consultaContenidos($ID){
	global $Conec1;
	
	$idstring=str_pad($ID, 5,0,STR_PAD_LEFT);
	$tabla = "CONT_".$idstring;
	$query="SELECT *
		FROM
		$tabla";
	
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	
	while($row=mysql_fetch_assoc($consulta)){
		foreach($row as $k => $v){			
			$dat['id']=$row['id'];	
			$campos[$k][$v]['instancias'][]=$dat;
			unset($dat);
		}
	}
	
	foreach($campos as $k => $kv){
			ksort($campos[$k]);
	}
	
	foreach($campos as $k => $kv){
		foreach($kv as $v => $dat){
			$d=$dat;				
			$d['dato']=utf8_encode($v);
			$d['cantidad']=count($dat['instancias']);
			$resumen[$k][]=$d;	
		}
	}
	//print_r($resumen);	
	return $resumen;		
}

function consultaContenidosTodo($ID,$Idcampo){
	global $Conec1;
	
	$campos='*';
	
	if(is_array($Idcampo)){
		$campos='id, ';
		foreach($Idcampo as $v){
			$campos .= "`".$v."`, ";
		}
		$campos=substr($campos, 0,-2);
	}
	
	$idstring=str_pad($ID, 5,0,STR_PAD_LEFT);
	$tabla = "CONT_".$idstring;
	$query="SELECT $campos
		FROM
		$tabla
	";	
	
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	
	while($row=mysql_fetch_assoc($consulta)){
		$datos[$row['id']]=$row;
	}
	
	return $datos;		
}

/*
 *llama funciones de consulta de registros (encuestas) y de criterios de depuración y aplica los segundos sobre los primeros
 * contiene funciones para las distintas acciones de depuarcion
 */		
function consultaContenidosDepurados($ID,$Idcampo){
	global $Conec1;	
	global $Depurado, $cDep, $cDat, $depId, $DESAGREG, $DESindex, $Nombres;

	$campos = consultaCampos($ID,$Idcampo);
	
	$Nombres['id'] = 'id';
	foreach($campos as $k => $v){
		$Nombres[$k] = $v['Comment'];
	}
	
	$query="
		SELECT `DEPcamposVisibles`.`id`,
	    `DEPcamposVisibles`.`id_p_PROproyectos`,
	    `DEPcamposVisibles`.`estado`,
	    `DEPcamposVisibles`.`campo`
	FROM `estadisticas`.`DEPcamposVisibles`
	WHERE id_p_PROproyectos = '".$ID."'
	;
	";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	
	//print_r($campos);	
	//identifica la visibilidad de campos específicos, utilizado para preservar el anonimato de los encuestados. Por defecto todos los campos son visibles
	$Visibles=array();
	while($row=mysql_fetch_assoc($consulta)){
		if(isset($campos[$row['campo']])){
			$Visibles[$row['campo']]=$row['estado'];
		}
	}	
	
	
	$query="
	SELECT `RETretirados`.`id`,
	    `RETretirados`.`campo_selec`,
	    `RETretirados`.`valor_selec`,
	    `RETretirados`.`motivo`
	FROM `estadisticas`.`RETretirados`
	";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	
	while($row=mysql_fetch_assoc($consulta)){
		$Retirados[]=$row;
		if(count($Idcampo)>1){
			$Idcampo[]=$row['campo_selec'];//incorpora los campos utilzados para filtro a la seleccionde campos en la consulta;
		}
	}		
	//print_r($Retirados);
	//print_r($Idcampo);
	//ini_set('display_errors', '');
	$cont=consultaContenidosTodo($ID,$Idcampo);// consulta contenidos crudos de la base de registros (encuestas)
	//print_r($cont);
	$dep= consultaDepuracion($ID,'');

	/*acción de depuración pone en mayúscula a la primer letra de cada palabra y en minúscula a las demás, en la cadena $tx
	 */ 
	function MaMi_MaMi($datosfila,$tx,$v1,$v2){		
		$string='';
		$e=explode(" ",$tx);
		foreach($e as $k => $v){
			$string.=mb_strtoupper(substr($v,0,1),'latin1');
			$string.=mb_strtolower(substr($v,1,strlen($v)),'latin1');
			$string.=" ";	
		}
		$string=substr($string, 0,-1);		
		return $string;
	}

	/*acción de depuración pone en mayúscula a la primer letra de la primer palabra y en minúscula a las demás, en la cadena $tx
	 */ 
	function MaMi_MiMi($datosfila,$tx,$v1,$v2){		
		$string='';
		$string.=mb_strtoupper(substr($tx,0,1),'latin1');
		$string.=mb_strtolower(substr($tx,1,strlen($tx)),'latin1');
		return $string;
	}	

	/*acción de depuración reemplaza en la cadena $tx, la subcadena $v1 por la cadena $v2 
	 */ 
	function reemplazar($datosfila,$tx,$v1,$v2){			
		$string=str_replace($v1,utf8_decode($v2),$tx);
		
	//if(substr($tx,-3)=='rez' && substr($v1,-3)=='rez'){	
	//echo $datosfila['id']." : ".$tx. " -> " .$v1. " -> " .$v2. "
	//";}		
		if($v1=='Ns / ns'){
		//echo 'f:"'.$v1.'","'.$v2.'".'.$tx;
		}
		return $string;
	}

	/*reemplaza la cadena $tx por la cadena $v2, solo si $tx es igual a $v1 
	 */ 	
	function reemplazarcompleto($datosfila,$tx,$v1,$v2){
		$a='';
	//if(strlen($tx)<2&&strlen($v1)<2&&$v2=='Ns/nc'){$a='si';echo PHP_EOL.$datosfila['id']." : ".$tx. " -> " .$v1. " -> " .$v2;}
	
	if($tx==$v1){				
			$tx=utf8_decode($v2);
	}
		//if($a=='si'){echo " --> " .$tx;}
		return $tx;
	}
	
	/*reemplaza la cadena $tx por el contenido del cámpo cuyo código expresa $v2 
	 */ 	
	function reemplazarporcampo($datosfila,$tx,$v1,$v2){

		if($tx==$v1){
			$tx=$datosfila[$v2];	
		}
		return $tx;
	}
	
	$NNDES = array();
	
	/* desagréga el campo en campos múltiples por cada sub cadena delimitada por el caracter indicado en $v1 
	 * los nuevos campos generados son definidos en $DESAGREG y su contenido será 1 si el registro contiene su subcadena correspondiente.
	 */ 		
	function desagregarporseparador($datosfila,$tx,$v1,$v2){
		global $Depurado, $ridRef, $cDat, $cDep, $depId, $DESAGREG, $DESindex, $Nombres, $NNDES;
		$e=explode($v1,$tx);
		
		$Nombres['id']='id';
		
		if(!isset($NNDES[$cDep])){$NNDES[$cDep]=0;}
		foreach($e as $comp){
			
			if($comp==''){continue;}
			$comp=utf8_encode($comp);
			$preexistente='no';
			
			
			if(count($DESAGREG)>0){				
				foreach($DESAGREG as $DK => $reg){
							
					if($reg['Comment']==$comp){
						//echo $reg['descripcion']." vs ".$comp;
						$preexistente=$DK;
					}else{
						//echo $reg['descripcion']." vs ".$comp;
					}
				}
			}
			

			if($preexistente=='no'){							
				$NNDES[$cDep]++;
				$cDes="dps".$NNDES[$cDep].$cDep;
				//echo "
				//	nueva: $cDes - $comp -.. ".$NNDES[$cDep]; 
				$a['Comment']=$comp;
				//$a['nom']=$cDes;
				$DESindex[$cDes]=$a;
				$DESAGREG[$cDes]=$a;
				$DESAGREG[$cDes]['regs'][$ridRef]='1';
				$Nombres[$cDes]=$comp;
			}else{
				$DESAGREG[$preexistente]['regs'][$ridRef]='1';
			}
			
		}
		//echo "HHi";
		//print_r($DESindex);
		return $tx;
	}	
					
	$DESAGREG=array();

	
	$RetirarId=array();
	foreach($cont as $rid => $datos){// itera los distintos registros (encuestas)
		global $ridRef;
		$ridRef=$rid;
		
		//echo " rid:". $rid." ";
		$retirar='no';
		
		$Depurado[$rid]['id']=$datos['id'];
		

		$RetirarId[$rid]='no';
		foreach($Retirados as $ret){
			if(isset($datos[$ret['campo_selec']])){
				//echo "HOLA".$datos[$ret['campo_selec']]." vs ".$ret['valor_selec'];
				if($datos[$ret['campo_selec']]==$ret['valor_selec']){
					//echo " H";
					$retirar='si';
					$RetirarId[$rid]='si';
					//continue ;
				}
				//echo PHP_EOL;
			}
		}
		//echo $RetirarId[$rid]. PHP_EOL;
		//print_r($Retirados);
			
		//print_r($RetirarId);
		//echo "<pre>arr:";print_r($Depurado);echo "</pre>";
		
		foreach($datos as $cDat => $d){// itera los distintos campos de cada registro
			//echo $cDat;
			if(isset($Depurado[$rid])){//carga los datos de los campos ya depurados para utilizar compo poencial input en funciones como reempolazarcampo
				$datosfila=$Depurado[$rid];
			}else{
				$datosfila=array();
			}
			$d=trim($d);
			
			//print_r($datosfila);
			//$retirar='no';
			/*
			foreach($Retirados as $ret){		
				if($ret['campo_selec']==$cDat&&$ret['valor_selec']==$d){
					unset($cont[$rid]);
					//print_r($Retirados);
					$retirar='si';
					echo "HHHOLLLA";print_r($datos);
					//continue 3;
					
				}
			}*/

			if($retirar=='no'){
				//if($rid==119){print_r($datos);}
				//echo "?;";		
				$dat=$d;
				foreach($dep as $depId => $acc){
					
					//echo "2?;";
					$c=explode(";",$acc['campos']);
					foreach($c as $cDep){
						//echo ";".$cDep." vs ".$cDat;
						//print_r($datosfila);
						if($cDep==$cDat){
							$func=str_replace("()","",$acc['procesamiento']);
							//ini_set('display_errors', '1');
							///echo ":$func, $dat);	";		
							//print_r(count($datosfila));echo "_ ";
								
							$dat=call_user_func_array($func, array($datosfila,$dat,$acc['variable1'],$acc['variable2']));
							//ini_set('display_errors', '0');
							//$dat=MaMi_MaMi($dat);	
										
						}
					}
				}

				$Depurado[$rid][$cDat]=$dat;
			}else{
				
			}			
		}		
	}
	
	
	foreach($cont as $rid => $datos){
		foreach($DESAGREG as $cDes => $res){
			//echo PHP_EOL."$cDes ".$res['descripcion'];print_r($res['regs']);			
			
			if(isset($res['regs'][$rid])){
				$Depurado[$rid][$cDes]=$res['regs'][$rid];
			}else{
				$Depurado[$rid][$cDes]=0;
			}
		}
	}
	//print_r($Depurado);
	
//print_r($Depurado);
	
	foreach($RetirarId as $rid => $vv){
		//echo $vv.PHP_EOL;
		if($vv=='si'){
			//echo "depurando ".$rid.PHP_EOL;//print_r($Depurado);
			unset($Depurado[$rid]);
		}else{
			//echo "manteniendo ".$rid.PHP_EOL;//print_r($Depurado);
		}	
			
	}	
	//print_r($Depurado[119]);
	$r['registros']=$Depurado;
	$r['nuevoscampos']=$DESindex;
	$r['nombres']=$Nombres;
	$r['visibles']=$Visibles;
	//echo "HOLA";
	//print_r($Nombres);
	return $r;
}


/**
 * genera los resultados para una seleecion de campos, es util para calcular factores de expansion.
 *
 * 
 */
function consultaResumen($IDproy,$Idcampo){
	global $Conec1;		
	//$Idcampo define un campo único de consulta para optimizar consultas
	

	$res=consultaContenidosDepurados($IDproy,$Idcampo);

	//print_r($Idcampo);
	$Depurado=$res['registros'];
	//print_r($res['nombres']);

	foreach($Depurado as $row){
		unset($dat);
		foreach($row as $k => $v){
			$dat['id']=$row['id'];
			$campos[$k][$v]['instancias'][]=$dat;
			unset($dat);
		}
	}
	
	foreach($campos as $k => $kv){
			ksort($campos[$k]);
	}
	
	
	
	foreach($campos as $k => $kv){		
		foreach($kv as $v => $dat){
			$d=$dat;		
			$d['dato']=utf8_encode($v);
			$d['cantidad']=count($dat['instancias']);
			$resumen[$k]['valores'][]=$d;
			
			if(!isset($res['nombres'][$k])){$res['nombres'][$k]='';}
			$resumen[$k]['nombre']=$res['nombres'][$k];
		}

		if(!isset($res['visibles'][$k])){$res['visibles'][$k]=null;}
		if($res['visibles'][$k]===0){
			$resumen[$k]['visible']=0;
		}elseif($res['visibles'][$k]===1||$res['visibles'][$k]===null){
			//echo "V:".$k. " : ".$Visibles[$k]." _";
			$resumen[$k]['visible']=1;
		}
	}
	
	foreach($resumen as $k1 => $arr1){
		unset($cants);
		$res=$resumen[$k1]['valores'];
		foreach($arr1['valores'] as $k2 => $arr2){
			$cants[]=$arr2['cantidad'];
		}
		
		array_multisort($cants,SORT_DESC,$res);
		
		$resumen[$k1]['valores']=$res;
	}
		
//print_r($resumen);
	return $resumen;		
	
}

function apagarCampoDepurado($ID, $campo){
	global $Conec1;		
	
	$query="
	SELECT * FROM DEPcamposVisibles WHERE id_p_PROproyectos='".$ID."' AND campo='".$campo."'
	";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);	
	if(mysql_num_rows($consulta)>0){
		$query="		
		UPDATE DEPcamposVisibles SET estado='0' WHERE id_p_PROproyectos='".$ID."' AND campo='".$campo."'";
		mysql_query($query,$Conec1);
		$Log['tx'][]='actualizado registro, campo apagado';
		$Log['data']['apagado']=$campo;
		$Log['res']='exito';
	}else{
		$query="		
		INSERT INTO DEPcamposVisibles SET estado='0', id_p_PROproyectos='".$ID."', campo='".$campo."'";
		mysql_query($query,$Conec1);
		$Log['tx'][]='generado registro, campo apagado';
		$Log['data']['apagado']=$campo;
		$Log['res']='exito';
	}
	
	return $Log;
}
	
function consultaConsultas($IDproy){
	global $Conec1;
	
	$query="
	SELECT `CONconsultasAgrupadas`.`id`,
    `CONconsultasAgrupadas`.`id_p_PROproyectos`,
    `CONconsultasAgrupadas`.`nombre`,
    `CONconsultasAgrupadas`.`descripcion`,
    `CONconsultasAgrupadas`.`tipo`,    
    `CONconsultasAgrupadas`.`campo`,
	`CONconsultasAgrupadas`.`orden`,
	`CONconsultasAgrupadas`.`corteCant`,
	 `CONconsultasAgrupadas`.`cortePor`,
	 `CONconsultasAgrupadas`.`camposbinarios`
	
	FROM `estadisticas`.`CONconsultasAgrupadas`
	WHERE id_p_PROproyectos='$IDproy'
	";
	$ConsultasAgrup=mysql_query($query,$Conec1);
	$Log['tx'][]='consulta err: '.mysql_error($Conec1);
	echo mysql_error($Conec1);
	$Consultas=array();
	while($row=mysql_fetch_assoc($ConsultasAgrup)){
		$Consultas[$row['id']]['data']['id']=$row['id'];
		$Consultas[$row['id']]['data']['nombre']=utf8_encode($row['nombre']);
		$Consultas[$row['id']]['data']['descripcion']=utf8_encode($row['descripcion']);	
		$Consultas[$row['id']]['grupos']=array();
		$Consultas[$row['id']]['tipo']=utf8_encode($row['tipo']);
		$Consultas[$row['id']]['campo']=$row['campo'];
		$Consultas[$row['id']]['camposbinarios']=$row['camposbinarios'];
		$Consultas[$row['id']]['orden']=utf8_encode($row['orden']);
		$Consultas[$row['id']]['corteCant']=$row['corteCant'];
		$Consultas[$row['id']]['cortePor']=$row['cortePor'];
		$Consultas[$row['id']]['grupos']=array();
	}

	$query="
	SELECT `CONagrupadasGrupos`.`id`,
    `CONagrupadasGrupos`.`id_p_CONagrupadas`,
    `CONagrupadasGrupos`.`nombre`,
    `CONagrupadasGrupos`.`descripcion`,
    `CONagrupadasGrupos`.`exclusivo`
	FROM `estadisticas`.`CONagrupadasGrupos`
	WHERE id_p_PROproyectos='$IDproy'
	";
	$Grupos=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	$Log['tx'][]='consulta err: '.mysql_error($Conec1);	
	while($row=mysql_fetch_assoc($Grupos)){
		$Consultas[$row['id_p_CONagrupadas']]['grupos'][$row['id']]['data']['id']=$row['id'];
		$Consultas[$row['id_p_CONagrupadas']]['grupos'][$row['id']]['data']['nombre']=utf8_encode($row['nombre']);
		$Consultas[$row['id_p_CONagrupadas']]['grupos'][$row['id']]['data']['descripcion']=utf8_encode($row['descripcion']);
		$Consultas[$row['id_p_CONagrupadas']]['grupos'][$row['id']]['data']['exclusivo']=$row['exclusivo'];
		$Consultas[$row['id_p_CONagrupadas']]['grupos'][$row['id']]['casos']=array();
		$Consultas[$row['id_p_CONagrupadas']]['grupos'][$row['id']]['cant']=0;
	}
	
	$query="	
	SELECT `CONagrupadasCasos`.`id`,
    `CONagrupadasCasos`.`id_p_CONagrupadas`,
    `CONagrupadasCasos`.`id_p_CONagrupadasGrupos`,
    `CONagrupadasCasos`.`campo`,
    `CONagrupadasCasos`.`valor`,
    `CONagrupadasCasos`.`valorno`,
    `CONagrupadasCasos`.`valmin`,
    `CONagrupadasCasos`.`valmax`,
    `CONagrupadasCasos`.`especial`
	FROM `estadisticas`.`CONagrupadasCasos`
	WHERE id_p_PROproyectos='$IDproy'
	";
	$Casos=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	$Campos=consultaCampos($IDproy,'');
	
	$Log['tx'][]='consulta err: '.mysql_error($Conec1);	
	while($row=mysql_fetch_assoc($Casos)){
		$Consultas[$row['id_p_CONagrupadas']]['grupos'][$row['id_p_CONagrupadasGrupos']]['casos'][$row['id']]=$row;
		if(isset($Campos[$row['campo']])){$d=$Campos[$row['campo']];}else{$d='...';}
		$Consultas[$row['id_p_CONagrupadas']]['grupos'][$row['id_p_CONagrupadasGrupos']]['casos'][$row['id']]['CampoDesc']=$d;
		//if($Campos[$row['campo']]==''){echo "AAAC·A".$row['campo'];	}
	}
	
	return $Consultas;
}


/**
 * $IDproy: id del proyecto, limita la consulta a un solo proyecto (obligatorio)
 * $seleccion: array de ids de consultas solicitadas, permite una cosulta parcial para optimizar recursos (opcional) 
 */
function consultaConsultasResultados($IDproy,$seleccion){
	global $Conec1;		
	
	$Cons = array();//listado de consultas y casos sin datos relevados
	$Cons=consultaConsultas($IDproy);
	//echo "<pre>";print_r($Cons);echo "</pre>";	
	
	$query="SELECT
	`EXPdefinicion`.`id`,
	`EXPdefinicion`.`id_p_PROproyectos`,
	`EXPdefinicion`.`campoId`,
	`EXPdefinicion`.`serieref`,
	`EXPdefinicion`.`seriefact`
	FROM `estadisticas`.`EXPdefinicion`
	WHERE id_p_PROproyectos='".$IDproy."'
	";
	$expan=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	$expan=mysql_fetch_assoc($expan);
	$CampoExpan=$expan['campoId'];
	//$FactoresExpan=json_decode($expan['seriefact'],true);
	
	//echo $expan['seriefact'];
	// lo que sigue reemplaza jsondecode que no está funcionando en servitest.
	$recorte=substr($expan['seriefact'],2);
	$recorte=substr($recorte,0,-2);
	$terminos=explode('","',$recorte);
	foreach($terminos as $tdat){
		//echo $tdat;
		$def=explode('":"',$tdat);
		//print_r($def);
		$d['nombre']=$def[0];
		$d['valor']=$def[1];		
		$FactoresExpan[$def[0]]=$def[1];
	}

	//print_r($FactoresExpan);
		
	$res=consultaContenidosDepurados($IDproy,'');	
	$Val=array();//listado de valores obtenidos
	$Val=$res['registros'];
	//echo "<pre>";print_r($Val);echo "</pre>";
	
	$camposnuevos=$res['nuevoscampos'];//campos resultante de la desagregación por separadores
	//print_r($camposnuevos);	
	$Campos=consultaCampos($IDproy,'');	
	//print_r($Campos);
	$Campos=array_merge($Campos,$camposnuevos); //arreglar esto
	//print_r($Campos);
		
	foreach($Cons as $kcon => $con){
		
		//print_r($seleccion);		
		if(count($seleccion)>0){
			if($seleccion[0]>0){
				//echo "HOLA".count($selecion);
				$incluido='no';
				foreach($seleccion as $sId){
					if($sId==$kcon){
						$incluido='si';
					}
				}
				if($incluido=='no'){unset($Cons[$kcon]);continue;}
			}
		}
		//print_r($con);
		unset($Otros);
		unset($gr);
		unset($Total);
		$TotalPob=0;
		unset($grupos);
		unset($gruposPR);
		unset($gruposRes);
		unset($grOrd);
		
		if($con['tipo']==utf8_encode('Agrupación Automática')){
			// la agrupación automática define grupos de un valor. permite incluir un grupo otros.
			
			
			$val1=$Val;
			
			$Cons[$kcon]['registros']=count($val1);// la cantidad de registros es la cantidad de encuestas, que puede diferir de la cantidad de incidencias.
			foreach($val1 as $rid => $rdata){
				$categoria=$rdata[$CampoExpan];			
				if(!isset($Cons[$kcon]['pobTotal'])){$Cons[$kcon]['pobTotal']=0;}
				$Cons[$kcon]['pobTotal']+=$FactoresExpan[$categoria];//población representada
			}	
			
			
			unset($otros);
			$Total=0;
			$c=0;
			$cant=array();
			foreach($val1 as $rid => $rdata){
				$Total++;
				$key= utf8_encode($rdata[$con['campo']]);
				//if($key==''){print_r($rdata);}
				if(!isset($grupos[$key])){$grupos[$key]=0;}
				$grupos[$key]++;//encuestados por grupo
				$categoria=$rdata[$CampoExpan];
				if(!isset($gruposPR[$key])){$gruposPR[$key]=0;}	
				$gruposPR[$key]+=$FactoresExpan[$categoria];//población representada
				$TotalPob+=$FactoresExpan[$categoria];//población representada
			}
			
			unset($dd);
			//$nn=0;
			foreach($grupos as $k => $v){
				$dd['nombre']=$k;
				$dd['cant']=$v;
				//$nn++;
				//$dd['id']=$nn;
				$dd['pobrepres']=$gruposPR[$k];
				$gr[]=$dd;
				$grOrd[]=$v;
			}			

			array_multisort(
				$grOrd, SORT_NUMERIC, SORT_DESC,
				$gr, SORT_ASC
            );

			$Otros=0;
			$OtrosPR=0;
			foreach($gr as $k =>$v){	
				if($v['cant']<=$con['corteCant']){					
					$Otros+=$v['cant'];
					$OtrosPR+=$v['pobrepres'];
					unset($gr[$k]);
				}				
			}				
			
			if($Otros>0){
				$dd['cant']=$Otros;
				$dd['nombre']='otros';
				$dd['pobrepres']=$OtrosPR;
				$gr[]=$dd;
			}
			
			foreach($gr as $k => $v){
				$gr[$k]['Pinc']=100*$v['cant']/$Total;
				$gr[$k]['Penc']=100*$v['cant']/$Cons[$kcon]['registros'];				
				$gr[$k]['PincEX']=100*$v['pobrepres']/$TotalPob;
				$gr[$k]['PencEX']=100*$v['pobrepres']/$Cons[$kcon]['pobTotal'];
				
				if(!isset($Cons[$kcon]['gruposRes']['maxP'])){$Cons[$kcon]['gruposRes']['maxP']=0;}
				$Cons[$kcon]['gruposRes']['maxP']=max($Cons[$kcon]['gruposRes']['maxP'],$gr[$k]['Penc'],$gr[$k]['PencEX']);
			}
			
			$Cons[$kcon]['grupos']=$gr;
			$Cons[$kcon]['gruposRes']['totalcant']=$Total;
			$Cons[$kcon]['gruposRes']['totalPob']=$TotalPob;
			
			unset($Otros);
			unset($gr);
			unset($Total);
			unset($grupos);
		
		}elseif($con['tipo']==utf8_encode('Campos Binarios')){
			// la agrupación  por campos binarios, genera grupos a partir de una sicecion de campos que con valor 1 .
			
			$e=explode(";",$con['camposbinarios']);
			//echo $con['camposbinarios']." son:". count($e)."campos binarios utilizados";
			
			
			$val1=$Val;	
			$Cons[$kcon]['registros']=count($val1);// la cantidad de registros es la cantidad de encuestas, que puede diferir de la cantidad de incidencias.

			unset($otros);
			$Total=0;
			$c=0;
			$cant=array();
									
			foreach($val1 as $rid => $rdata){
				
				$categoria=$rdata[$CampoExpan];			
				if(!isset($Cons[$kcon]['pobTotal'])){$Cons[$kcon]['pobTotal']=0;}
				$Cons[$kcon]['pobTotal']+=$FactoresExpan[$categoria];//población representada
				
				foreach($e as $campo){
					if($rdata[$campo]==1){
						//echo $FactoresExpan[$categoria]." ";
						$Total++;
						if(!isset($grupos[$campo])){$grupos[$campo]=0;}
						$grupos[$campo]++;
						if(!isset($gruposPR[$campo])){$gruposPR[$campo]=0;}	
						$gruposPR[$campo]+=$FactoresExpan[$categoria];//población representada
						//echo $gruposPR[$key];
						$TotalPob+=$FactoresExpan[$categoria];//población representad
						//echo $TotalPob;
					}

				}
			}	
			
			//echo $gruposPR[$key];
			//echo $TotalPob;
			unset($dd);
			//$nn=0;
			foreach($grupos as $k => $v){
				//$dd['nombre']=$k;
				$dd['descripcion']=$Campos[$k]['Comment'];
				$dd['nombre']=substr($Campos[$k]['Comment'],0,25);
				$dd['cant']=$v;
				//$nn++;
				//$dd['id']=$nn;
				$dd['pobrepres']=$gruposPR[$k];
				$gr[]=$dd;
				$grOrd[]=$v;
			}			

			array_multisort(
				$grOrd, SORT_NUMERIC, SORT_DESC,
				$gr, SORT_ASC
            );

			$Otros=0;
			$OtrosPR=0;
			foreach($gr as $k =>$v){	
				if($v['cant']<=$con['corteCant']){					
					$Otros+=$v['cant'];
					$OtrosPR+=$v['pobrepres'];
					unset($gr[$k]);
				}				
			}				
			
			if($Otros>0){
				$dd['cant']=$Otros;
				$dd['nombre']='otros';
				$dd['pobrepres']=$OtrosPR;
				$gr[]=$dd;
			}
			
			foreach($gr as $k => $v){
				$gr[$k]['Pinc']=100*$v['cant']/$Total;
				$gr[$k]['Penc']=100*$v['cant']/$Cons[$kcon]['registros'];				
				$gr[$k]['PincEX']=100*$v['pobrepres']/$TotalPob;
				$gr[$k]['PencEX']=100*$v['pobrepres']/$Cons[$kcon]['pobTotal'];
				
				if(!isset($Cons[$kcon]['gruposRes']['maxP'])){$Cons[$kcon]['gruposRes']['maxP']=0;}
				$Cons[$kcon]['gruposRes']['maxP']=max($Cons[$kcon]['gruposRes']['maxP'],$gr[$k]['Penc'],$gr[$k]['PencEX']);
			}
			
			$Cons[$kcon]['grupos']=$gr;
			$Cons[$kcon]['gruposRes']['totalcant']=$Total;
			$Cons[$kcon]['gruposRes']['totalPob']=$TotalPob;
			
			unset($Otros);
			unset($gr);
			unset($Total);
			unset($grupos);
			
		}else{
			
			// la agrupación, o agrupación común, carga cirterios de agrupación definidos uno a uno. Una misma encuesta oregistro pueden pertenecer a más de un grupo.
			
			$val1=$Val;	
			//print_r($Val);	
			$Cons[$kcon]['registros']=count($val1);
			
			foreach($val1 as $rid => $rdata){
				
				$categoria=$rdata[$CampoExpan];			
				if(!isset($Cons[$kcon]['pobTotal'])){$Cons[$kcon]['pobTotal']=0;}
				$Cons[$kcon]['pobTotal']+=$FactoresExpan[$categoria];//población representada
			}	
			
			unset($otros);			
			
			foreach($con['grupos'] as $Gid => $Gdata){
				//print_r($Gdata);				
				$grupos[$Gid]=$Gdata['data'];
				
				foreach($Gdata['casos'] as $Cid => $Cdata){
					
					$grupos[$Gid]['casos'][$Cid]=$Cdata;					
					
					if($Cdata['especial']=='otros'){
						if(isset($otros)){
							//echo "mas de una definoción para otros: ERRR.";break;
						}
						$otros=$Gdata;
						$otros['Gid']=$Gid;
						continue;
					}
					//if($Cdata['especial']!=''&&$Cdata['campo']==''){$Cdata['campo']=$Cdata['especial'];}
					
					if($Campos[$Cdata['campo']]==''){
						$Campos[$Cdata['campo']]['Comment']=$camposnuevos[$Cdata['campo']]['descripcion'];
					}			
					$grupos[$Gid]['casos'][$Cid]['CampoDesc']=$Campos[$Cdata['campo']];
					
					foreach($val1 as $rid => $rdata){
						
						//if($Cdata['campo']=="c025"){echo PHP_EOL.$Cdata['campo'].": ".$rdata[$Cdata['campo']];}
 	
						
						$incluido='no';
						if($rdata[$Cdata['campo']]==$Cdata['valor']&&$Cdata['valor']!=''){
							$incluido='si';
						}else{
							
							$e=explode(";",$Cdata['valor']);
							if(count($e)>0){
								foreach($e as $v){
									//echo "HHHH: $v - ".$Cdata['valor'];
									if($v==$rdata[$Cdata['campo']]&&$Cdata['valor']!=''){
										$incluido='si';
									}
								}
							}
						}
						if($incluido=='si'){
							//captura registro por inclusión. si contiene el valor de inclución indicado pertenece al grupo
								
								
							//echo PHP_EOL."cons: ".$kcon ." ".$Cdata['campo']. ": ".$rdata[$Cdata['campo']]." = ". $Cdata['valor'] ." ". $Gid." r:".$grupos[$Gid]['cant']." p:".$grupos[$Gid]['pobrepres'];
							
							if(!isset($grupos[$Gid]['cant'])){$grupos[$Gid]['cant']=0;}
							$grupos[$Gid]['cant']++;
							if(!isset($gruposRes['totalcant'])){$gruposRes['totalcant']=0;}
							$gruposRes['totalcant']++;
							//echo " - suma por inclusion: ".$grupos[$Gid]['cant'];
							//$Cons[$kcon]['grupos'][$Gid]['cant']++;
							//$Cons[$kcon]['totalcant']++;
							
							$categoria=$rdata[$CampoExpan];								
							if(!isset($grupos[$Gid]['pobrepres'])){$grupos[$Gid]['pobrepres']=0;}
							$grupos[$Gid]['pobrepres']+=$FactoresExpan[$categoria];//población representada
							if(!isset($gruposRes['totalPob'])){$gruposRes['totalPob']=0;}
							$gruposRes['totalPob']+=$FactoresExpan[$categoria];//población representada
	
							
							if($Gdata['data']['exclusivo']=='1'){
								unset($val1[$rid]);
							}
							
							continue;
						}elseif($rdata[$Cdata['campo']]!=$Cdata['valorno']&&$Cdata['valorno']!=''){
							//captura registro por exclusion. si contiene el valor de exclusión indicado pertenece al grupo
							
							if(!isset($grupos[$Gid]['cant'])){$grupos[$Gid]['cant']=0;}
							$grupos[$Gid]['cant']++;
							$gruposRes['totalcant']++;
							//echo " - suma por exclusion: ".$grupos[$Gid]['cant'];
							//echo PHP_EOL.".$Cdata['campo']. " ". $Gid;
							//$Cons[$kcon]['grupos'][$Gid]['cant']++;
							//$Cons[$kcon]['totalcant']++;
							
							$categoria=$rdata[$CampoExpan];	
							if(!isset($grupos[$Gid]['pobrepres'])){$grupos[$Gid]['pobrepres']=0;}
							$grupos[$Gid]['pobrepres']+=$FactoresExpan[$categoria];//población representada
							$gruposRes['totalPob']+=$FactoresExpan[$categoria];//población representada
							
							if($Gdata['data']['exclusivo']=='1'){
								unset($val1[$rid]);
							}
							continue;
						}elseif(is_numeric($rdata[$Cdata['campo']])&&$Cdata['valmin']!==null&&$Cdata['valmax']!==null){
							//captura registro por rango de inclusión. si contiene un valor contenido dentro del rango de máximo y mínimo
							
							if($rdata[$Cdata['campo']]>=$Cdata['valmin']&&$rdata[$Cdata['campo']]<=$Cdata['valmax']){
								
								if(!isset($grupos[$Gid]['cant'])){$grupos[$Gid]['cant']=0;}
								$grupos[$Gid]['cant']++;
								if(!isset($gruposRes['totalcant'])){$gruposRes['totalcant']=0;}
								$gruposRes['totalcant']++;
								//echo " - suma por rango: ".$grupos[$Gid]['cant'];
								
								//$Cons[$kcon]['grupos'][$Gid]['cant']++;
								//$Cons[$kcon]['totalcant']++;
								
								$categoria=$rdata[$CampoExpan];	
								if(!isset($grupos[$Gid]['pobrepres'])){$grupos[$Gid]['pobrepres']=0;}
								$grupos[$Gid]['pobrepres']+=$FactoresExpan[$categoria];//población representada
								if(!isset($gruposRes['totalPob'])){$gruposRes['totalPob']=0;}
								$gruposRes['totalPob']+=$FactoresExpan[$categoria];//población representada
									
								
								if($Gdata['data']['exclusivo']=='1'){
								unset($val1[$rid]);
							}
								continue;
							}
						}
					}				
				}
			}
			//print_r($grupos);
			
			
			if(isset($otros)){
				
				if(!isset($grupos[$otros['Gid']]['cant'])){$grupos[$otros['Gid']]['cant']=0;}
				
				
				$grupos[$otros['Gid']]['cant']+=count($val1);
				
				$gruposRes['totalcant']+=count($val1);
				
				foreach($val1 as $rid => $rdata){
					//print_r($rdata);					
					$categoria=$rdata[$CampoExpan];
					if(!isset($grupos[$otros['Gid']]['pobrepres'])){$grupos[$otros['Gid']]['pobrepres']=0;}
					$grupos[$otros['Gid']]['pobrepres']+=$FactoresExpan[$categoria];						
					$gruposRes['totalPob']+=$FactoresExpan[$categoria];//población representada
				}
				unset($val1);				
			}

			unset($grOrd);
			foreach($grupos as $k => $v){
				$grupos[$k]['Pinc']=100*$v['cant']/$gruposRes['totalcant'];
				$grOrd[]=$v['cant'];
				$grupos[$k]['Penc']=100*$v['cant']/$Cons[$kcon]['registros'];				
				$grupos[$k]['PincEX']=100*$v['pobrepres']/$gruposRes['totalPob'];
				$grupos[$k]['PencEX']=100*$v['pobrepres']/$Cons[$kcon]['pobTotal'];
				
				if(!isset($gruposRes['maxP'])){$gruposRes['maxP']=0;}
				$gruposRes['maxP']=max($gruposRes['maxP'],$grupos[$k]['Penc'],$grupos[$k]['PencEX']);	
				
			}

			
			array_multisort(
				$grOrd, SORT_NUMERIC, SORT_DESC,
				$grupos, SORT_ASC, SORT_STRING
            );



			$Cons[$kcon]['grupos']=$grupos;
			$Cons[$kcon]['gruposRes']=$gruposRes;
		}
	}
	//print_r($Cons);
	return $Cons;
	
}


function consultaConsultasResultadosCrucesAuto($IDproy,$seleccion){
	global $Conec1;	
	

	$Cons=consultaConsultas($IDproy);
	//echo "HOLA<pre>";print_r($Cons);echo "</pre>";
	$res=consultaContenidosDepurados($IDproy,'');	
	$Val=$res['registros'];


	$WhereAnd=" ";
	
	if(count($seleccion)>0){
		if($seleccion[0]>0){
			foreach($seleccion as $sId){				
				$WhereAnd.=" AND id='".$sId."' ";
			}
			if($incluido=='no'){unset($Cons[$kcon]);continue;}
		}
	}
			
	$query="
		SELECT `CONconsultasCruces`.`id`,
	    `CONconsultasCruces`.`nombre`,
	    `CONconsultasCruces`.`descripcion`,
	    `CONconsultasCruces`.`id_p_CONconsultasAgrupadas_A`,
	    `CONconsultasCruces`.`id_p_PROproyectos`,
	    `CONconsultasCruces`.`id_p_CONconsultasAgrupadas_B`
	FROM `estadisticas`.`CONconsultasCruces`
	WHERE id_p_PROproyectos = '".$IDproy."'
	".$WhereAnd."
	";
	$CrucesAgrup=mysql_query($query,$Conec1);
	$Log['tx'][]='consulta err: '.mysql_error($Conec1);
	echo mysql_error($Conec1);
	$Cruces=array();
	
	while($row=mysql_fetch_assoc($CrucesAgrup)){
		$Cruces[$row['id']]['data']['nombre']=utf8_encode($row['nombre']);
		$Cruces[$row['id']]['data']['descripcion']=utf8_encode($row['descripcion']);	
		$Cruces[$row['id']]['data']['id']=$row['id'];
		$Cruces[$row['id']]['consultas'][$row['id_p_CONconsultasAgrupadas_A']]=$Cons[$row['id_p_CONconsultasAgrupadas_A']];
		$Cruces[$row['id']]['consultas'][$row['id_p_CONconsultasAgrupadas_B']]=$Cons[$row['id_p_CONconsultasAgrupadas_B']];
	}
	
	//print_r($Val);	
	//$camposnuevos=$res['nuevoscampos'];
	//print_r($camposnuevos);
	//$Campos=consultaCampos($IDproy);
	//$Campos=array_merge($Campos,$camposnuevos);
	//print_r($Campos);
		
	foreach($Cruces as $kcruce => $cruce){
		$ret=$cruce;
		unset($CruceGrupos);
		unset($matriz);
		unset($CrCo);
		unset($CrCons);
		unset($gr);
		unset($grupos);
		$Total=0;//total de incidencias registradas se acumulan en esta variable
		//print_r($cruce['consultas']);
		
		foreach($cruce['consultas'] as $kcon => $con){
			//print_r($con);	
			$val1=$Val;
			if($con['tipo']==utf8_encode('Agrupación Automática')){
				unset($otros);
				$Total=0;
				unset($grupos);//ojo acá
				foreach($val1 as $rid => $rdata){
					$Total++;
					$key=utf8_encode($rdata[$con['campo']]);
					//if($key==''){print_r($rdata);}
					if(!isset($grupos[$key])){$grupos[$key]=0;}
					$grupos[$key]++;					
					$CruceGrupos[$rid][$kcon]=$key;		
				}			
				
				unset($gr);//ojo acá
				foreach($grupos as $k => $v){				
					$gr['nombre'][]=$k;
					$gr['cant'][]=$v;	
				}
				//$grupos = $gruposSk
				/*if($con['orden']=='alfabetico'){
					ksort($grupos);
				}elseif($con['orden']=='cantidades'){
					arsort($grupos);	
				}*/	
				array_multisort(
					$gr['cant'], SORT_NUMERIC, SORT_DESC, 
					$gr['nombre'], SORT_ASC, SORT_STRING
	            );
					
					
				$Otros=0;			
				
				foreach($gr['cant'] as $k =>$vvv){	
					if($vvv<=$con['corteCant']){
						$Otros++;
						$CrOtros[$kcon][$k]='';
						unset($gr['cant'][$k]);
						unset($gr['nombre'][$k]);	
					}
				}
				
				if($Otros>0){
					$gr['cant'][]=$Otros;
					$gr['nombre'][]='otros';
				}
				
				//arsort($gr['nombre'],$gr['cant']);
				//print_r($gr);
				$CrCons[$kcon]['grupos']=$gr;
				$CrCons[$kcon]['totalcant']=$Total;
				$CrCons[$kcon]['nombre']=$con['data']['nombre'];
				$CrCons[$kcon]['descripcion']=$con['data']['descripcion'];
				
				unset($Otros);
				unset($gr);
				unset($grupos);
				
			}else{
				
				
				$val1=$Val;	
				//print_r($Val);	
				$Cons[$kcon]['registros']=count($val1);
				unset($grupos);//ojo acá 
				//unset($otros);			
				foreach($con['grupos'] as $Gid => $Gdata){
					//print_r($Gdata);				
					$Gnom=$Gdata['data']['nombre'];
					$grupos[$Gnom]=0;
					
					foreach($Gdata['casos'] as $Cid => $Cdata){
						//echo "<br>- ".$Gnom;
						
						//$Cons[$kcon]['grupos'][$Gid]['casos'][$Cid]['CampoDesc']=$Campos[$Cdata['campo']];
						
						if($Cdata['especial']=='otros'){
							if(isset($otros)){
								//echo "mas de una definoción para otros: ERRR.";break;
							}
							$otros=$Gdata;
							$otros['Gid']=$Gnom;
							continue;
						}
						
						foreach($val1 as $rid => $rdata){						
							
							
							
							$incluido='no';
							if($rdata[$Cdata['campo']]==$Cdata['valor']&&$Cdata['valor']!=''){
								$incluido='si';
							}else{
								
								$e=explode(";",$Cdata['valor']);
								if(count($e)>0){
									foreach($e as $v){
										//echo "HHHH: $v - ".$Cdata['valor'];
										if($v==$rdata[$Cdata['campo']]&&$Cdata['valor']!=''){
											$incluido='si';
										}
									}
								}
							}
							if($incluido=='si'){
								//echo PHP_EOL."cons: ".$kcon ." ".$Cdata['campo']. ": ".$rdata[$Cdata['campo']]." = ". $Cdata['valor'] ." ". $Gid." r:".$Cons[$kcon]['grupos'][$Gid]['cant'];

								$grupos[$Gnom]++;					
								$CruceGrupos[$rid][$kcon][]=$Gnom;
								$Total++;	
								
								if($Gdata['data']['exclusivo']=='1'){
									unset($val1[$rid]);
								}
							}elseif($rdata[$Cdata['campo']]!=$Cdata['valorno']&&$Cdata['valorno']!=''){
								//echo PHP_EOL.".$Cdata['campo']. " ". $Gnom;
								$grupos[$Gnom]++;					
								$CruceGrupos[$rid][$kcon][]=$Gnom;	
								$Total++;
								
								if($Gdata['data']['exclusivo']=='1'){
									unset($val1[$rid]);
								}
							}elseif(is_numeric($rdata[$Cdata['campo']])){
								if($rdata[$Cdata['campo']]>=$Cdata['valmin']&&$rdata[$Cdata['campo']]<=$Cdata['valmax']){
									
									
									$grupos[$Gnom]++;					
									$CruceGrupos[$rid][$kcon][]=$Gnom;	
									$Total++;
									
									if($Gdata['data']['exclusivo']=='1'){
										unset($val1[$rid]);
									}
									
								}
							}								
						}				
					}
				}
				
				if(isset($otros)){
					//echo "HOLA";
					//print_r($otros);
					foreach($val1 as $rid => $rdata){							
						$CruceGrupos[$rid][$kcon]=$otros['Gid'];		
						$grupos[$otros['Gid']]++;	
						$Total++;
					}
					unset($val1);				
				}
				
				unset($gr);//OJO
				foreach($grupos as $k => $v){				
					$gr['nombre'][]=$k;
					$gr['cant'][]=$v;					
				}	
				
				$CrCons[$kcon]['grupos']=$gr;
				$CrCons[$kcon]['totalcant']=$Total;
				$CrCons[$kcon]['nombre']=$con['data']['nombre'];
				$CrCons[$kcon]['descripcion']=$con['data']['descripcion'];
			}
		}

		foreach($CruceGrupos as $rid => $rdat){
			foreach($rdat as $kcon => $kgrup){
								
				
				//print_r($kgrup);
				if(is_array($kgrup)){
					$valores=array();
					foreach($kgrup as $valor){
						$valores[]=$valor;
					}
					$g[]=$valores;
					unset($valores);
				}else{
					$g[]=$kgrup;				
				}
			}		
			//print_r($g);
			if(is_array($g[1])){
				
				foreach($g[1] as $valores){
					//echo " ".$valores." ";
					//print_r($g[0]);
					if(!isset($matriz[$g[0]][$valores])){
						$matriz[$g[0]][$valores]=0;
					}	
					$matriz[$g[0]][$valores]++;
					
					$CrOtros[$kcon][$k]='';
				}
				unset($g);
			}else{
				if(is_array($g[0])){
					foreach($g[0] as $valores){
						//echo " ".$valores." ";
						if(!isset($matriz[$valores][$g[1]])){
							$matriz[$valores][$g[1]]=0;
						}	
						$matriz[$valores][$g[1]]++;
						
						$CrOtros[$kcon][$k]='';
					}
					unset($g);	
					
				}else{
						
					if(!isset($matriz[$g[0]][$g[1]])){
						$matriz[$g[0]][$g[1]]=0;
					}	
					$matriz[$g[0]][$g[1]]++;
					unset($g);
					$CrOtros[$kcon][$k]='';		
				}		
			}
		
		}

		foreach($CrCons as $reg){
			$CrCo[]=$reg;
		}	
		
		$ret['consultas']=$CrCo;
		$ret['matriz']=$matriz;
		
		$resultado[]=$ret;
	}
	//print_r($resultado);	
	return $resultado;
}


function consultaExpansion($IDproy){
	global $Conec1;
	
	$query="
		SELECT `PROproyectos`.`id`,
		    `PROproyectos`.`descripcion`,
		    `PROproyectos`.`campoident`,
		    EXPdefinicion.id as iddef,
		    EXPdefinicion.campoId,
		    EXPdefinicion.serieref,
		    EXPdefinicion.seriefact
		FROM `estadisticas`.`PROproyectos`, `estadisticas`.`EXPdefinicion`
		WHERE PROproyectos.id='".$IDproy."' AND EXPdefinicion.id_p_PROproyectos=PROproyectos.id;
	";
	$consulta=mysql_query($query,$Conec1);	
	echo mysql_error($Conec1);	
	$conf=mysql_fetch_assoc($consulta);
	
	$resultado['configuracion']=$conf;
	$resultado['configuracion']['descripcion']=utf8_encode($conf['descripcion']);
	$resultado['configuracion']['serieref']=utf8_encode($conf['serieref']);
	//echo "EEE".utf8_encode($conf['serieref']);
	
	


		
	$resultado['configuracion']['seriefact']=utf8_encode($conf['seriefact']);	
	
	if($conf==null){		
		$resultado['estado']='sin expansion';		
	}elseif($conf['campoId']==null){		
		$resultado['estado']='campo sin cargar';
		$resultado['campos']=consultaCampos($IDproy,'');			
	}else{
		
		$arr[$conf['campoId']]=$conf['campoId'];
		//print_r($arr);
		$res=consultaResumen($IDproy,$arr);
		$resultado['valores']=$res;
		
		if($conf['serieref']==null){
			$resultado['estado']='serie sin cargar';
		}else{
			
			//ini_set('display_errors', '1');
			$resultado['estado']='datos cargados a la serie';
			
			// lo que sigue reemplaza jsondecode que no está funcionando en servitest.
			$recorte=substr($conf['serieref'],2);
			$recorte=substr($recorte,0,-2);
			$terminos=explode('","',$recorte);
			foreach($terminos as $tdat){
				$def=explode('":"',$tdat);
				$d['nombre']=$def[0];
				$d['valor']=$def[1];
				$serieRefa[]=$d;
			}
			
			//$serieRef=json_decode($conf['serieref'], true);
			
			$lista=array();
			$Total=0;
			$TotatlRef=0;
			$refpendientes='no';
			
			
			//print_r($serieRef);

			
			$queriSerie='';
			foreach($res[$conf['campoId']]['valores'] as $a){
				$dato= utf8_decode($a['dato']);
				$lista[$a['dato']]=$a['cantidad'];
				$Total+=$a['cantidad'];
				
				if($a['cantidad']>0){
					unset($datoenserie);
					foreach($serieRefa as $ser){
						//print_r($ser['nombre']);
						
						if($ser['nombre']==$dato){
							$datoenserie=$ser['valor'];
						}
					}
					if(!isset($datoenserie)){									
						$serieRef[$a['dato']]='SD!';
						$refpendientes='si';					 		
					}else{
						$TotatlRef+=$datoenserie;
						$resultado['seriefact'][$a['dato']]=$datoenserie/$a['cantidad'];
						//echo $dato;
						
						$seriequery[utf8_encode($dato)]=$datoenserie/$a['cantidad'];						
						$queriSerie.='"'.$dato.'":"'.$datoenserie/$a['cantidad'].'",';	
					}
				}
			}			

			$queriSerie="{".substr($queriSerie, 0,-2)."}";
			//print_r($res[$conf['campoId']]);
			
			if($refpendientes=='si'){
				$resultado['estado']='carga de la serie incompleta';
			}	
			
			if($queriSerie!==$conf['seriefact']){
			 
				$query="
					UPDATE `estadisticas`.`EXPdefinicion` SET seriefact = '".$queriSerie."'
					WHERE id = '".$conf['iddef']."'";
				//echo $query;
				$Grupos=mysql_query($query,$Conec1);
				echo mysql_error($Conec1);
				
				$resultado['mensajes'][]='actualizada la serie de factores de expansion';
				$resultado['mensajes'][]=//json_encode($seriequery);
				$resultado['mensajes'][]=mysql_error($Conec1);
			}
			//$resultado['resieref2']=$serieRef;
		}
	}
	
	return $resultado;		
}


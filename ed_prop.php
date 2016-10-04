<?php

include ('./includes/encabezado.php');	

$Seccion = $_SESSION['AppSettings']->SECCION;
//$Usuario = usuarioaccesos();// en ./includes/usuarioaccesos.php
	
	
	foreach($_GET as $gk => $gv){
		$VAR[$gk]=$gv;
	}
	foreach($_POST as $pk => $pv){
		unset($VAR[$pk]);
		$VAR[$pk]=$pv;
	}		
	
	$Base = $_SESSION['AppSettings']->DATABASE_NAME;
	$Tabla = $VAR["tabla"];
	
	$Id_contrato = $VAR["contrato"];
	
	$Id = $VAR["id"];
	$Accion = $VAR["accion"];	
	$Origenid = $VAR["Origenid"];      /* variable para cuando la entrada agregada responde a otra que debe ser cerrada (comunicaciones)*/
	$Paraorigen = $VAR["Paraorigen"];	/* variable para incorporar al orgigenid */	
	$Fecha = $Fechaa."-".$Fecham."-".$Fechad;
	$Tablahermana = $VAR["tablahermana"];
	$Idhermana = $VAR["idhermana"];		
	$Salida = $VAR['salida'];
	$Salidaid = $VAR['salidaid'];	
	$Salidatabla = $VAR['salidatabla'];				
	$HOY = date("Y-m-d");
	$HOYd = date("d");
	$HOYm = date("m");
	$HOYa = date("Y");
	$Publicacion .= "<br><br>";
 	$result = mysql_query('SHOW FULL COLUMNS FROM `'.$Tabla.'`',$Conec1);
	echo mysql_error($Conec1);
	print_r($VAR);
	
	if($VAR['accion']=='anulacelda'){	
		
		if($Tabla!='PROaccNull'){
			echo "Acción anulada, tabla solicitada no está reconocida para la acción requerida, cod 44953202";
			exit;
		}
		while ($row = mysql_fetch_assoc($result)){
			if($row['Field']=='id'){continue;}
			$sets.=	$row['Field']."='".$_GET[$row['Field']] ."', ";
		}
		$sets=substr($sets,0,-2);	
		$query=
			"
			INSERT INTO $Base.$Tabla SET $sets
			";
	}elseif($VAR['accion']=='borra'){	
		
		if($Tabla!='PROaccNull'){
			echo "Acción anulada, tabla solicitada no está reconocida para la acción requerida, cod 409822";
			exit;
		}
		
		
		while ($row = mysql_fetch_assoc($result)){
			if($row['Field']=='zz_borrada'){$haypapelera='si';}
		}
		
		if($haypapelera=='si'){
			$query=
			"
			UPDATE $Base.$Tabla SET zz_borrada='1';
			";
		}else{
			$query=
			"
			DELETE FROM $Base.$Tabla WHERE id='".$VAR['id']."';
			";			
		}
		
	}
	

	//echo $query;
	//echo "<br>";
	
	mysql_query($query,$Conec1);
	echo mysql_error($Conec1)."<br>";
    $Id = mysql_insert_id($Conec1);
	
	echo "<script type='text/javascript'>";
	echo "
		page_y = parent.window.scrollY;
		_loc=parent.location.href;
		var res = _loc.split('?');
		";
	echo "parent.window.location.assign(res[0]+'?y='+page_y);";
	echo "</script>";
		
	break;
	
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
        	
        	$campo = $row['Field'];
			$datomas = $VAR[$campo];
			$Type = substr($row['Type'],0,3);
			$Typolink = substr($row['Field'],0,4);
			$Typo = substr($row['Field'],0,3);			
			
			if($Tabla=='usuarios'){
				if($campo=='zz_pass'){$datomas=md5($datomas);}
				if($campo=='zz_fechacreacion'){$datomas=$HOY;}
				if($campo=='zz_ipcreacion'){$datomas=$_SERVER['REMOTE_ADDR'];}
				if($campo=='zz_autor'){$datomas=$UsuarioI;}
				if($campo=='zz_id_p_paneles'){$datomas=$PanelI;}
			}
			/* para tablas padre */
			if($Typolink == "id_p"){
				$Publicacion .= "<br>padre en: ".$campo. "->".$datomas;
				if($datomas == "n"){
					$Publicacion .= "<br>n solicita nuevo item";
					$Baselink = substr($row['Field'],0,6);
					if($Baselink != "id_p_B")
					{
						
						$Publicacion .= "<br>padre interno";
						$o = explode("_", $row['Field']);
						$basepadre = $Base;
						$tablapadre = $o[2];
						$campopadre = $o[4];
						$extra = "";
						if($o[5]=='tipoa'){$extra = ", tipo = 'a'";}
						elseif($o[5]=='tipob'){$extra = ", tipo = 'b'";}
						elseif($o[5]=='tipo'){$extra.=", ".$o[5]."='".$o[6]."'";}
						$padre = $basepadre . "." . $tablapadre;
						$campocont = $campo."_n";
						$nuevocontenido=$VAR[$campocont];
						
						//Verifica no repetición en el nombre para tablas específicas, ej: grupos
						$query="	
							SELECT 
								* 
							FROM
								 $tablapadre
								 WHERE $campopadre='$nuevocontenido' AND id_p_paneles_id_nombre='$PanelI'
						";
						$existe=mysql("$Base",$query,$Conec1);
						$Publicacion .= mysql_error($Conec1);
						if(mysql_num_rows($existe)>0){
							$Publicacion .= "<br>nombre de item existente, creación anulada";
							$Publicacion .= $query;
							$Idnuevo=mysql_result($existe,0,'id');
							$Publicacion .= "<br>id reciclado: ".$Idnuevo;
							$datomas = $Idnuevo;
						}else{						
							$query = "INSERT INTO $tablapadre SET $campopadre='$nuevocontenido', id_p_paneles_id_nombre='$PanelI'$extra, zz_AUTOPANEL='$PanelI'";
							mysql("$Base",$query,$Conec1);
							$Publicacion .= mysql_error($Conec1);
							$Idnuevo = mysql_insert_id($Conec1);
							$Publicacion .= "nuevo id: ".$Idnuevo;
							$datomas = $Idnuevo;
							$Publicacion .= "agregará: ".$datomas;
						}
					}
				}
				$Publicacion .= "<br>otro;". " - " . $row['Field']. " - " . $datomas."<br>";
				if($datomas != ""){
					$Datos .= " `" . $campo . "`='" .  $datomas . "',";
				}
					
			}elseif($Typo == 'zz_' && $campo == 'zz_AUTOFECHAMODIF'){
				$Datos .= " `" . $campo . "`='" .  $HOY . "',";
			}elseif($Typo == 'zz_'&& $campo == 'zz_AUTOFECHACREACION'){
				$Datos .= " `" . $campo . "`='" .  $HOY . "',"; /* este campo nunca se debe modificar, debe ser una impresión del momento de creación del registro */
			}elseif($Typo == 'zz_'&& $campo == 'zz_AUTOPANEL'){
				$Datos .= " `" . $campo . "`='" .  $PanelI . "',"; /* este campo nunca se debe modificar, debe ser una impresión del momento de creación del registro */
			}elseif($Typo == 'FI_'){

				$_SESSION['DEBUG']['mensajes'][] = "Dectectado campo de fichero (FI_), se guardaran los archivos enviados:<br>";
				echo "<pre>";print_r($_FILES);echo "</pre>";
				
				$NombrePHParchivo='archivo_'.$campo;
				if(isset($_FILES[$NombrePHParchivo])){
					echo "archivo enviado";
					$imagenid = $_FILES[$NombrePHParchivo]['name'];	
					$_SESSION['DEBUG']['mensajes'][] = "<br>cargando: ".$imagenid."<br>";
					$b = explode(".",$imagenid);
					$ext = strtolower($b[(count($b)-1)]);	

					$path = $VAR[('archivo_'.$campo.'_path')];
					
					// verificar y crear directorio 
						$Publicacion.="<p>analizando ruta</p>";
						$carpetas= explode("/",$path);		
						if($carpetas[0]!='.'){
							$path='./'.$path;
						}
						
						$carpetas= explode("/",$path);		
						if($carpetas[1]!='documentos'){
							$path=str_replace('./','./documentos/',$path);
						}
						
						$carpetas= explode("/",$path);		
						if($carpetas[2]!="p_$PanelI"){
							$path=str_replace("./documentos/","./documentos/p_$PanelI/",$path);
						}
						
						if(substr($path,-1)!='/'){
							$path=$path."/";
						}
								
						$carpetas= explode("/",$path);	
						$rutaacumulada="";			
						foreach($carpetas as $valor){		
						$Publicacion .= "<p>instancia de ruta: $valor </p>";
						$rutaacumulada.=$valor."/";
							if (!file_exists($rutaacumulada)&&$valor!=''){
								$Publicacion.="<p>creando: $rutaacumulada </p>";
							    mkdir($rutaacumulada, 0777, true);
							    chmod($rutaacumulada, 0777);
							}
						}		
					// FIN verificar y crear directorio				
					
						$nombretipo = $Tabla."[NID]";
						$nombrerequerido = isset($VAR[$Campo]) ? $VAR[$Campo]:'';
																		
						if($nombrerequerido!=''&&!file_exists($nombrerequerido)){
							$nombre=$nombrerequerido;
							$nombreprliminar='no';//indica que el documento NO debe ser renombrado luego de creado el registro.
						}else{
							$nombre=$nombretipo;
							$nombreprliminar='si';//indica que el documento debe ser renombrado luego de creado el registro.			
						}				
						
						$c=explode('.',$nombre);
						
						$cod = cadenaArchivo(10); // define un código que evita la predictivilidad de los documentos ante búsquedas maliciosas
						$nombre=$path.$c[0].$cod.".".$ext;
						
						if($nombreprliminar=='si'){
							$d['nombre']=$nombre;
							$d['campo']=$campo;
							$Reemplazos[]=$d;
						
						}//incorpora la ruta al listado de docuemtnso a renombrar.
	
						$extVal['jpg']='1';
						$extVal['png']='1';
						$extVal['tif']='1';
						$extVal['bmp']='1';
						$extVal['gif']='1';
						$extVal['pdf']='1';
						$extVal['zip']='1';
						
						if(isset($extVal[strtolower($ext)])){
							$_SESSION['DEBUG']['mensajes'][] = "guardado en: ".$nombre."<br>";
							
							if (!copy($_FILES[$NombrePHParchivo]['tmp_name'], $nombre)) {
							    $_SESSION['DEBUG']['mensajes'][] = "Error al copiar $pathI...\n";
							}else{
								$_SESSION['DEBUG']['mensajes'][] = "imagen guardada";
								$datomas = $nombre;	
								$Datos .= " `" . $campo . "`='" .  $datomas . "',";
								$_SESSION['DEBUG']['mensajes'][] = 'sentencia: '.$campo . "`='" .  $datomas . "',";
							}
						}else{
							$ms="solo se aceptan los formatos:";
							foreach($extVal as $k => $v){$ms.=" $k,";}
							$_SESSION['DEBUG']['mensajes'][] = $ms;
							$imagenid='';
							
						}	
							
				}

				
			}elseif($row['Field'] != "id"){
				if ($Type == "tex"){
					$datomas = str_replace("<br />","",$VAR[$campo]);
						if($datomas != ""){
							$Datos .= " `" . $campo . "`='" .  $datomas . "',";
						}
				}elseif($Type == "dat"){
				$Publicacion .= "<br>fecha;". " - " . $row['Field']. " - " . $datomas;
					$campo_a = $campo . "_a";
					$campo_m = $campo . "_m";
					$campo_d = $campo . "_d";
					
					$contenidoa = $VAR[$campo_a];
					$contenidom = $VAR[$campo_m];
					$contenidod = $VAR[$campo_d];
					
					/* ojo este comentario tal vez gener conflicto
					if($contenidoa == '' || $contenidom == '' ||$contenidod == ''){
						if($contenidod == ''){$contenidod =$HOYd;}
						if($contenidom == ''){$contenidom =$HOYm;}
						if($contenidoa == ''){$contenidoa =$HOYa;}
					}
					*/
					
					$datomas = $contenidoa . "-" . $contenidom . "-" . $contenidod;
			
						if($datomas != "--"){
							$Datos .= " `" . $campo . "`='" .  $datomas . "',";
						}
				}else{
						if($datomas != ""){
							$Datos .= " `" . $campo . "`='" .  $datomas . "',";
						}
				}

			
        	}
		}
	}


$Datos = substr($Datos,0,(strlen($Datos)-1));


$Publicacion .= "<br>id base: ";
$Publicacion .= $Id_contrato;
$Publicacion .= "<br>tabla: ";
$Publicacion .= $Tabla;
$Publicacion .= "<br>id: ";
$Publicacion .= $Id;
$Publicacion .= "<br>accion: ";
$Publicacion .= $Accion;
$Publicacion .= "<br>";
$Publicacion .= "<br>datos: ";
$Publicacion .= $Datos;
$Publicacion .= "<br>";
$Publicacion .= "<br>";


$query="INSERT INTO $Base.$Tabla SET $Datos";
mysql_query($query,$Conec1);
$Publicacion .= $query."<br>";
$Publicacion .= mysql_error($Conec)."<br>";
$Id = mysql_insert_id($Conec1);

$NID = $Id;
$Publicacion .= $Id . "<br>";



$Publicacion .= $Salida;
$Publicacion .=".php?tabla=";
$Publicacion .= $Tabla;
$Publicacion .="&id=";
$Publicacion .= $Salidaid;
if($Salidaid=='nid'){
	$Salidaid=$NID;
}	


echo $Publicacion;

$_SESSION['DEBUG']['mensajes'][]=$Publicacion;



if($Salida!='__ALTO'){
	if($Salida!=''){
		$Publicacion .= "saliendo...";
		echo $Publicacion;
		$cadenapasar='';
		foreach($PASAR as $k => $v){
			$cadenapasar.='&'.substr($k,5).'='.$v;
		}
		
		$location="./".$Salida.".php?tabla=".$Tabla."&id=".$Salidaid.$cadenapasar;
		
		
		?><SCRIPT LANGUAGE="javascript">location.href = "<?php echo $location;?>";</SCRIPT><?php  	
		
	}else{
		?>
			<button onclick="window.close();">cerrar esta ventana</button>
		<?php  
	}
}


?>

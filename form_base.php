<?php

include("../includes/mensajesdesarrollo.php");// carga sistema de mensajes de desarrollo
include ('./includes/encabezado.php');	
include ('../includes/usuarioaccesos.php');
rendimiento_checkpoint('conectado: ',__DIR__,__FILE__,__LINE__);//medicion rendimiento lamp
$Seccion = $_SESSION['AppSettings']->SECCION;
$Usuario = usuarioaccesos();// en ./includes/usuarioaccesos.php	

//if($_SERVER[SERVER_ADDR]=='192.168.0.252')ini_set('display_errors', '1');ini_set('display_startup_errors', '1');ini_set('suhosin.disable.display_errors','0'); error_reporting(-1);
	


		
	$Id_contrato = $_GET["contrato"];
	$Tabla = $_GET["tabla"];
	$Id = $_GET["id"];
	$Accion = $_GET["accion"];
	$Modo = $_GET["modo"];
	$Campo = $_GET["campo"];//DECRECATED;
	if($Campo!=''){
		$_SESSION['DEBUG']['mensajes'][]="llamada desactualizada en $_GET. 'campo=' debe cambiar por 'campos[]='.";
	}
	$CampoS = $_GET["campos"];	
	if($Campo==''&&$CampoS!=''){$Campo="--HHHHH--";}
	$Tablahermana = $_GET["tablahermana"];
	$Idhermana = $_GET["idhermana"];
	
	$campofijo = $_GET["campofijo"];
	if($campofijo == ""){$campofijo = "vacio";}
	$campofijo_c = $_GET["campofijo_c"];
	
	$campofijob = $_GET["campofijob"];
	if($campofijob == ""){$campofijob = "vacio";}
	$campofijob_c = $_GET["campofijob_c"];

	$Salida = $_GET["salida"];
	$Salidaid = $_GET["salidaid"];	
	$Salidatabla = $_GET["salidatabla"];
	
	//$ConUsu = mysql_query("usuarios", $query,$Conec1);	

	

	
	mysql_select_db ('laminaimagen',$Conec1);
													
	
	foreach($_GET as $k => $v){// estas variables son pasadas por als aplicaciones comunes manteniendose.
		if(substr($k,0,5)=='PASAR'){
			$PASAR[$k]=$v;
		}
	}

												
	
	
?>
<!DOCTYPE html>
<head>
	<title>Panel de Control</title>
	<link rel="stylesheet" type="text/css" href="./css/general.css">
	<?php
	if($Modo!='mini'){
		echo '<link rel="stylesheet" type="text/css" href="./css/panelbase.css">';
	?>
	<style type="text/css">
		.gris {
		background-color:lightgray;
		}
		
		.carga {
		background-color:#A9F5BC;
		}
		
		select{
		width:300px;
		float:left;
		}
		
		form{
		margin:0;
		width:160px;
		width:auto;
		}

		table{
		width:800px;
		margin:5px;
		}

		textarea{
			font-family:'arial';
			font-size:11px;
			width:100%;
		}
		
		.dato{
		min-height: 55px;
		border-top:2px solid #000;
		margin-top:2px;
		}
		
		.boton{
		width:80px;		
		}
		
				
		.boton2{
		width:25px;		
		}
		
		.boton3{
		width:150px;
		}
		
		p{
		margin:0 10px;
		}
		
		h1{
		margin:0 10px;
		}
		
		input.fecha{
		float:left;
		width:40px;		
		}		

		input.chico{
		width:300px;
		}		
		
		input.mini{
		float:left;
		width:25px;
		}		
		
		select.chico{
		width:100px;
		}			
			
		th{
		font-size:12px;
		text-align:right;
		background-color:lightblue;
		width:100px;
		}

		.paquete{
		width:300px;
		margin-bottom:5px;
		float:left;
		border: 1px gray solid; 
		padding: 5px;
		background-color:#C4DBE2;
		}
				
		.salva{
		width:90px;
		margin-bottom:5px;
		float:left;
		}

		.reporte{
		width:200px;
		}

		.marco{
		border-bottom: 1px solid gray;
		float: none;
		width: 100%;		
		}
		
		.referencia{
		font-size: 10px;
		}

		.alerta{
		background-color:red;
		}

		.similth{
		background-color:none;
		width:800px;
		text-align:left;
		}	
	
	
		.similth > div{
		background-color:lightblue;
		width:100px;
		margin-left:3px;
		border:white 2px solid;
		text-align:right;
		position:relative;
		top:-4px;
		}
		
	#reporte_bid{
	float:left;
	}
	
	#PTUBA{
	float:left;
	}
	
	#imagenesI{
	float:left;
	}
	
	#imagenesII{
	float:left;
	}
	
	iframe{
		border:1px solid gray;
		width:310px;
		height:80px;
	}

	input[readonly='readonly']{	
		background-color:lightblue;		
		height:4px;
	}

	input[type="button"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;
	}
	input[type="submit"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;	  
	    top:20px;
	    left:150px;
	    width:105px;	      
	}
	input[type="reset"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;		  
	    top:20px;
	    left:260px;      
	    width:105px;	    
	}		

	input[value="Eliminar"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;		  
	    top:20px;
	    left:490px;    
	    width:105px;
	    background-color:#FA5858;
	    font-weight:bold;
	    color:#000;
	}		
	input[value="Recuperar"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;		  
	    top:20px;
	    left:490px;    
	    width:105px;
	    background-color:#FA5858;
	    font-weight:bold;
	    color:#000;
	}	
	input[value="Cancelar"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;		  
	    top:20px;
	    left:370px;    
	    width:105px;
	}		


	
	.dato > a{
	    display: inline-block;
	    height: 12px;
	    overflow: visible;
	    width: 145px;
	}
	
	.dato > a:hover{
	    z-index:30;
	}	
	
	.contenedor{
		font-size:9px;
		height:10px;
		display:inline-block;
		width:20px;
		margin-right:2px;
		position:relative;
		overflow:visible;
		vertical-align: top;
	}
	.contenido{
		background-color:lightblue;
		border:#08AFD9 1px solid;
		color:#000;
		display:inline-block;
		width:19px;
		height:10px;
		margin:0px;
		position:absolute;
		overflow:hidden;
		text-align:center;
	}
	
	.subcontenedor.oculto{
		display:none;
		width:90px;
		height:auto;
		position:absolute;
	}
	
	
	
	.contenedor:hover > .contenido.aclara{
		display:none;
	}
	
	.contenedor:hover > .subcontenedor.oculto{
		display:block;
	}
	
	.contenedor:hover > .subcontenedor.oculto > .contenido.oculto{
		display:block;
		position:absolute;		
		width:auto;
		min-width:20px;
		max-width:90px;
		z-index:20;
		height:auto;
		border:1px solid #000;
	}	
	
		.aprobada{
			background-color:#55BF55;
			border-color: #CDF7CD #008000 #008000 #CDF7CD;
		}
		.rechazada{
			background-color:#FF565B;
			border-color:  #FFC0CB #F00 #F00 #FFC0CB;
		}
		.enevaluacion{
			background-color:blue;
			background-color:#3797FF;
			border-color:   #5CF2FF   #00F #00F #5CF2FF;
		}
		.apresentar{
			background-color:grey;
			border-color:  silver #000 #000 silver;			
		}	
		.anuladamuestra{
			background-color:#000;
			color:#fff;
			border-color:  silver #000 #000 silver;			
		}	
	
	</style>
	<?php
	}else{
		echo '<link rel="stylesheet" type="text/css" href="./css/mini.css">';
	}
?>
	
	
	<script LANGUAGE="javascript">
	_fechaobjetivo='';
	</script>	
	
</head>
<body>
	<?php
	



	if($Accion == ""){
		$Accion = "agrega";
	}		

	
	$Consulta = mysql_query("SELECT * FROM $Tabla WHERE id='$Id'",$Conec1);
	$Consulta_filas = mysql_num_rows($Consulta);
	
	if($Consulta_filas>0&&$Accion!='borra'&&$Accion!='recupera'){
		$Accion = "cambia";
	}
	


		$Datos = "";
	
	if ($Accion == "agrega"){$Href = "agrega.php";$AccionNom = "Agregar";}
	elseif ($Accion == "cambia"){$Href = "cambia.php";$AccionNom = "Guardar";}
	elseif ($Accion == "borra"){$Href = "borra.php";$AccionNom = "Borrar";}
	elseif ($Accion == "recupera"){$Href = "borra.php";$AccionNom = "Recuperar";}
	else {$Href = "error.php";}
	
	

	if($Tabla=='DOCdocumento'){		
		if($config['doc-criterionum']=="�nico"){
			$numeracion=$PLANOSACARGADOS;
		}elseif($config['doc-criterionum']=="�nico por grupo"){
			$numeracion=$PLANOSgruposACARGADOS;								
		}elseif($config['doc-criterionum']=="irrestricto"){
			$numeracion=0;
		}
		
	}
	
	echo "<script type='text/javascript'>";
	echo "var _arraydenombres = [$textarray];";
	echo "</script>";
	
	
	
	if ($Accion == "agrega" || $Accion == "cambia"){
	?>
			<div id="marco">
				<form action="./<?php echo $Href;?>" method="POST" enctype='multipart/form-data'>
					<input type="hidden" name="tabla" value="<?php echo $Tabla;?>">
					<input type="hidden" name="contrato" value="<?php echo $Id_contrato;?>">			
					<input type="hidden" name="id" value="<?php echo $Id;?>">
					<input type="hidden" name="<?php echo $campofijo;?>" value="<?php echo $campofijo_c;?>">
					<input type="hidden" name="<?php echo $campofijob;?>" value="<?php echo $campofijob_c;?>">					
					<input type="hidden" name="accion" value="<?php echo $Accion;?>">
					<input type="hidden" name="campo" value="<?php echo $Campo;?>">
					<input type="hidden" name="tablahermana" value="<?php echo $Tablahermana;?>">
					<input type="hidden" name="idhermana" value="<?php echo $Idhermana;?>">	
					<input type="hidden" name="salida" value="<?php echo $Salida;?>">
					<input type="hidden" name="salidaid" value="<?php echo $Salidaid;?>">					
					<input type="hidden" name="salidatabla" value="<?php echo $Salidatabla;?>">										
	
					<h1><?php echo $Accion . " " . $Tabla;?></h1>
					<h2><?php echo $Nombre_contrato;?></h2>
					<div id="hoja">	
			
						<?php	
						foreach($PASAR as $k => $v){
							echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
						}
						
					    $result = mysql_query('SHOW FULL COLUMNS FROM `'.$Tabla.'`',$Conec1);
						echo mysql_num_rows($result);
						echo mysql_error($Conec1);
					    if (mysql_num_rows($result) > 0) {
					    			 
							$empaquetado = -100;
							$borradato="no";
					        while ($row = mysql_fetch_assoc($result)){
					        	if($row['Field']=='zz_borrada'&&mysql_result($Consulta, 0, $row['Field'])=='1'){$REGISTROENPAPELERA="si";}
						
					        	$campohabilitado='no';
								
								foreach($CampoS as $c){
									if($c==$row['Field']){										
										$campohabilitado='si';	
									}
								}
								
					        	$contenido='';
					        	$comentario = $row['Comment'];
								
								
								if($row['Default']!=''&&$row['Default']!=null){$Def=$row['Default'];}else{$Def='';}
								
					        	$wheremas ="";
					        	if($row['Field']=='id_p_grupos_id_nombre_tipoa'&&$Tabla=='comunicaciones'){
					        		$comentario = $config['com-grupoa'];
					        	}
								$Consultalista="";
								
								if($empaquetado == -100){echo "<div class='paquete'>";$empaquetado =0;}
								
								if($borradato=="no"){
									echo "<div class='dato'>";
								}else{
									$borradato="no";
								}
								
								if($Accion != "agrega"){
									$contenido = mysql_result($Consulta, 0, $row['Field']);
								}elseif($Accion == "agrega"){
									$x = "C-".$row['Field'];
									$contenido = $_GET[$x];
									if($contenido==''){
										$contenido = $Def;
									}
								}
								
								$i=substr($row['Field'],0,3);
								/* print_r($row); */
								$Type = substr($row['Type'],0,3);
								
								if($row['Field'] == 'id'){
									$empaquetado --;
									$borradato="si";
								
								}elseif($row['Field'] == 'id_p_B_usuarios_usuarios_id_nombre'){
									echo '<input type="hidden" name="id_p_B_usuarios_usuarios_id_nombre" value="'.$UsuarioI.'">';
									echo "<p>".$comentario.": ".$UsuarioN."</p>";
								}elseif($row['Field'] == 'numerodeplano'){
									echo '<input onkeyup="nombreunico(this.value);" id="'.$row['Field'].'" class="chico" type="text" size="2" name="'.$row['Field'].'" value="'.$contenido.'">';
									
									echo "<p>".$comentario.": ".$UsuarioN."</p>";
								}elseif($i == 'zz_'){
									$valor='';
									
									if($row['Field']=="zz_pass"){
										echo $comentario;
										echo "<input type='password' name='".$row['Field']."' value='".$valor."'>";
										
									}else{
										
										echo "<input type='hidden' name='".$row['Field']."' value='".$valor."'>";
										$empaquetado --;
										$borradato="si";
									}
									$valor='';
								
									
								}elseif($row['Field'] == $campofijo){
									echo "<p>".$comentario.": ".$campofijo_c."</p>";

								}elseif($row['Field'] == $campofijob){
									echo "<p>".$comentariob.": ".$campofijob_c."</p>";
								
								}elseif($i == 'FI_'){
									if($Campo == "" || $Campo == $row['Field'] || $campohabilitado=='si'){
										echo "<p>".$comentario.": ".$campofijo_c."</p>";
										if($contenido!=''){
											echo "<a href='$contenido'>documento actual</a>";
											echo "reemplazar por:";
										}	
										echo "<input type='hidden' name='".$row['Field']."' value='".$contenido."'>";
										
										$path='path'.$row['Field'];
										echo "<input type='hidden' name='archivo_".$row['Field']."_path' value='".$_GET[$path]."'>";	
										
										echo "<input type='file' name='archivo_".$row['Field']."'>";
									}else{
										echo "<input type='hidden' name='archivo_".$row['Field']."' value='".$contenido."'>";
									}
									
								}elseif($i != 'id_'){
									if($Campo == "" || $Campo == $row['Field']||$campohabilitado=='si'){
										echo $comentario;
										echo "<br>";
										
										if ($Type == "tex"){
											$empaquetado =+ 3;
											?>
											<textarea cols="34" rows="8" name="<?php echo $row['Field'];?>"><?php echo $contenido;?></textarea>
											
											<?php	
										}elseif ($Type == "dat"){
											/* if($Accion == "agrega" ){$contenido = date("Y") . "-" . date("m") . "-" . date("d");} */
											if($Accion == "agrega" && $row['Field'] == "fechacierre"){$contenido = "0000-00-00";}
											if($Accion == "agrega" && $Tabla == "comunicaciones" && $row['Field'] == "cerradodesde"){$contenido = "0000-00-00";}
											if($row['Comment'] == "fecha en que se realiza el pedido"){$estado = "READONLY";}else{$estado = "";}
											if(!fechavalida($contenido)){$contenido="";}
											
											echo "<input class='mini' type='text' size='2' id='".$row['Field']."_d' name='".$row['Field']."_d' value='".dia($contenido)."' $estado";
												if($Accion == "agrega" && $Tabla == "comunicaciones" && $row['Field'] == "fecharecepcion"){
													echo " onchange='
														getElementById(\"fechainicio_d\").value = this.value;
														if(_fechaobjetivo==\"fijada\"){
															getElementById(\"fechaobjetivo_d\").value = this.value;
															getElementById(\"cerradodesde_d\").value = this.value;
														}'
													";
												}
											echo ">";
											
											echo "<input class='mini' type='text' id='".$row['Field']."_m' name='".$row['Field']."_m' value='".mes($contenido)."' $estado";

													if($Accion == "agrega" && $Tabla == "comunicaciones" && $row['Field'] == "fecharecepcion"){
													echo " onchange='
														getElementById(\"fechainicio_m\").value = this.value;
														if(_fechaobjetivo==\"fijada\"){
															getElementById(\"fechaobjetivo_m\").value = this.value;
															getElementById(\"cerradodesde_m\").value = this.value;
														}'
													";
												}
											echo ">";
											
											echo "<input class='fecha' type='text' id='".$row['Field']."_a' name='".$row['Field']."_a' value='".ano($contenido)."' $estado";

													if($Accion == "agrega" && $Tabla == "comunicaciones" && $row['Field'] == "fecharecepcion"){
													echo " onchange='
														getElementById(\"fechainicio_a\").value = this.value;
														if(_fechaobjetivo==\"fijada\"){
															getElementById(\"fechaobjetivo_a\").value = this.value;
															getElementById(\"cerradodesde_a\").value = this.value;
														}'
													";
												}
											echo ">";
													
												
											if($contenido!=''){
												?>
												<input type='button' value='borrar fecha' 
													onclick="
														document.getElementById('<?php echo $row['Field'];?>_d').value = '00';
														document.getElementById('<?php echo $row['Field'];?>_d').setAttribute('readonly', 'readonly');
														document.getElementById('<?php echo $row['Field'];?>_m').value = '00';
														document.getElementById('<?php echo $row['Field'];?>_m').setAttribute('readonly', 'readonly');
														document.getElementById('<?php echo $row['Field'];?>_a').value = '0000';
														document.getElementById('<?php echo $row['Field'];?>_a').setAttribute('readonly', 'readonly');
														">
												<?php
											}
											
											?>
											
											
											
											<?php
										}elseif ($Type == "enu"){
											$campo=$row['Field'];
											$listado = $row['Type'];
											$listado = str_replace("enum('","",$listado);
											$listado = str_replace("')","",$listado);
											$lista = explode("','", $listado);
											echo "<select name='".$campo."' ";
												
												if($Accion == "agrega" && $Tabla == "comunicaciones" && $row['Field'] == "requerimiento"){
													?>
													onchange="
														if(this.value=='no'){
															_fechaobjetivo='fijada';
															getElementById('fechaobjetivo_a').value = getElementById('fecharecepcion_a').value;
															getElementById('fechaobjetivo_m').value = getElementById('fecharecepcion_m').value;	
															getElementById('fechaobjetivo_d').value = getElementById('fecharecepcion_d').value;	
															
															getElementById('cerradodesde_a').value = getElementById('fecharecepcion_a').value;
															getElementById('cerradodesde_m').value = getElementById('fecharecepcion_m').value;
															getElementById('cerradodesde_d').value = getElementById('fecharecepcion_d').value;
															
															getElementById('cerradodesde_a').setAttribute('readOnly','readonly');
															getElementById('cerradodesde_a').style.backgroundColor='lightblue';
															getElementById('cerradodesde_m').setAttribute('readOnly','readonly');
															getElementById('cerradodesde_m').style.backgroundColor='lightblue';
															getElementById('cerradodesde_d').setAttribute('readOnly','readonly');
															getElementById('cerradodesde_d').style.backgroundColor='lightblue';
															
															getElementById('fechaobjetivo_a').setAttribute('readOnly','readonly');
															getElementById('fechaobjetivo_a').style.backgroundColor='lightblue';
															getElementById('fechaobjetivo_m').setAttribute('readOnly','readonly');
															getElementById('fechaobjetivo_m').style.backgroundColor='lightblue';
															getElementById('fechaobjetivo_d').setAttribute('readOnly','readonly');
															getElementById('fechaobjetivo_d').style.backgroundColor='lightblue';		
															
															getElementById('fechainicio_a').setAttribute('readOnly','readonly');
															getElementById('fechainicio_a').style.backgroundColor='lightblue';
															getElementById('fechainicio_m').setAttribute('readOnly','readonly');
															getElementById('fechainicio_m').style.backgroundColor='lightblue';
															getElementById('fechainicio_d').setAttribute('readOnly','readonly');
															getElementById('fechainicio_d').style.backgroundColor='lightblue';															
														};
														if(this.value=='si'){
															this.form.cerradodesde_a.removeAttribute('readOnly'); 
															
															getElementById('cerradodesde_a').removeAttribute('readOnly','readonly');
															getElementById('cerradodesde_a').style.backgroundColor='white';
															getElementById('cerradodesde_m').removeAttribute('readOnly','readonly');
															getElementById('cerradodesde_m').style.backgroundColor='white';
															getElementById('cerradodesde_d').removeAttribute('readOnly','readonly');
															getElementById('cerradodesde_d').style.backgroundColor='white';
															
															getElementById('fechaobjetivo_a').removeAttribute('readOnly','readonly');
															getElementById('fechaobjetivo_a').style.backgroundColor='white';
															getElementById('fechaobjetivo_m').removeAttribute('readOnly','readonly');
															getElementById('fechaobjetivo_m').style.backgroundColor='white';
															getElementById('fechaobjetivo_d').removeAttribute('readOnly','readonly');
															getElementById('fechaobjetivo_d').style.backgroundColor='white';		
															
															getElementById('fechainicio_a').removeAttribute('readOnly','readonly');
															getElementById('fechainicio_a').style.backgroundColor='white';
															getElementById('fechainicio_m').removeAttribute('readOnly','readonly');
															getElementById('fechainicio_m').style.backgroundColor='white';
															getElementById('fechainicio_d').removeAttribute('readOnly','readonly');
															getElementById('fechainicio_d').style.backgroundColor='white';		
														}
													
													"
													<?php
												}
											
											echo ">";
											foreach ($lista as $v) {	
												if($contenido == $v){$selecta = "selected='yes'";
												}else{$selecta = "";}
												if($v == 'entrante'){$va = $entra." (".$v.")";
												}elseif($v == 'saliente'){$va = $sale." (".$v.")";
												}else{$va = $v;}
											    echo "<option value='" . $v . "'" . $selecta . ">". $va . "</option>";
											}
											echo "</select>";									
											
											?>	
											 
											<?php
										}elseif ($row['Type'] == "tinyint(1)"){
											$campo=$row['Field'];
											
											if($contenido==1){$contenidocheck=" checked";}else{$contenidocheck="";}							
											?>	
											
											<input type="hidden" name="<?php echo $row['Field'];?>" id="<?php echo $row['Field'];?>" value="">
											<input type="checkbox" name="" value="" <?php echo $contenidocheck;?> 
											 onclick="
											 	alterna('<?php echo $row['Field'];?>', this.checked);
											 	<?php if(($campo=='com-aprobacion'||$campo=='com-aprobacion-sale')&&$Tabla=='configuracion'){
											 		echo "
											 			window.open('./ediciontablagenerica.php?tabla=comunestadoslista&accion=cambia','mywindow');
											 		";
												}?>
											"
											>
											<?php 
												if(
													($campo=='com-aprobacion'||$campo=='com-aprobacion-sale')
													&&$Tabla=='configuracion'&&$contenido=='1'
												){
											 		echo "
											 			<a target='_blank' href='./ediciontablagenerica.php?tabla=comunestadoslista&accion=cambia'>
											 			ver listado de aprobaciones
											 			</a>
											 		";
												}elseif($campo=='doc-visadomultiple'&&$Tabla=='configuracion'&&$contenido=='1'){
											 		echo "
											 			<a target='_blank' href='./ediciontablagenerica.php?tabla=DOCvisados&accion=cambia'>
											 			ver listado de aprobaciones
											 			</a>
											 		";
												}
											?>
											
																						
											
											<?php
										}else{
											?>
											<input class="chico" type="text" size="2" name="<?php echo $row['Field'];?>" value="<?php echo $contenido;?>">
											
											<?php
											if($Consultalista != ''){
												while ($listaitem = mysql_fetch_assoc($Consultalista)) {
													$itemlista=$listaitem[$row['Field']];
													?>	
													<a onclick="document.getElementById('<?php echo $row['Field'];?>').value = '<?php echo $itemlista;?>'">
														<?php echo $itemlista;?>
													</a>
													<?php
												}
												?>
												<a onclick="document.getElementById('<?php echo $row['Field'];?>').value = ''">
														ninguno
												</a>
												<?php
											}
										}
										
									}elseif($campofijo == $row['Field']){
									?>	
									<input type="hidden" name="<?php echo $campofijo;?>" value="<?php echo $campofijo_c;?>">
									<?php
										
									}else{
										?>
										<input class="chico" type="hidden" size="2" name="<?php echo $row['Field'];?>" value="<?php echo str_replace('"',"'",$contenido);?>">
										<?php				
									}
									
									
								}elseif($i == 'id_' && $row['Field'] != $campofijo && $row['Field'] != "id_p_paneles_id_nombre"){
									
									if($Campo != "" && $Campo != $row['Field']){
										if($campofijo == $row['Field']){
											?>	
											<input type="hidden" name="<?php echo $campofijo;?>" value="<?php echo $campofijo_c;?>">
											<?php
										}else{
											?>
											<input class="chico" type="hidden" size="2" name="<?php echo $row['Field'];?>" value="<?php echo str_replace('"',"'",$contenido);?>">
											<?php	
										}
										
									}else{
										
										$Typolink = substr($row['Field'],0,4);
										/* para tablas padre */
										if($Typolink == "id_p" && ($config['com-grupob']!="NO" || $row['Field']!="id_p_grupos_id_nombre_tipob")){	
											$Baselink = substr($row['Field'],0,6);
											/* para tablas padre en otras bases*/
											if($Baselink == "id_p_B"){
												$o = explode("_", $row['Field']);
												$basepadre = $o[3];
												$tablapadre = $o[4];
												$campopadre = $o[5];
												echo $comentario;
												
												if($o[6] != ""){$referencia = $o[6];}else{
												$Column=mysql_query("SHOW FULL COLUMNS FROM $basepadre.$tablapadre",$Conec1);
													echo mysql_error($Conec1);
													while($col=mysql_fetch_assoc($Column)){
														if($col['Field']!='id'){
															$referencia =$col['Field'];
															break;
														}
													}
												}
												
											/*	echo "<br> linkeado a: base: ".$basepadre." tabla: ".$tablapadre." campo: ".$campopadre." referencia: ".$referencia;
											*/
												$Consultadosactual = mysql_query("SELECT * FROM $basepadre.$tablapadre WHERE $campopadre = '$contenido' ORDER BY $referencia",$Conec1);
												echo mysql_error();
												if(mysql_num_rows($Consultadosactual)>0){
													$contenidopadre = mysql_result($Consultadosactual, 0, $campopadre);
												}
												
												$Consultados = mysql_query("SELECT * FROM $basepadre.$tablapadre order by id desc",$Conec1);
												$Consultados_filas = mysql_num_rows($Consultados);	
												echo "<select name='".$row['Field']."'>";
												echo "<option value='0'>-elegir-</option>";
												if($Consultados_filas > 0){
													$filas = 0;
													if($Accion == "agrega" && $row['Field'] == "id_p_B_usuarios_usuarios_id_nombre_autor"){$contenidopadre = $UsuarioI;}elseif($Accion == "agrega"){$contenidopadre = "";}
													if($Accion == "cambia"){$contenidopadre=$contenido;}
											        while ($filas < $Consultados_filas ) {
											        	
														$tx = mysql_result($Consultados, $filas, $referencia);
														$idl = mysql_result($Consultados, $filas, $campopadre);
														
														if($contenidopadre == $idl)
														{
															$selecta = "selected='yes'";
														}
														
														else
														{
															$selecta = "";
														}
														
														echo "<option value='" . $idl . "'" . $selecta . ">". $tx . "</option>";
														$filas ++;
													}
													
													echo "</select><br>";
												}
											}else{
												
												/* para tablas padre en la misma base*/
												$o = explode("_", $row['Field']);
												$basepadre = $Base;
												$tablapadre = $o[2];
												$padre = $basepadre . "." . $tablapadre;
												echo $comentario;
																								
												/*echo "<a target='_blank' href='./ediciontablagenerica.php?tabla=".$tablapadre."'> (editar listado)</a>";*/
												
												if($edicionextra =='si'){
													$extracondicion='';
													if($o[5]=='tipo'){$extracondicion.='&campofijo=tipo&campofijo_c='.$o[6];}
													/*
													echo " <a href='./agrega_f.php?tabla=$tablapadre&salida=$Salida&accion=agrega$extracondicion'>agregar ".$tablapadre."</a>";
													 * */
												}
												
												$estructurapadre = mysql_query('SHOW FULL COLUMNS FROM '.$basepadre.'.'.$tablapadre);
												echo mysql_error();
						  						if (mysql_num_rows($estructurapadre) > 0) {
						  							/*$a = mysql_fetch_assoc($estructurapadre);*/
						  							$campozzborra='no';
													$wheremas = "WHERE '1'='1'";
						  							while ($value = mysql_fetch_assoc($estructurapadre)){
						  								if($value['Field']=='zz_borrada'){
															$wheremas .= " AND zz_borrada='0'";
														}
													}		
																  					
						  							mysql_data_seek($estructurapadre, 0);
						  							
													while ($value = mysql_fetch_assoc($estructurapadre)){
														if(substr($value['Field'],0,15)=="id_p_paneles_id"){
															$wheremas .= " AND ".$tablapadre.".".$value['Field']." = ".$PanelI;
														}
													}	
												}		
												
												if($o[5]!=''){
													if($o[5]=='tipoa'){$wheremas.=" AND $tablapadre.tipo = 'a'";}
													if($o[5]=='tipob'){$wheremas.=" AND $tablapadre.tipo = 'b'";}
													if($o[5]=='tipo'){$wheremas.=" AND $tablapadre.tipo ='".$o[6]."'";}
												}
																								
												if($Tabla == 'comunicaciones' || $Tabla == 'indicadores'){
												$JOIN = "LEFT JOIN $Tabla ON $Tabla.".$row['Field']." = ".$tablapadre.".id";
												$GROUP = "GROUP BY ".$tablapadre.".id";
												$ORDER = "ORDER BY ".$Tabla.".id DESC";
												}
												
												if($tablapadre=='comunicaciones'){
													$ORDER = "ORDER BY ".$tablapadre.".id DESC";
												}
												
												$resultdos = mysql_query('SHOW FULL COLUMNS FROM '.$padre);		
												if (mysql_num_rows($result) > 0) {
													if($tablapadre == 'grupos')
														{$wheremas .= " AND ".$tablapadre.".id_p_paneles_id_nombre = '$PanelI' ";
													}elseif($tablapadre == 'paneles'){
														$wheremas .= " AND id = '$PanelI' ";
													}
													
													/*echo $JOIN." ".$wheremas;*/
													$query= "SELECT * FROM $padre $JOIN $wheremas $GROUP $ORDER";
													$Consultados = mysql_query($query);
													echo mysql_error();
													$Consultados_filas = mysql_num_rows($Consultados);	
													$Consultados_filas_cuenta = 0;
													
													echo "<input type='hidden' id='".$row['Field']."' name='".$row['Field']."' value='".$contenido."'>";
													
													
													if($row['Field']=='id_p_comunicaciones_id_ident_entrante'||$row['Field']=='id_p_comunicaciones_id_ident_rechazada'||$row['Field']=='id_p_comunicaciones_id_ident_aprobada'||$row['Field']=='id_p_comunicaciones_id_ident_anulada'){
														$readonly = "READONLY";
													}else{$readonly = "";}/* algunos campos no permiten la autogeneraci�n de nuevos registros asociados*/
														
													if($row['Field'] == 'id_p_DOCdocumento_id' && $Tabla == 'DOCversion'){ /* no despliega listado en caso de estar llamando la tabla de vesriones de docuemntos*/
														echo "<input type='button' style='position:relative;' class='chico' id='".$row['Field']."-n' name='".$row['Field']."_n' value=''";
														?>
														onclick="window.location='./agrega_f.php?tabla=DOCdocumento&accion=cambia&id=<?php echo $contenido;?>';"
														<?php
														echo ">";
													}else{
														
														echo "<input $readonly class='chico' id='".$row['Field']."-n' name='".$row['Field']."_n' value=''";
														?>
															 onKeyUp="
															 _valor = this.value;
															 _campo = '<?php echo $row['Field'];?>';
															 document.getElementById('<?php echo $row['Field'];?>').value = includes(_campo, _valor);"												 
														<?php
													
													
														echo"><br>";
														?>
														   <a 
														    onclick="
														    	document.getElementById('<?php echo $row['Field'];?>-n').value = '';
														    	document.getElementById('<?php echo $row['Field'];?>').value = '0'
														    ">
																-vacio-
															</a>
														<?php
													}
													
													$campovalor = isset($o[4])?$o[4]:'nombre';
													
													$ocultoporexeso='no';
													while($Consultados_filas_cuenta < $Consultados_filas){
														$v = mysql_result($Consultados, $Consultados_filas_cuenta, $campovalor);
														$va = $v;
														
														if($Consultados_filas_cuenta>'50'&&$ocultoporexeso=='no'){
															//oculta los resultados posteriores al numero 50 para evitar formularios confusos
															$ocultoporexeso='si';
															echo "<br><a ";?>onclick="this.nextSibling.style.display='block';"<?php echo ">m�s (m�s viejos)-></a>";
															echo "<div class='dato' style='display:none;'>";
														}
														
														if($row['Field']=='id_p_comunicaciones_id_ident_entrante'||$row['Field']=='id_p_comunicaciones_id_ident_rechazada'||$row['Field']=='id_p_comunicaciones_id_ident_aprobada'||$row['Field']=='id_p_comunicaciones_id_ident_anulada'){
															if(mysql_result($Consultados, $Consultados_filas_cuenta, 'preliminar')=='extraoficial'){$o='x';}else{$o='';}
															$PREN['entrante']=$config['com-entra-preN'.$o];
															$PREN['saliente']=$config['com-sale-preN'.$o];	
															
															$sentido=mysql_result($Consultados, $Consultados_filas_cuenta, 'sentido');
															
															$ca=$GRUPOS['a'][mysql_result($Consultados, $Consultados_filas_cuenta, 'id_p_grupos_id_nombre_tipoa')]['codigo'];
															$na=$GRUPOS['a'][mysql_result($Consultados, $Consultados_filas_cuenta, 'id_p_grupos_id_nombre_tipoa')]['nombre'];
															$cb=$GRUPOS['b'][mysql_result($Consultados, $Consultados_filas_cuenta, 'id_p_grupos_id_nombre_tipob')]['codigo'];
															$nb=$GRUPOS['b'][mysql_result($Consultados, $Consultados_filas_cuenta, 'id_p_grupos_id_nombre_tipob')]['nombre'];
															
															if($ca!=''){$A = $ca;}else{$A = $na;}
															if($cb!=''){$B = $cb;}else{$B = $nb;}	
															
																			
															$v = $PREN[$sentido].$v;
															$va = "<span class='contenedor aclara'><span class='subcontenedor oculto'><span class='contenido oculto'>".$na."</span></span><span class='contenido aclara'>".$A."</span></span><span class='contenedor aclara'><span class='subcontenedor oculto'><span class='contenido oculto'>".$nb."</span></span><span class='contenido aclara'>".$B."</span></span>".$v;
											
															if(mysql_result($Consultados, $Consultados_filas_cuenta, 'id')==$contenido){
																?>
																<script type='text/javascript'>
																	document.getElementById('<?php echo $row['Field'];?>-n').value = '<?php echo $v;?>';
																	
																</script>
																<?php																
															}
															if($row['Field']=='id_p_comunicaciones_id_ident_entrante'){
																if($sentido=='saliente'){
																	$Opcionespostergadas[mysql_result($Consultados, $Consultados_filas_cuenta, 'id')]=$v;
																	$Consultados_filas_cuenta ++;continue;/* saltea el restro del while evitando que se cargue inicialmente la opci�n */
																}
															}
															if(($row['Field']=='id_p_comunicaciones_id_ident_rechazada'||$row['Field']=='id_p_comunicaciones_id_ident_aprobada'||$row['Field']=='id_p_comunicaciones_id_ident_anulada')){
																if($sentido=='entrante'){
																	$Opcionespostergadas[mysql_result($Consultados, $Consultados_filas_cuenta, 'id')]=$v;
																	$Consultados_filas_cuenta ++;continue;/* saltea el restro del while evitando que se cargue inicialmente la opci�n */
																}
															}	
														}
														$vt = mysql_result($Consultados, $Consultados_filas_cuenta, 'nombre');
														$v = ($v != '') ? $v : $vt;
														$idv = mysql_result($Consultados, $Consultados_filas_cuenta, 'id');
														if($idv==$contenido){
															?>
															<script LANGUAGE="javascript">
																document.getElementById('<?php echo $row['Field'];?>-n').value = '<?php echo $v;?>';
															</script>
															<?php
														}
															?>
															
														<script LANGUAGE="javascript">	
															var <?php echo $row['Field'];?>=new Array();
															<?php echo $row['Field'];?>['id']=new Array();
															<?php echo $row['Field'];?>['n']=new Array();
															
															<?php echo $row['Field'];?>['id'].push("<?php echo $idv;?>");
															<?php echo $row['Field'];?>['n'].push("<?php echo $v;?>");  
		
														</script>	
																
														<?php													
														if($row['Field'] != 'id_p_DOCdocumento_id' || $Tabla !='DOCversion'){ /* no despliega listado en caso de estar llamando la tabla de vesriones de docuemntos*/
															?>
															
														    <a 
														    title='<?php echo $vt;?>'
															
														   	ondblclick="window.location='./agrega_f.php?accion=cambia&tabla=<?php echo $tablapadre;?>&id=<?php echo $idv;?>';"												   
														    onclick="
														    	document.getElementById('<?php echo $row['Field'];?>-n').value = '<?php echo $v;?>';
														    	document.getElementById('<?php echo $row['Field'];?>').value = '<?php echo $idv;?>'
														    ">
																<?php echo $va;?>
															</a>
														<?php
														}
														$Consultados_filas_cuenta ++;
													}

													if($ocultoporexeso=='si'){
															//oculta los resultados posteriores al numero 50 para evitar formularios confusos
															echo "</div>";
													}

													if(isset($Opcionespostergadas)){
														echo "<br><a ";?>onclick="this.nextSibling.style.display='block';"<?php echo ">m�s (de otro tipo)-></a>";
														echo "<div class='dato' style='display:none;'>";
														foreach($Opcionespostergadas as $rowid => $rownombre){
															?>
															 <a 
														    title='<?php echo $rownombre;?>'
															
														   	ondblclick="window.location='./agrega_f.php?accion=cambia&tabla=<?php echo $tablapadre;?>&id=<?php echo $rowid;?>';"												   
														    onclick="
														    	document.getElementById('<?php echo $row['Field'];?>-n').value = '<?php echo $rownombre;?>';
														    	document.getElementById('<?php echo $row['Field'];?>').value = '<?php echo $rowid;?>'
														    ">
																<?php echo $rownombre;?>
															</a>
															<?php
														}
														
														echo "</div>";
														unset($Opcionespostergadas);
													}
													
													
													/* variante select 
													echo "<select name='".$row['Field']."'>";
													echo "<option value=''>-elegir-</option>";
													
													while($Consultados_filas_cuenta < $Consultados_filas){
														$v = mysql_result($Consultados, $Consultados_filas_cuenta, 'nombre');
														$idv = mysql_result($Consultados, $Consultados_filas_cuenta, 'id');
														if($contenido == $idv)
														{$selecta = "selected='yes'";}
														else{$selecta = "";}	
													    echo "<option value='" . $idv . "'" . $selecta . ">". $v . "</option>";
														$Consultados_filas_cuenta ++;
													}
													echo "</select>";		
													 
													*/							
													
												}
											}	
										}else{
											$empaquetado --;
											$borradato="si";
										}
									}
								}elseif($row['Field'] == "id_p_paneles_id_nombre"){
									echo "<input type='hidden' name='id_p_paneles_id_nombre' value='".$PanelI."'>";
									$empaquetado --;
									$borradato="si";
								}else{
									$empaquetado --;
									$borradato="si";
								}

								
								/*print_r($row);*/
								if($borradato!="si"){
								echo "</div>";	
								}
								$empaquetado ++;
								if($empaquetado > 5){
									echo "</div>";
									$empaquetado = -100;
								}
					        }
					    }
	
						?>
						
					
						
					</div>	
							
					<input type="submit" value="<?php echo $AccionNom;?>">
					<input type="reset" value="Reiniciar" onclick='window.location=window.location'>					
					<input type="button" value="Cancelar" onclick="window.location.href='./<?php echo $Salida;?>.php?tabla=<?php echo $Tabla;?>&salida=<?php echo $Salida;?>&salidatabla=<?php echo $Salidatabla;?>';">
					
					<?php
					
					if($Campo==''&&$Accion!='agrega'){
						if($REGISTROENPAPELERA=="si"){
						?>
						<input type="button" value="Recuperar" onclick="window.location.href = './agrega_f.php?tabla=<?php echo $Tabla;?>&id=<?php echo $Id;?>&accion=recupera&salida=<?php echo $Salida;?>&salidatabla=<?php echo $Salidatabla;?>';">
						<?php	
						}else{
						?>
						<input type="button" value="Eliminar" onclick="window.location.href = './agrega_f.php?tabla=<?php echo $Tabla;?>&id=<?php echo $Id;?>&accion=borra&salida=<?php echo $Salida;?>&salidatabla=<?php echo $Salidatabla;?>';">
						<?php
						}
					}
					
					
					?>
				</form>
			</div>
	
	<?php
		}
elseif($Accion == "borra"||$Accion == "recupera"){
	?>
			<div id="marco">
				<form action="./<?php echo $Href;?>" method="POST">
					<input type="hidden" name="tabla" value="<?php echo $Tabla;?>">
					<input type="hidden" name="contrato" value="<?php echo $Id_contrato;?>">
					<input type="hidden" name="id" value="<?php echo $Id;?>">
					<input type="hidden" name="accion" value="<?php echo $Accion;?>">
					<input type="hidden" name="tablahermana" value="<?php echo $Tablahermana;?>">
					<input type="hidden" name="idhermana" value="<?php echo $Idhermana;?>">
					<input type="hidden" name="salida" value="<?php echo $Salida;?>">
					<input type="hidden" name="salidaid" value="<?php echo $Salidaid;?>">					
					<input type="hidden" name="salidatabla" value="<?php echo $Salidatabla;?>">	
					<h1><?php echo $Accion . " " . $Tabla;?></h1>
					<input type="submit" value="<?php echo $Accion;?>">	
					<div id="hoja">	
			
						<?php	
						foreach($PASAR as $k => $v){
							echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
						}
						
					    $result = mysql_query("SHOW FULL COLUMNS FROM $Tabla",$Conec1);
						echo mysql_error($Conec1);
					    if (mysql_num_rows($result) > 0) {		    	
							
					        while ($row = mysql_fetch_assoc($result)) {
					 	
					        	$contenido = mysql_result($Consulta, 0, $row['Field']);
						
								echo '<div class="campo">';	
									$i=substr($row['Field'],0,2);
									
									echo "<h4>".$row['Comment']. "</h4> ".$contenido;
								echo '</div>';	
					        }					        
					    }
					?>
						
					</div>	
									
				</form>
			</div>
	
	
	
	<?php	
	}


	?>	
	
	
</body>


<script type="text/javascript">

	function nombreunico(_nombre){
	var arrayLength = _arraydenombres.length;
	_nombre = _nombre.replace(/ /g, "");
	_nombre = _nombre.toUpperCase()
	_primero='';	
	for (var i = 0; i < arrayLength; i++) {

		if(_arraydenombres[i]==_nombre){
			_primero=_nombre;
	   		alert('Nombre repetido! '+_arraydenombres[i]);
	   		document.getElementById(_nombre).style.backgroundColor='red';
			document.getElementById("listadenombres").scrollTop = document.getElementById(_nombre).offsetTop;
		}else if(_arraydenombres[i].indexOf(_nombre)>-1){
			if(_primero==''){_primero=_arraydenombres[i];}
			document.getElementById(_arraydenombres[i]).style.backgroundColor='yellow';
			//document.getElementById("listadenombres").scrollTop = document.getElementById(_arraydenombres[i]).offsetTop;
		}else{
			document.getElementById(_arraydenombres[i]).style.backgroundColor='#fff';
		}
		if(_primero!=''){
			document.getElementById("listadenombres").scrollTop = document.getElementById(_primero).offsetTop;
		}
		

		
	}
}
</script>

<script type="text/javascript">

	function cambiame() 
{ 
    window.open("","ventanita","width=800,height=600,toolbar=0"); 
    var o = window.setTimeout("document.form1.submit();",500); 
}

	function cambiametb() 
{ 
    window.open("","ventanitatb","width=800,height=600,toolbar=0"); 
    var o = window.setTimeout("document.form1.submit();",500); 
}  

function include(arr, obj) {
    for(var i=0; i<arr['n'].length; i++) {
        if (arr['n'][i] == ob){ return arr['id'][i];}
        else {return 'n';}
    }
}

function includes(_arr, obj) {
    return 'n';
}

function alterna(_id, _estado){
	if(_estado==false){
		document.getElementById(_id).value='0';
	}else if(_estado==true){
		document.getElementById(_id).value='1';
	}
}


</script>

<?php
include('./includes/encabezado.php');
include('./cons_general.php');

//if($_SERVER[SERVER_ADDR]=='192.168.0.252')ini_set('display_errors', '1');ini_set('display_startup_errors', '1');ini_set('suhosin.disable.display_errors','0'); error_reporting(-1);

	$Base='estadisticas';
		
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

	
	$IDproy=str_replace("CONT_","",$Tabla);
	$Conf=consultaProyectoConf($IDproy);	
	$campoident=$Conf['campoident'];
	
	
	
													
	
	foreach($_GET as $k => $v){// estas variables son pasadas por als aplicaciones comunes manteniendose.
		if(substr($k,0,5)=='PASAR'){
			$PASAR[$k]=$v;
		}
	}

		
											
	
	
?>
<!DOCTYPE html>
<head>
	<title>Panel de Control</title>
	<link rel="stylesheet" type="text/css" href="./css/estadisticas.css">
	<style type="text/css">
	h2{
		margin-top:2px;
	}
	h3{
		margin-top:1px;
	}
	div.campo{
		display:inline-block;
		border: 1px solid gray;
		margin:2px;
		height:100px;
		width:250px;
		overflow:hidden;
	}
	div#botonera{
		position:fixed;
		top:1px;
		left:200px;
	}
	
	div#confirmar{
		display:none;
	}
	</style>	
</head>
<body>
	<?php

	
	$Consulta = mysql_query("SELECT * FROM $Tabla WHERE id='$Id'",$Conec1);
	$Consulta_filas = mysql_num_rows($Consulta);
	
	
	if($Consulta_filas>0&&$Accion!='borra'&&$Accion!='recupera'){
		$Accion = "cambia";
	}
	$Accion='retirar';
	$AccionNom='Retirar';
	
	echo "<script type='text/javascript'>";
	echo "var _arraydenombres = [$textarray];";
	echo "</script>";
	
	
	
	if ($Accion == "retirar"){
	?>
	

			<div id="marco">
				<form action="./ed_<?php echo $Accion;?>.php" method="POST" enctype='multipart/form-data'>
					<input type="hidden" name="tabla" value="RETretirados">
					<input type="hidden" name="id_p_PROproyectos" value="<?php echo $IDproy;?>">				
					<input type="hidden" name="accion" value="<?php echo $Accion;?>">
					<input type="hidden" name="campo_selec" value="<?php echo $campoident;?>">
					<input type="hidden" name="valor_selec" value="<?php echo mysql_result($Consulta, 0, $campoident);?>">
	
					<h1><?php echo $Accion . " " . $Tabla;?></h1>
					<div id="hoja">	
			
						<?php	
						foreach($PASAR as $k => $v){
							echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
						}
						
					    $result = mysql_query('SHOW FULL COLUMNS FROM `'.$Tabla.'`',$Conec1);
						echo mysql_num_rows($result);
						echo mysql_error($Conec1);
					    if (mysql_num_rows($result) > 0) {
					        while ($row = mysql_fetch_assoc($result)){
					        	if(strlen($row['Comment'])>70){$com=substr($row['Comment'],0,67).'...';}else{$com=$row['Comment'];}					        	
					        	echo "<div class='campo' title='".$row['Comment']."'>";
									echo "<h2>".$row['Field']."</h2>";
									echo "<h3>".$com."</h3>";
									echo "<p>".mysql_result($Consulta, 0, $row['Field'])."</p>";
								echo "</div>";
					        }
					    }
	
						?>
						
					
						
					</div>	
					<div id='botonera'>		
						
						<input type="button" value="Cancelar" onclick="window.location.href='./<?php echo $Salida;?>.php?tabla=<?php echo $Tabla;?>&salida=<?php echo $Salida;?>&salidatabla=<?php echo $Salidatabla;?>';">
						<input id='accion' type="button" onclick='activarSubmit()' value="<?php echo $AccionNom;?>">			
						<div id='confirmar'>
							motivo: <input type="text" name='motivo' value="">			
							<input type="submit" value="Confirmo">		
						</div>
					</div>
				</form>
			</div>
	
	<?php
		}
elseif($Accion == "borra"||$Accion == "recupera"){
	?>
			<div id="marco">
				<form action="./ed_<?php echo $Href;?>" method="POST">
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
	function activarSubmit(){
		document.getElementById('accion').style.display='none';
		document.getElementById('confirmar').style.display='inline-block';			
		
	}	
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

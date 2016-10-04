<?php
include('./includes/encabezado.php');


if(count($_POST)>0){
	$Entrada=$_POST;
}else{
	$Entrada=$_GET;
}

	$Id_contrato = $Entrada['contrato'];
	$Tabla = $Entrada['tabla'];
	$Id = $Entrada['id'];
	$Accion = $Entrada['accion'];
	
	$Campo = $Entrada['campo'];
	$Salida = $Entrada['salida'];
	$Salidaid = $Entrada['salidaid'];	
	$Salidatabla = $Entrada['salidatabla'];	
	

	$Base = $_SESSION['panelcontrol']->DATABASE_NAME;
	$Index = $_SESSION['panelcontrol']->INDEX;
	
	$HOY = date(Y."-".m."-".d);
	
	$HOYa = date(Y);
	$HOYm = date(m);
	$HOYd = date(d);
	
	
	foreach($Entrada as $k => $v){// estas variables son pasadas por als aplicaciones comunes manteniendose.
		if(substr($k,0,5)=='PASAR'){
			$PASAR[$k]=$v;
		}
	}
	
	?>
	<head>
	<style>
	img{
		width:150px;
	}
	div{
		display:inline;
	}
	</style>
	</head>
	<?php

	$CODIGOELIMINACION = '-[-BORRX-]-'; //esta es la codificación con la que debe recibirse un campo que debe ser eliminado, a diferencia de un campo sobre el que no halla cambios requeridos.

	$_SESSION['DEBUG']['mensajes'][] = "tabla: $Tabla ".$Entrada['tabla'];
echo "tabla: $Tabla ".$Entrada['tabla'];
	$query="SELECT * FROM $Base.$Tabla WHERE id='$Id'";
	$Consulta = mysql_query($query,$Conec1);	
	$_SESSION['DEBUG']['mensajes'][] = mysql_error($Conec1);	
echo "<br>".$query;	
echo "<br>".mysql_error($_SESSION['panelcontrol']->Conec1);	
 	$result = mysql_query('SHOW FULL COLUMNS FROM `'.$Tabla.'`',$Conec1);
	$_SESSION['DEBUG']['mensajes'][] = mysql_error($Conec1);
			
		echo "tabla: ".$Tabla."<br>";
	$query="
	INSERT INTO 
	`estadisticas`.`".$Tabla."`
		SET
		
		`campo_selec`='".$_POST['campo_selec']."',
		`valor_selec`='".$_POST['valor_selec']."',
		`motivo`='".$_POST['motivo']."',
		id_p_PROproyectos'".$_POST['id_p_PROproyectos']."'
	";
		
		mysql_query($query,$Conec1);
		
		$_SESSION['DEBUG']['mensajes'][]="query=$query" ;
		
		$_SESSION['DEBUG']['mensajes'][] = mysql_error($Conec1);
		echo mysql_error($Conec1);
		echo $query;
		

		$_SESSION['DEBUG']['mensajes'][] = $Salida;
		$_SESSION['DEBUG']['mensajes'][] =".php?tabla=";
		$_SESSION['DEBUG']['mensajes'][] = $Salidatabla;
		$_SESSION['DEBUG']['mensajes'][] ="&id=";
		$_SESSION['DEBUG']['mensajes'][] = $Salidaid;
	
		if($Salida==''){$Salida="indice_contenidos.php?id=$Id";}
	
	
	
		if($Salida!=''){
			$cadenapasar='';
			foreach($PASAR as $k => $v){
				$cadenapasar.='&'.substr($k,5).'='.$v;
			}	
		?>
		    <SCRIPT LANGUAGE="javascript">
			   location.href = "./<?php echo $Salida;?>.php?tabla=<?php echo $Salidatabla;?>&id=<?php echo $Salidaid.$cadenapasar;?>";
		    </SCRIPT>
		<?php
		}else{
			
		?>
		
		    <SCRIPT LANGUAGE="javascript">
			   window.close();
		    </SCRIPT>
		<?php   
		
		}

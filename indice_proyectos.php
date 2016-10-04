<!DOCTYPE html>
<head>
	 
	   <title>ANALiSIS ESTADÌSTICO</title>
	   <link href="./img/pdfajpg.ico" type="image/x-icon" rel="shortcut icon">
	   <link rel="stylesheet" type="text/css" href="./css/estadisticas.css" />   
	<style>
	
	</style>
	
</head>
<body>
<?php

include('./includes/encabezado.php');
include('./cons_general.php');


echo "
<iframe id='ventanaaccion' name='ventanaaccion'></iframe>
";

$Result=consultaProyectos();
//echo "<pre>";print_r($Result);echo "</pre>";
$Contenidos=$Result['Contenidos'];

	echo "<table>";
	
		echo "<tr class='TOT'>";
		echo "<th>";
				echo "proyecto";
			echo "</th>";
		echo "<th>";
				echo "tabla";
			echo "</th>";
		echo "<th>";
				echo "estado";
			echo "</th>";
				
			echo "<th>";
				echo "columnas";
			echo "</th>";
		echo "<th>";
				echo "registros";
			echo "</th>";
		echo "</tr>";

		
	foreach($Contenidos as $tabla => $Tdata){			
			echo "<tr class='fm$a'>";
		
				echo "<td><a href='./indice_contenidos.php?id=".$Tdata['id']."'>".$Tdata['proyecto']."</a></td>";
				echo "<th>$tabla</th>";
				echo "<td>".$Tdata['estado']."</td>";
				echo "<td>".$Tdata['columnas']."</td>";
				echo "<td>".$Tdata['registros']."</td>";
			echo "</tr>";				

	}
echo "</table>";

?>

        <script type="text/javascript">        
                    window.scrollTo(0,'<?php echo $_GET['y'];?>');     
        </script>
     


<script type="text/javascript">  
	
	function cargarCSV(_idinputF,_modo) {

	var files = document.getElementById(_idinputF).files;		
	for (i = 0; i < files.length; i++) {
    
		var parametros = new FormData();
		parametros.append("upload",files[i]);
		parametros.append("id",'<?php echo $ID;?>');
		parametros.append("modo",_modo);
		
		cargando(files[i].name);
		
		//Llamamos a los puntos de la actividad
		$.ajax({
				data:  parametros,
				url:   'ed_carga_csv.php',
				type:  'post',
				processData: false, 
				contentType: false,
				success:  function (response) {
					archivoSubido(response);
				}
		});
	}
}


</script>
</body>
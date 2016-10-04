<!DOCTYPE html>
<head>
	 
	   <title>ANALiSIS ESTADíSTICO</title>
	   <link href="./img/pdfajpg.ico" type="image/x-icon" rel="shortcut icon">
	   <link rel="stylesheet" type="text/css" href="./css/estadisticas.css" />   
	   
	<style>
	
	th{
		background-color:#55a;
	}
	</style>
	
</head>
<body>
	<script language="javascript" type="text/javascript" src="./jscripts/jquery/jquery-1.8.2.js"></script>	
<?php

include('./includes/encabezado.php');
include('./cons_general.php');


$ID=$_GET['id'];


$Result=consultaPropEstructura();
//echo "<pre>";print_r($Result);echo "</pre>";
$Contenidos=$Result['Contenidos'];

$Result=consultaProyectos();
$Result=$Result[$ID];
$stringid=str_pad($ID,5,"0",STR_PAD_LEFT);
$tabla="CONT_".$stringid;

$Estrategias=$Result['Estrategias'];
$Areas=$Result['Areas'];
$Ambitos=$Result['Ambitos'];


echo "
<h2><a href='./indice_contenidos.php?id=$ID'>ir a contenidos generales</a></h2>
";


echo "
<table id='tablacont'>

</table>
<a href='./form_tabla.php?tabla=CONconsultasAgrupadas&campofijo=id_p_PROproyectos&campofijo_c=".$ID."'> añadir consulta</a>

<table id='tablacons'>
</table>

";

echo "<div id='carga'>";
echo "</div>";




echo "<table display='none' id='modfila'>";
	echo "<tr><th >Descripcion</td><th>Agrupaciones</th></tr>";
	echo "<tr><td></td><th>Incidencias (cant.)</th></tr>";
	echo "<tr><td></td><th>Incidencias / incidencias (%)</th></tr>";
	echo "<tr><td>Encuestas totales: XX</td><th>Incidencias / Encuestas (%)</th></tr>";
echo "</table>";
?>
<script type="text/javascript">        
            window.scrollTo(0,'<?php echo $_GET['y'];?>');     
</script>
 


<script type="text/javascript">  
	
function cargarConsultas(){
		var parametros = {
			"id" : '<?php echo $ID;?>',
			"funcion" : 'consultaConsultasResultados'
		};
		
		$.ajax({
				data:  parametros,
				url:   'cons_ajax.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					//console.log(_res);
					consultasCargadas(response);
					//procesarSigReticulaResutado(response);
				}
		});	
		var parametros = {
			"id" : '<?php echo $ID;?>',
			"funcion" : 'consultaConsultasResultadosAutoAg'
		};
		
		$.ajax({
				data:  parametros,
				url:   'cons_ajax.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					consultasAutoAgCargadas(response);
					//
				}
		});				
}



function consultasCargadas(response){	
	var _res = $.parseJSON(response);	
		
	if(_res.res=='exito'){
		
		
		console.log(_res.data); 

		_con=document.getElementById('tablacons');
		_con.innerHTML='';
		
		for(_idCon in _res.data){		
			_mod= document.getElementById('modfila').cloneNode(true);
			//_mod.removeAttribute('id');
			_mFilas=_mod.childNodes[0].childNodes;
			_mFilas[0].childNodes[0].innerHTML=_res.data[_idCon].data.nombre;		
				
			_Inc=_res.data[_idCon].totalcant;
			
			_mFilas[2].childNodes[0].innerHTML="Incidencias totales: "+_Inc;
			
			_Reg=_res.data[_idCon].registros;
			_mFilas[3].childNodes[0].innerHTML="Encuesta totales: "+_Reg;
						

			for (_idGrupo in _res.data[_idCon].grupos) {
				
				_td = document.createElement("th");
				_td.setAttribute('idgrupo',_idGrupo);				
				//console.log(_res.data[_idCon].grupos[_idGrupo].data);
				
				//_td.innerHTML=_res.data[_idCon].grupos[_idGrupo].data.nombre;
				
				
				_desc='';				
				for(_idcaso in _res.data[_idCon].grupos[_idGrupo].casos){
					_estecaso=_res.data[_idCon].grupos[_idGrupo].casos[_idcaso];
					console.log(_estecaso);
					
				
					if(_estecaso.CampoDesc===null){
						_desc=_desc+" --- ";
					}else{						
						_desc=_desc+_estecaso.CampoDesc.descripcion;
					}
		
				}
				
				_td.innerHTML=_td.innerHTML+"<span>"+_desc+"</span>";
				_mFilas[0].appendChild(_td);
				
				_td = document.createElement("td");
				_Cant=_res.data[_idCon].grupos[_idGrupo].cant;
				_td.innerHTML= _Cant ;
				_mFilas[1].appendChild(_td);
				
				
				_td = document.createElement("td");
				_Pinc=Math.round(100/_Inc*_Cant);
				_td.innerHTML=_Pinc+" %";
				_mFilas[2].appendChild(_td);
				
				_td = document.createElement("td");
				_Preg=Math.round(100/_Reg*_Cant);
				_td.innerHTML=_Preg+" %";
				_mFilas[3].appendChild(_td);				
				
									
			}
			
			
			
			
			_con.appendChild(_mFilas[0]);
			_con.appendChild(_mFilas[0]);
			_con.appendChild(_mFilas[0]);
			_con.appendChild(_mFilas[0]);
		}	
					
	}else if(_res.res=='err'){		
		_carga.innerHTML=_carga.innerHTML+' error al cargar el archivo.';
		for(i=0;i<_res.tx.length; i++){
			_carga.innerHTML=_carga.innerHTML+' '+_res.tx[i];
		}		
	}	
}




function consultasAutoAgCargadas(response){	
	var _res = $.parseJSON(response);	
		
	if(_res.res=='exito'){
		
		console.log(_res.data); 

		_con=document.getElementById('tablacons');
		//_con.innerHTML='';
		
		for(_idCon in _res.data){		
			
			_mod= document.getElementById('modfila').cloneNode(true);
			//_mod.removeAttribute('id');
			_mFilas=_mod.childNodes[0].childNodes;
			_mFilas[0].childNodes[0].innerHTML=_res.data[_idCon].data.nombre;		
				
			_Inc=_res.data[_idCon].totalcant;
			
			_mFilas[2].childNodes[0].innerHTML="Incidencias totales: "+_Inc;
			
			_Reg=_res.data[_idCon].registros;
			_mFilas[3].childNodes[0].innerHTML="Encuesta totales: "+_Reg;
			

			for (_idGrupo in _res.data[_idCon].grupos.nombre) {
				
				_td = document.createElement("th");
							
				//console.log(_res.data[_idCon].grupos[_idGrupo].data);
				
				//_td.innerHTML=_res.data[_idCon].grupos[_idGrupo].data.nombre;
				
				
				_desc='';				
				
				
				_td.innerHTML=_res.data[_idCon].grupos.nombre[_idGrupo];
				_mFilas[0].appendChild(_td);
				
				_td = document.createElement("td");
				_Cant=_res.data[_idCon].grupos.cant[_idGrupo];
				_td.innerHTML= _Cant ;
				_mFilas[1].appendChild(_td);
				
				_td = document.createElement("td");
				_Pinc=Math.round(100/_Inc*_Cant);
				_td.innerHTML=_Pinc+" %";
				_mFilas[2].appendChild(_td);
				
				_td = document.createElement("td");
				_Preg=Math.round(100/_Reg*_Cant);
				_td.innerHTML=_Preg+" %";
				_mFilas[3].appendChild(_td);				
				
									
			}
			
			
			
			
			_con.appendChild(_mFilas[0]);
			_con.appendChild(_mFilas[0]);
			_con.appendChild(_mFilas[0]);
			_con.appendChild(_mFilas[0]);



			
			//_td.setAttribute('title',_res.data[_idCon].data.descripcion);
			_tr.appendChild(_td);
			
			for (_idGrupo in _res.data[_idCon].grupos.nombre) {
				
				_td = document.createElement("td");
				_td.setAttribute('idgrupo',_idGrupo);	
				_td.innerHTML=_res.data[_idCon].grupos.nombre[_idGrupo];
				
				
				_Cant=_res.data[_idCon].grupos.cant[_idGrupo];
				_td.innerHTML=_td.innerHTML+"<br> <b> "+ _Cant +" x</b>";
				
				_Preg=Math.round(100/_Reg*_Cant);
				_td.innerHTML=_td.innerHTML+"<br> <b> "+ _Preg+" % reg</b>";			
				
				//_td.setAttribute('title',_res.data[_idCon].grupos[_idGrupo].data.descripcion);
				
				_tr.appendChild(_td);						
			}
			
				_td = document.createElement("td");
				_td.innerHTML="<a onclick='nuevoGrupo(this);'>nuevo grupo</a>";
				_tr.appendChild(_td);
				
		
			_con.appendChild(_tr);
		}	
					
	}else if(_res.res=='err'){		
		_carga.innerHTML=_carga.innerHTML+' error al cargar el archivo.';
		for(i=0;i<_res.tx.length; i++){
			_carga.innerHTML=_carga.innerHTML+' '+_res.tx[i];
		}		
	}	
}


function editarGrupo(_this){
	_idcon=_this.parentNode.parentNode.getAttribute('idcon');
	_idgrupo=_this.parentNode.getAttribute('idgrupo');
	window.location.assign("./form_tabla.php?salidaid=<?php echo $ID;?>&tabla=CONagrupadasGrupos&accion=cambia&id="+_idgrupo+"&salida=indice_consultas&campofijo=id_p_PROproyectos&campofijo_c=<?php echo $ID;?>&campofijob=id_p_CONagrupadas&campofijob_c="+_idcon)
}

function nuevoGrupo(_this){
	_idcon=_this.parentNode.parentNode.getAttribute('idcon');
	window.location.assign("./form_tabla.php?salidaid=<?php echo $ID;?>&tabla=CONagrupadasGrupos&accion=agrega&salida=indice_consultas&campofijo=id_p_PROproyectos&campofijo_c=<?php echo $ID;?>&campofijob=id_p_CONagrupadas&campofijob_c="+_idcon)
}

function nuevoCaso(_this){
	_idgrupo=_this.parentNode.getAttribute('idgrupo');
	_idcon=_this.parentNode.parentNode.getAttribute('idcon');
	window.location.assign("./form_tabla.php?salidaid=<?php echo $ID;?>&tabla=CONagrupadasCasos&accion=agrega&salida=indice_consultas&campofijo=id_p_CONagrupadasGrupos&campofijo_c="+_idgrupo+"&campofijob=id_p_CONagrupadas&campofijob_c="+_idcon)
}
function editarCaso(_this){
	_idgrupo=_this.parentNode.parentNode.getAttribute('idgrupo');
	_idcon=_this.parentNode.parentNode.parentNode.getAttribute('idcon');
	_idcaso=_this.parentNode.getAttribute('idcaso');
	window.location.assign("./form_tabla.php?salidaid=<?php echo $ID;?>&tabla=CONagrupadasCasos&accion=cambia&id="+_idcaso+"&salida=indice_consultas&campofijo=id_p_CONagrupadasGrupos&campofijo_c="+_idgrupo+"&campofijob=id_p_CONagrupadas&campofijob_c="+_idcon)
}
</script>


<?php

echo "
<script type='text/javascript'>
cargarConsultas();
</script>";
?>
</body>
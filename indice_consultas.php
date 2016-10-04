<?php
include('./includes/encabezado.php');
include('./cons_general.php');
?>
<!DOCTYPE html>
<head>
	 
	   <title>ANALiSIS ESTADíSTICO</title>
	   <link href="./img/pdfajpg.ico" type="image/x-icon" rel="shortcut icon">
	   <link rel="stylesheet" type="text/css" href="./css/estadisticas.css" />   
	   
	<style>
	
	</style>
	
</head>
<body>
	<script language="javascript" type="text/javascript" src="./jscripts/jquery/jquery-1.8.2.js"></script>	
<?php


$ID=$_GET['id'];
if(!isset($_GET['unaconsulta'])){$_GET['unaconsulta']=0;}
$UnaCons=$_GET['unaconsulta'];//valores mayores a 0 limitan la visualizacion a una sola consulta o cruce. En el caso de la consulta cara el id, en el caso del ruse, la cadena 'cr' seguide del cruce.

if($UnaCons!='0'){
	if(substr($UnaCons,0,2)=='cr'){
		$UnCruce=substr($UnaCons,2);	
		echo "<H1>Visualizando solo Cruce id: $UnCruce</H1>";
		$UnaCons='NO';
	}else{
		echo "<H1>Visualizando solo Consulta id: $UnaCons</H1>";
	
		$UnCruce='NO';
	}
}

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
<h2><a href='./indice_consultas_expo.php?id=$ID'>ir consultas para exportar</a></h2>
";


echo "
<table id='tablacont'>

</table>
<a href='./form_tabla.php?salida=indice_consultas&salidaid=".$ID."&tabla=CONconsultasAgrupadas&campofijo=id_p_PROproyectos&campofijo_c=".$ID."'> añadir consultas por grupos definidos</a>
<a href='./form_tabla.php?tabla=CONconsultasCruces&campofijo=id_p_PROproyectos&campofijo_c=".$ID."'> añadir cruce de consultas por grupos</a>

<table id='tablacons'>
</table>

";

echo "<div id='carga'>";
echo "</div>";



?>

<script type="text/javascript">        
            window.scrollTo(0,'<?php echo $_GET['y'];?>');     
</script>
 


<script type="text/javascript">  
	
function cargarConsultas(){//carga consulta y cruces

	
		var _IDproy = '<?php echo $ID;?>';
		
		if('<?php echo $UnaCons;?>'!='NO'){
			if('<?php echo $UnaCons;?>'>0){
				var _IdUnaCons = ['<?php echo $UnaCons;?>'];
			}else{
				var _IdUnaCons = null;
			}
			var parametros = {
				"id" : _IDproy,
				"v1" : _IdUnaCons,
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
					}
			});		
				
		}
			
		if('<?php echo $UnCruce;?>'!='NO'){
			if('<?php echo $UnCruce;?>'>0){
				var _IdUnCruce = ['<?php echo $UnCruce;?>'];
			}else{
				var _IdUnCruce = null;
			}	
			
			
			var parametros = {
				"id" : _IDproy,
				"v1" : _IdUnCruce,
				"funcion" : 'consultaConsultasResultadosCrucesAuto'
			};
			
			$.ajax({
					data:  parametros,
					url:   'cons_ajax.php',
					type:  'post',
					success:  function (response){
						var _res = $.parseJSON(response);
						console.log(_res);
						consultasCrucesCargados(response);					
					}
			});	
		}
}
</script>

<script type="text/javascript">  

function consultasCrucesCargados(response){	
	
	var _res = $.parseJSON(response);	


	if(_res.res=='exito'){
		if(_res.data!=null){

		console.log(_res); 
		
		for(_Ncruce in _res.data){
			
			_con=document.getElementById('tablacons');
			
			_tr = document.createElement("tr");
			_tr.innerHTML="<td><a href='./form_tabla.php?salida=indice_consultas&salidaid=1&tabla=CONconsultasCruces&accion=cambia&id="+_res.data[_Ncruce].data.id+"'>editar cruce</a></td>";
			_con.appendChild(_tr);
			
			_tr = document.createElement("tr");
			_td = document.createElement("th");
			_td.innerHTML=_res.data[_Ncruce].data.nombre;
			_td.innerHTML+=" "+_res.data[_Ncruce].data.descripcion;
			_tr.appendChild(_td);
			
			for (_gNum in _res.data[_Ncruce].consultas[0].grupos.nombre){
				_gNom= _res.data[_Ncruce].consultas[0].grupos.nombre[_gNum];
				_td = document.createElement("th");
				_td.innerHTML=_gNom;
				_tr.appendChild(_td);
			}
			_con.appendChild(_tr);
			
			
			for (_gbNum in _res.data[_Ncruce].consultas[1].grupos.nombre){
				
				_gbNom= _res.data[_Ncruce].consultas[1].grupos.nombre[_gbNum];
				
				_tr = document.createElement("tr");
				_td = document.createElement("th");
				_td.innerHTML=_gbNom;
				_tr.appendChild(_td);
				
				for (_gaNum in _res.data[_Ncruce].consultas[0].grupos.nombre){	
					_gaNom= _res.data[_Ncruce].consultas[0].grupos.nombre[_gaNum];
					//console.log(_gbNom);
					//console.log(_res.data[_Ncruce].matriz[_gaNom][_gbNom]);
					_td = document.createElement("td");
					_cant=0;
					//console.log("N:"+_Ncruce);
					//console.log("a:"+_gaNom);
					//console.log("b:"+_gbNom);
					if(typeof _res.data[_Ncruce].matriz[_gaNom][_gbNom] != 'undefined'){
						_cant=_res.data[_Ncruce].matriz[_gaNom][_gbNom];
					}
					
					_td.innerHTML=_cant;
					_tr.appendChild(_td);				
				}

				_con.appendChild(_tr);
			}		
		}
		}		
	}else if(_res.res=='err'){		
		_carga.innerHTML=_carga.innerHTML+' error al cargar el archivo.';
		for(i=0;i<_res.tx.length; i++){
			_carga.innerHTML=_carga.innerHTML+' '+_res.tx[i];
		}		
	}	
}

function consultasCargadas(response){	
	var _res = $.parseJSON(response);	
		
	if(_res.res=='exito'){
		
		console.log(_res.data); 

		_con=document.getElementById('tablacons');
		//_con.innerHTML='';
		console.log("es.");
		console.log(_res.data);
		for(_idCon in _res.data){		
			console.log(_idCon);
			if(_res.data[_idCon].tipo=='Agrupación Automática'){
				_tr = document.createElement("tr");
				_tr.setAttribute('idcon',_idCon);
				//console.log('cargando');
				//console.log(_res.data[_idCon]);
	
				_td = document.createElement("td");
				
				
				//console.log('hola');	
				//console.log(_res.data[_idCon]);
				
				_td.innerHTML=_res.data[_idCon].data.nombre;
				_td.innerHTML+=" "+_res.data[_idCon].data.descripcion;
				
				_Reg=_res.data[_idCon].totalcant;
				_td.innerHTML+="<br>encuestas: "+_Reg;
				_td.innerHTML+="<a onclick='editarConsulta(this);'>editar</a>";
				_td.innerHTML+="<a onclick='limitarConsulta(this);'>trabajar solo con esta consulta</a>";
				
				//_td.setAttribute('title',_res.data[_idCon].data.descripcion);
				_tr.appendChild(_td);
				
				for (_nn in _res.data[_idCon].grupos) {
					_idgrupo=_res.data[_idCon].grupos[_nn].id
					_td = document.createElement("td");
					//_td.setAttribute('idgrupo',_idGrupo);	
					_td.innerHTML=_res.data[_idCon].grupos[_nn].nombre;

					
					_Cant=_res.data[_idCon].grupos[_nn].cant;
					_td.innerHTML=_td.innerHTML+"<br> <b> "+ _Cant +" x</b>";
					
					_Preg=Math.round(100/_Reg*_Cant);
					_td.innerHTML=_td.innerHTML+"<br> <b> "+ _Preg+" % reg</b>";			
					
					//_td.setAttribute('title',_res.data[_idCon].grupos[_idGrupo].data.descripcion);
					
					_tr.appendChild(_td);						
				}
				
				_con.appendChild(_tr);
			}else{			
				_tr = document.createElement("tr");
				_tr.setAttribute('idcon',_idCon);	
				_td = document.createElement("td");				
				_td.innerHTML=_res.data[_idCon].data.nombre;
				

				
				_Inc=_res.data[_idCon].totalcant;
				_td.innerHTML+="<br>incidencias: "+_Inc;
				
				_Reg=_res.data[_idCon].registros;
				_td.innerHTML+="<br>encuestas: "+_Reg;
				_td.innerHTML+="<a onclick='editarConsulta(this);'>editar</a>";
				_td.innerHTML+="<a onclick='limitarConsulta(this);'>trabajar solo con esta consulta</a>";
				
				_td.setAttribute('title',_res.data[_idCon].data.descripcion);
				_tr.appendChild(_td);
				
				for (_nn in _res.data[_idCon].grupos) {
					_idGrupo = _res.data[_idCon].grupos[_nn].id;
					//console.log(_res.data[_idCon].grupos[_idGrupo]);
					_td = document.createElement("td");
					_td.setAttribute('idgrupo',_idGrupo);									
					_td.innerHTML="<a onclick='editarGrupo(this)'>"+_res.data[_idCon].grupos[_nn].nombre+"</a>";
					
					if(_res.data[_idCon].grupos[_nn].exclusivo=='1'){
						_td.innerHTML+=" (e)";
					}else{
						_td.innerHTML+=" (i)";
					}
					
					for(_idcaso in _res.data[_idCon].grupos[_nn].casos){
						_estecaso=_res.data[_idCon].grupos[_nn].casos[_idcaso];						
						_div =document.createElement("div");
						_div.setAttribute('idcaso',_idcaso);						
						console.log(_estecaso);						
						
						
						if(_estecaso.especial=='otros'){
							_div.innerHTML=" <a onclick='editarCaso(this);'>grupo, otros, no requiere un cmpo asociado</a>";					
						}else if(_estecaso.CampoDesc===null){
							_div.innerHTML=" <a onclick='editarCaso(this);'>ERROR EN LA DEFINICION DEL CAMPO. EDITAR</a>";
						}else{
							
							_div.innerHTML=" <a onclick='editarCaso(this);'>"+_estecaso.campo + "- "+ _estecaso.CampoDesc.Comment;
							
							if((_estecaso.valor != null)){
								_div.innerHTML=_div.innerHTML+ " = " + _estecaso.valor;
							}else if((_estecaso.valorno != null)){
								_div.innerHTML=_div.innerHTML+ " != " + _estecaso.valorno;
							}else{
								if((_estecaso.valmin != null)){
									_div.innerHTML=_div.innerHTML+ " >= " + _estecaso.valmin;
								}
								if((_estecaso.valmax != 'null')){
									_div.innerHTML=_div.innerHTML+ " <= " + _estecaso.valmax;
								}
							}
							_div.innerHTML=_div.innerHTML + "</a>";
						}
						//console.log(_res.data[_idCon].grupos[_idGrupo].casos[_idcaso].CampoDesc);
						_td.appendChild(_div);
					}
					
					_Cant=_res.data[_idCon].grupos[_nn].cant;
					_td.innerHTML=_td.innerHTML+"<br> <b> "+ _Cant +" x</b>";
					_Pinc=Math.round(100/_Inc*_Cant);
					_td.innerHTML=_td.innerHTML+"<br> <b> "+ Math.round(_res.data[_idCon].grupos[_nn].Pinc)+" % inc</b>";
					
					_Preg=Math.round(100/_Reg*_Cant);
					_td.innerHTML=_td.innerHTML+"<br> <b> "+ Math.round(_res.data[_idCon].grupos[_nn].Penc)+" % reg</b>";
					
					_td.innerHTML=_td.innerHTML+" <a onclick='nuevoCaso(this);'>nuevo caso</a>";				
					
					if(_res.data[_idCon].grupos[_nn].descripcion!=undefined){
						_td.setAttribute('title',_res.data[_idCon].grupos[_nn].descripcion);
					}
					
					_tr.appendChild(_td);						
				}
				
				_td = document.createElement("td");
				_td.innerHTML="<a onclick='nuevoGrupo(this);'>nuevo grupo</a>";
				_tr.appendChild(_td);		
		
				_con.appendChild(_tr);
			}
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
	_loc="./form_tabla.php?tabla=CONagrupadasGrupos&accion=cambia&id="+_idgrupo
	_loc+="&salidaid=<?php echo $ID;?>&salida=indice_consultas";
	_loc+="&campofijo=id_p_PROproyectos&campofijo_c=<?php echo $ID;?>";
	_loc+="&campofijob=id_p_CONagrupadas&campofijob_c="+_idcon;
	_loc+="&PASARunaconsulta=<?php echo $_GET['unaconsulta'];?>";
	window.location.assign(_loc);
}

function nuevoGrupo(_this){
	_idcon=_this.parentNode.parentNode.getAttribute('idcon');
	_loc="./form_tabla.php?tabla=CONagrupadasGrupos&accion=agrega";
	_loc+="&salidaid=<?php echo $ID;?>&salida=indice_consultas";
	_loc+="&campofijo=id_p_PROproyectos&campofijo_c=<?php echo $ID;?>";
	_loc+="&campofijob=id_p_CONagrupadas&campofijob_c="+_idcon;
	_loc+="&PASARunaconsulta=<?php echo $_GET['unaconsulta'];?>";	
	window.location.assign(_loc);
}

function nuevoCaso(_this){
	_idgrupo=_this.parentNode.getAttribute('idgrupo');
	_idcon=_this.parentNode.parentNode.getAttribute('idcon');
	_href="./form_tabla.php?salidaid=<?php echo $ID;?>&tabla=CONagrupadasCasos&accion=agrega&salida=indice_consultas";	
	_href+="&campofijo=id_p_CONagrupadasGrupos&campofijo_c="+_idgrupo;
	_href+="&campofijob=id_p_CONagrupadas&campofijob_c="+_idcon;
	_href+="&campofijoc=id_p_PROproyectos&campofijoc_c=<?php echo $ID;?>";
	_href+="&PASARunaconsulta=<?php echo $_GET['unaconsulta'];?>";
	window.location.assign(_href);
}
function editarCaso(_this){
	_idgrupo=_this.parentNode.parentNode.getAttribute('idgrupo');
	_idcon=_this.parentNode.parentNode.parentNode.getAttribute('idcon');
	_idcaso=_this.parentNode.getAttribute('idcaso');

	_href="./form_tabla.php?salidaid=<?php echo $ID;?>&tabla=CONagrupadasCasos&accion=agrega&salida=indice_consultas";
	_href+="&campofijo=id_p_CONagrupadasGrupos&campofijo_c="+_idgrupo;
	_href+="&campofijob=id_p_CONagrupadas&campofijob_c="+_idcon;
	_href+="&campofijoc=id_p_PROproyectos&campofijoc_c=<?php echo $ID;?>";
	_href+="&PASARunaconsulta=<?php echo $_GET['unaconsulta'];?>";
		
	window.location.assign("./form_tabla.php?salidaid=<?php echo $ID;?>&tabla=CONagrupadasCasos&accion=cambia&id="+_idcaso+"&salida=indice_consultas&campofijo=id_p_CONagrupadasGrupos&campofijo_c="+_idgrupo+"&campofijob=id_p_CONagrupadas&campofijob_c="+_idcon)
}
function editarConsulta(_this){
	_idcon=_this.parentNode.parentNode.getAttribute('idcon');
	window.location.assign("./form_tabla.php?salidaid=<?php echo $ID;?>&tabla=CONconsultasAgrupadas&accion=cambia&id="+_idcon+"&salida=indice_consultas&PASARunaconsulta=<?php echo $_GET['unaconsulta'];?>");
}
function limitarConsulta(_this){
	_idcon=_this.parentNode.parentNode.getAttribute('idcon');
	window.location.assign("./indice_consultas.php?id=<?php echo $ID;?>&unaconsulta="+_idcon);
}
</script>


<?php

echo "
<script type='text/javascript'>
cargarConsultas();
</script>";
?>
</body>
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
		.salida{
			vertical-align:top;
			 page-break-inside: avoid;
		}
		.salida >div{
			vertical-align:top;
		}
		.salida{
			margin:10px 5px 10px 5px;
			padding:5px;
			width:750px;
			border:3px solid #000;
			box-shadow: 6px 6px 5px #888888;
			position:relative;
		}
		
		.tabla{
			display:inline-block;
			width:300px;
		}
		
		.consulta .tabla table td{
			min-width: 50px;
			text-align:right;
		}
		.tabla table td > span.uni{
			font-size:10px;
		}
		.tabla table th > span.desc{
			font-size:10px;
			font-weight:normal;
		}		
		.salida.cruce .tabla{
			width:750px;
		}
		.salida.cruce .tabla > table{
			font-size:11px;
			font-weight:normal;
			display:inline-block;
			width:750px;
			table-layout:fixed;
			word-break: break-all;
		}
		
		.salida.cruce > .tabla > table th, .salida.cruce > .tabla > table td{
			font-size:11px;
			font-weight:normal;word-break: break-all;}
		
		.grafico{
			display:inline-block;
			margin-left:30px;
			width: 416px;
			height:320px;
			border:1px solid #ccc;
			background-color:#fffefd;
		}

		.salida.consulta .grafico{
			overflow:hidden;
		}
	
		.salida.cruce .grafico{
			display:inline-block;
			width: 535px;
			height:320px;
			border:1px solid #ccc;
			background-color:#fffefd;
		}
		
		<?php		
		if($_GET['modo']=='imagen'){
			
			echo "
				.salida.cruce .grafico{
					margin:0;
					border:0px;
				}	
			";	
		}		
		?>		
		
		.toggleexp{
		    right: 428px;
		    position: absolute;
		    top: 4px;
		}
				
		.jqplot-yaxis {		 
		  width:60px;
		}

		.jqplot-yaxis-tick{
			padding-left:18px;
		}	
		table.jqplot-table-legend:hover{
			z-index:5;
		} 
			
		.salida.consulta .jqplot-yaxis-label{
			transform: rotate(-90deg);
			position:absolute !important;
			left:-25px !important;
			border-bottom:1px solid #333;
		}
		
		.salida.cruce .jqplot-yaxis-label {
		    border-bottom: 1px solid #333;
		    text-align:center;
		    width: 100px;
		    left: -60px !important;
		    background-color:#fff;
		    position: absolute !important;
		    transform: rotate(-90deg);
		}
	
		.tabla td:hover{
			background-color:#fefeff;
			cursor:help;
		}
		.tabla p{
			margin:0px;
		}
		table.jqplot-table-legend{
			position: absolute;
			left: 540px !important;
			overflow: hidden;
		    table-layout: fixed;
		    white-space: nowrap;
		    width: 178px;
		}
		table.jqplot-table-legend {
			white-space: nowrap;
		}
		
		td.jqplot-table-legend-label{
			width:150px;
		}
		
		.jqplot-axis.jqplot-yaxis{
			width:75px;
			
		}
		.jqplot-table-legend-swatch{
			min-width:13px;
			min-height:13px;
		}
	</style>
	
</head>
<body>
	<script language="javascript" type="text/javascript" src="./jscripts/jquery/jquery-1.8.2.js"></script>
	<script language="javascript" type="text/javascript" src="./jscripts/js/graficarCons.js"></script>	
	
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
	<script language="javascript" type="text/javascript" src="./jscripts/jsplot/jquery.min.js"></script>
	<script language="javascript" type="text/javascript" src="./jscripts/jsplot/jquery.jqplot.min.js"></script>
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.highlighter.min.js"></script>
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.cursor.min.js"></script>
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.dateAxisRenderer.min.js"></script>	
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.barRenderer.min.js"></script>
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.pointLabels.min.js"></script>	
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.pieRenderer.min.js"></script>
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.donutRenderer.min.js"></script>
	
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.logAxisRenderer.js"></script>
	<script type="text/javascript" src="./jscripts/jsplot/plugins/jqplot.canvasAxisTickRenderer.js"></script>

	
<?php

if(!isset($_GET['id'])){$_GET['id']=2;}
$ID=$_GET['id'];
if(!isset($_GET['modo'])){$_GET['modo']='actual';}

global $DESindex;
$DESindex=array();

/*
$Result=consultaPropEstructura();
//echo "<pre>";print_r($Result);echo "</pre>";
$Contenidos=$Result['Contenidos'];

$Result=consultaProyectos();
echo "<pre>";print_r($Result);echo "</pre>";
$Result=$Result[$ID];
$stringid=str_pad($ID,5,"0",STR_PAD_LEFT);
$tabla="CONT_".$stringid;

$Estrategias=$Result['Estrategias'];
$Areas=$Result['Areas'];
$Ambitos=$Result['Ambitos'];
*/

echo "
<h2><a href='./reporte_contenidos.php?id=$ID'>ver reporte de procesamiento de datos</a></h2>

";


if($_GET['modo']=='actual'){
	echo "<h2><a href='./indice_consultas_expo.php?id=$ID&modo=imagen'>ver modo fijo (para copiar y pegar en documento de texto)</a></h2>";
}elseif($_GET['modo']=='imagen'){
	echo "<h2><a href='./indice_consultas_expo.php?id=$ID&modo=actual'>ver modo actualizado (regenera todos los gráficos)</a></h2>";	
}

echo "
<table id='tablacont'>

</table>

<div id='tablacons'>
</div>

";

echo "<div id='carga'>";
echo "</div>";


echo "
<script type='text/javascript'>        
     var _MODO = '".$_GET['modo']."';     
</script>
"

?>

<script type="text/javascript">        
            window.scrollTo(0,'<?php echo $_GET['y'];?>');     
</script>
 


<script type="text/javascript">  


var _IDproy = '<?php echo $ID;?>';
function cargarConsultas(){
		

		var parametros = {
			"id" : _IDproy,
			"funcion" : 'consultaConsultasResultados'
		};
		
		var _Cid='consultas';
		cargandoT('Resultados de consultas simples',_Cid);
		
		$.ajax({
				data:  parametros,
				url:   'cons_ajax.php',
				type:  'post',
				success:  function (response){
					cargadoT(_Cid);
					var _res = $.parseJSON(response);
					console.log(_res);
					consultasCargadas(response);
					console.log('a los cruces');
					cargarCruces();
				}
		});		
		
			
		
			
}

function cargarCruces(){	
var parametros = {
			"id" : _IDproy,
			"funcion" : 'consultaConsultasResultadosCrucesAuto'
		};
		
		var _Cid='cruces';
		cargandoT('Resultados de cruces entre consultas simples',_Cid);
		
		$.ajax({
				data:  parametros,
				url:   'cons_ajax.php',
				type:  'post',
				success:  function (response){
					cargadoT(_Cid);
					var _res = $.parseJSON(response);					
					console.log(_res);
					consultasCrucesCargados(response);					
				}
		});	
}


function consultasCrucesCargados(response){		
	var _res = $.parseJSON(response);	

	if(_res.res=='exito'){
		if(_res.data!=null){

		//console.log(_res); 
		_pag=document.getElementById('tablacons');
				
		for(_Ncruce in _res.data){
			
			_con=document.createElement('div');
			_con.setAttribute('class','salida cruce');
			_con.setAttribute('idcon',_Ncruce);
			
			_pag.appendChild(_con);
			_dat=document.createElement('div');
			_dat.setAttribute('class','tabla');
			_con.appendChild(_dat);
			_gra=document.createElement('div');
			_gra.setAttribute('class','grafico');
			_idDest='GraCr'+_Ncruce;
			_gra.setAttribute('id',_idDest);
			
			_con.appendChild(_gra);
			
			_n = document.createElement("h2");
			_n.innerHTML=_res.data[_Ncruce].data.nombre;
			_dat.appendChild(_n);
			_n = document.createElement("p");
			_n.innerHTML=_res.data[_Ncruce].data.descripcion;
			_n.innerHTML+="<br>encuestas: "+_Reg;
			_dat.appendChild(_n);			
			
			_tab = document.createElement("table");
			_dat.appendChild(_tab);
			
			var _ArrTits= new Array();
			var _ArrDatos= new Array();
			
			_tr = document.createElement("tr");
			_tab.appendChild(_tr);
			_td = document.createElement("th");
			_td.innerHTML=_res.data[_Ncruce].data.nombre;
			_tr.appendChild(_td);
			
			
			for (_gNum in _res.data[_Ncruce].consultas[0].grupos.nombre){
				_gNom= _res.data[_Ncruce].consultas[0].grupos.nombre[_gNum];
				_td = document.createElement("th");
				_td.innerHTML=_gNom;
				_tr.appendChild(_td);
			}
			
			var _ArrGbDat= new Array();
			var _ArrTitsB = new Array();
			for (_gbNum in _res.data[_Ncruce].consultas[1].grupos.nombre){
				
				_IntgbNum=parseInt(_gbNum)+1;
				
				_gbNom= _res.data[_Ncruce].consultas[1].grupos.nombre[_gbNum];
				_ArrTitsB.push(_gbNom);
				
				_tr = document.createElement("tr");
				_tab.appendChild(_tr);
				_td = document.createElement("th");
				_td.innerHTML=_gbNom;
				_tr.appendChild(_td);
								
				_tot=0;
				
				for (_gaNum in _res.data[_Ncruce].consultas[0].grupos.nombre){
					_gaNom= _res.data[_Ncruce].consultas[0].grupos.nombre[_gaNum];
					if(typeof _res.data[_Ncruce].matriz[_gaNom][_gbNom] != 'undefined'){
						_tot+=_res.data[_Ncruce].matriz[_gaNom][_gbNom];
					}
				}
				
				var _ArrGaDat=new Array();

				for (_gaNum in _res.data[_Ncruce].consultas[0].grupos.nombre){					
										
					_tDd = document.createElement("td");
					_tr.appendChild(_tDd);	
					_cant=0;
					_gaNom= _res.data[_Ncruce].consultas[0].grupos.nombre[_gaNum];
					
					if(typeof _res.data[_Ncruce].matriz[_gaNom][_gbNom] != 'undefined'){
						_cant=_res.data[_Ncruce].matriz[_gaNom][_gbNom];
					}	
					
					
					_Preg=Math.round(100/_tot*_cant);
					_tDd.innerHTML=_Preg+" % <span class='uni'> reg</span>";
					_tDd.title = _cant + " incidencias"; 
					
					_ArrGaDat.push(_Preg);
					
					_IntgaNum=parseInt(_gaNum)+1;
				}
				_ArrGbDat.push(_ArrGaDat);
			}

			var _Arr2GaDat= new Array();
			var _ArrTitsA = new Array();
			for(_gaN in _ArrGbDat[0]){
				_ArrTitsA.push(_res.data[_Ncruce].consultas[0].grupos.nombre[_gaN]);
				var _Arr2GbDat= new Array();
				for(_gbN in _ArrGbDat){
					_Arr2GbDat.push(_ArrGbDat[_gbN][_gaN]);
				}				
				_Arr2GaDat.push(_Arr2GbDat);				
			}	

			_SerieB= _res.data[_Ncruce].consultas[0].nombre;
			_SerieA= _res.data[_Ncruce].consultas[1].nombre;
			//console.log(_SerieA);
			//_ArrDatos=_ArrGbDat;// alternativa para series invertidas							
			_ArrDatos=_Arr2GaDat;
			//console.log(_ArrTitsA);
			
			if(_MODO=='actual'){
				graficarCruce(_idDest,_ArrDatos,_ArrTitsA,_ArrTitsB,_SerieA,_SerieB);
				console.log('guardando img: '+'GraCr'+_Ncruce+" / "+_res.data[_Ncruce].data.id);
				enviarImagenNueva('cruces',_res.data[_Ncruce].data.id,$('#GraCr'+_Ncruce).jqplotToImageStr({}));	
			}else if(_MODO=='imagen'){
				_img=document.createElement('img');
				_img.src='./imagenes/cruces/'+_res.data[_Ncruce].data.id+'.png';
				document.getElementById('GraCr'+_Ncruce).appendChild(_img);
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

var ValoresConsultas={};

function enviarImagenNueva(_subcarpeta,_id,_datos){
	
	var parametros = {
		"id" : _id,
		"imagen" : _datos,
		"carpeta" : _subcarpeta
	};
	$.ajax({
		data:  parametros,
		url:   'ed_guardar_imagen.php',
		type:  'post',
		success:  function (response){
			var _res = $.parseJSON(response);
			console.log(_res);
			if(_res.res=='exito'){
				cargarMediciones(_res);			
			}else{
				console.log('falló la actualizaicón de estados');
			}
		}
	});	
}


function consultasCargadas(response){	
	var _res = $.parseJSON(response);		
	if(_res.res=='exito'){
		//console.log(_res.data);
		ValoresConsultas=_res.data;
		
		//console.log(ValoresConsultas);
		_pag=document.getElementById('tablacons');
		for(_idCon in _res.data){
			_con=document.createElement('div');
			_con.setAttribute('class','salida');
			_pag.appendChild(_con);
			_dat=document.createElement('div');
			_dat.setAttribute('class','tabla');
			_con.appendChild(_dat);
			_gra=document.createElement('div');
			_gra.setAttribute('class','grafico');
			_idDest='GraC'+_idCon;
			_gra.setAttribute('id',_idDest);
			

			
			_con.appendChild(_gra);
			if(_res.data[_idCon].grupos.length>65){
				_gra.style.height="1200px";
			}else if(_res.data[_idCon].grupos.length>25){
				_gra.style.height="900px";
			}		
			_Reg=_res.data[_idCon].registros;			
			_Inc=_res.data[_idCon].gruposRes.totalcant;
				
			_con.setAttribute('class','salida consulta');
			_con.setAttribute('idcon',_idCon);
			_n = document.createElement("h2");
			_n.innerHTML=_res.data[_idCon].data.nombre;
			_dat.appendChild(_n);
			_n = document.createElement("p");
			_n.innerHTML=_res.data[_idCon].data.descripcion;
			_n.innerHTML+="<br>encuestas: "+_Reg;
			_n.innerHTML+="<br>incidencias: "+_Inc;
			_dat.appendChild(_n);
			
			_tab = document.createElement("table");
			_dat.appendChild(_tab);
							
			var _ArrTits= new Array();
			var _ArrDatos= new Array();				
	
			if(_res.data[_idCon].pobTotal>0){
				_atog=document.createElement('a');
				_atog.setAttribute('onclick','cambiarExp(this)');
				_atog.setAttribute('class','toggleexp');
				_atog.setAttribute('modo','crudo');
				_atog.innerHTML="ver resultados expandidos";
				_atog.title='Los valores expandidos son el resultado de aplica un facor de representatividad a las cantidades obtenidas de modo que la ponderación diferenciada de la muestra arroja proporciones más significaticas de la población total analizada'
				_con.appendChild(_atog);
			}
	
			_tab=document.createElement("table");
			_dat.appendChild(_tab);
			
			for (_idGrupo in _res.data[_idCon].grupos) {
				
				_tr = document.createElement("tr");
				_tab.appendChild(_tr);
				_tr.setAttribute('idgrupo',_idGrupo);
				_td = document.createElement("th");
				_tr.appendChild(_td);
				_titulo=_res.data[_idCon].grupos[_idGrupo].nombre;
				_td.innerHTML=_titulo;
				
				_span=document.createElement("span");
				_span.setAttribute('class','desc');
				_td.appendChild(_span);
				
				if(_res.data[_idCon].grupos[_idGrupo].descripcion!=undefined){
					_descrip=_res.data[_idCon].grupos[_idGrupo].descripcion;					
					_span.innerHTML+="<br>"+_descrip;
				}
									
				_ArrTits.unshift(_titulo.toString());
				
				_tDd = document.createElement("td");
				_tr.appendChild(_tDd);
				_Cant=_res.data[_idCon].grupos[_idGrupo].cant;
				
				_tD2 = document.createElement("td");
				_tr.appendChild(_tD2);	
				
				if(_res.data[_idCon].tipo=='Agrupación'){		
					for(_idcaso in _res.data[_idCon].grupos[_idGrupo].casos){
						
						_estecaso=_res.data[_idCon].grupos[_idGrupo].casos[_idcaso];				
						//console.log(_estecaso);
												
						if(_estecaso.CampoDesc===null){
							//_td.innerHTML=" <a onclick='editarCaso(this);'>ERROR EN LA DEFINICION DEL CAMPO. EDITAR</a>";
						}else{
							
							_td.title+=_estecaso.CampoDesc.descripcion;
							
							if((_estecaso.valor != null)){
								_span.innerHTML+= " = " + _estecaso.valor;
							}else if((_estecaso.valorno != null)){
								_span.innerHTML+= " != " + _estecaso.valorno;
							}else{
								if((_estecaso.valmin != null)){
									_span.innerHTML+= " >= " + _estecaso.valmin;
								}
								if((_estecaso.valmax != 'null')){
									_span.innerHTML+= " <= " + _estecaso.valmax;
								}
							}
						}
						//console.log(_res.data[_idCon].grupos[_idGrupo].casos[_idcaso].CampoDesc);
					}
				}		
				
				_tDd.title=_Cant +" incidencias";
				_tD2.title=_Cant +" incidencias";
				
				
				_tD2.innerHTML+= Math.round(_res.data[_idCon].grupos[_idGrupo].Pinc)+" %<span class='uni'> inc</span>";
				
				_Penc=Math.round(_res.data[_idCon].grupos[_idGrupo].Penc);
				_tDd.innerHTML+= _Penc+" %<span class='uni'> reg</span>";
				_ArrDatos.unshift(_Penc);			
							
			}
			
			_maxP=Math.min((Math.floor(_res.data[_idCon].gruposRes.maxP/10)+2)*10,100);
			


			
			if(_MODO=='actual'){
				graficarConsulta(_idDest,_ArrDatos,_ArrTits,_maxP);
				console.log('guardando img: '+'GraC'+_idCon+" / "+_res.data[_idCon].data.id);
				enviarImagenNueva('consultas',_res.data[_idCon].data.id,$('#GraC'+_idCon).jqplotToImageStr({}));	
			}else if(_MODO=='imagen'){
				_img=document.createElement('img');
				_img.src='./imagenes/consultas/'+_res.data[_idCon].data.id+'.png';
				document.getElementById('GraC'+_idCon).appendChild(_img);
			}
						
		}	
					
	}else if(_res.res=='err'){		
		_carga.innerHTML=_carga.innerHTML+' error al cargar el archivo.';
		for(i=0;i<_res.tx.length; i++){
			_carga.innerHTML=_carga.innerHTML+' '+_res.tx[i];
		}		
	}	
}

/*
 * cambia el modo del div salida entre modo crudo y modo expandido.
 * 
 */
function cambiarExp(_this){
	_ArrDatos=Array();
	_ArrTits=Array();
	_modo=_this.getAttribute('modo');
	_idCon=_this.parentNode.getAttribute('idcon');
	_str="[idcon='"+_idCon+"'].consulta .tabla table > tr";
	//console.log(_str);
	_idDest="GraC"+_idCon;
	document.getElementById(_idDest).innerHTML='';
	_filas=document.querySelectorAll(_str);
	if(_modo=='expandido'){
		_this.innerHTML="ver resultados expandidos";
		_this.setAttribute('modo','crudo');
		_this.title='Los valores expandidos son el resultado de aplica un facor de representatividad a las cantidades obtenidas de modo que la ponderación diferenciada de la muestra arroja proporciones más significaticas de la población total analizada';
		
		
		for(_nn in _filas){
			if(typeof _filas[_nn]=='object'){
			//console.log(typeof _filas[_nn]);
				_idGrupo=_filas[_nn].getAttribute('idgrupo');
				_filas[_nn].childNodes[1].innerHTML=Math.round(ValoresConsultas[_idCon].grupos[_idGrupo].Penc)+" % reg";
				_ArrDatos.unshift(Math.round(ValoresConsultas[_idCon].grupos[_idGrupo].Penc));
				_filas[_nn].childNodes[2].innerHTML=Math.round(ValoresConsultas[_idCon].grupos[_idGrupo].Pinc)+" % inc";
				_ArrTits.unshift(ValoresConsultas[_idCon].grupos[_idGrupo].nombre);
			}
		}
					
	}else{
		_this.innerHTML="ver resultados crudos";
		_this.setAttribute('modo','expandido');
		_this.title='Los valores crudos resultan menos representativos de la población representada pero son representativos de los individuos relevados.';	
		for(_nn in _filas){
			if(typeof _filas[_nn]=='object'){
			//console.log(typeof _filas[_nn]);
				_idGrupo=_filas[_nn].getAttribute('idgrupo');
				_filas[_nn].childNodes[1].innerHTML=Math.round(ValoresConsultas[_idCon].grupos[_idGrupo].PencEX)+" % reg";
				_ArrDatos.unshift(Math.round(ValoresConsultas[_idCon].grupos[_idGrupo].PencEX));
				_filas[_nn].childNodes[2].innerHTML=Math.round(ValoresConsultas[_idCon].grupos[_idGrupo].PincEX)+" % inc";
				_ArrTits.unshift(ValoresConsultas[_idCon].grupos[_idGrupo].nombre);
			}
		}	
	}
	
	maxP=Math.min((Math.floor(ValoresConsultas[_idCon].gruposRes.maxP/10)+2)*10,100);
	
	graficarConsulta(_idDest,_ArrDatos,_ArrTits,maxP);
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
function editarConsulta(_this){
	_idcon=_this.parentNode.parentNode.getAttribute('idcon');
	window.location.assign("./form_tabla.php?salidaid=<?php echo $ID;?>&tabla=CONconsultasAgrupadas&accion=cambia&id="+_idcon+"&salida=indice_consultas");
}


function cargandoT(_Tx,_id){			
	//console.log(_id);
	_contenedor=document.getElementById('carga');
	var div = document.createElement("div");
	div.setAttribute('id','cargandoT'+_id);
	div.setAttribute('contenido',_Tx);
	div.innerHTML=' cargando:  '+_Tx;
	_img=document.createElement('img');
	_img.setAttribute('src','./img/cargando.gif');	
	div.appendChild(_img);
	_contenedor.appendChild(div);
}


function cargadoT(_id){		
	//console.log(_id);
	div = document.getElementById('cargandoT'+_id);
	_txt=div.getAttribute('contenido');
	div.innerHTML="<span>cargado: "+_txt+"</span>";		
}

</script>


<?php

echo "
<script type='text/javascript'>
cargarConsultas();
</script>";
?>
</body>
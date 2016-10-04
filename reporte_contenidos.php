<!DOCTYPE html>
<head>
	 
	   <title>ANALiSIS ESTADíSTICO</title>
	   <link href="./img/pdfajpg.ico" type="image/x-icon" rel="shortcut icon">
	   <link rel="stylesheet" type="text/css" href="./css/estadisticas.css" />   
	   
	<style>
	table{
		width: 100%;
	}

	</style>
	
</head>
<body>
	<script language="javascript" type="text/javascript" src="./jscripts/jquery/jquery-1.8.2.js"></script>	
	
	
<?php

include('./includes/encabezado.php');
include('./cons_general.php');


$ID=$_GET['id'];


//echo "<h2 class='boton'><a href='./indice_contenidos.php?id=$ID'>ir al índice</a></h2>";
echo "<h2 class='boton'><a href='./indice_consultas_expo.php?id=$ID'>ir a salidas de datos</a></h2>";



$Result=consultaProyectos();
//echo "<pre>";print_r($Result);echo "</pre>";
$stringid=str_pad($ID,5,"0",STR_PAD_LEFT);
$tabla="CONT_".$stringid;

$Datos=$Result['Contenidos'][$tabla];

?>
<h1>Introducción</h1>
<p>
	El presente documento sintetiza el proceso de análisis realizado para el proyecto almacenado como: "<?php echo $Datos['proyecto'];?>".
	El proceso de análisis descrito implica las instancias de, indexación, depuración, agrupación, cruzamiento de datos.
	Para cada una de estas instancias se adoptaron criterios de síntesis los cuales son descritos más adelante. 
</p>
<p>
	En tanto resulta prioritario para la calidad de la información, durante todo el procesamiento, no fueron guardadas las sucesivos resultados intermedios de los datos.
	En su lugar se archivaron todos los procesos, permitiendo el presente documento, generado automáticamente a partir los procesos archivados, y aplicados en última instancia sobre el cuerpo de datos original. 
</p>
<h1>Indexación</h1>
<p>
	Denominamos indexación al proceso por el cual un conjunto de datos es desagregado en valores estructurados 
	dentro de una base de datos diseñada específicamente para su análisis estadístico. 
</p>
<h2>Documentos Fuente</h2>
<p>
	El documento fuente es un archivo en formato csv generado a partir un archivo .ods descargado directamente de la plataforma google forms.
	En tanto eta plataforma garantiza la estabilidad de los datos cargados originalmente, se minimizó toda posibilidad de alteración del contenido.
	La única transformación realizada fue el reemplazo autómatico de todas las apariciones del caracter ";" (punto y coma) a fin de utilizarlo como separador de campos en el formato csv.
	Dicho procedimiento se realizó con el software Libreoffice.
</p>
<h2>Estructura</h2>
<p>
	La información fue estructurada dentro de una base de datos mySql. Todos los datos base fueron registrados en una única tabla creada automáticamente y exclusivamente a los propósitos del presente análisis 
	La estructura de base de datos reproduce el cuerpo de datos original ingresando cada fila como un registro y identificando cada columna como un campo.
</p>
<p>
	La primer fila del documento .csv se reconoce como encabezado y se registran sus valores como descripciones de cada campo. 
	Dado que los valores responden a la pregunta correspondiente en cada caso, su extensión y caracteres utilizados no responden a las exigencias del sistema de base de datos utilizado.
	En su lugar se utilizó un código numerado progresivo.
</p>
</p>
El tipo de campo se definió e función de las características del contenido de cada columna. Se iteraron todos los valores de cada columna, identificando el de mayor longitud. Luego se utilizaron los tipos varchar(100), varchar(500), y text.
En cada caso para garantizar que el contenido de cada celda cupiera en la base de datos para el campo correspondiente.
<p>
<h2>Codificación de preguntas en campos</h2>
<div id='listadocampos'>
	<table id='listadocamposT'>
		<tr>
			<th>Campo</th><th>Descripción</th><th>Tipo</th>
		</tr>
	</table>
</div>
<h1>Depuración</h1>
<h2>Identificación de casos</h2>
<p>
	La información de cada campo fue analizada de forma aislada identificando dispersiones en el contenido debido a los criteros de carga adoptados.
	A tal fin se realizó una análisis de variantes para cada campo. a continuación se incorpora una muestra de los valores iniciales.
</p>
<p>
	La siguiente secuencia incorpora una muestra de 10 variantes identificadas para cada campo, incluyendo entre paréntesis la cantidad de incidencias halladas. 
</p>
<div id='variacionesiniciales'>
	variaciones iniciales	
</div>

<h2>Depuración masiva</h2>
<p>
	A fin de homogeneizar la información y sintetizarla, minimizando el riesgo de corrupción de la misma, se definieron primeramente criterios de depuración masiva.
	Esto es, procesar los datos mediante la repetición de algoritmos. A continuación se presenta un listado de los algoricmos aplicados, los campos aplicados en cada caso y una descripción de sus efectos. 
</p>
<div id='depuracion'>
	depuración
</div>

<h2>Variaciones resultantes</h2>
<p>
	Una vez depurada la información se obtienen lasa siguientes variaciones para cada campo.
</p>
<p>
	La siguiente secuencia incorpora una muestra de 10 variantes identificadas para cada campo, incluyendo entre paréntesis la cantidad de incidencias halladas. 
</p>
<div id='variacionesfinales'>
	variaciones finales	
</div>

<h1>Síntesis</h1>
<h2>Consultas</h2>
<p>
	A fin de obtener valores sitéticos representativos de la muestra analizada se definieron una serie de consultas que permiten la extracción de información específica.
	La definición de una consulta implica la definición de criterios de agrupacmiento. En ese sentido se utilizaron dos sitemas para definir los criterios de agrupamiento: un sistema de agrupamiento manual y un sistema de agrupamiento automático.
	A continuación de incorporan cada una de las consultas realizadas, su definición y la definició de sus agrupamientos. 
</p>
<div id='consultas'>
	consultas	
</div>

<h2>Cruces</h2>
<p>
	A fin de obtener valores significativos a partir del cruce de dos consultas, se definieron los siguientes criterios de cruce. 
</p>
<div id='cruces'>
	cruces
</div>

<h1>Resultados</h1>
<p>
	Como reultado de los procesamientos realizados se obtuvienron los siugientes valores. 
</p>
<div id='resultados'>
	resultados
</div>


<script type="text/javascript">        
            window.scrollTo(0,'<?php echo $_GET['y'];?>');     
</script>
 


<script type="text/javascript">  

function muestraCampos(){
		var parametros = {
			"id" : '<?php echo $ID;?>',
			"funcion" : 'consultaCampos'
		};
		$.ajax({
				data:  parametros,
				url:   'cons_ajax.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					contenidosCargados(response);
					_tabla=document.getElementById('listadocamposT');
					for(_key in _res.data){
						if(_key!='res'&&_key!='id'){						
						_tr=document.createElement('tr');
						_td1=document.createElement('td');
						_td2=document.createElement('td');
						_td3=document.createElement('td');
						_td1.innerHTML=_key;
						_td2.innerHTML=_res.data[_key].Comment;
						_td3.innerHTML=_res.data[_key].Type;
						
						_tr.appendChild(_td1);
						_tr.appendChild(_td2);
						_tr.appendChild(_td3);
						
						_tabla.appendChild(_tr);
						}
					}
				}
		});			
}
muestraCampos();



function muestraContenidos(){
		var parametros = {
			"id" : '<?php echo $ID;?>',
			"funcion" : 'consultaContenidos'
		};
		$.ajax({
				data:  parametros,
				url:   'cons_ajax.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					contenidosCargados(response);
				}
		});			
}


function contenidosCargados(response){	
	var _res = $.parseJSON(response);
	
	if(_res.res=='exito'){
		_dep=document.getElementById('variacionesiniciales');
		_dep.innerHTML='';
		
		
		for (_key in _res.data){
			if(_key!='res'&&_key!='id'){
			_h3 = document.createElement("h3");
			_h3.innerHTML=_key;
			_dep.appendChild(_h3);
			
			_par = document.createElement("p");
			_long=_res.data[_key].length;
			_muestra=Math.min(_long,10);
			_par.innerHTML='Se muestran '+_muestra +' de '+_long+' variantes identificadas.<br>'  
			
			for(i=0;i<_res.data[_key].length&&i<10; i++){	
		 // console.log(_key, _res.data[i][_key]);
		 	_tx=_res.data[_key][i]['dato'];
		 	_par.innerHTML +=' / '+ _tx.substr(0, 450);
		 	_par.innerHTML +=' ('+ _res.data[_key][i]['cantidad'] +')';
		 				
			}
			_dep.appendChild(_par);
			}
		}

					
	}else if(_res.res=='err'){		
		_carga.innerHTML=_carga.innerHTML+' error al cargar el archivo.';
		for(i=0;i<_res.tx.length; i++){
			_carga.innerHTML=_carga.innerHTML+' '+_res.tx[i];
		}		
	}	
}

function muestraDepuracion(){
		var parametros = {
			"id" : '<?php echo $ID;?>',
			"funcion" : 'consultaDepuracion'
		};
		//Llamamos a los puntos de la actividad
		$.ajax({
				data:  parametros,
				url:   'cons_ajax.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					depuracionCargada(response);
					//procesarSigReticulaResutado(response);
				}
		});			
}
function depuracionCargada(response){	
	var _res = $.parseJSON(response);
	
	if(_res.res=='exito'){
		_dep=document.getElementById('depuracion');
				
		
		
		for(i=0;i<_res.data.length; i++){		
			
			_h3 = document.createElement("h3");
			_h3.innerHTML='Criterio Nº '+(i + 1);
			_dep.appendChild(_h3);
			
			_pros=_res.data[i].procesamiento;
			_Expl='';
			if(_pros=='reemplazarcompleto'){
				_Expl='operación: reemplazo del texto cuando la totalidad del dato corresponde con la cadena de caracteres "'+_res.data[i].variable1+'"<br>';
				_Expl+='valor adoptado: '+_res.data[i].variable2+'<br>';
				_Expl+='campos afectados: '+_res.data[i].campos+'.<br>';
			}else if(_pros=='reemplazar'||_pros=='reemplazar()'){
				_Expl='operación: reemplazo del texto contenido cuando corresponde con la cadena de caracteres "'+_res.data[i].variable1+'"<br>';
				_Expl+='valor adoptado: '+_res.data[i].variable2+'<br>';
				_Expl+='campos afectados: '+_res.data[i].campos+'.<br>';
			}else if(_pros=='MaMi_MiMi()'||_pros=='MaMi_MiMi'){
				_Expl='operación: Redefinición de mayúsculas y minúsculas, asignando mayúscula solo a la primer letra de la primer palabra de cada campo.<br>';
			}else if(_pros=='MaMi_MaMi()'||_pros=='MaMi_MaMi'){
				_Expl='operación: Redefinición de mayúsculas y minúsculas, asignando mayúscula a la primer letra de cada una de las palabras separadas por un espacio en cada campo.<br>';
			}else if(_pros=='desagregarporseparador'||_pros=='desagregarporseparador'){
				_Expl='operación: Creación de nuevos campos resultado de la desagregación de su contenido.<br>';
				_Expl+='Utiliza el caracter "'+_res.data[i].variable1+'" como separador. Toda cadena contenida entre estos separadores define un grupo. cada registro puede o no pertenecer a dicho grupo.<br>';
			}else if(_pros=='reemplazarporcampo'||_pros=='reemplazarporcampo'){
				_Expl='operación: Reemplazo del contenido cuando coincide con la cadena de caracteres "'+_res.data[i].variable1+'".<br>';
				_Expl+='valor adoptado: el contenido del campo: '+_res.data[i].variable2+'.<br>';
			}
	

			_par = document.createElement("p");
			_par.innerHTML=_Expl;
			_dep.appendChild(_par)
			
		}
					
	}else if(_res.res=='err'){		
		_carga.innerHTML=_carga.innerHTML+' error al cargar el archivo.';
		for(i=0;i<_res.tx.length; i++){
			_carga.innerHTML=_carga.innerHTML+' '+_res.tx[i];
		}		
	}	
}

function muestraContenidosDepurados(){
		var parametros = {
			"id" : '<?php echo $ID;?>',
			"funcion" : 'consultaResumen'
		};
		$.ajax({
			data:  parametros,
			url:   'cons_ajax.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				console.log(_res);
				contenidosDepuradosCargados(response);
			}
		});		
}

function contenidosDepuradosCargados(response){	
	var _res = $.parseJSON(response);
	
	if(_res.res=='exito'){
		_dep=document.getElementById('variacionesfinales');
		_dep.innerHTML='';
		
		for (_key in _res.data){
			if(_key!='res'&&_key!='id'){
			_h3 = document.createElement("h3");
			_h3.innerHTML=_key;
			_dep.appendChild(_h3);
			
			_par = document.createElement("p");
			_long=_res.data[_key].valores.length;
			_muestra=Math.min(_long,50);
			_par.innerHTML='Se muestran '+_muestra +' de '+_long+' variantes identificadas.<br>'  
			
			_span = document.createElement("span");
			_span.setAttribute('style','font-size:8pt;');
			for(i=0;i<_res.data[_key].valores.length&&i<50; i++){	
		 // console.log(_key, _res.data[i][_key]);
		 	_tx=_res.data[_key].valores[i]['dato'];
		 	_span.innerHTML +=' / '+ _tx.substr(0, 250);
		 	_span.innerHTML +=' ('+ _res.data[_key].valores[i]['cantidad'] +')';
		 				
			}
			_par.appendChild(_span);
			_dep.appendChild(_par);
			}
		}

		
					
	}else if(_res.res=='err'){		
			_carga.innerHTML=_carga.innerHTML+' error al cargar el archivo.';
			for(i=0;i<_res.tx.length; i++){
				_carga.innerHTML=_carga.innerHTML+' '+_res.tx[i];
			}		
	}	
}

function apagarCampoDepurado(_campo){	
	var parametros = {
		"id" : '<?php echo $ID;?>',
		"funcion" : 'apagarCampoDepurado',
		"v1" : _campo
	};
	$.ajax({
			data:  parametros,
			url:   'cons_ajax.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				_elem = document.getElementById('th'+_res.data.apagado);
				_elem.parentNode.removeChild(_elem);
				_elem = document.getElementById('td'+_res.data.apagado);
				_elem.parentNode.removeChild(_elem);
				console.log(_res);				
			}
	});		
	
}

	
function cargarConsultas(){
		var _IDproy = '<?php echo $ID;?>';

		var parametros = {
			"id" : _IDproy,
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
		
			
		var parametros = {
			"id" : _IDproy,
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



function consultasCrucesCargados(response){	
	var _res = $.parseJSON(response);	
	

	if(_res.res=='exito'){
		_con=document.getElementById('cruces');
		_con.innerHTML='';
		console.log(_res.data); 
		
		for(_Ncruce in _res.data){
			
			_h3 = document.createElement("h3");
			_h3.innerHTML="Cruce: "+_res.data[_Ncruce].data.nombre;
			_con.appendChild(_h3);
			
			_par = document.createElement("p");
			_par.innerHTML="<br><span>"+_res.data[_Ncruce].data.descripcion+"</span><br>";
			_par.innerHTML+="Este cruce combina las siguientes consultas definidas previamente:";
			_con.appendChild(_par);

			_ul = document.createElement("ul");
			_con.appendChild(_ul);
			
			_li = document.createElement("li");
			_ul.appendChild(_li);
			_li.innerHTML=_res.data[_Ncruce].consultas[0].nombre;
			
			_li = document.createElement("li");
			_ul.appendChild(_li);
			_li.innerHTML=_res.data[_Ncruce].consultas[1].nombre;
			
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

		_con=document.getElementById('consultas');
		//_con.innerHTML='';
		
		for(_idCon in _res.data){	
			
				_h3 = document.createElement("h3");
				_h3.innerHTML="Consulta: "+_res.data[_idCon].data.nombre;
				_con.appendChild(_h3);
				
				_par= document.createElement("p");
				_par.innerHTML=_res.data[_idCon].data.descripcion+'<br>';
				_par.innerHTML+=_res.data[_idCon].tipo;
				_con.appendChild(_par);		
			
			if(_res.data[_idCon].tipo=='Agrupación Automática'){
				
				_par= document.createElement("p");
				_par.innerHTML='Campo de agrupación: '+_res.data[_idCon].campo;
				if(_res.data[_idCon].corteCant>0){
					_par.innerHTML='<br>Cantidad de incidencias para valor de corte (<= agrupado como otros): '+_res.data[_idCon].corteCant+'<br>';
				}
				_con.appendChild(_par);
				
				for (_idGrupo in _res.data[_idCon].grupos.nombre) {
					
					_h4 = document.createElement("h4");
					_h4.innerHTML='Grupo: '+_res.data[_idCon].grupos.nombre[_idGrupo];
					_con.appendChild(_h4);						
				}

			}else{		

				for (_idGrupo in _res.data[_idCon].grupos) {
					
					
					_h4 = document.createElement("h4");
					_h4.innerHTML='Grupo: '+_res.data[_idCon].grupos[_idGrupo].data.nombre;
					_con.appendChild(_h4);
					
					_ul = document.createElement("ul");
					_con.appendChild(_ul);
					
					for(_idcaso in _res.data[_idCon].grupos[_idGrupo].casos){
						
						_estecaso=_res.data[_idCon].grupos[_idGrupo].casos[_idcaso];						
										
						//console.log(_estecaso);
												
						if(_estecaso.CampoDesc===null){
							
						}else{
							_li =document.createElement("li");
							
							_li.innerHTML='campo: '+_estecaso.campo;
							
							if(_estecaso.CampoDesc.descripcion!=undefined){
							 	_li.innerHTML+= " ( "+ _estecaso.CampoDesc.descripcion+" )";
							}
							_li.innerHTML+="<br>";
							
							if(_estecaso.especial=='otros'){
								_li.innerHTML+= "se incluyen los registros que no son incluidos en los otros casos definidos";
							}else if((_estecaso.valor != null)){
								_li.innerHTML+= ", criterio:  = " + _estecaso.valor;
							}else if((_estecaso.valorno != null)){
								_li.innerHTML+= ", criterio:  != " + _estecaso.valorno;
							}else{
								if((_estecaso.valmin != null)){
									_li.innerHTML+= ", criterio: >= " + _estecaso.valmin;
								}
								if((_estecaso.valmax != 'null')){
									_li.innerHTML+= ", criterio: <= " + _estecaso.valmax;
								}
							}
							_ul.appendChild(_li);
						}
						//console.log(_res.data[_idCon].grupos[_idGrupo].casos[_idcaso].CampoDesc);
				
					}
					
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



function ReScargarConsultas(){
		var _IDproy = '<?php echo $ID;?>';

		var parametros = {
			"id" : _IDproy,
			"funcion" : 'consultaConsultasResultados'
		};
		
		$.ajax({
				data:  parametros,
				url:   'cons_ajax.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					//console.log(_res);
					ReSconsultasCargadas(response);
					
				}
		});		
		
			
		var parametros = {
			"id" : _IDproy,
			"funcion" : 'consultaConsultasResultadosCrucesAuto'
		};
		
		$.ajax({
				data:  parametros,
				url:   'cons_ajax.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					ReSconsultasCrucesCargados(response);					
				}
		});	
			
}



function ReSconsultasCrucesCargados(response){	
	var _res = $.parseJSON(response);	
	

	if(_res.res=='exito'){
		
		_con=document.getElementById('resultados');
		
		console.log(_res.data); 
		
		for(_Ncruce in _res.data){
			
			
			_h3 = document.createElement("h3");
			_h3.innerHTML=_res.data[_Ncruce].data.nombre;
			_con.appendChild(_h3);
			
			_par = document.createElement("p");
			_par.innerHTML=_res.data[_Ncruce].data.nombre;
			_con.appendChild(_par);
			_par=_res.data[_Ncruce].data.descripcion;
			
			_tab = document.createElement("table");
			_con.appendChild(_tab);	
			
			_tr = document.createElement("tr");
			_td = document.createElement("th");
			_td.innerHTML=" \ ";
			_tr.appendChild(_td);
			
			for (_gNum in _res.data[_Ncruce].consultas[0].grupos.nombre){
				_gNom= _res.data[_Ncruce].consultas[0].grupos.nombre[_gNum];
				_td = document.createElement("th");
				_td.innerHTML=_gNom;
				_tr.appendChild(_td);
			}
			_tab.appendChild(_tr);
			
			
			for (_gbNum in _res.data[_Ncruce].consultas[1].grupos.nombre){
				
				_gbNom= _res.data[_Ncruce].consultas[1].grupos.nombre[_gbNum];
				
				_tr = document.createElement("tr");
				_td = document.createElement("th");
				_td.innerHTML=_gbNom;
				_tr.appendChild(_td);
				
				for (_gaNum in _res.data[_Ncruce].consultas[0].grupos.nombre){	
					_gaNom= _res.data[_Ncruce].consultas[0].grupos.nombre[_gaNum];
					_td = document.createElement("td");
					_cant=0;
					if(typeof _res.data[_Ncruce].matriz[_gaNom][_gbNom] != 'undefined'){
						_cant=_res.data[_Ncruce].matriz[_gaNom][_gbNom];
					}
					
					_td.innerHTML=_cant;
					_tr.appendChild(_td);				
				}

				_tab.appendChild(_tr);
			}		
			
		}
		
	
					
	}else if(_res.res=='err'){		
		_carga.innerHTML=_carga.innerHTML+' error al cargar el archivo.';
		for(i=0;i<_res.tx.length; i++){
			_carga.innerHTML=_carga.innerHTML+' '+_res.tx[i];
		}		
	}	
}

function ReSconsultasCargadas(response){	
	var _res = $.parseJSON(response);	
		
	if(_res.res=='exito'){
		
		console.log(_res.data); 

		_con=document.getElementById('resultados');
		//_con.innerHTML='';
		
		for(_idCon in _res.data){		
			
			
			
			if(_res.data[_idCon].tipo=='Agrupación Automática'){
				
				_h3 = document.createElement("h3");
				_con.appendChild(_h3);
				_h3.innerHTML="Consulta: "+_res.data[_idCon].data.nombre;
				
				_Reg=_res.data[_idCon].totalcant;
				_Inc=_res.data[_idCon].totalcant;
				
				_par = document.createElement("p");
				_con.appendChild(_par);
				_par.innerHTML="incidencias totales para todos los grupos: "+_res.data[_idCon].totalcant;
				_par = document.createElement("p");
				_con.appendChild(_par);
				_par.innerHTML="Encuestas realizadas: "+_Reg;
							
				_tab = document.createElement("table");
				_con.appendChild(_tab);			
			
				
				_tr = document.createElement("tr");
				_tab.appendChild(_tr);
				_tr.innerHTML="<th>Grupos</th><th>incidencias</th><th>incidencias / incidencias x 100</th><th>incidencias / encuestas x 100</th>";

					
				for (_idGrupo in _res.data[_idCon].grupos.nombre) {
					_tr = document.createElement("tr");
					_tab.appendChild(_tr);			
					
					_Cant=_res.data[_idCon].grupos.cant[_idGrupo];
					_Pinc=Math.round(100/_Inc*_Cant);
					_Preg=Math.round(100/_Reg*_Cant);
					
					
					_td = document.createElement("td");
					_tr.appendChild(_td);
					_td.innerHTML=_res.data[_idCon].grupos.nombre[_idGrupo];
					
					_td = document.createElement("td");
					_tr.appendChild(_td);
					_td.innerHTML=_Cant;
					
					_td = document.createElement("td");
					_tr.appendChild(_td);
					_td.innerHTML=_Pinc;	
							
					_td = document.createElement("td");
					_tr.appendChild(_td);
					_td.innerHTML=_Preg;					
					//_td.setAttribute('title',_res.data[_idCon].grupos[_idGrupo].data.descripcion);
					
				}
				

			}else{	
				
				
				_Inc=_res.data[_idCon].totalcant;
				_Reg=_res.data[_idCon].registros;
				
				
				_h3 = document.createElement("h3");
				_con.appendChild(_h3);
				_h3.innerHTML="Consulta: "+_res.data[_idCon].data.nombre;
				
				_par = document.createElement("p");
				_con.appendChild(_par);
				_par.innerHTML="incidencias totales para todos los grupos: "+_res.data[_idCon].totalcant;
				_par = document.createElement("p");
				_con.appendChild(_par);
				_par.innerHTML="Encuestas realizadas: "+_Reg;
							
				_tab = document.createElement("table");
				_con.appendChild(_tab);			
			
				_tr = document.createElement("tr");
				_tab.appendChild(_tr);
				_tr.innerHTML="<th>Grupos</th><th>incidencias</th><th>incidencias / incidencias x 100</th><th>incidencias / encuestas x 100</th>";


				for (_idGrupo in _res.data[_idCon].grupos) {
					_tr = document.createElement("tr");
					_tab.appendChild(_tr);		
					
					_Cant=_res.data[_idCon].grupos[_idGrupo].cant;
					_Pinc=Math.round(100/_Inc*_Cant);
					_Preg=Math.round(100/_Reg*_Cant);
					
					_td = document.createElement("th");
					_tr.appendChild(_td);
					_td.innerHTML=_res.data[_idCon].grupos[_idGrupo].data.nombre;
					
					_td = document.createElement("td");
					_tr.appendChild(_td);
					_td.innerHTML=_Cant;
					
					_td = document.createElement("td");
					_tr.appendChild(_td);
					_td.innerHTML=_Pinc;	
												
					_td = document.createElement("td");
					_tr.appendChild(_td);
					_td.innerHTML=_Preg;						
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

      muestraContenidos();
      muestraDepuracion();
      muestraContenidosDepurados();
      cargarConsultas();
      ReScargarConsultas();
</script>
</body>
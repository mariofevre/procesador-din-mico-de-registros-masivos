<!DOCTYPE html>
<head>
	 
	   <title>ANALiSIS ESTADíSTICO</title>
	   <link href="./img/pdfajpg.ico" type="image/x-icon" rel="shortcut icon">
	   <link rel="stylesheet" type="text/css" href="./css/estadisticas.css" />   
	   
	<style>
	#carga{
		vertical-align:middle;
		border:1px solid #000;
	}
	#carga div,#carga img{
		vertical-align:middle;
	}
	</style>
	
</head>
<body>
	<script language="javascript" type="text/javascript" src="./jscripts/jquery/jquery-1.8.2.js"></script>	
	
	
<?php

include('./includes/encabezado.php');
include('./cons_general.php');

if(!isset($_GET['id'])){$_GET['id']=1;}
$ID=$_GET['id'];

echo "
<h2><a href='./indice_consultas.php?id=$ID'>ir a consultas</a></h2>
<h2><a href='./form_expansion.php?id=$ID'>configurar factor de expansión</a></h2>
";

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
$Acciones=$Result['Acciones'];
	echo "<table>";
	
		echo "<tr class='TOT'>";
		echo "<th>";
				echo "tabla";
			echo "</th>";
			echo "<th>";
				echo "columnas";
			echo "</th>";
		echo "<th>";
				echo "registros";
			echo "</th>";
		echo "<th>";
				echo "cargar csv";
			echo "</th>";	
		echo "</tr>";

			echo "<tr class='fma'>";
				echo "<th>$tabla</th>";
				echo "<td>".$Contenidos[$tabla]['columnas']."</td>";
				echo "<td>".$Contenidos[$tabla]['registros']."</td>";
				echo "<td><input id='inputFile' type='file' name='upload' onchange='cargarCSV(\"inputFile\");'></td>";
			echo "</tr>";		
	
echo "</table>";

echo "
<table id='tablacont'></table>

<a href='./form_tabla.php?tabla=DEPacciones&campofijo=id_p_PROproyectos&campofijo_c=".$ID."&salida=indice_contenidos&salidaid=".$ID."'> añadir criterio de depuaración</a>

<table id='tabladepur'></table>

<table id='tablacontdepur'></table>
";

echo "<div id='carga'>";
echo "</div>";



?>

<script type="text/javascript">        
            window.scrollTo(0,'<?php echo $_GET['y'];?>');     
</script>
 


<script type="text/javascript">  
	
function cargarCSV(_idinputF) {
	var files = document.getElementById(_idinputF).files;		
	for (i = 0; i < files.length; i++) {
    
		var parametros = new FormData();
		parametros.append("upload",files[i]);
		parametros.append("id",'<?php echo $ID;?>');
		
		cargando(files[i].name);
		
		//Llamamos a los puntos de la actividad
		$.ajax({
				data:  parametros,
				url:   'ed_cargar_csv.php',
				type:  'post',
				processData: false, 
				contentType: false,
				success:  function (response) {
					archivoSubido(response);
				}
		});
	}
}


function archivoSubido(response){	
	var _res = $.parseJSON(response);
	
	if(_res.res=='exito'){
		_carga=document.getElementById('cargando'+_res.data.nombreorig);
		_carga.parentNode.removeChild(_carga);		
	var div = document.createElement("div");
		console.log(_res);	
		cargarTabla();		
	}else if(_res.res=='err'){		
		_carga=document.getElementById('cargando'+_res.data.nombreorig);
		_carga.innerHTML=_carga.innerHTML+' error al cargar el archivo.';
		for(i=0;i<_res.tx.length; i++){
			_carga.innerHTML=_carga.innerHTML+' '+_res.tx[i];
		}		
	}	
}

function cargando(_nombre){		
	var div = document.createElement("div");
	div.setAttribute('id','cargando'+_nombre);
	div.innerHTML=' cargando archivo '+_nombre+' ...';
	document.getElementById('carga').appendChild(div);		
}

function cargarTabla(){
	var parametros = {
		"id" : '<?php echo $ID;?>'
	};
	cargandoT('Tabla','Tabla');	
	$.ajax({
		data:  parametros,
		url:   'ed_cargar_tabla.php',
		type:  'post',
		success:  function (response){
			var _res = $.parseJSON(response);
			console.log(_res);
			tablaCargada(response);
			//procesarSigReticulaResutado(response);
		}
	});	
}

function cargandoT(_Tx,_id){			
	console.log(_id);
	_contenedor=document.getElementById(_id);		
	var div = document.createElement("div");
	div.setAttribute('id','cargandoT'+_id);
	div.setAttribute('contenido',_Tx);
	div.innerHTML=' cargando:  '+_Tx;
	_img=document.createElement('img');
	_img.setAttribute('src','./img/cargando.gif');	
	div.appendChild(_img);
	_contenedor.parentNode.insertBefore(div,_contenedor);
}

function cargadoT(_id){		
	console.log(_id);
	div = document.getElementById('cargandoT'+_id);
	_txt=div.getAttribute('contenido');
	div.innerHTML="<h2>"+_txt+"</h2>";		
}

function tablaCargada(response){	
	var _res = $.parseJSON(response);
	
	if(_res.res=='exito'){
		//_carga.innerHTML='Tabla Cargada, '+ _res.data.campos+' campos. ' + _res.data.registros + ' registros.';
		muestraDepuracion();				
	}else if(_res.res=='err'){		
		_carga.innerHTML=_carga.innerHTML+' error al cargar el archivo.';
		for(i=0;i<_res.tx.length; i++){
			_carga.innerHTML=_carga.innerHTML+' '+_res.tx[i];
		}		
	}	
}

function muestraContenidos(){
	var parametros = {
		"id" : '<?php echo $ID;?>',
		"funcion" : 'consultaContenidos'
	};
	var _Cid='tablacont';
	cargandoT('Muestras de Contenidos Originales',_Cid);
	$.ajax({
		data:  parametros,
		url:   'cons_ajax.php',
		type:  'post',
		success:  function (response){
			var _res = $.parseJSON(response);
			cargadoT(_Cid);
			console.log(_res);
			contenidosCargados(response);
		}
	});			
}

function contenidosCargados(response){	
	var _res = $.parseJSON(response);	
	if(_res.res=='exito'){
		_dep=document.getElementById('tablacont');
		_dep.innerHTML='';
		
		_tr = document.createElement("tr");
		for (_key in _res.data){
			_th = document.createElement("th");
			_th.innerHTML=_key;
			_tr.appendChild(_th);
		}
		_dep.appendChild(_tr);

		_tr = document.createElement("tr");
		
		for (_key in _res.data) {
			_td = document.createElement("td");
			for(i=0;i<_res.data[_key].length&&i<10; i++){	
		 // console.log(_key, _res.data[i][_key]);
		 	_span = document.createElement("div");
		 	_span.setAttribute('class','elem');
		 	_tx=_res.data[_key][i]['dato'];
		 	_tx=_tx.substr(0, 45);
		 	_span.innerHTML=_tx;
		 	_td.appendChild(_span);			
			}
			_tr.appendChild(_td);
		}
		
		_dep.appendChild(_tr);
		
					
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
		var _Cid='tabladepur';
		cargandoT('Criterios de Depuración Definidos',_Cid);
		//Llamamos a los puntos de la actividad
		$.ajax({
				data:  parametros,
				url:   'cons_ajax.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					cargadoT(_Cid);
					depuracionCargada(response);
					//procesarSigReticulaResutado(response);
				}
		});			
}
function depuracionCargada(response){	
	var _res = $.parseJSON(response);
	
	if(_res.res=='exito'){
		_dep=document.getElementById('tabladepur');
		_dep.innerHTML='';
		
		
		_tr = document.createElement("tr");
		for (_key in _res.data[0]){
			_th = document.createElement("th");
			_th.innerHTML=_key;
			_tr.appendChild(_th);
		}
		_dep.appendChild(_tr);
		
		for(i=0;i<_res.data.length; i++){		
			
			_tr = document.createElement("tr");
			
			for (_key in _res.data[i]) {
				
			 // console.log(_key, _res.data[i][_key]);

				_td = document.createElement("td");
				
				if(_key=='id'){	
					_link = document.createElement("a");
					_link.setAttribute('href','./form_tabla.php?salida=indice_contenidos&salidaid=<?php echo $ID;?>&accion=cambia&tabla=DEPacciones&id='+_res.data[i][_key]);
					_link.innerHTML=_res.data[i][_key];
					_td.appendChild(_link);
				}else{
					_td.innerHTML=_res.data[i][_key];
				}
				
				_tr.appendChild(_td);
			}
			_dep.appendChild(_tr);
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
		
		var _Cid='tablacontdepur';
		cargandoT('Contenidos Obtenidos tras la depuración',_Cid);
		$.ajax({
			data:  parametros,
			url:   'cons_ajax.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				cargadoT(_Cid);
				console.log('consultaResumen:');
				console.log(_res);
				contenidosDepuradosCargados(response);
			}
		});		
}

function contenidosDepuradosCargados(response){	
	var _res = $.parseJSON(response);
	
	if(_res.res=='exito'){
		_dep=document.getElementById('tablacontdepur');
		_dep.innerHTML='';
		
		_tr = document.createElement("tr");
		for (_key in _res.data){
			if(_res.data[_key].visible=='1'){
			_th = document.createElement("th");
			_th.setAttribute('id','th'+_key);
			_th.innerHTML=_key;
			_th.title=_res.data[_key].nombre;
			_linkth= document.createElement("a");
			_linkth.setAttribute("onclick","apagarCampoDepurado('"+_key+"')");
			_linkth.innerHTML='x';
			_th.appendChild(_linkth);
			_tr.appendChild(_th);
			}
		}
		_dep.appendChild(_tr);

		_tr = document.createElement("tr");
		
		for (_key in _res.data) {
			if(_res.data[_key].visible=='1'){
				_td = document.createElement("td");
				_td.setAttribute('id','td'+_key);
				for(i=0;i<_res.data[_key].valores.length&&i<300; i++){	
			 // console.log(_key, _res.data[i][_key]);
			 	_span = document.createElement("div");
			 	_span.setAttribute('class','elem');
			 	_tx=_res.data[_key].valores[i]['dato'];
			 	_tx=_tx.substr(0, 85);
			 	_span.innerHTML=_tx;
			 	_link=document.createElement("a");
			 	
			 	_unid=_res.data[_key].valores[i]['instancias'][0]['id'];
			 	_href="form_auditar.php?accion=cambia&tabla=<?php echo $tabla;?>&id="+_unid;
			 	_link.setAttribute('href',_href);
			 	_link.innerHTML=_unid+' ('+ _res.data[_key].valores[i]['cantidad'] +')';
			 	_span.appendChild(_link);			
			 	_td.appendChild(_span);			
				}
				_tr.appendChild(_td);
			}
		}
		
		_dep.appendChild(_tr);
		
					
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
	//var _Cid='tcontO';
	//cargandoT('Muestras de Contenidos Originales',_Cid);
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
				//cargado(_Cid);
				console.log(_res);				
			}
	});			
}

</script>

<script type='text/javascript'>
      muestraContenidos();
      muestraDepuracion();
      muestraContenidosDepurados();
</script>

</body>
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
<h2><a href='./indice_consultas.php?id=$ID'>configurar factor de expansión</a></h2>
";

//$Result=consultaPropEstructura('');

//$Contenidos=$Result['Contenidos'];
/*
$Result=consultaProyectos();
echo "<pre>";print_r($Result);echo "</pre>";
$Result=$Result[$ID];
$stringid=str_pad($ID,5,"0",STR_PAD_LEFT);
$tabla="CONT_".$stringid;

$Estrategias=$Result['Estrategias'];
$Areas=$Result['Areas'];
$Ambitos=$Result['Ambitos'];
$Acciones=$Result['Acciones'];
*/
echo "
<table id='tablacont'></table>

<a href='./form_tabla.php?tabla=DEPacciones&campofijo=id_p_PROproyectos&campofijo_c=".$ID."&salida=indice_contenidos&salidaid=".$ID."'> añadir criterio de depuaración</a>

<table id='tabladepur'></table>

<table id='tablacontdepur'></table>
";

echo "<div id='carga'>";


echo "</div>";

if(!isset($_GET['y'])){$_GET['y']=0;}


?>

<script type="text/javascript">        
            window.scrollTo(0,'<?php echo $_GET['y'];?>');     
</script>
 


<script type="text/javascript">  

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

function muestraContenidosDepurados(){
		var parametros = {
			"id" : '<?php echo $ID;?>',
			"funcion" : 'consultaExpansion'
		};
		
		var _Cid='tablacontdepur';
		cargandoT('Configuración de expansión',_Cid);
		$.ajax({
			data:  parametros,
			url:   'cons_ajax.php',
			type:  'post',
			success:  function (response){
				var _res = $.parseJSON(response);
				cargadoT(_Cid);
				
				if(_res.data.estado=='sin expansion'){
					expandirBoton();
				}else				
				if(_res.data.estado=='campo sin cargar'){	
					selectorCampos(_res.data.configuracion.iddef,_res.data.campos);
				}else{
					console.log(_res);
					crearTablaExp(_res);
				}
				console.log('consultaResumen:');
				console.log(_res);
				//contenidosDepuradosCargados(response);
			}
		});		
}

function expandirBoton(){
	_sel=document.createElement('button');
	_sel.setAttribute('onclick','creaDef()');
	_sel.innerHTML='Crear criterio de expansión';
	document.body.appendChild(_sel);
}

function creaDef(_idCampo){
	var parametros = {
		"id_p_PROproyectos" : '<?php echo $ID;?>',
		"tabla" : 'EXPdefinicion',
		"accion" : 'agrega',
	};
	
	$.ajax({
		data:  parametros,
		url:   'ed_agrega.php',
		type:  'post',
		success:  function (response){
			window.location.reload();
		}
	});
}



function selectorCampos(_iddef,_campos){
	_sel=document.createElement('select');
	_sel.setAttribute('onchange','eligeCampo('+_iddef+',this.value)');
	for(_nn in _campos){
		_opt=document.createElement('option');
		_opt.value=_nn;
		_opt.innerHTML=_campos[_nn].Comment;
		_sel.appendChild(_opt);
	}
	document.body.appendChild(_sel);
}

function eligeCampo(_iddef,_idCampo){
		var _iddef=_iddef;
		var parametros = {
			"id" : _iddef,
			"campoId" : _idCampo,
			"tabla" : 'EXPdefinicion',
			"accion" : 'cambia',
		};
		
		var _Cid='tablacontdepur';
		cargandoT('Configuración de expansión',_Cid);
		$.ajax({
			data:  parametros,
			url:   'ed_cambia.php',
			type:  'post',
			success:  function (response){
				window.location.reload();
			}
		});		
}

function eligeCampo(_iddef,_idCampo){
		var _iddef=_iddef;
		var parametros = {
			"id" : _iddef,
			"campoId" : _idCampo,
			"tabla" : 'EXPdefinicion',
			"accion" : 'cambia',
		};
		
		var _Cid='tablacontdepur';
		cargandoT('Configuración de expansión',_Cid);
		$.ajax({
			data:  parametros,
			url:   'ed_cambia.php',
			type:  'post',
			success:  function (response){
				window.location.reload();
			}
		});		
}

var _cantTot=0;
var _cpo = '';//campo de referencia
var _iddef='';

function crearTablaExp(_res){
	var _res = _res;
	_cpo = _res.data.configuracion.campoId;
	_tabla=document.createElement('table');
	_tabla.setAttribute('id','tablaExp');
	_iddef=_res.data.configuracion.iddef;
	document.body.appendChild(_tabla);
	
	for(_nn in _res.data.valores[_cpo].valores){		
		_tr=document.createElement('tr');
		_tr.setAttribute('valor',_res.data.valores[_cpo].valores[_nn].dato);
		_tabla.appendChild(_tr);
		
		
		_td=document.createElement('td');
		_tr.appendChild(_td);
		_td.innerHTML=_res.data.valores[_cpo].valores[_nn].dato;
		
		_td=document.createElement('td');
		_tr.appendChild(_td);
		
		_td.innerHTML=_res.data.valores[_cpo].valores[_nn].cantidad;
		_cantTot+=_res.data.valores[_cpo].valores[_nn].cantidad;
		
		_td=document.createElement('td');
		_td.setAttribute('class','ref');
		_tr.appendChild(_td);
		_in=document.createElement('input');
		_in.setAttribute('onkeypress','edReg(event,this,'+_iddef+',"EXPdefinicion")');
		_td.appendChild(_in);		
		
	}	
	
	_tr=document.createElement('tr');
	_tr.setAttribute('valor','total');
	_tabla.appendChild(_tr);
	
		_td=document.createElement('th');
		_tr.appendChild(_td);
		_td.innerHTML='Total';
		
		_td=document.createElement('th');
		_tr.appendChild(_td);
		_td.innerHTML=_cantTot;
		
	cargarSeries(_res);
}


var _Serie = {};//array de serie de datos


var _pobTot=0;
function cargarSeries(_res){
	
	if(_res.data.estado!='serie sin cargar'){
		
		for(_nn in _res.data.valores[_res.data.configuracion.campoId].valores){
			_nom=_res.data.valores[_res.data.configuracion.campoId].valores[_nn].dato;
			_Serie[_nom] = 0;
		}
				
		_serieref=JSON.parse(_res.data.configuracion.serieref);
		
		//console.log(_serieref);
		for(_nn in _serieref){
			console.log(_nn);
			_Serie[_nn] = _serieref[_nn];
			_rep=document.body.querySelectorAll("#tablaExp > tr[valor='"+_nn+"'] > td.ref")[0];			
			_rep.innerHTML="<span valor='+_serieref[_nn]+' ondblclick='ainput(this);'>"+_serieref[_nn]+"</span>";	
			_pobTot+=parseInt(_serieref[_nn]);	
		}
		
		_td=document.createElement('th');
		_td.innerHTML=_pobTot;
		_tr=document.body.querySelectorAll("#tablaExp > tr[valor='total']")[0];	
		_tr.appendChild(_td);	
		
		cargarFactores(_res);
	}
}


function cargarFactores(_res){	
		_seriefact=_res.data.seriefact;
		//console.log(_serieref);
		for(_nn in _seriefact){
			console.log(_nn);
			_td=document.createElement('td');
			_td.innerHTML=_seriefact[_nn];
			_tr=document.body.querySelectorAll("#tablaExp > tr[valor='"+_nn+"']")[0];		
			_tr.appendChild(_td);
		}

		_tr=document.body.querySelectorAll("#tablaExp > tr[valor='total']")[0];	
		_td=document.createElement('th');
		_td.innerHTML=_tr.childNodes[2].innerHTML/_tr.childNodes[1].innerHTML;
		
		_tr.appendChild(_td);	
}

function ainput(_this){
	_pp=_this.parentNode;
	_in=document.createElement('input');
	_in.setAttribute('onkeypress','edReg(event,this,'+_iddef+',"EXPdefinicion")');
	_in.value=_this.innerHTML;
	_pp.appendChild(_in);
	_pp.removeChild(_this);	

}

function edReg(_event,_this,_id,_tabla){
	if(_event.keyCode==13){
		var _campo = _this.getAttribute('name');
		_val=_this.parentNode.parentNode.getAttribute('valor');
		_Serie[_val]=_this.value;
		var _this = _this;			
			_parametros={};
			_parametros["id"]=_id;
			_parametros["serieref"]=JSON.stringify(_Serie),
			_parametros["tabla"]=_tabla;
			_parametros["accion"]="cambia";

		$.ajax({
			data: _parametros,
			url:   'ed_cambia_serie.php',
			type:  'post',
			success:  function (response){
				//var _res = $.parseJSON(response);
				//console.log(_res);
				window.location.reload();
			}
		});			
	}
}

/*
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
			 	_href="form_auditar.php?accion=cambia&tabla=TABLA&id="+_unid;
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
*/
</script>

<script type='text/javascript'>
      muestraContenidosDepurados();
</script>

</body>
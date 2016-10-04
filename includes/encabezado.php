<?php 
	
	header("Cache-control: private");

	include('./includes/Settings.php');	
	
	session_start();

	//if($_SESSION["UsuarioI"]<1){
		//header('Location: ../login.php');
	//}
	
	include('./includes/mySqonect.php');	
	include_once('./includes/cadenas.php');				
	include_once('./includes/fechas.php');		

	$Base = $_SESSION["AppSettings"]->DATABASE_NAME;
?>

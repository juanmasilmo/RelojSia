<?php 
session_start();
$carpeta = explode("/", $_SERVER['PHP_SELF']);
$ruta = $_SERVER['DOCUMENT_ROOT'] . '/' . $carpeta[1] . '/';
include($ruta . "core/env.php");


//////para ver documentos
if(isset($_GET['documento']))
{
	$name = $_GET['documento'];
	$filename=DOCUMENTOS.$_GET['documento'];
	if (file_exists($filename)) {
		header('Content-type: application/force-download');
		header('Content-Disposition: attachment; filename='.$name);
		readfile($filename);
	}else{
		echo "<script>alert('No se pudo recuperar el documento.');
		window.close();</script>";
	}
}


<?php

function conectar()
{
	global $con;
	
	$con = pg_connect("host=".$_SESSION['DB_HOST']." port=".$_SESSION['DB_PORT']." user=".$_SESSION['DB_USER']." password=".$_SESSION['DB_PASS']." dbname=".$_SESSION['DB_NAME']);
	
	if (!$con){
		echo "ERROR EN LA CONEXION CON LA BASE DE DATOS";
	}else {
		return $con;
	}
}

function desconectar()
{
	global $con;
	pg_close($con);
}
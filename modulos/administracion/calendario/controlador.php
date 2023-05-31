<?php

if (isset($_GET['f'])) {
  $function = $_GET['f'];
}else {
  $function = "";
}

session_start();
include("../../../inc/conexion.php");
$con = conectar();

if (function_exists($function)) {
  $function($con);
}else {
  echo "La funcion" . $function . "no exite...";
}


/**
 * Registros
 */
function getRegistros($con)
{
  $sql = "SELECT * FROM estados";
  $rs = pg_query($con, $sql);
  $res = pg_fetch_all($rs);

  echo json_encode($res);
  //return json_encode(['result' => $res]);
}
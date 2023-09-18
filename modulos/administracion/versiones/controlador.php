<?php

session_start();
include("../../../inc/conexion.php");
$con = conectar();

// verifico si viene el parametro
if (isset($_GET['f']))
  $function = $_GET["f"];
else
  $function = "";

// verifico si existe la funcion
if (function_exists($function)) {
  $function($con);
}else {
  echo "la funcion " .$function. " no existe";
}

function editar($con){

  $id = $_GET['id'];
  $tag = $_POST['tag'];
  $descripcion = $_POST['descripcion'];
  $usuario_abm = $_SESSION['usuario'];

  if ($id > 0) {
    //update
   $mensaje = '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡OK!</strong> El registro se MODIFIC&Oacute; con &eacute;xito.</div>';
   $sql = "UPDATE sistema_versiones SET descripcion = '$descripcion',tag = '$tag', usuario_abm = '$usuario_abm' WHERE id = $id";
 }else {
    // insert
   $mensaje = '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡OK!</strong> El registro se CRE&Oacute; con &eacute;xito.</div>';
   $sql = "INSERT INTO sistema_versiones (descripcion,tag,usuario_abm) VALUES ('$descripcion','$tag','$usuario_abm');";
 }

  //ejecuto la consulta
 if(pg_query($con,$sql)){
  echo $mensaje;
  echo "<script>listado();</script>";
}else {
  echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>No se pudo crear el registro</strong></div>';
}
}


function eliminar($con)
{
  $id = $_POST['id'];
  $sql = "DELETE FROM sistema_versiones WHERE id = $id";

  if (pg_query($con,$sql))
  {
    $mensaje = '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡OK!</strong> El registro se Eliminó con Exito.</div>';
    echo $mensaje;
  }else {
   echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>No se pudo Eliminar el registro</strong></div>';
 }
}

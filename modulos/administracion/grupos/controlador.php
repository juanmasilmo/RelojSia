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


function editar($con){
  $id = $_POST['id'];
  $descripcion = $_POST['descripcion'];  
  $usuario_abm=$_SESSION['username']; 

  if ($id > 0) {
//update
    $sql = "UPDATE grupos SET descripcion = '$descripcion', usuario_abm='$usuario_abm' WHERE id = $id";
    $mesaje="El registro se modificó con éxito";
  }else {
// insert
    $sql = "INSERT INTO grupos (descripcion,usuario_abm) VALUES ('$descripcion', '$usuario_abm');";
    $mesaje="El registro se creó con éxito";
  }

//ejecuto la consulta
  $sql=pg_query($con,$sql);
  if(pg_result_status($sql))  {

    echo '
    <div class="alert alert-primary animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> '.$mesaje.'
    </div>';
    echo "<script>listado();</script>";
    echo "<script>cerrar_formulario();</script>";
  }
  else{
    echo '
    <div class="alert alert-danger animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo crear el registro
    </div>';
  }
}


function eliminar($con){
  $id = $_POST['id'];
  $sql = "DELETE FROM grupos WHERE id = ".$id;
  $res=@pg_query($con,$sql);
  if ($res === false) {
    echo '
    <div class="alert alert-danger animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo eliminar el registro
    </div>';
  }
  else{
    echo '
    <div class="alert alert-primary animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> El registro se eliminó con éxito
    </div>';
  }

}
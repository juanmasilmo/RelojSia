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


function cambiar_estado($con)
{
  $id = $_POST['id'];
  $sql = "SELECT estado FROM dependencias WHERE id = " . $id;
  $rs = pg_query($con, $sql);
  $row = pg_fetch_array($rs);

  if (!empty($row)) {
    if ($row['estado'] > 0) {
      $sql = "UPDATE dependencias SET estado = 0 WHERE id = " . $id;
    } else {
      $sql = "UPDATE dependencias SET estado = 1 WHERE id = " . $id;
    }
    pg_query($con, $sql);
     echo '
    <div class="alert alert-primary animated--grow-in" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se modificó con éxito</div>';
  }
}




function editar($con){

  $descripcion = $_POST['descripcion'];    
  $id_localidad = $_POST['id_localidad'];
  $padre = $_POST['padre'];
  $direccion = $_POST['direccion'];
  $usuario_abm=$_SESSION['username']; 

  $id=$_POST['id'];

  if ($id > 0) {
//update
    $sql = "update dependencias set descripcion='".$descripcion."', id_localidad=".$id_localidad.",direccion='".$direccion ."', padre=".$padre.", usuario_abm='".$usuario_abm."' where id=".$id;
    $mesaje="El registro se modificó con éxito";
  }else {
// insert
    $sql = "INSERT INTO dependencias(descripcion,id_localidad,direccion,padre,usuario_abm) VALUES ('".$descripcion."',".$id_localidad.",'".$direccion."',".$padre.",'".$usuario_abm."');";
    $mesaje="El registro se creó con éxito";
  }

//ejecuto la consulta
  $sql=pg_query($con,$sql);
  if(pg_result_status($sql))  {

    echo '
    <div class="alert alert-primary animated--grow-in" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
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

?>
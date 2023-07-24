<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar();

if (isset($_GET['f'])) {
  $function = $_GET['f'];
}else {
  $function = "";
}


if (function_exists($function)) {
  $function($con);
}else {
  echo "La funcion" . $function . "no exite...";
}


function cambiar_estado($con)
{
  $id = $_POST['id'];
  $sql = "SELECT estado FROM articulos WHERE id = " . $id;
  $rs = pg_query($con, $sql);
  $row = pg_fetch_array($rs);

  if (!empty($row)) {
    if ($row['estado'] > 0) {
      $sql = "UPDATE articulos SET estado = 0 WHERE id = " . $id;
    } else {
      $sql = "UPDATE articulos SET estado = 1 WHERE id = " . $id;
    }
    pg_query($con, $sql);
    echo '
    <div class="alert alert-primary animated--grow-in" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se modificó con éxito</div>';
  }
}




function editar($con){

  if($_POST['id_leu']=='')
    $id_leu='null';
  else
    $id_leu=$_POST['id_leu'];

  $nro_articulo=$_POST['nro_articulo'];
  $inciso=$_POST['inciso'];
  $descripcion=$_POST['descripcion'];
  if($_POST['cantidad_mensual']=='')
    $cantidad_mensual='null';
  else
    $cantidad_mensual=$_POST['cantidad_mensual'];

  if($_POST['cantitad_anual']=='')
    $cantitad_anual='null';
  else
    $cantitad_anual=$_POST['cantitad_anual'];

  $observacion= $_POST['observacion'];
  $inasistencias= $_POST['inasistencias'];
  $licencias= $_POST['licencias'];
  $retiro= $_POST['retiro'];
  $tardanza = $_POST['tardanza'];
  $excluye_feria = $_POST['excluye_feria'];
  $tipo_licencias = $_POST['tipo_licencias'];
  $sin_fecha_fin = $_POST['sin_fecha_fin'];
  $cobra_presentismo=$_POST['cobra_presentismo'];
  $usuario_abm=$_SESSION['username'];
  $id=$_POST['id'];

  if ($id > 0) {
//update
    $sql = "update articulos set id_leu=".$id_leu.",nro_articulo='".$nro_articulo."', inciso='".$inciso."',descripcion='".$descripcion ."', cantidad_mensual=".$cantidad_mensual.",cantitad_anual=".$cantitad_anual.",observacion='".$observacion."',inasistencias=".$inasistencias.",licencias=".$licencias.",retiro=".$retiro.",tardanza=".$tardanza.",excluye_feria=".$excluye_feria.",tipo_licencias='".$tipo_licencias."',sin_fecha_fin=".$sin_fecha_fin.",cobra_presentismo=".$cobra_presentismo.",usuario_abm='".$usuario_abm."' where id=".$id;
    $mesaje="El registro se modificó con éxito";
  }else {
// insert
    $sql = "INSERT INTO articulos(id_leu,nro_articulo,inciso,descripcion,cantidad_mensual,cantitad_anual,observacion,inasistencias,licencias,retiro,tardanza,excluye_feria,tipo_licencias,sin_fecha_fin,cobra_presentismo,usuario_abm) VALUES (".$id_leu.",'".$nro_articulo."','".$inciso ."','".$descripcion."',".$cantidad_mensual.",".$cantitad_anual.",'".$observacion."',".$inasistencias.",".$licencias.",".$retiro.",".$tardanza.",".$excluye_feria.",'".$tipo_licencias."',".$sin_fecha_fin.",".$cobra_presentismo.",'".$usuario_abm."')";
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
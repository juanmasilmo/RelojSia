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

  $apellido = $_POST['apellido'];    
  $nombres = $_POST['nombres'];
  $legajo = $_POST['legajo'];
  $correo = $_POST['correo'];
  $id_dependencia = $_POST['id_dependencia'];
  $id_leu = $_POST['id_leu'];
  $nro_documento = $_POST['nro_documento'];
  $fecha_nacimiento = $_POST['fecha_nacimiento'];
  $domicilio = $_POST['domicilio'];
  $id_categoria = $_POST['id_categoria'];  
  $usuario_abm=$_SESSION['username'];
  $id=$_POST['id'];

  if ($id > 0) {
//update
    $sql = "update personas set apellido='".$apellido."', nombres='".$nombres."',legajo='".$legajo."',
    correo='".$correo."', id_dependencia=".$id_dependencia.", id_leu=".$id_leu.", nro_documento=".$nro_documento.", fecha_nacimiento='".$fecha_nacimiento."', domicilio='".$domicilio."',id_categoria=".$id_categoria.",  usuario_abm='".$usuario_abm."' where id=".$id;
    $mesaje="El registro se modificó con éxito";
  }else {
// insert
    $sql = "INSERT INTO personas(apellido,nombres,legajo,correo,id_dependencia,id_leu,nro_documento,fecha_nacimiento,domicilio,id_categoria,usuario_abm) VALUES ('".$apellido."','".$nombres."','".$legajo."','".$correo."',".$id_dependencia.",".$id_leu.",".$nro_documento.",'".$fecha_nacimiento."','".$domicilio."',".$id_categoria.",'".$usuario_abm."')";
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



function cambiar_estado($con)
{
  $id = $_POST['id'];
  $sql = "SELECT activo FROM personas WHERE id = " . $id;
  $rs = pg_query($con, $sql);
  $row = pg_fetch_array($rs);

  if (!empty($row)) {
    if ($row['activo'] > 0) {
      $sql = "UPDATE personas SET activo = 0 WHERE id = " . $id;
    } else {
      $sql = "UPDATE personas SET activo = 1 WHERE id = " . $id;
    }
    pg_query($con, $sql);
     echo '
    <div class="alert alert-primary animated--grow-in" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se modificó con éxito</div>';
  }
}



?>
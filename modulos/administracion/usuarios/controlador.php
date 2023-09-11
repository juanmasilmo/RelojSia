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
  $usuario = $_POST['usuario'];
  $nombre_apellido = $_POST['nombre_apellido'];
  $id_grupo = $_POST['id_grupo'];
  $clave = password_hash($usuario, PASSWORD_DEFAULT);
  $usuario_abm=$_SESSION['username']; 

  if ($id > 0) {
//update
    $sql = "UPDATE usuarios SET usuario = '$usuario',nombre_apellido='$nombre_apellido', id_grupo = $id_grupo, usuario_abm='$usuario_abm' WHERE id = $id";
    $mesaje="El registro se modificó con éxito";
  }else {
// insert

    $sql = "INSERT INTO usuarios (usuario,nombre_apellido,id_grupo,clave, usuario_abm) VALUES ('$usuario','$nombre_apellido',$id_grupo,'$clave', '$usuario_abm');";
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
    <div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo crear el registro
    </div>';
  }
}

function vincular_usuario_dependencia($con){
  $id_usuario = $_POST['id_usuario'];
  $id_dependencia = $_POST['id_dependencia'];   
  $usuario_abm=$_SESSION['username']; 

// insert

    $sql = "INSERT INTO usuario_dependencias VALUES ($id_usuario,$id_dependencia,'$usuario_abm');";
    $mesaje="El registro se creó con éxito";  

//ejecuto la consulta
  $sql=pg_query($con,$sql);
  if(pg_result_status($sql))  {

    echo '
    <div class="alert alert-primary animated--grow-in" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> '.$mesaje.'
    </div>';
    echo "<script>listado_dependencias_vinculadas();</script>";
  }
  else{
    echo '
    <div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo crear el registro
    </div>';
  }
}



function quitar_dependencia($con){
  
  $id_usuario = $_GET['id_usuario'];
  $id_dependencia = $_GET['id_dependencia'];   

  $sql = "DELETE FROM usuario_dependencias WHERE id_usuario = ".$id_usuario." AND id_dependencia=".$id_dependencia;
  $res=@pg_query($con,$sql);
  if ($res === false) {
    echo '
    <div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo eliminar el registro
    </div>';
  }
  else{
    echo '
    <div class="alert alert-primary" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se eliminó con éxito
    </div>';
  }
}





function eliminar($con){
  $id = $_POST['id'];
  $sql = "DELETE FROM usuarios WHERE id = ".$id;
  $res=@pg_query($con,$sql);
  if ($res === false) {
    echo '
    <div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo eliminar el registro
    </div>';
  }
  else{
    echo '
    <div class="alert alert-primary" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se eliminó con éxito
    </div>';
  }
}




function resetear_clave($con){

  $id = $_POST['id'];

  //obtengo el nombre de usuarios
  $sql = "SELECT usuario FROM usuarios WHERE id = ".$id;
  $rs = pg_query($con,$sql);
  $row = pg_fetch_array($rs);
  $usuario = $row['usuario'];

  // creo la clave por defecto (nombre de usuario)
  $clave = password_hash($usuario, PASSWORD_DEFAULT);
  $sql = "UPDATE usuarios SET clave='$clave' WHERE id=$id";
  $sql=pg_query($con,$sql);
  if(!pg_result_error($sql))
  {
    echo ' <div class="alert alert-primary" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> Clave del Usuario:<strong> '.$usuario.'</strong> fué Reseteada  con exito
    </div>';
  }
}


function bloquear_usuario($con){

  $id = $_POST['id'];

  //obtengo el nombre de usuarios
  $sql = "SELECT usuario FROM usuarios WHERE id = ".$id;
  $rs = pg_query($con,$sql);
  $row = pg_fetch_array($rs);
  $usuario = $row['usuario'];

  // creo la clave por defecto (nombre de usuario)
  $sql = "UPDATE usuarios SET estado=0 WHERE id=$id";
  $sql=pg_query($con,$sql);
  if(!pg_result_error($sql))
  {
    echo ' <div class="alert alert-primary" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El Usuario:<strong> '.$usuario.'</strong> fué bloqueado con exito </div>';
  }
}


function activar_usuario($con){

  $id = $_POST['id'];

  //obtengo el nombre de usuarios
  $sql = "SELECT usuario FROM usuarios WHERE id = ".$id;
  $rs = pg_query($con,$sql);
  $row = pg_fetch_array($rs);
  $usuario = $row['usuario'];

  // creo la clave por defecto (nombre de usuario)
  $sql = "UPDATE usuarios SET estado=1 WHERE id=$id";
  $sql=pg_query($con,$sql);
  if(!pg_result_error($sql))
  {
    echo ' <div class="alert alert-primary" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El Usuario:<strong> '.$usuario.'</strong> fué activado con exito </div>';
  }
}
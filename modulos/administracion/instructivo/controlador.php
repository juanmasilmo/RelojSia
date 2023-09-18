<?php
session_start();
include("../../../inc/conexion.php");
include_once("../../../core/env.php");
$con = conectar();

if (isset($_GET['f'])) {
  $function = $_GET['f'];
} else {
  $function = "";
}


if (function_exists($function)) {
  $function($con);
} else {
  echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡ADVERTENCIA!</strong> La funcion ' . $function . ' no exite...</div>';
}

function editar($con)
{

  $mensaje="";
  $titulo = $_POST['titulo'];
  if (!empty($_POST['descripcion'])) {
    $descripcion = $_POST['descripcion'];
  } else {
    $descripcion = NULL;
  }
  if (!empty($_POST['url'])) {
    $url = $_POST['url'];
  } else {
    $url = NULL;
  }
  $id = $_GET['id'];
  //$usuario_abm = $_SESSION['usuario'];

  /** editar */
  if ($id != 0) {
    $sql = "UPDATE instructivo SET titulo='$titulo', descripcion='$descripcion', url_video='$url' WHERE id = $id";
    $qry1 = pg_query($con, $sql);
  } else {
    $sql = "INSERT INTO instructivo (titulo, descripcion, url_video) VALUES ('$titulo','$descripcion','$url') Returning id";
    $qry1 = pg_query($con, $sql);
    $res = pg_fetch_array($qry1);
    $id = $res[0];
  }

  //controlo el documento
  $doc_name = $_FILES["documento"]["name"];
  // $docroot = $_SERVER['DOCUMENT_ROOT']."".$_SERVER['PHP_SELF'];
  // $docroot = explode(".php",$docroot);
  // $docroot = $docroot[0];
  // $docroot = explode("/",$docroot);
  // $ruta="";

  // for($i=0;$i<count($docroot)-4;$i++){
  //   $ruta=$ruta.$docroot[$i];
  //   $ruta=$ruta."/";
  // }

  // preparo el directorio para guardar el pdf
  $carpeta = DOCUMENTOS."reloj_instructivos/";

  if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true);
  }

  $target_dir = $carpeta;
  $target_file = $target_dir . basename($doc_name);
  $uploadOk = 1;

  $tipo_archivo = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  if($tipo_archivo!="")
  {
    $archivo_original = $target_dir.$id.".".$tipo_archivo;
        // si existe borro
    if (file_exists($archivo_original))
    {
      if (!unlink($archivo_original))
      {
        $mensaje.="El Documento no se pudo reemplazar.";
        $uploadOk = 0;
      }
      else
      {
        $uploadOk = 1;
      }
    }
        // verifico que el tamaño sea menor a 5MB
    if ($_FILES["documento"]["size"] > (5242880 * 10))
    {
      $mensaje.=" El Documento es muy Grande.";
      $uploadOk = 0;
    }
        // verifico que sea pdf
    $imageinfo = getimagesize($_FILES['documento']['tmp_name']);//consulto si no es una IMG con la extension modificada.
    if($tipo_archivo != "pdf" || !empty($imageinfo['mime']))
    {
      $mensaje.="Solo se permiten los Documento PDF.";
      $uploadOk = 0;
    }
        // verifico si se genero algun error en el camino
    if ($uploadOk == 0)
    {
      echo "<script>alertas('Error: ".$mensaje."');</script>";
    }
        // si todo fue correcto guardo el archivo en su directorio
        // guardo la url en la BD
    elseif (move_uploaded_file($_FILES["documento"]["tmp_name"], $archivo_original))
    { 
        //cambio la version de compresion del pdf
      $archivo_n=$archivo_original;
      $archivo_n=str_replace('.pdf','_xxx.pdf',$archivo_n);

      exec( $_SESSION['GS'].' -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile='.$archivo_n." ".$archivo_original);

      @unlink($archivo_original);
      @rename($archivo_n, $archivo_original);

    }
    $documento=$id.".".$tipo_archivo;

    $sql = "UPDATE instructivo SET url_documento = '".$documento."' WHERE id = ".$id;
    $qry3 = pg_query($con,$sql);

    if ($qry1){
      echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡OK!</strong> El registro se Guard&Oacute; con &eacute;xito.</div>';
    }else{
      echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡ADVERTENCIA!</strong> No se pudo Guardar el registro.</div>';
    }
  }
}

function eliminar($con)
{
  $id = $_POST['id'];
  $usuario_abm = $_SESSION['usuario'];

  $sql = "DELETE FROM instructivo where id=$id";
  if (pg_query($con, $sql)) {
    echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡OK!</strong> El registro se ELIMIN&Oacute; con &eacute;xito.</div>';
?>
    <script>
      listado();
    </script>
<?php
  } else {
    echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡ADVERTENCIA!</strong> No se pudo eliminar el registro.</div>';
  }
}



<?php
session_start();
include("../../../inc/conexion.php");
include_once("../../../core/env.php");
conectar();

$id = $_GET['id'];
if($id!=0){
  $query = pg_query($con, "SELECT * FROM instructivo WHERE id = $id");
  $row = pg_fetch_array($query);
  $id = $row['id'];
}

?>

<form  method="post" id="form" enctype="multipart/form-data" >

  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $id; ?>">

  <div class="row">

    <div class="col-md-6">

      <label for="titulo" class="form-label">Titulo
        <?php if($id!=0) echo "[".$row['titulo']."]";?></label>

      <input type="text" class="form-control" id="titulo" name="titulo" required minlength="3"
        value="<?php if($id!=0) echo $row['titulo'];?>">

      <div class="invalid-feedback">
        controlar el campo
      </div>
    </div>

    <div class="col-md-6">

      <label for="descripcion" class="form-label">Descripción
        <?php if($id!=0) echo "[".$row['descripcion']."]";?></label>

      <input type="text" class="form-control" id="descripcion" name="descripcion" required minlength="3"
        value="<?php if($id!=0) echo $row['descripcion'];?>">

      <div class="invalid-feedback">
        controlar el campo
      </div>
    </div>



        <div class="col-md-6">
          <p>(*) ID Video (Youtube)</p>
          <?php if($id != 0 and $row['url_video']){
              $onblur =  "onblur='getUrl();'";
              $url_video = $row['url_video'];
            }else {
              $onblur =  "";
              $url_video = "";
            }?>
          <input type="text" class="form-control" <?php echo $onblur?> name="url" id="url" value="<?php if($id!=0) echo $url_video;?>" >
        </div>
        <div class="col-md-6">
          <p>Subir Archivo:</p>
          <input class="form-control" type="file" id="documento" name="documento" accept=".pdf">
          <?php if(isset($row['url_documento'])) {?> <p><a class="btn btn-primary" target="_blank"
            href="<?php echo DOCUMENTOS.$row['url_documento'];?>" title="Ver Documentación"><span
            class="glyphicon glyphicon-file"></span> Documento</a></p>
            <?php } ?>
            <div id="mensaje_documento" style="display: none; color: red;"></div>
        </div>
          
    <div class="form-group col-md-12 col-lg-12 borde">
      <div id="youtube-player"></div>
    </div>
  </div>

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="controlar(<?php echo $id; ?>)">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>
<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar();
?>

<div class="content-popup">
  <div class="close"><a class="popup-cerrar" onclick="cerrar();">X</a></div>
  <div>
   <?php
   /**
    * [si recibe 0 crea y 1 edita]
    * @var id [integer]
    */
   $id=$_GET['id'];
   if($id > 0){
    $titulo="Editar";
    $sql=pg_query($con,"SELECT descripcion,tag FROM sistema_versiones WHERE id=".$id);
    $row=pg_fetch_array($sql);
  }
  else{
    $row="";
    $titulo="Nuevo";
  }
  ?>
  <h2>
    <?php
    echo $titulo;
    ?>
  </h2>

  <div class="form-group row">
    <div class="col-md-12" >
      <form method="post" id="formulario">
         <div class="form-group col-md-12 col-lg-12 borde">
      <div class="col-md-6">
        <p>(*) TAG:</p>
          <input class="form-control" type="text" name="tag" placeholder="TAG"  id="tag" value="<?php if(!empty($row)) echo $row['tag'];?>" />
           <div id="mensaje_tag"></div>
        </div>
      </div>
      <div class="form-group col-md-12 col-lg-12 borde">
        <div class="col-md-12">
        <p>(*) Descripción:</p>
          <textarea  class="form-control" type="text" name="descripcion" placeholder="Descripción"  id="descripcion" rows="5"><?php if(!empty($row)) echo $row['descripcion'];?></textarea>
          <div id="mensaje_descripcion"></div>
        </div>
      </div>
      </form>
    </div>
  </div>
  <button class="btn btn-primary"
  onclick="controlar(<?php echo $id; ?>)" value="Guardar">
  <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
</button>
</fieldset>
</div>
</div>

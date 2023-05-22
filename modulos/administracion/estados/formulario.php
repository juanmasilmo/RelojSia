<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if($_GET['id']!=0){
  $sql="SELECT * FROM estados WHERE id =".$_GET['id'];
  $sql=pg_query($con,$sql);
  $row=pg_fetch_array($sql);
}

?>
<form method="post" id="form" class="needs-validation">

  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="row">

    <div class="col-md-6">

      <label for="descripcion" class="form-label">Descripción
        <?php if($_GET['id']!=0) echo "[".$row['descripcion']."]";?></label>

      <input type="text" class="form-control" id="descripcion" name="descripcion" required minlength="3"
        value="<?php if($_GET['id']!=0) echo $row['descripcion'];?>">

      <div class="invalid-feedback">
        controlar el campo
      </div>
    </div>

    <div class="col-md-2">

      <label for="letra" class="form-label">Letra
        <?php if($_GET['id']!=0) echo "[".$row['letra']."]";?></label>

      <input type="text" class="form-control" id="letra" name="letra" required minlength="3"
        value="<?php if($_GET['id']!=0) echo $row['letra'];?>">

      <div class="invalid-feedback">
        controlar el campo
      </div>
    </div>

    <div class="col-md-3">
      <label for=" color" class="form-label">Color</label>
      <div class=" col-md-4" style="baground:<?php if($_GET['id']!=0) echo $row['color'];?>">
        <input type="color" class="form-control" id="color" name="color"
          value="<?php if($_GET['id']!=0) echo $row['color'];?>" required>
      </div>
      <div class="invalid-feedback">
        controlar el campo
      </div>
    </div>
  </div>

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>
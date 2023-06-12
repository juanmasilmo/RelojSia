<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if($_GET['id']!=0){
  $sql="select * from categorias where id=".$_GET['id'];
  $sql=pg_query($con,$sql);
  $row=pg_fetch_array($sql);  
}
?>
<form method="post" id="form" class="row needs-validation" >
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-5 position-relative">
    <label for="descripcion" class="form-label">Descripción <?php if($_GET['id']!=0) echo "[".$row['descripcion']."]";?></label>
    <input type="text" class="form-control" id="descripcion" name="descripcion" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['descripcion'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  
 
   <div class="col-md-2 position-relative">
    <label for="cod_categoria" class="form-label">Codigo<?php if($_GET['id']!=0) echo "[".$row['cod_categoria']."]";?></label>
    <input type="number" class="form-control" id="cod_categoria" name="cod_categoria" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['cod_categoria'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

 <div class="col-md-5 position-relative">
    <label for="descripcion_tipo_categoria" class="form-label">Domicilio <?php if($_GET['id']!=0) echo "[".$row['descripcion_tipo_categoria']."]";?></label>
    <input type="text" class="form-control" id="descripcion_tipo_categoria" name="descripcion_tipo_categoria" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['descripcion_tipo_categoria'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  


</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>

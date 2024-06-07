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
  <br>
  <div class="col-md-4 position-relative">
    <label for="listar_reporte" class="form-label">Listar en Reporte<?php if($_GET['id']!=0) echo "[".$row['listar_reporte']."]";?></label>
    <select class="form-control" id="listar_reporte" name="listar_reporte" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php if(isset($row['listar_reporte']) && $row['listar_reporte'] == 0){ echo "selected";}?>>NO</option>
      <option value="1" <?php if(isset($row['listar_reporte']) && $row['listar_reporte'] == 1){ echo "selected";}?>>SI</option>       
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div> 

  <div class="col-md-4 position-relative">
    <label for="presentismo" class="form-label">Cobra Presentismo<?php if($_GET['id']!=0) echo "[".$row['presentismo']."]";?></label>
    <select class="form-control" id="presentismo" name="presentismo" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php if(isset($row['presentismo']) && $row['presentismo'] == 0){ echo "selected";}?>>NO</option>
      <option value="1" <?php if(isset($row['presentismo']) && $row['presentismo'] == 1){ echo "selected";}?>>SI</option>       
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div> 

  <div class="col-md-4 position-relative">
    <label for="pasajes" class="form-label">Cobra Pasajes<?php if($_GET['id']!=0) echo "[".$row['pasajes']."]";?></label>
    <select class="form-control" id="pasajes" name="pasajes" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php if(isset($row['pasajes']) && $row['pasajes'] == 0){ echo "selected";}?>>NO</option>
      <option value="1" <?php if(isset($row['pasajes']) && $row['pasajes'] == 1){ echo "selected";}?>>SI</option>       
    </select>
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

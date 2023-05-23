<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if($_GET['id']!=0){
  $sql="select * from dependencias where id=".$_GET['id'];
  $sql=pg_query($con,$sql);
  $row=pg_fetch_array($sql);  
}
?>
<form method="post" id="form" class="row needs-validation" >
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-8 position-relative">
    <label for="descripcion" class="form-label">Descripción <?php if($_GET['id']!=0) echo "[".$row['descripcion']."]";?></label>
    <input type="text" class="form-control" id="descripcion" name="descripcion" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['descripcion'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  
  <div class="col-md-4 position-relative">
    <label for="Localidad" class="form-label">Localidad <?php if($_GET['id']!=0) echo "[".$row['id_localidad']."]";?></label>
    <select class="form-control" id="id_localidad" name="id_localidad" required>
         <option selected disabled value="">Seleccionar</option>
        <?php
        $selected="";
        $sql=pg_query($con,"select * from localidades order by descripcion");
        while($row1=pg_fetch_array($sql))
        { 
          if(isset($row)){ 
            if($row['id_localidad'] == $row1['id']){ 
              $selected="selected";
            }else {
              $selected="";
            }
          }?>       
          <option <?php echo $selected;?> value="<?php echo $row1['id'];?>"><?php echo $row1['descripcion'];?></option>
        <?php }
        ?> 
      </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  
   <div class="col-md-6 position-relative">
    <label for="domicilio" class="form-label">Domicilio <?php if($_GET['id']!=0) echo "[".$row['direccion']."]";?></label>
    <input type="text" class="form-control" id="direccion" name="direccion" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['direccion'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

<div class="col-md-6 position-relative">
    <label for="domicilio" class="form-label">Padre</label>
   <select id="padre" name="padre" class="form-control">
        <option value="0">Sin Padre</option> 
        <?php
        $sql=pg_query($con,"select * from dependencias where estado=1 order by descripcion");
        while($row1=pg_fetch_array($sql))
          {  ?>
           <option  <?php if(isset($row)) if($row['padre'] == $row1['id']) echo"selected"; ?> value="<?php echo $row1['id'];?>"><?php echo $row1['descripcion'];?></option>
         <?php }
         ?> 
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

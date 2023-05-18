<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if($_GET['id']!=0){
  $sql="select * from items where id=".$_GET['id'];
  $sql=pg_query($con,$sql);
  $row=pg_fetch_array($sql);
  $orden=$row['orden'];
}

?>
<form method="post" id="form" class="row needs-validation" >
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-3  position-relative">
    <label for="descripcion" class="form-label">Descripción <?php if($_GET['id']!=0) echo "[".$row['descripcion']."]";?></label>
    <input type="text" class="form-control" id="descripcion" name="descripcion"  placeholder="Descripción" aria-describedby="Descripción" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['descripcion'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="enlace" class="form-label">Enlace</label>
    <input type="text" class="form-control" id="enlace" name="enlace" placeholder="Enlace" aria-describedby="Enlace" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['enlace'];?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="titulo" class="form-label">Opción</label>
    <select class="form-control" id="id_opcion" name="id_opcion" required>
         <option selected disabled value="">Seleccionar</option>
        <?php
        $selected="";
        $sql=pg_query($con,"select * from opciones order by descripcion");
        while($row1=pg_fetch_array($sql))
        { 
          if(isset($row)){ 
            if($row['id_opcion'] == $row1['id']){ 
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

  <div class="col-md-2">
    <label for="orden" class="form-label">Orden</label>
    <input type="number" class="form-control" id="orden" name="orden" value="<?php echo $orden;?>" required>
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

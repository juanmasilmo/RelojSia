<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if($_GET['id']!=0){
  $sql="select * from usuarios where id=".$_GET['id'];
  $sql=pg_query($con,$sql);
  $row=pg_fetch_array($sql);
}

?>
<form method="post" id="form" class="row needs-validation" >
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-3  position-relative">
    <label for="usuario" class="form-label">Usuario <?php if($_GET['id']!=0) echo "[".$row['usuario']."]";?></label>
    <input type="text" class="form-control" id="usuario" name="usuario"  placeholder="Usuario" aria-describedby="Usuario" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['usuario'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

  <div class="col-md-3  position-relative">
    <label for="usuario" class="form-label">Nombre y Apellido <?php if($_GET['id']!=0) echo "[".$row['nombre_apellido']."]";?></label>
    <input type="text" class="form-control" id="nombre_apellido" name="nombre_apellido"  placeholder="Nombre y Apellido" aria-describedby="Nombre y Apellido" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['nombre_apellido'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="grupo" class="form-label">Grupo</label>
    <select class="form-control" id="id_grupo" name="id_grupo" required>
         <option selected disabled value="">Seleccionar</option>
        <?php
        $selected="";
        $sql=pg_query($con,"select * from grupos order by descripcion");
        while($row1=pg_fetch_array($sql))
        { 
          if(isset($row)){ 
            if($row['id_grupo'] == $row1['id']){ 
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

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>

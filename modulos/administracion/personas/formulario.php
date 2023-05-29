<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if($_GET['id']!=0){
  $sql="select * from personas where id=".$_GET['id'];
  $sql=pg_query($con,$sql);
  $row=pg_fetch_array($sql);  
}
?>
<form method="post" id="form" class="row needs-validation" >
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-3 position-relative">
    <label for="apellido" class="form-label">Apellido <?php if($_GET['id']!=0) echo "[".$row['apellido']."]";?></label>
    <input type="text" class="form-control" id="apellido" name="apellido" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['apellido'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

<div class="col-md-3 position-relative">
    <label for="nombres" class="form-label">Nombres <?php if($_GET['id']!=0) echo "[".$row['nombres']."]";?></label>
    <input type="text" class="form-control" id="nombres" name="nombres" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['nombres'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

   <div class="col-md-2 position-relative">
    <label for="legajo" class="form-label">Legajo<?php if($_GET['id']!=0) echo "[".$row['legajo']."]";?></label>
    <input type="number" class="form-control" id="legajo" name="legajo" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['legajo'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

 <div class="col-md-4 position-relative">
    <label for="correo" class="form-label">correo <?php if($_GET['id']!=0) echo "[".$row['correo']."]";?></label>
    <input type="text" class="form-control" id="correo" name="correo" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['correo'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  


<div class="col-md-6 position-relative">
    <label for="id_dependencia" class="form-label">Dependencias</label>
    <select class="form-control" id="id_dependencia" name="id_dependencia" required>
         <option selected disabled value="">Seleccionar</option>
        <?php
        $selected="";
        $sql=pg_query($con,"select d.id,d.descripcion,(select l.descripcion from localidades l WHERE d.id_localidad=l.id) as localidad from dependencias d order by d.descripcion");
        while($row1=pg_fetch_array($sql))
        { 
          if(isset($row)){ 
            if($row['id_dependencia'] == $row1['id']){ 
              $selected="selected";
            }else {
              $selected="";
            }
          }?>       
          <option <?php echo $selected;?> value="<?php echo $row1['id'];?>"><?php echo $row1['descripcion']." - ".$row1['localidad'];?></option>
        <?php }
        ?> 
      </select>
    <div class="invalid-feedback">
     controlar el campo
    </div>
  </div>
  <div class="col-md-2 position-relative">
    <label for="id_leu" class="form-label">ID LEU<?php if($_GET['id']!=0) echo "[".$row['id_leu']."]";?></label>
    <input type="number" class="form-control" id="id_leu" name="id_leu" readonly value="<?php if($_GET['id']!=0) echo $row['id_leu'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  
<div class="col-md-2 position-relative">
    <label for="nro_documento" class="form-label">DNI <?php if($_GET['id']!=0) echo "[".$row['nro_documento']."]";?></label>
    <input type="number" class="form-control" id="nro_documento" name="nro_documento" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['nro_documento'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div> 
  <div class="col-md-2 position-relative">
    <label for="fecha_nacimiento" class="form-label">Fecha Nac. <?php if($_GET['id']!=0) echo "[".$row['fecha_nacimiento']."]";?></label>
    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" minlength="3" value="<?php if($_GET['id']!=0) echo $row['fecha_nacimiento'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div> 
  <div class="col-md-6 position-relative">
    <label for="domicilio" class="form-label">Domicilio <?php if($_GET['id']!=0) echo "[".$row['domicilio']."]";?></label>
    <input type="text" class="form-control" id="domicilio" name="domicilio" minlength="3" value="<?php if($_GET['id']!=0) echo $row['domicilio'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div> 
  <div class="col-md-6 position-relative">
    <label for="id_categoria" class="form-label">Categoria</label>
    <select class="form-control" id="id_categoria" name="id_categoria" required>
         <option selected disabled value="">Seleccionar</option>
        <?php
        $selected="";
        $sql=pg_query($con,"select id,descripcion from categorias where estado=1 order by descripcion");
        while($row1=pg_fetch_array($sql))
        { 
          if(isset($row)){ 
            if($row['id_categoria'] == $row1['id']){ 
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


<script src="inc/js/selectize.min.js"></script>
<link rel="stylesheet" href="inc/css/selectize.bootstrap3.min.css" />
<script type="text/javascript">
  $("#id_dependencia").selectize({
    create: true
  });
  $("#id_categoria").selectize({
    create: true
  });
</script>
<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if($_GET['id']!=0){
  echo $sql="select * from articulos where id=".$_GET['id'];
  $sql=pg_query($con,$sql);
  $row=pg_fetch_array($sql);  
}
?>
<form method="post" id="form" class="row needs-validation" >
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-2 position-relative">
    <label for="id_leu" class="form-label">ID LEU<?php if($_GET['id']!=0) echo "[".$row['id_leu']."]";?></label>
    <input type="number" class="form-control" id="id_leu" name="id_leu" minlength="3" readonly value="<?php if($_GET['id']!=0) echo $row['id_leu'];?>" >

  </div>  
  <div class="col-md-2 position-relative">
    <label for="nro_articulo" class="form-label">Articulo<?php if($_GET['id']!=0) echo "[".$row['nro_articulo']."]";?></label>
    <input type="text" class="form-control" id="nro_articulo" name="nro_articulo" minlength="3" value="<?php if($_GET['id']!=0) echo $row['nro_articulo'];?>" >

  </div>  
  <div class="col-md-2 position-relative">
    <label for="inciso" class="form-label">Inciso<?php if($_GET['id']!=0) echo "[".$row['inciso']."]";?></label>
    <input type="text" class="form-control" id="inciso" name="inciso" minlength="3" value="<?php if($_GET['id']!=0) echo $row['inciso'];?>" >

  </div> 

  <div class="col-md-6 position-relative">
    <label for="descripcion" class="form-label">Descripción <?php if($_GET['id']!=0) echo "[".$row['descripcion']."]";?></label>
    <input type="text" class="form-control" id="descripcion" name="descripcion" required minlength="3" value="<?php if($_GET['id']!=0) echo $row['descripcion'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

  <div class="col-md-2 position-relative">
    <label for="cantidad_mensual" class="form-label">Cant. Mensual<?php if($_GET['id']!=0) echo "[".$row['cantidad_mensual']."]";?></label>
    <input type="number" class="form-control" id="cantidad_mensual" name="cantidad_mensual" minlength="3" value="<?php if($_GET['id']!=0) echo $row['cantidad_mensual'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

  <div class="col-md-2 position-relative">
    <label for="cantitad_anual" class="form-label">Cant. Anual<?php if($_GET['id']!=0) echo "[".$row['cantitad_anual']."]";?></label>
    <input type="number" class="form-control" id="cantitad_anual" name="cantitad_anual" minlength="3" value="<?php if($_GET['id']!=0) echo $row['cantitad_anual'];?>" >
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

  <div class="col-md-2 position-relative">
    <label for="inasistencias" class="form-label">Inasistencias<?php if($_GET['id']!=0) echo "[".$row['inasistencias']."]";?></label>
    <select class="form-control" id="inasistencias" name="inasistencias" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php if(isset($row['inasistencias']) && $row['inasistencias'] == 0){ echo "selected";}?>>NO</option>
      <option value="1" <?php if(isset($row['inasistencias']) && $row['inasistencias'] == 1){ echo "selected";}?>>SI</option>       
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

  <div class="col-md-2 position-relative">
    <label for="licencias" class="form-label">Licencias<?php if($_GET['id']!=0) echo "[".$row['licencias']."]";?></label>
    <select class="form-control" id="licencias" name="licencias" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php if(isset($row['licencias']) && $row['licencias'] == 0){ echo "selected";}?>>NO</option>
      <option value="1" <?php if(isset($row['licencias']) && $row['licencias'] == 1){ echo "selected";}?>>SI</option>       
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

  <div class="col-md-2 position-relative">
    <label for="retiro" class="form-label">Retiro<?php if($_GET['id']!=0) echo "[".$row['retiro']."]";?></label>
    <select class="form-control" id="retiro" name="retiro" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php if(isset($row['retiro']) && $row['retiro'] == 0){ echo "selected";}?>>NO</option>
      <option value="1" <?php if(isset($row['retiro']) && $row['retiro'] == 1){ echo "selected";}?>>SI</option>       
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

  <div class="col-md-2 position-relative">
    <label for="tardanza" class="form-label">Tardanza<?php if($_GET['id']!=0) echo "[".$row['tardanza']."]";?></label>
    <select class="form-control" id="tardanza" name="tardanza" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php if(isset($row['tardanza']) && $row['tardanza'] == 0){ echo "selected";}?>>NO</option>
      <option value="1" <?php if(isset($row['tardanza']) && $row['tardanza'] == 1){ echo "selected";}?>>SI</option>       
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

  <div class="col-md-2 position-relative">
    <label for="excluye_feria" class="form-label">Excluye Feria<?php if($_GET['id']!=0) echo "[".$row['excluye_feria']."]";?></label>
    <select class="form-control" id="excluye_feria" name="excluye_feria" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php if(isset($row['excluye_feria']) && $row['excluye_feria'] == 0){ echo "selected";}?>>NO</option>
      <option value="1" <?php if(isset($row['excluye_feria']) && $row['excluye_feria'] == 1){ echo "selected";}?>>SI</option>       
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

  <div class="col-md-4 position-relative">
    <label for="tipo_licencias" class="form-label">Tipo Licencias<?php if($_GET['id']!=0) echo "[".$row['tipo_licencias']."]";?></label>
    <select class="form-control" id="tipo_licencias" name="tipo_licencias" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="ORDINARIAS" <?php if(isset($row['tipo_licencias']) && $row['tipo_licencias'] == 'ORDINARIAS'){ echo "selected";}?>>ORDINARIAS</option>
      <option value="EXTRAORDINARIAS" <?php if(isset($row['tipo_licencias']) && $row['tipo_licencias'] == 'EXTRAORDINARIAS'){ echo "selected";}?>>EXTRAORDINARIAS</option>    
      <option value="MEDICAS" <?php if(isset($row['tipo_licencias']) && $row['tipo_licencias'] == 'MEDICAS'){ echo "selected";}?>>MEDICAS</option>   
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

 <div class="col-md-2 position-relative">
    <label for="sin_fecha_fin" class="form-label">Sin Fecha Fin<?php if($_GET['id']!=0) echo "[".$row['sin_fecha_fin']."]";?></label>
    <select class="form-control" id="sin_fecha_fin" name="sin_fecha_fin" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php if(isset($row['sin_fecha_fin']) && $row['sin_fecha_fin'] == 0){ echo "selected";}?>>NO</option>
      <option value="1" <?php if(isset($row['sin_fecha_fin']) && $row['sin_fecha_fin'] == 1){ echo "selected";}?>>SI</option>       
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  

  <div class="col-md-2 position-relative">
    <label for="cobra_presentismo" class="form-label">Cobra Presentismo<?php if($_GET['id']!=0) echo "[".$row['cobra_presentismo']."]";?></label>
    <select class="form-control" id="cobra_presentismo" name="cobra_presentismo" required>
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php if(isset($row['cobra_presentismo']) && $row['cobra_presentismo'] == 0){ echo "selected";}?>>NO</option>
      <option value="1" <?php if(isset($row['cobra_presentismo']) && $row['cobra_presentismo'] == 1){ echo "selected";}?>>SI</option>       
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  
  
  <!-- check para cargar manual el articulo en el calenario agente -->
  <div class="col-md-2 position-relative">
    <label for="c_manual" class="form-label">Carga Manual<?php if($_GET['id']!=0) echo "[".$row['c_manual']."]";?></label>
    <select class="form-control" id="c_manual" name="c_manual" required >
      <option selected disabled value="">Seleccionar</option>
      <option value="0" <?php echo (isset($row['c_manual']) && $row['c_manual'] == 0) ? "selected" : ''; ?> >NO</option>
      <option value="1" <?php echo (isset($row['c_manual']) && $row['c_manual'] == 1) ? "selected" : ''; ?> >SI</option>
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>  
  
  <div class="col-md-6 position-relative">
    <label for="observacion" class="form-label">Observación</label>
    <textarea name="observacion" rows="6" id="observacion" placeholder="observacion" class="form-control"><?php if(isset($row)) echo $row['observacion'];?></textarea>
  </div>  

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>

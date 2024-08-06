<?php
session_start();
include("../../../inc/conexion.php");
conectar();
$sql="select * from usuarios where id=".$_GET['id'];
$sql=pg_query($con,$sql);
$row=pg_fetch_array($sql);
$nombre_apellido=$row['nombre_apellido'];
$usuario=$row['usuario'];

?>

<script>listado_dependencias_vinculadas();</script>



<form method="post" id="form" class="row needs-validation" >
  <input type="hidden" class="form-control" id="id_usuario" name="id_usuario" value="<?php echo $_GET['id']; ?>">

  <!-- Usuario -->
  <div class="col-md-6">
    <label for="">Usuario</label>
    <input type="text" class="form-control" name="usuario" id="usuario" value="<?php echo $usuario; ?>" readonly />    
  </div>
   <div class="col-md-6">
    <label for="">Nombre, Apellido</label>
    <input type="text" class="form-control" name="nombre_apellido" id="nombre_apellido" value="<?php echo $nombre_apellido; ?>" readonly/>
  </div>


  <!-- Dependencias -->
  <div class="col-md-6">
    <label for="">Dependencia</label>
    <input type="text" class="form-control" placeholder="buscar dependencia ..." name="dependencia"
    id="dependencia" size="60" onKeyUp="listar_dependencia(this.value);" value="" />
    <div id="lista_dependencia"></div>
  </div>

</form>
<div class="mt-4" align="center">

  <button type="submit" class="btn btn-primary" onclick="vincular_usuario_dependencia()">Vincular Dependencia</button>  
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>

<div id="mensaje_vincular" style="display: none;"></div>
<div id="listado_dependencias_vinculadas" style="display: none;"></div>
<?php
session_start();
include("../../inc/conexion.php");
conectar();
$id=$_GET['id'];

$sql="select d.id, d.descripcion,l.descripcion as localidad,d.direccion from dependencias d, localidades l where d.id_localidad=l.id and d.estado=1 and d.id=".$id;
$sql=pg_query($con,$sql);
$row=pg_fetch_array($sql);
?>

  <!-- <h6>Dependencia Seleccionada</h6> -->
<input type="hidden" value="<?php echo $id;?>" name="id_dependencia" id="id_dependencia" />
<!-- agrego este campo para el request del modulo secretaria -->
<p><?php echo $row['descripcion'];?> - <i><b><?php echo $row['localidad'];?></b></i></p>
<p>Domicilio: <i><b><?php echo $row['direccion'];?></b></i></p>




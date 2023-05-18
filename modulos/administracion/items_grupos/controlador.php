<?php 
session_start();
include("../../../inc/conexion.php");
conectar();
if($_GET['tipo']=='add')
{
  $sql="insert into grupos_items values(".$_GET['id_grupo'].",".$_GET['id_item'].")";
 echo '
    <div class="alert alert-primary animated--grow-in" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> Habilitado correctamente!
    </div>';
}
else
{
  $sql="delete from grupos_items where id_grupo=".$_GET['id_grupo']." and id_item=".$_GET['id_item'];
  echo '
    <div class="alert alert-primary animated--grow-in" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> Deshabilitado correctamente!
    </div>';
}
$sql=pg_query($con,$sql);    
$row2=pg_fetch_array($sql)

?>

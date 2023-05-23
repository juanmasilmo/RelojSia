<?php
session_start();
include("../../../inc/conexion.php");
conectar();
$bandera=0;

$username = 'notificaciones-sia';
$password = 'hEFPNu89bT';

$context = stream_context_create(array(
    'http' => array(
        'header'  => "Authorization: Basic " . base64_encode("$username:$password")
    )
));
$data = @file_get_contents('https://jusmisiones.gov.ar/leu/rest/liquidacion/categorias', true, $context);

$items = json_decode($data, true);
$vector=array();
$vector=$items;

pg_query("BEGIN") or die("Could not start transaction\n");

$where="(";
for ($i=0;$i<count($vector);$i++){

    $leu_idcategoria=$vector[$i]['idcategoria'];  
    $leu_descripcion=$vector[$i]['descripcion'];  
    $leu_cod_categoria=$vector[$i]['cod_categoria'];  
    $leu_descripcion_tipo_categoria=$vector[$i]['descripcion_tipo_categoria'];  

    $where.=$leu_idcategoria.",";

    $sql="SELECT count(id_leu) as id_leu,estado,descripcion,cod_categoria,descripcion_tipo_categoria from categorias where id_leu=".$leu_idcategoria." group by estado,descripcion,cod_categoria,descripcion_tipo_categoria" ;
    $res=pg_query($con, $sql);
    $row=pg_fetch_array($res);

    if(pg_num_rows($res)==0)
    {        
        $sql="INSERT INTO categorias (descripcion, cod_categoria, id_leu, descripcion_tipo_categoria,usuario_abm)VALUES ('".$leu_descripcion."',".$leu_cod_categoria.", ".$leu_idcategoria.",'".$leu_descripcion_tipo_categoria."','".$_SESSION['usuario']."');";  
            $res=pg_query($con, $sql);
            if ($res){
                echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Categoria Nueva: <strong>'.$leu_descripcion.' - '.$leu_cod_categoria.' - '.$leu_descripcion_tipo_categoria.'</strong></div>';
                $bandera=0;
            }
            else{              
             echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> No se pudo crear la Categoria: <strong>'.$leu_descripcion.' - '.$leu_cod_categoria.' - '.$leu_descripcion_tipo_categoria.'</strong></div>';
             $bandera=1;
         }
     }else {

        if ($row['id_leu']==1){

            /////CAMBIO EN EL ESTADO
            if($row['estado']==0){
                $sql="UPDATE categorias SET estado=1 WHERE id_leu=".$leu_idcategoria;

                $res=pg_query($con, $sql);

                if ($res){
                    echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en el estado: <strong>'.$row['descripcion'].' - de 0 a 1</strong></div>';
                    $bandera=0;
                }
                else{
                    echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> No se pudo editar la descripcion: <strong>'.$leu_descripcion.' - '.$leu_cod_categoria.' - '.$leu_descripcion_tipo_categoria.'</strong></div>';
                    $bandera=1;
                }
            }


            /////CAMBIO EN LA DESCRIPCION
            if($row['descripcion']!=$leu_descripcion){
                $sql="UPDATE categorias SET descripcion='".$leu_descripcion."' WHERE id_leu=".$leu_idcategoria;

                $res=pg_query($con, $sql);

                if ($res){
                    echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en la descripcion: <strong>'.$row['descripcion'].' - de '.$row['descripcion'].' a '.$leu_descripcion.'</strong></div>';
                    $bandera=0;
                }
                else{
                    echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> No se pudo editar la descripcion: <strong>'.$leu_descripcion.' - '.$leu_cod_categoria.' - '.$leu_descripcion_tipo_categoria.'</strong></div>';
                    $bandera=1;
                }
            }
            /////CAMBIO EN EL cod_categoria
            if($row['cod_categoria']!=$leu_cod_categoria){
                $sql="UPDATE categorias SET cod_categoria='".$leu_cod_categoria."' WHERE id_leu=".$leu_idcategoria;

                $res=pg_query($con, $sql);

                if ($res){
                    echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en el cod_categoria: <strong>'.$row['descripcion'].' - de '.$row['cod_categoria'].' a '.$leu_cod_categoria.'</strong></div>';
                    $bandera=0;
                }
                else{
                    echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> No se pudo editar el cod_categoria: <strong>'.$leu_descripcion.' - '.$leu_cod_categoria.' - '.$leu_descripcion_tipo_categoria.'</strong></div>';
                    $bandera=1;
                }
            }
            /////CAMBIO EN EL TIPO DE CATEGORIA
            if($row['descripcion_tipo_categoria']!=$leu_descripcion_tipo_categoria){
                $sql="UPDATE categorias SET descripcion_tipo_categoria='".$leu_descripcion_tipo_categoria."' WHERE id_leu=".$leu_idcategoria;

                $res=pg_query($con, $sql);

                if ($res){
                    echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en la descripción del tipo de categoria: <strong>'.$row['descripcion'].' - de '.$row['descripcion_tipo_categoria'].' a '.$leu_descripcion_tipo_categoria.'</strong></div>';
                    $bandera=0;
                }
                else{
                    echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> No se pudo editar el cod_categoria: <strong>'.$leu_descripcion.' - '.$leu_cod_categoria.' - '.$leu_descripcion_tipo_categoria.'</strong></div>';
                    $bandera=1;
                }
            }
        }
    }
}
$where = substr($where, 0, -1).")";
//$where.=")";

$sql="select * from categorias where estado=1 and id_leu not in ".$where;
$sql=pg_query($con,$sql);
while($row=pg_fetch_array($sql))
{
    //CAMBIO EN EL ESTADO

    $sqlu="UPDATE categorias SET estado=0 WHERE id=".$row['id'];
    $res=pg_query($con, $sqlu);
    if ($res){
        echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en la descripción del tipo de categoria: <strong>'.$row['descripcion'].' - de 1 a 0</strong></div>';
        $bandera=0;
    }
    else{
        echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> No se cambiar el estado de: <strong>'.$row['descripcion'].'</strong></div>';
        $bandera=1;
    }
}


if ($bandera==0) {

 pg_query("COMMIT") or die("Transaction commit failed\n");
 echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong>¡OK!</strong> Se sincronizó con Éxito.</div>'; 
 echo "<script>listado();</script>";

} else {

  pg_query("ROLLBACK") or die("Transaction rollback failed\n");
  echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> No se pudo sincronizar.</div>';
}

?>
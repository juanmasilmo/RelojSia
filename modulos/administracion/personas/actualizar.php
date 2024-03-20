<?php
session_start();
include("../../../inc/conexion.php");
conectar();
$bandera=0;
$bandera_dep=0;

$username = 'notificaciones-sia';
$password = 'hEFPNu89bT';

$context = stream_context_create(array(
    'http' => array(
        'header'  => "Authorization: Basic " . base64_encode("$username:$password")
    )
));
$data = @file_get_contents('http://jusmisiones.gov.ar/leu/rest/liquidacion/agentes', true, $context);

$items = json_decode($data, true);

$vector=array();
$vector=$items;

pg_query("BEGIN") or die("Could not start transaction\n");

for ($i=0;$i<count($vector);$i++){

    $fecha_nacimiento = str_replace("\/", "-", $vector[$i]['fecha_nacimiento']);

    $vector[$i]['apellido']=str_replace("'", " ", $vector[$i]['apellido']);
    $vector[$i]['nombres']=str_replace("'", " ", $vector[$i]['nombres']);

    if($vector[$i]['domicilio']==null)
        $domicilio="";      
    else
        $domicilio = str_replace("\/", "/", $vector[$i]['domicilio']);

    if($vector[$i]['iddependencia']==null)
        $dependencia_leu=1;
    else
        $dependencia_leu=$vector[$i]['iddependencia'];

    if($vector[$i]['estado']=='ACTIVO')
        $activo = 1;
    else
        $activo = 0;

    if($vector[$i]['id_categoria']!=null){
        $vector[$i]['id_categoria']=$vector[$i]['id_categoria'];
         $sql="SELECT id from categorias where id_leu=".$vector[$i]['id_categoria'];
        $res=pg_query($con, $sql);
        $row=pg_fetch_array($res);  
        $id_categoria_control=$row['id'];
    }
    else{
        $vector[$i]['id_categoria']='null';
        $id_categoria_control="";
    }

    if (($vector[$i]['legajo']!=null)||($vector[$i]['legajo']!="")){
        $vector[$i]['legajo']=$vector[$i]['legajo'];
    }    
    else{
        $vector[$i]['legajo']='';
        //echo "entro ".$vector[$i]['apellido']." ".$vector[$i]['nombres']."<br>";
    }


    $sql="SELECT COUNT(id_leu) as id_dependencia from dependencias where id_leu=".$dependencia_leu;
    $res=pg_query($con, $sql);
    $row=pg_fetch_array($res);  
    if($row['id_dependencia']>0){

        $sql="SELECT COUNT(p.id_leu) as id_persona,
        p.id,
        p.activo,
        to_char(p.fecha_nacimiento,'DD/MM/YYYY') as fecha_nacimiento,
        p.domicilio,
        p.apellido,
        p.nombres,
        p.nro_documento,
        p.legajo,
        p.correo,
        p.id_categoria,
        d.id_leu as id_leu_dependencia,
        d.descripcion as descripcion_dependencia,
        p.id_dependencia
        from personas p join dependencias d on d.id=p.id_dependencia
        where p.id_leu=".$vector[$i]['idagente']." group by p.id,d.id_leu,d.descripcion";

        $res=pg_query($con, $sql);
        $row=pg_fetch_array($res);
        if($row['id_persona']==0){

///SI ES UNA PERSONA NUEVA      
            $vector[$i]['apellido']=str_replace("'", "''", $vector[$i]['apellido']);
            if($vector[$i]['legajo']!=""){
                $sql="INSERT INTO personas (id_leu, apellido, nombres, legajo, correo, activo, id_dependencia, estado, usuario_abm,nro_documento,fecha_nacimiento,domicilio,id_categoria)VALUES (".$vector[$i]['idagente'].",'".$vector[$i]['apellido']."', '".$vector[$i]['nombres']."','".$vector[$i]['legajo']."','".$vector[$i]['email_laboral']."',".$activo.",(select id from dependencias where id_leu=".$dependencia_leu."),1,'admin',".$vector[$i]['documento'].",'".$fecha_nacimiento."','".$domicilio."',(select id from categorias where id_leu=".$vector[$i]['id_categoria']."))";
                    $res=pg_query($con, $sql);
                    if ($res){
                        echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> Empleado Nuevo: <strong>'.$vector[$i]['apellido'].', '.$vector[$i]['nombres'].',</strong> Se sincronizaron las personas con &eacute;xito</div>';          
                        $bandera=0;
                    }else{
                        echo  $sql;
                        $bandera=1;
                    }
            }
        }else{
////SI HAY CAMBIOS EN EL ESTADO

    if($row['activo']!=$activo){

        $sqlUC="UPDATE personas SET activo=".$activo." WHERE id=".$row['id'];
        $res=pg_query($con, $sqlUC);
        if ($res){
            echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> Cambio en el estado <strong>'.$vector[$i]['apellido'].','.$vector[$i]['nombres'].' - de '.$row['activo'].' a '.$activo.'</strong></div>';
            $bandera=0;
        }else{
            $bandera=1;
        }   
    }


////SI HAY CAMBIOS EN LA FECHA DE NACIMIENTO                
    if($row['fecha_nacimiento']!=$fecha_nacimiento){

        $sqlUC="UPDATE personas SET fecha_nacimiento='".$fecha_nacimiento."' WHERE id=".$row['id'];
        $res=pg_query($con, $sqlUC);
        if ($res){
            echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> Cambio en la fecha de nacimiento <strong>'.$vector[$i]['apellido'].','.$vector[$i]['nombres'].' - de '.$row['fecha_nacimiento'].' a '.$fecha_nacimiento.'</strong></div>';
            $bandera=0;
        }else{
            $bandera=1;
        }   
    }

////SI HAY CAMBIOS EN EL CORREO               
    if($row['correo']!=$vector[$i]['email_laboral']){

        $sqlUC="UPDATE personas SET correo='".$vector[$i]['email_laboral']."' WHERE id=".$row['id'];
        $res=pg_query($con, $sqlUC);
        if ($res){
            echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> Cambio en la fecha de nacimiento <strong>'.$vector[$i]['apellido'].','.$vector[$i]['nombres'].' - de '.$row['correo'].' a '.$vector[$i]['email_laboral'].'</strong></div>';
            $bandera=0;
        }else{
            $bandera=1;
        }   
    }

////SI HAY CAMBIOS EN EL ID DE LA CATEGORIA               
    if($row['id_categoria']!=$id_categoria_control){

        $sqlUC="UPDATE personas SET id_categoria=(select id from categorias where id_leu=".$vector[$i]['id_categoria'].") WHERE id=".$row['id'];
        $res=pg_query($con, $sqlUC);
        if ($res){
            echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> Cambio en el id_categoria <strong>'.$vector[$i]['apellido'].','.$vector[$i]['nombres'].' - de '.$row['id_categoria'].' a '.$vector[$i]['id_categoria'].'</strong></div>';
            $bandera=0;
        }else{
            $bandera=1;
        }   
    }



////SI HAY CAMBIOS EN EL DOMICILIO
    if($row['domicilio']!=$domicilio){

        $sqlUC="UPDATE personas SET domicilio='".$domicilio."' WHERE id=".$row['id'];
        $res=pg_query($con, $sqlUC);
        if ($res){
            echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> Cambio en el Domicilio <strong>'.$vector[$i]['apellido'].','.$vector[$i]['nombres'].' - de '.$row['domicilio'].' a '.$domicilio.'</strong></div>';
            $bandera=0;
        }else{
            $bandera=1;
        }   
    }


////SI HAY CAMBIOS EN EL NUMERO_DOCUMENTO
    if($row['nro_documento']!=$vector[$i]['documento']){

        $sqlUC="UPDATE personas SET nro_documento=".$vector[$i]['documento']." WHERE id=".$row['id'];
        $res=pg_query($con, $sqlUC);
        if ($res){
            echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> Cambio en el Numero de Documento <strong>'.$vector[$i]['apellido'].','.$vector[$i]['nombres'].' - de '.$row['nro_documento'].' a '.$vector[$i]['documento'].'</strong></div>';
            $bandera=0;
        }else{
            $bandera=1;
        }   
    }


////SI HAY CAMBIOS EN EL APELLIDO
    if($row['apellido']!=$vector[$i]['apellido']){

        $sqlUC="UPDATE personas SET apellido='".$vector[$i]['apellido']."' WHERE id=".$row['id'];
        $res=pg_query($con, $sqlUC);
        if ($res){
            echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en el apellido <strong>'.$vector[$i]['apellido'].','.$vector[$i]['nombres'].' - de '.$row['apellido'].' a '.$vector[$i]['apellido'].'</strong></div>';
            $bandera=0;
        }else{
            $bandera=1;
        }   
    }


////SI HAY CAMBIOS EN EL NOMBRE
    if($row['nombres']!=$vector[$i]['nombres']){

        $sqlUC="UPDATE personas SET nombres='".$vector[$i]['nombres']."' WHERE id=".$row['id'];
        $res=pg_query($con, $sqlUC);
        if ($res){
            echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en el nombre <strong>'.$vector[$i]['apellido'].','.$vector[$i]['nombres'].' - de '.$row['nombres'].' a '.$vector[$i]['nombres'].'</strong></div>';
            $bandera=0;
        }else{
            $bandera=1;
        }   
    }



////SI HAY CAMBIOS EN LA DEPENDENCIA

    $sqldn="select descripcion from dependencias where id_leu=".$dependencia_leu;
    $resdn=pg_query($con, $sqldn);
    $resdn=pg_fetch_array($resdn);

    if($row['id_leu_dependencia']!=$dependencia_leu){

        $sqlU="UPDATE personas SET id_dependencia=(select id from dependencias where id_leu=".$dependencia_leu."), legajo='".$vector[$i]['legajo']."' WHERE id=".$row['id'];
        $res=pg_query($con, $sqlU);
        if ($res){
            echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en Dependencia: <strong>'.$vector[$i]['apellido'].','.$vector[$i]['nombres'].' - de '.$row['descripcion_dependencia'].' a '.$resdn['descripcion'].'</strong></div>';
            $bandera=0;
        }else{
            $bandera=1;
        }       
    }

////SI HAY CAMBIOS EN EL LEGAJO
    if($row['legajo']!=$vector[$i]['legajo']){

        $sqlU="UPDATE personas SET legajo='".$vector[$i]['legajo']."' WHERE id=".$row['id'];
        $res=pg_query($con, $sqlU);
        if ($res){
            echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en Legajo: <strong>'.$vector[$i]['apellido'].','.$vector[$i]['nombres'].' - de '.$row['legajo'].' a '.$vector[$i]['legajo'].'</strong></div>';
            $bandera=0;
        }else{
            $bandera=1;
        }       
    }
}
}
else{
    $bandera_dep=1;
}
}
if ($bandera==0){
    if ($bandera_dep==0){
        pg_query("COMMIT") or die("Transaction commit failed\n");
        echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong>¡OK!</strong> Se sincronizaron las personas con &eacute;xito</div>';?>
        <script>listado()</script>
        <?php
    }
    else{
        pg_query("ROLLBACK") or die("Transaction rollback failed\n");
        echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> <strong>¡OK!</strong> Se sincronizaron personas, pero faltaron dependencias, por favor actualice las dependencias y vuelva a sincronizar personas</div>';
    }
}
else{
    pg_query("ROLLBACK") or die("Transaction rollback failed\n");
    echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> <strong>¡ADVERTENCIA!</strong> Hubo problemas en la sincronizacion.</div>'; 
}
?>

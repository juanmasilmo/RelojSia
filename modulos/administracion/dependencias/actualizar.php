<?php
session_start();
include("../../../inc/conexion.php");
conectar();
$bandera=0;
$data = @file_get_contents('https://jusmisiones.gov.ar/leu/ws/dependencias.php');
$items = json_decode($data, true);
$vector=array();
$vector=$items['data'];
for ($i=0;$i<count($vector);$i++){


	if($vector[$i]['estado']=='ACTIVO')
		$activo = 1;
	else
		$activo = 0;

	if($vector[$i]['domicilio']==null)
		$domicilio='';		
	else
		$domicilio = str_replace("\/", "/", $vector[$i]['domicilio']);

	if($vector[$i]['iddependencia_padre']==null){
		$padre_leu=0;
		$descripcion_padre='';
	}else{
		$sqldn="select id,descripcion from dependencias where id_leu=".$vector[$i]['iddependencia_padre'];
		$resdn=pg_query($con, $sqldn);
		$resdn=pg_fetch_array($resdn);
		if($resdn['id']==null){
			$padre_leu=0;
		}else{
			$padre_leu=$resdn['id'];
		}
		$descripcion_padre=$resdn['descripcion'];
	}

	$sql="SELECT COUNT(id_leu) as id_dependencia,direccion,padre,descripcion from dependencias where id_leu=".$vector[$i]['iddependecia']." group by direccion,padre,descripcion";
	$res=pg_query($con, $sql);
	$row=pg_fetch_array($res);
	if ($row['id_dependencia']==0){
//		if ($vector[$i]['tipo_dependencia']!='ORGANISMO EXTERNO'){
			
			$sql="INSERT INTO dependencias (id_leu, descripcion,  id_localidad, estado, direccion,padre,usuario_abm)VALUES (".$vector[$i]['iddependecia'].",'".$vector[$i]['nombre_largo']."', ".$vector[$i]['idlocalidad'].", ".$activo.",'".$domicilio."',".$padre_leu.",'admin');";  		

			$res=pg_query($con, $sql);
			if ($res){
				echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> Dependencia Nueva: <strong>'.$vector[$i]['nombre_largo'].'</strong></div>';
				$bandera=0;
			}
			else{	  		
				echo $sql;
				$bandera=1;
			}
	//	}
	}else {
		if ($row['id_dependencia']==1){

			/////CAMBIO EN EL PADRE		
			if($row['padre']!=$padre_leu){
				$sql="UPDATE dependencias SET padre=".$padre_leu." WHERE id_leu=".$vector[$i]['iddependecia'];

				$res=pg_query($con, $sql);

				if ($res){
					echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en el Padre: <strong>'.$row['descripcion'].'</strong> Padre: '.$descripcion_padre.'</div>';
					$bandera=0;
				}
				else{	  		
					echo $sql;
					$bandera=1;
				}
			}


			/////CAMBIO EN EL PADRE		
			if($row['descripcion']!=$vector[$i]['nombre_largo']){
				$sql="UPDATE dependencias SET descripcion='".$vector[$i]['nombre_largo']."' WHERE id_leu=".$vector[$i]['iddependecia'];

				$res=pg_query($con, $sql);

				if ($res){
					echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en la Descripcion: <strong>'.$row['descripcion'].'</strong> Descripcion: '.$vector[$i]['nombre_largo'].'</div>';
					$bandera=0;
				}
				else{	  		
					echo $sql;
					$bandera=1;
				}
			}

			////SI HAY CAMBIOS EN EL ESTADO
			if ($vector[$i]['tipo_dependencia']!='ORGANISMO EXTERNO'){


				$sqlc="select estado from dependencias where id_leu=".$vector[$i]['iddependecia'];
				$resc=pg_query($con, $sqlc);
				$rowc=pg_fetch_array($resc);
				if($rowc['estado']!=$activo){

					$sqlUC="UPDATE dependencias SET estado=".$activo." WHERE id_leu=".$vector[$i]['iddependecia'];
					$res=pg_query($con, $sqlUC);
					if ($res){
						echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en el estado <strong>'.$vector[$i]['nombre_largo'].' - de '.$rowc['estado'].' a '.$activo.'</strong></div>';
						$bandera=0;
					}else{
						$bandera=1;
					}	
				}
			}

			/////CAMBIO EN EL DOMICILIO
			if($row['direccion']!=$domicilio){
				$sql="UPDATE dependencias SET direccion='".$domicilio."' WHERE id_leu=".$vector[$i]['iddependecia'];

				$res=pg_query($con, $sql);

				if ($res){
					echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i>Cambio en Dependencia: <strong>'.$vector[$i]['nombre_largo'].'</strong> Domicilio: '.$domicilio.'</div>';
					$bandera=0;
				}
				else{	  		
					echo $sql;
					$bandera=1;
				}
			}
		}
	}

}
if ($bandera==0){
	echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong>¡OK!</strong> Se sincronizaron las dependencias con &eacute;xito</div>';?>
	<script>listado()</script>
	<?php
}
else{
	echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> <strong>¡ADVERTENCIA!</strong> Hubo problemas en la sincronizacion.</div>';	
}
?>
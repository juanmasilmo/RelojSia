<?php
session_start();
include("../../../inc/conexion.php");
conectar();
$bandera=0;

/*
$fecha_actual=date("Y-m-d");

$fecha_desde= strtotime('-15 day', strtotime($fecha_actual));
$fecha_desdea = date('Y-m-d', $fecha_desde);

$fecha_hasta= strtotime('+30 day', strtotime($fecha_actual));
$fecha_hasta = date('Y-m-d', $fecha_hasta);
*/

$fecha_desdea=$_POST['fecha_desde'];
$fecha_hasta=$_POST['fecha_hasta'];

$username = 'notificaciones-sia';
$password = 'hEFPNu89bT';

// if($_SESSION['ENTORNO'] == 'PRODUCCION'){
// 	$html="https://jusmisiones.gov.ar/leu/rest/art_licencias/licencias/".$fecha_desdea."?fecha_hasta=".$fecha_hasta;
// 	$context = stream_context_create(array(
// 		'https' => array(
// 			'header'  => "Authorization: Basic " . base64_encode("$username:$password")
// 		)
// 	));
// }else{
$context = stream_context_create(array(
	'http' => array(
		'header'  => "Authorization: Basic " . base64_encode("$username:$password")
		)
	));

if($fecha_hasta==''){
	$where="";
	$html="http://jusmisiones.gov.ar/leu/rest/art_licencias/licencias/".$fecha_desdea;
}else{
	$where="and registro <='$fecha_hasta'";
	$html="http://jusmisiones.gov.ar/leu/rest/art_licencias/licencias/".$fecha_desdea."?fecha_hasta=".$fecha_hasta;
}


// $html="http://jusmisiones.gov.ar/leu/rest/art_licencias/licencias/".$fecha_desdea;
// }

$data = @file_get_contents($html, true, $context);

$items = json_decode($data, true);
$vector=array();
$vector=$items;

/**
 * inicializo una variable para crear un string de los insert y ejecutar al final
 */
$insert_articulos = '';

pg_query("BEGIN") or die("Could not start transaction\n");

$sql="delete from calendario_agente where registro >='$fecha_desdea' $where and leu=1";
$res=pg_query($con, $sql);

if(count($vector) > 0){
	$co = count($vector);
	for ($i=0;$i<count($vector);$i++){
		
		$fecha_desde = $vector[$i]['fecha_desde'];
		$cant_dias = $vector[$i]['cant_dias'];
		
		if($cant_dias > 0){

			for ($j = 0; $j < $cant_dias; $j++) {
				
				$fecha = date('Y-m-d', strtotime("$fecha_desde + $j days"));
				
				$sqla="select id from articulos where id_leu=".$vector[$i]['idarticulo'];
				$resa=pg_query($con, $sqla);
				$x = pg_num_rows($resa);
				$rowa=pg_fetch_array($resa);
				$id_articulo=$rowa['id'];
				


				$insert_articulos .= "INSERT INTO calendario_agente (legajo, registro, id_articulo, fecha_abm,usuario_abm,leu) VALUES 
					((select cast(legajo as integer) from personas WHERE id_leu=".$vector[$i]['idagente']."),
					'".$fecha."', 
					".$id_articulo.",
					now(),		
					'".$_SESSION['usuario']."',
					1
				);";
				
				
			}
		}
	}
}
if (pg_query($con, $insert_articulos)){
	pg_query("COMMIT") or die("Transaction commit failed\n");
	echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong>¡OK!</strong> Se sincronizaron las licencias desde '.$fecha_desdea.' hasta '.$fecha_hasta.'</div>';
	?>
		<script>log_sincronizar_leu(<?php echo $x ?>,<?php echo $co ?> )</script>
	<?php
}
else{              
	pg_query("ROLLBACK") or die("Transaction rollback failed\n");
	echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> <strong>¡ADVERTENCIA!</strong> Hubo problemas en la sincronizacion.</div>';	
}
?>
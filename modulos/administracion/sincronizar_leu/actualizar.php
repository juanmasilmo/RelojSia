<?php
session_start();
include("../../../inc/conexion.php");
conectar();
$bandera=0;


$fecha_desdea=$_POST['fecha_desde'];
$fecha_hasta=$_POST['fecha_hasta'];

$username = 'notificaciones-sia';
$password = 'hEFPNu89bT';

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


$data = @file_get_contents($html, true, $context);

$items = json_decode($data, true);
$vector=array();
$vector=$items;

/**
 * inicializo una variable para crear un string de los insert y ejecutar al final
 */
$insert_articulos = '';
$msj = '';

// pg_query("BEGIN") or die("Could not start transaction\n");
// leu 1 reloj (script charly)
// leu 2 articulos de ws de leu
$sql="DELETE FROM calendario_agente WHERE registro >= '$fecha_desdea' $where and leu=2";
$res=pg_query($con, $sql);

// por cada licencia (posicion en el array) inserto la cantidad de dias y articulo

if(count($vector) > 0){

	foreach ($vector as $licencia) {

		// inicializo la variable 
		$sql_insert = '';
		
		// cantidad de dias de la licencia
		$cant_dias = $licencia['cant_dias'];

		// fecha desde 
		$fecha_insert = $licencia['fecha_desde'];

		// busco el id del articulo en la DB RELOJ2
		$id_leu = $licencia['idarticulo'];
		$rs = pg_query($con,"SELECT id FROM articulos WHERE id_leu = $id_leu");
		$res = pg_fetch_array($rs);
		$id_articulo = $res['id'];

		// busco el legajo del agente en la DB RELOJ2
		$legajo = $licencia['idagente'];
		$sql_legajo = "SELECT legajo FROM personas WHERE id_leu = $legajo";
		$rs = pg_query($con,$sql_legajo);
		$res = pg_fetch_array($rs);
		(int)$legajo = $res['legajo'];

		$user_abm = $_SESSION['usuario'];
		
		// recorro la cantidad de dias e inserto los datos
		for ($dias=0; $dias < $cant_dias; $dias++) { 

			// inserto los datos en el calendario agente
			$sql_insert .= "INSERT INTO calendario_agente (legajo, registro, id_articulo, fecha_abm, usuario_abm, leu)
								VALUES ($legajo, '$fecha_insert', $id_articulo, now(), '$user_abm', 2);";
			
			// incremento la fecha
			$fecha_insert = date('Y-m-d', strtotime("$fecha_insert + 1 days"));
		
		} // FIN for

		// ejecuto la query por cada agente
		if (!pg_query($con, $sql_insert)){
			echo '<div class="alert alert-primary alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong>¡WARNING!</strong> .... '. $sql_legajo .'</div><br>';
			echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong> WARNING </strong> ... '. $sql_insert .'</div><br>';
		}
		else {
			// echo '<div class="alert alert-primary alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong>¡OK!</strong> .... '. $sql_legajo .'</div><br>';
			$msj = '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong>¡OK!</strong> Sincronización Exitosa </div><br>';
		} // FIN if


	} // FIN foreach
	
} // FIN if

echo $msj;
// if(count($vector) > 0){git  
// 	$co = count($vector);
// 	for ($i=0;$i<count($vector);$i++){
		
// 		$fecha_desde = $vector[$i]['fecha_desde'];
// 		$cant_dias = $vector[$i]['cant_dias'];
		
// 		if($cant_dias > 0){

// 			for ($j = 0; $j < $cant_dias; $j++) {
				
// 				$fecha = date('Y-m-d', strtotime("$fecha_desde + $j days"));
				
// 				$sqla="select id from articulos where id_leu=".$vector[$i]['idarticulo'];
// 				$resa=pg_query($con, $sqla);
// 				$x = pg_num_rows($resa);
// 				$rowa=pg_fetch_array($resa);
// 				$id_articulo=$rowa['id'];
// 				$insert_articulos = "INSERT INTO calendario_agente (legajo, registro, id_articulo, fecha_abm,usuario_abm,leu) VALUES 
// 					((select cast(legajo as integer) from personas WHERE id_leu=".$vector[$i]['idagente']."),
// 					'".$fecha."', 
// 					".$id_articulo.",
// 					now(),		
// 					'".$_SESSION['usuario']."',
// 					2
// 				);";
				
// 				if (!pg_query($con, $insert_articulos)){
// 					echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong> NO </strong> ... '. $insert_articulos .'</div><br>';
// 				}
// 				else {
// 					echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong>¡OK!</strong> .... '. $insert_articulos .'</div><br>';
// 				}				
// 			}
// 		}
// 	}
// }
// if (pg_query($con, $insert_articulos)){
// 	pg_query("COMMIT") or die("Transaction commit failed\n");
// 	echo '<div class="alert alert-success alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="far fa-check-circle"></i> <strong>¡OK!</strong> Se sincronizaron las licencias desde '.$fecha_desdea.' hasta '.$fecha_hasta.'</div>';

// }
// else{              
// 	pg_query("ROLLBACK") or die("Transaction rollback failed\n");
// 	echo '<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="fas fa-exclamation-triangle"></i> <strong>¡ADVERTENCIA!</strong> Hubo problemas en la sincronizacion.</div>';	
// }
?>
<?php
session_start();
include("../../../inc/conexion.php");
conectar();


$id_usuario = $_SESSION['userid'];

$sql_dep = "SELECT id_dependencia FROM usuario_dependencias WHERE id_usuario = $id_usuario";

$rs_dep = pg_query($con, $sql_dep);
$res_dep = pg_fetch_all($rs_dep);
$where = '';
if(pg_num_rows($rs_dep) > 0) {

  $ids_dep = '';
  for ($i = 0; $i < count($res_dep); $i++) {
    if(!isset($res_dep[$i+1])){
      $ids_dep .= $res_dep[$i]['id_dependencia'];
    }else{
      $ids_dep .= $res_dep[$i]['id_dependencia'].',';
    }
  }

  $where = ' WHERE id IN (' . $ids_dep . ')';
}
/**
 * Dependencias relacionadas al usuario
 */
$sql_dependencia = "SELECT id
                    ,CONCAT(descripcion, ' (', (SELECT descripcion FROM localidades WHERE id = id_localidad),')') as dep
            FROM dependencias $where";
$rs_dependencia = pg_query($con, $sql_dependencia);
$res_dependencia = pg_fetch_all($rs_dependencia);


/**
 * Anios
 */
$sql_anio = "SELECT DISTINCT(EXTRACT('YEAR' FROM registro)) as anio
              FROM calendario_agente
              ORDER BY anio";
$rs_anio = pg_query($con, $sql_anio);
$res_anio = pg_fetch_all($rs_anio);

$meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
          ];

//obtengo el año actual
$year = date('Y');
//obtengo el mes actual
$month = date('m');
//obtengo el ultimo dia del mes
$ultimo_dia = date('t');

//value por defecto del input date
$dt = new DateTime();
$hoy = $dt->format('Y-m-d');

//le agrego un 0 en caso de la resta.-
$month_min = $month - 1;
if($month_min < 10)
$month_min = '0'.$month_min;

//seteo los atributos del input
$min = $year.'-'. $month_min .'-25';
$max = $year.'-'.$month.'-'.$ultimo_dia;
?>


<!-- Formulario -->
<?php include_once('inc/formulario.php') ?>
<!-- FIN Formulario -->


<!-- MODAL -->
<?php include_once('inc/agregar_registro.php') ?>
<!-- FIN MODAL -->


<!-- MODAL MODIFICACION REGISTRO LEGAJO-->
<?php include_once('inc/modificar_registro.php') ?>
<!-- FIN MODAL -->

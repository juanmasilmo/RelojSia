<?php

if (isset($_GET['f'])) {
  $function = $_GET['f'];
}else {
  $function = "";
}

session_start();
include("../../../inc/conexion.php");
$con = conectar();

if (function_exists($function)) {
  $function($con);
}else {
  echo "La funcion" . $function . "no exite...";
}

function get_agentes($con){
  
  $id_dependencia = $_GET['id_dependencia'];
  
  $sql = "SELECT legajo as legajo
                ,CONCAT(apellido,' ',nombres) nombre
          FROM personas
          WHERE id_dependencia = $id_dependencia";
  $rs = pg_query($con, $sql);
  $res = pg_fetch_all($rs);

  if(pg_num_rows($rs) > 0) {

    echo json_encode($res);

  }else{

    echo json_encode(false);

  }
}

/**
 * Registros
 */
function get_registros_agentes($con)
{
  $id_dependencia = $_GET['id_dependencia'];
  
  /**
   * Obtengo los legajos de la dependencia para filtrar los registros
   */ 
  $sql_legajo = "SELECT legajo, concat(apellido, ' ', nombres) as nombre FROM personas WHERE id_dependencia = $id_dependencia ORDER BY legajo";
  $rs_legajo = pg_query($con, $sql_legajo);
  $res_legajo = pg_fetch_all($rs_legajo);
  
  $mes = $_GET['mes'];
  $anio = $_GET['anio'];

  /**
   * Creo una cadena para el sql
   */
  $legajos = '';

  //si la dependencia tiene agentes relacionados hago todo el chimi
  if(pg_num_rows($rs_legajo) > 0){

      for ($i=0; $i < count($res_legajo); $i++) { 
        if(isset($res_legajo[$i+1])){
          $legajos .= $res_legajo[$i]['legajo'].',';
        }else{
          $legajos .= $res_legajo[$i]['legajo'];
        }
      }
      
      /**
       * Obtengo los registros (marcas reloj)
       */
      $sql_registro = "SELECT legajo
                            ,EXTRACT(DAY FROM fecha_abm) as fecha_abm
                            ,EXTRACT(HOUR FROM registro) as hora
                            ,EXTRACT(MINUTE FROM registro) as minutos
                            ,to_char(registro_modificado, 'hh24:mi') as registro_modificado
                            ,(SELECT nro_articulo FROM articulos WHERE id = id_articulo)
                            ,EXTRACT(DAY FROM registro) as dia
                            ,EXTRACT(DAY FROM registro_modificado) as dia_m
                        FROM calendario_agente
                        WHERE EXTRACT(YEAR FROM registro) = $anio and EXTRACT(MONTH FROM registro) = $mes and borrado is null and legajo in ($legajos)
                        ORDER BY legajo, hora";
    $rs_registro = pg_query($con, $sql_registro);
    $res_registro = pg_fetch_all($rs_registro);
    
    $count = 0;
    //si tengo registros al menos 1, cargo el array
    if(pg_num_rows($rs_registro) > 0) {
    
      echo json_encode(['legajos' => $res_legajo, 'registros' => $res_registro]);

    }else{

      echo json_encode(['legajos' => $res_legajo, 'registros' => false]);
      
    }
    
  }else{

    echo json_encode(false);

  }
  
}


//-------------------------------------*------------*----------------

/**
 * Registros
 */
function registros($con)
{

$sql = "SELECT id
            ,id_estado
            ,(SELECT descripcion FROM estados WHERE id = id_estado) title
            ,(SELECT letra FROM estados WHERE id = id_estado) letra
            ,(SELECT color FROM estados WHERE id = id_estado) color 
            ,to_char(fecha_inicio,'YYYY-MM-DD') fecha_inicio
            ,to_char(fecha_fin,'YYYY-MM-DD') fecha_fin
          FROM calendario_anual";
$rs = pg_query($con, $sql);
$res = pg_fetch_all($rs);

  
foreach ($res as $row) {
  $r[] = [ 
         
          'id' => $row['id'],
          'title' => $row['title'] . '(' . $row['letra'] . ')',
          'start' => $row['fecha_inicio'],
          'end' => $row['fecha_fin'],
          'color' => $row['color'],
          'id_estado' => $row['id_estado'],
          // extendedProps
          'tipo' => 'evento',
          'event_id' => $row['id'],
          'descripcion' => $row['title'] . '(' . $row['letra'] . ')',
          'fecha_inicio' => $row['fecha_inicio'],
          'fecha_fin' => $row['fecha_fin'],

        ];
}

echo json_encode($r);
//return json_encode(['result' => $res]);
}


function guardar_registro_completo($con){
  
  $usuario_abm = $_SESSION['usuario'];
  $fecha = $_GET['fecha'];
  $opcion = $_GET['opcion'];
  $id_dependencia = $_GET['id_dependencia'];

  //traigo los legajos de la dependencia
  $sql = "SELECT legajo FROM personas WHERE id_dependencia = $id_dependencia ORDER BY legajo";
  $rs = pg_query($con, $sql);
  $res_legajos = pg_fetch_all($rs);


  //verifico que la fecha ya no tenga nada cargado
  $sql_fecha = "SELECT * FROM calendario_agente WHERE TO_CHAR(registro, 'YYYY-MM-DD') = '$fecha'";
  $rs_fecha = pg_query($con, $sql_fecha);
  $res_fecha = pg_fetch_all($rs_fecha);

  if(pg_num_rows($rs_fecha)){
    $sql = "DELETE FROM calendario_agente WHERE TO_CHAR(registro, 'YYYY-MM-DD') = '$fecha'";
    pg_query($con,$sql);
  }

  
  if(pg_num_rows($rs) > 0){
    
    $query = '';
    
    // id_articulo Firma Planilla
    if($opcion == 'fp') {
      
      //ontego el id del articulo Firma Planilla
      $sql_art = "SELECT id FROM articulos WHERE nro_articulo = 'FP'";
      $rs_art = pg_query($con, $sql_art);
      $res_art = pg_fetch_array($rs_art);
      $id_articulo = $res_art['id'];

      // creo un strin query con la fecha y el articulo x legajo
      foreach ($res_legajos as $legajo) {
        
        $legajo = $legajo['legajo'];
        $query .= "INSERT INTO calendario_agente (legajo, registro, id_articulo, fecha_abm, usuario_abm) VALUES ($legajo, '$fecha 00:00:00', '$id_articulo', now(), '$usuario_abm');";
  
      }

    }else{
      
      // creo un strin query con la fecha entrada y salida x legajo
      foreach ($res_legajos as $legajo) {
        
        $fecha1 = $fecha.' 6:30:00';
        $fecha2 = $fecha.' 12:30:00';
        $legajo = $legajo['legajo'];

        $query .= "INSERT INTO calendario_agente (legajo, registro, fecha_abm, usuario_abm) VALUES ($legajo, '$fecha1', now(), '$usuario_abm');";
        $query .= "INSERT INTO calendario_agente (legajo, registro, fecha_abm, usuario_abm) VALUES ($legajo, '$fecha2', now(), '$usuario_abm');";

      } //fin foreach legajos
      
    } // fin if opfcion
    
    pg_query($con,$query);

  } // fin num_rows legajos result
  
  echo json_encode("ok");
}


// ---------------------------------------------------------------- //

function calendarioDia($con){
  
  /**
   * Session
   */
  $usuario_abm = $_SESSION['usuario'];

  /**
   * Post
   */
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $id_estado = $_POST['id_estado'];
  $id_estado_configurado = $_POST['id_estado_configurado'];
  
  /**
   * si es 0, viene un evento nuevo
   */
   if($id_estado_configurado == 0){
    $sql = "INSERT INTO calendario_anual 
            (id_estado, fecha_inicio, fecha_fin, usuario_abm)
          VALUES 
            ($id_estado, '$start_date', '$end_date', '$usuario_abm')
          RETURNING id";
  }else{
    /**
     * sino, reemplazo el actual por el nuevo evento
     */
    $sql = "UPDATE calendario_anual 
            SET id_estado = $id_estado
                ,fecha_inicio = '$start_date'
                ,fecha_fin = '$end_date'
                ,usuario_abm = '$usuario_abm'
            WHERE id = $id_estado_configurado";
  }
  $rs = pg_query($con, $sql);
  echo json_encode('ok');
  
}


function eliminarEvento($con){
  
 
  /**
   * Session
   */
  $ususario_abm = $_SESSION['usuario'];
  
  /**
   * Post
   */
  $id_evento = $_POST['id_evento'];

  $sql = "DELETE FROM calendario_anual WHERE id = $id_evento";
  $rs = pg_query($con, $sql);
  echo json_encode('ok');

}

function verificarDiaEventos($con)
{
  /**
   * se chequea si el dia clickeado tiene algun evento configurado
   */
  $fechaInicio = $_GET['fechaInicio'];
  $fechaFin = $_GET['fechaFin'];
  $sql = "SELECT id
                  ,id_estado
                  ,(SELECT descripcion FROM estados WHERE id = id_estado) descripcion 
                  ,TO_CHAR(fecha_inicio, 'YYYY-MM-DD') as fecha_inicio
                  ,TO_CHAR(fecha_fin, 'YYYY-MM-DD') as fecha_fin
          FROM calendario_anual 
          WHERE fecha_inicio BETWEEN '$fechaInicio' AND '$fechaFin' ";
  $rs = pg_query($con, $sql);
  $res = pg_fetch_array($rs);

  echo json_encode($res);
}
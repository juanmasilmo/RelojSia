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
          WHERE id_dependencia = $id_dependencia
          ORDER BY apellido";
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
  $sql_legajo = "SELECT legajo, concat(apellido, ' ', nombres) as nombre FROM personas WHERE id_dependencia = $id_dependencia ORDER BY apellido";
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
     * Busco datos duplicados
     */
    buscar_registros_duplicados($con, $mes, $anio, $legajos);

    /**
     * Obtengo los registros (marcas reloj)
     */
    $sql_registro = "SELECT legajo
                          ,EXTRACT(DAY FROM fecha_abm) as fecha_abm
                          ,EXTRACT(HOUR FROM registro) as hora
                          ,EXTRACT(MINUTE FROM registro) as minutos
                          ,to_char(registro_modificado, 'hh24:mi') as registro_modificado
                          ,(SELECT nro_articulo FROM articulos WHERE id = id_articulo) as nro_articulo
                          ,(SELECT color FROM articulos WHERE id = id_articulo) as color
                          ,EXTRACT(DAY FROM registro) as dia
                          ,EXTRACT(DAY FROM registro_modificado) as dia_m
                      FROM calendario_agente as cagente
                      WHERE EXTRACT(YEAR FROM registro) = $anio and EXTRACT(MONTH FROM registro) = $mes and borrado is null and legajo in ($legajos)
                      ORDER BY legajo, hora"; 
    $rs_registro = pg_query($con, $sql_registro);
    $res_registro = pg_fetch_all($rs_registro);

    /**
      * Obtengo los feriados anuales
      */
    $sql_feriados = "SELECT (SELECT letra FROM estados WHERE id = id_estado) as estado_letra
                            ,(SELECT color FROM estados WHERE id = id_estado) as estado_color
                            ,(SELECT descripcion FROM estados WHERE id = id_estado) as estado_descripcion
                            ,EXTRACT(MONTH FROM fecha_inicio) as feriado_mes
                            ,EXTRACT(DAY FROM fecha_inicio) as feriado_dia
                      FROM calendario_anual
                      WHERE EXTRACT(YEAR FROM fecha_inicio) = $anio and EXTRACT(MONTH FROM fecha_inicio) = $mes";
    $rs_feriados = pg_query($con, $sql_feriados);
    $res_feriados = pg_fetch_all($rs_feriados);

    (pg_num_rows($rs_registro) > 0) ? $registros = $res_registro : $registros = false;
    (pg_num_rows($rs_feriados) > 0) ? $feriados = $res_feriados : $feriados = false;

    $count = 0;
    
    echo json_encode(['legajos' => $res_legajo, 'registros' => $registros, 'feriados' => $feriados]);
    
  }else{

    echo json_encode(false);

  }
  
}

/**
  * Obtengo los registros (marcas reloj)
  */
function buscar_registros_duplicados($con, $mes, $anio, $legajos){
   
    $sql_registro = "SELECT id
                          ,legajo
                          ,to_char(registro, 'YYYY-MM-DD HH24:MI') as registro
                      FROM calendario_agente as cagente
                      WHERE EXTRACT(YEAR FROM registro) = $anio 
                        and EXTRACT(MONTH FROM registro) = $mes
                        and borrado is null 
                        and id_articulo is null
                        and legajo in ($legajos)
                      ORDER BY registro,legajo"; 
    $rs_registro = pg_query($con, $sql_registro);
    $res_registro = pg_fetch_all($rs_registro);

    // inicializo las variables 
    $date = '';
    $legajo = 0;
    $sql_delete_duplicados = '';

    if(pg_num_rows($rs_registro) > 0){

      foreach ($res_registro as $key => $value) {
        // echo $date; 
        if($value['registro'] === $date and $value['legajo'] === $legajo){
          // si es igual significa que esta duplicado
          $id = $value['id'];
          $sql_delete_duplicados .= "DELETE FROM calendario_agente WHERE id = $id;";
      
        } // FIN if()
        
        // seteo las variables
        $date = $value['registro'];
        $legajo = $value['legajo'];
      
      } // FIN foreach()
    
    } // FIN if()


    // elimino los duplicados
    if($sql_delete_duplicados)
      pg_query($con,$sql_delete_duplicados);
      
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
  //  if($id_estado_configurado == 0){
  //   $sql = "INSERT INTO calendario_anual 
  //           (id_estado, fecha_inicio, fecha_fin, usuario_abm)
  //         VALUES 
  //           ($id_estado, '$start_date', '$end_date', '$usuario_abm')
  //         RETURNING id";
  // }else{
  //   /**
  //    * sino, reemplazo el actual por el nuevo evento
  //    */
  //   $sql = "UPDATE calendario_anual 
  //           SET id_estado = $id_estado
  //               ,fecha_inicio = '$start_date'
  //               ,fecha_fin = '$end_date'
  //               ,usuario_abm = '$usuario_abm'
  //           WHERE id = $id_estado_configurado";
  // }
  // $rs = pg_query($con, $sql);
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

  // $sql = "DELETE FROM calendario_anual WHERE id = $id_evento";
  // $rs = pg_query($con, $sql);
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
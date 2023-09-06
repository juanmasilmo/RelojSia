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

  if(pg_num_rows($rs) > 0) {

    foreach ($res as $row) {

      $r[] = [
              'id' => $row['id'],
              'title' => '(' . $row['letra'] . ')',
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
  }else{

    // envio un array vcio para que el plugin pueda iterar y no genera error
    $r[]['id'] = '';
    $r[]['title'] = '';
    $r[]['start'] = '';
    echo json_encode([$r]);
  }

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
  
  
  $sql = '';
  $dia_inicial = explode('-', $start_date);
  $dia_fin = explode('-', $end_date);  

  //recorro los dias para ir guardando el evento.-
  for ($i=$dia_inicial[2]; $i <= $dia_fin[2]; $i++) { 

    //armo dia
    $dia = $dia_inicial[0] . '-' . $dia_inicial[1]  . '-' . $i;
    
    /**
     * si es 0, viene un evento nuevo
     */
    if($id_estado_configurado == 0){
      $sql .= "INSERT INTO calendario_anual 
              (id_estado, fecha_inicio, fecha_fin, usuario_abm)
            VALUES 
              ($id_estado, '$dia', '$dia', '$usuario_abm');";
    }else{
      /**
       * sino, reemplazo el actual por el nuevo evento
       */
      $sql .= "UPDATE calendario_anual 
              SET id_estado = $id_estado
                  ,fecha_inicio = '$dia'
                  ,fecha_fin = '$dia'
                  ,usuario_abm = '$usuario_abm'
              WHERE id = $id_estado_configurado;";
    }
  }

  if(pg_query($con, $sql)){
    echo json_encode('ok');
  }
  
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

  // var_dump($_REQUEST);die();

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
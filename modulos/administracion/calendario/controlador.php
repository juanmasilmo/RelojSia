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
            'color' => '#fff',
            'textColor' => $row['color']
          ];
  }

  echo json_encode($r);
  //return json_encode(['result' => $res]);
}

function calendarioDia($con){
  
  /**
   * Session
   */
  $ususario_abm = $_SESSION['usuario'];

  /**
   * Post
   */
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $id_estado = $_POST['id_estado'];
  $id_estado_configurado = $_POST['id_estado_configurado'];
  
  if($id_estado_configurado == 0){
    echo $sql = "INSERT INTO calendario_anual 
            (id_estado, fecha_inicio, fecha_fin, usuario_abm)
          VALUES 
            ($id_estado, '$start_date', '$end_date', '$ususario_abm')
          RETURNING id";
  }else{
    echo $sql = "UPDATE calendario_anual 
            SET id_estado = $id_estado
                ,fecha_inicio = '$start_date'
                ,fecha_fin = '$end_date'
                ,usuario_abm = '$usuario_abm'
            WHERE id = $id_estado_configurado";
  }
  $rs = pg_query($con, $sql);
  // if($rs = pg_query($con, $sql)){
  //   if($id_estado_configurado == 0){
  //     $id = $rs['id'];
  //   }
  //   $sql = "SELECT * FROM calendario_anual WHERE id = $";
  // };


  echo json_encode('ok');
  
  /**
   * Guardo la configuracion del calendario 
   * respecto a los estados de los dias 
   * << laborales, no laborales, asueto, etc >>
   */
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
  
  /**
   * Guardo la configuracion del calendario 
   * respecto a los estados de los dias 
   * << laborales, no laborales, asueto, etc >>
   */
}

function verificarDiaEventos($con)
{
  $fecha = $_GET['fecha'];
  $sql = "SELECT id, (SELECT descripcion FROM estados WHERE id = id_estado) descripcion FROM calendario_anual WHERE fecha_inicio = '$fecha'";
  $rs = pg_query($con, $sql);
  $res = pg_fetch_array($rs);
  // echo pg_num_rows($rs);


  echo json_encode($res);
}
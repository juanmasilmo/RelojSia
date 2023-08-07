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

  $id_agente = $_GET['id_agente'];
  $sql = "SELECT id
                  ,borrado
                  ,registro
                  ,registro_modificado
                  ,(SELECT nro_articulo FROM articulos WHERE id = id_articulo)
          FROM calendario_agente
          WHERE legajo = $id_agente";
  $rs = pg_query($con, $sql);
  $res = pg_fetch_all($rs);
   
  foreach ($res as $row) {
    
    // si borrado, no muestro el registro
    if(empty($row['borrado'])){

      //por defecto cardo la marca del reloj
      $registro = $row['registro'];

      //pregunto si se modifico a mano la marca del registro y lo cargo
      if(!empty($row['registro_modificado'])){
         $registro = $row['registro_modificado'];
      }

      $r[] = [ 
              'id' => $row['id'],
              'start' => $registro,
              'tipo' => 'registro',
              'title' => $row['nro_articulo']
            ];
    }
  }

  // si no tiene registro no envio nada, para no anular el cargado de eventos anuales mdel calendario
  if(!is_null($r)){
    echo json_encode($r);
  }
}


/**
 * AGENTES
 */
function listado_agentes($con)
{

  $id_dependencia = $_GET['id_dependencia'];
  $sql = "SELECT legajo as id
                ,CONCAT(apellido,' ',nombres) personal
          FROM personas
          WHERE id_dependencia = $id_dependencia";
  $rs = pg_query($con, $sql);
  $res = pg_fetch_all($rs);

  echo json_encode($res);
  //return json_encode(['result' => $res]);
}

function modificar_registro($con){
  
  // SESSION
  $usuario_abm = $_SESSION["usuario"];
  
  // GET
  $id_registro = $_GET["id_registro"];

  // POST
  $fecha = $_POST['fecha_registro'];
  $hora = $_POST['hora_registro'];
  $fecha_modificada = $fecha . ' ' . $hora;

  $sql = "UPDATE calendario_agente SET registro_modificado = '$fecha_modificada', fecha_abm = 'now()', usuario_abm = '$usuario_abm' WHERE id = $id_registro";
  if(pg_query($con, $sql)){
    echo json_encode("Se actualizo el registro");
  }else{
    echo json_encode("Error al actualizar el registro");
  }
}

function eliminar_registro($con){
  
  // SESSION
  $usuario_abm = $_SESSION["usuario"];
  
  // GET
  $id_registro = $_GET["id_registro"];

  $sql = "UPDATE calendario_agente SET borrado = 1, fecha_abm = 'now()', usuario_abm = '$usuario_abm' WHERE id = $id_registro";
  if(pg_query($con, $sql)){
    echo json_encode("Se actualizo el registro");
  }else{
    echo json_encode("Error al actualizar el registro");
  }
}

function get_articulos($con)
{

  $id_agente = $_GET['id_agente'];
  $sql = "SELECT TO_CHAR(registro, 'YYYY-MM-DD') as fecha
	              ,(SELECT nro_articulo FROM articulos WHERE id = id_articulo)
                ,(SELECT descripcion FROM articulos WHERE id = id_articulo)
          FROM calendario_agente
          WHERE legajo = $id_agente and id_articulo is not null";
  $rs = pg_query($con, $sql);
  $res = pg_fetch_all($rs);
   
  foreach ($res as $row) {
      $r[] = [ 
              'start' => $row['fecha'],
              'title' => $row['nro_articulo'] . ' - ' .$row['descripcion'],
            ];
  }
  
  // si no tiene registro no envio nada, para evitar complicaciones con el js
  if(!is_null($r)){
    echo json_encode($r);
  }

}

function get_articulos_agente($con)
{

  $id_agente = $_GET['id_agente'];
  $month = $_GET['month'];
  $year = $_GET['year'];
  $sql = "SELECT COUNT(id)
              ,(SELECT nro_articulo FROM articulos WHERE id = id_articulo)
              ,(SELECT descripcion FROM articulos WHERE id = id_articulo)
            FROM calendario_agente
            WHERE legajo = $id_agente
              and id_articulo is not null 
              and  EXTRACT(YEAR FROM registro) = $year
              and  EXTRACT(MONTH FROM registro) = $month               
            GROUP BY id_articulo ";
  $rs = pg_query($con, $sql);
  $res = pg_fetch_all($rs);


  if(!empty($res)){
    foreach ($res as $row) {
      $r[] = [ 
        'nro_articulo' => $row['nro_articulo'],
        'title' => $row['descripcion'],
        'cantidad' => $row['count']
      ];
    }
    echo json_encode($r);
  } else {
    // si no tiene registro no envio nada, para evitar complicaciones con el js
    echo json_encode(0);
  }
  
}
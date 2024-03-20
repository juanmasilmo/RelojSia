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
  echo "La funcion" . $function . "no existe...";
}

function listado($con){

  $id_mes = $_GET['id_mes'];
  $id_dependencia = $_GET['id_dependencia'];
  $anio = $_GET['id_anio'];
  
  $legajos = '';
  //obtengo los usuarios
  $sql_legajos = "SELECT legajo FROM personas WHERE id_dependencia = $id_dependencia ORDER BY apellido";
  $rs_legajos = pg_query($con,$sql_legajos);
  $res_legajos = pg_fetch_all($rs_legajos);

  if(pg_num_rows($rs_legajos) > 0){
  
    // for ($i=0; $i < count($res_legajos); $i++) { 
  
    //   if(isset($res_legajos[$i+1])){
  
    //     $legajos .= $res_legajos[$i]['legajo'].',';
  
    //   }else{
  
    //     $legajos .= $res_legajos[$i]['legajo'];
  
    //   }
  
    // }

    /**
     * Obtengo los registros (marcas reloj)
     */
    // $sql_registro = "SELECT legajo
    //                       ,EXTRACT(DAY FROM fecha_abm) as fecha_abm
    //                       ,EXTRACT(HOUR FROM registro) as hora
    //                       ,EXTRACT(MINUTE FROM registro) as minutos
    //                       ,to_char(registro_modificado, 'hh24:mi') as registro_modificado
    //                       ,(SELECT nro_articulo FROM articulos WHERE id = id_articulo)
    //                       ,EXTRACT(DAY FROM registro) as dia
    //                       ,EXTRACT(DAY FROM registro_modificado) as dia_m
    //                   FROM calendario_agente
    //                   WHERE EXTRACT(YEAR FROM registro) = $anio and EXTRACT(MONTH FROM registro) = $mes and borrado is null and legajo in ($legajos)
    //                   ORDER BY legajo, hora";
    // $rs_registro = pg_query($con, $sql_registro);
    // $res_registro = pg_fetch_all($rs_registro);
  
    obtener_registros_x_legajos($con,$res_legajos,$id_mes,$anio);
  }


  /**
   * Registros
   */

  // echo json_encode(['legajos' => $res_legajos]);



}

function obtener_registros_x_legajos($con,$legajos,$mes,$anio){
  // var_dump($legajos);

  foreach ($legajos as $value) {
    $legajo = $value['legajo'];
    $sql = "SELECT legajo
                    ,registro
                    ,id_articulo
                    ,leu
            FROM calendario_agente 
            WHERE legajo = $legajo
                  and EXTRACT(YEAR FROM registro) = $anio
                  and EXTRACT(MONTH FROM registro) = $mes";
    $rs = pg_query($con,$sql);
    $res = pg_fetch_all($rs);
  }

  $f_inicio = "$anio-$mes-01";

  echo " =>>> recorrer_registros ... =>>>";
  recorrer_registros($con,$f_inicio,$res,$anio);
  
}

function recorrer_registros($con,$inicio,$registros,$anio){
  
  $date = new DateTime($inicio);
  $fin = $date->format('Y-m-t');
  $cantidad_dias = $date->format('Y-m-t');
  
  // echo " =>>> recorrer_registros x2... =>>>";
  //obtengo los asuetos, feriados, etc
  $sql = "SELECT to_char(fecha_inicio, 'YYYY-MM-DD') as no_laborales FROM calendario_anual WHERE EXTRACT(YEAR from fecha_inicio) = $anio";
  $rs =pg_query($con,$sql);
  $res = pg_fetch_array($rs);
  // var_dump($res);

  $fechainicio = strtotime($inicio);
  $fechafin = strtotime($fin);
 
  // Incremento en 1 dia
  $diainc = 24*60*60;
  $count = 0; 
 
  // Arreglo de dias habiles, inicianlizacion
  $diashabiles = array();
 
  // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
  for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diainc) {
          // Si el dia indicado, no es sabado o domingo es habil
          if (in_array(date('Y-m-d', $midia), $res)) { // DOC: http://www.php.net/manual/es/function.date.php
                  // Si no es un dia feriado entonces es habil
                  // if (!in_array(date('Y-m-d', $midia), $diasferiados)) {
                  //         array_push($diashabiles, date('Y-m-d', $midia));
                  // }
                  echo $midia;
          }
          $count++;
  }
 
  // return $diashabiles;

}


function getDiasHabiles($fechainicio, $fechafin, $diasferiados = array()) {
  // Convirtiendo en timestamp las fechas
  $fechainicio = strtotime($fechainicio);
  $fechafin = strtotime($fechafin);
 
  // Incremento en 1 dia
  $diainc = 24*60*60;
 
  // Arreglo de dias habiles, inicianlizacion
  $diashabiles = array();
 
  // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
  for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diainc) {
          // Si el dia indicado, no es sabado o domingo es habil
          if (!in_array(date('N', $midia), array(6,7))) { // DOC: http://www.php.net/manual/es/function.date.php
                  // Si no es un dia feriado entonces es habil
                  if (!in_array(date('Y-m-d', $midia), $diasferiados)) {
                          array_push($diashabiles, date('Y-m-d', $midia));
                  }
          }
  }
 
  return $diashabiles;
}


// var_dump(getDiasHabiles('2013-12-10', '2013-12-16', [ '2013-12-16' ]));





/*----------------------------------------------------------*/

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

  if(pg_num_rows($rs) > 0){

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
    echo json_encode($r);
  }else{
      // envio un array vcio para que el plugin pueda iterar y no genera error
      $r[]['id'] = '';
      $r[]['title'] = '';
      $r[]['start'] = '';
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
                ,CONCAT(apellido,' ',nombres) as personal
          FROM personas
          WHERE id_dependencia = $id_dependencia
          ORDER BY personal";
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

  $sql = "UPDATE calendario_agente SET registro_modificado = '$fecha_modificada', usuario_abm = '$usuario_abm' WHERE id = $id_registro";
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

  $sql = "UPDATE calendario_agente SET borrado = 1, registro_modificado = 'now()', usuario_abm = '$usuario_abm' WHERE id = $id_registro";
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
          WHERE legajo = $id_agente 
              and id_articulo is not null
              and borrado is null
              ";
  $rs = pg_query($con, $sql);
  
  if(pg_num_rows($rs) != 0){
    // si no tiene registro no envio nada, para evitar complicaciones con el js
    
    $res = pg_fetch_all($rs);
    foreach ($res as $row) {
      $r[] = [ 
        'start' => $row['fecha'],
        'title' => $row['nro_articulo'] . ' - ' .$row['descripcion'],
        'overlap' => 'false'
      ];
    }
    
    echo json_encode($r);
  }else{
    // envio un array vcio para que el plugin pueda iterar y no genera error
    $r[]['id'] = '';
    $r[]['title'] = '';
    $r[]['start'] = '';
    echo json_encode($r);
  }

}

function get_articulos_agente($con)
{

  // Estos datos se envia para cargar la tabla al pie del calendario
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
              and borrado is null              
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

function verificarDia($con){

  /**
   * Verifica que no haya algun articulo cargado previamente
   */
  $fecha = $_GET['fecha'];
  $fecha_fin = $_GET['fecha_fin'];
  $legajo = $_GET['legajo'];

  $sql = "SELECT id
                ,id_articulo
                ,(SELECT CONCAT(nro_articulo, ' ' ,descripcion) FROM articulos WHERE id = id_articulo) as descripcion 
          FROM calendario_agente
          WHERE registro BETWEEN '$fecha' AND '$fecha_fin'
                and legajo = $legajo
                and borrado is null";
  $rs = pg_query($con,$sql);
  $res = pg_fetch_array($rs);

  if(!empty($res['id_articulo'])){
    echo json_encode([$res['id_articulo'], $res['descripcion'], $res['id']]);
  }else {
    echo json_encode(0);
  }
}

function guardarArticulo($con){
  
  // SESSION
  $usuario_abm = $_SESSION["usuario"];
  
  // POST
  $serialize_form = $_POST['form'];
  $form = explode("&",$serialize_form);
  foreach ($form as $key => $value) {
    $form = explode("=",$value);
    $array_datos[$form[0]] = $form[1];
  }

  $id_articulo = $array_datos["id_articulo"];
 
  // si existe y no esta vacio
  if(isset($array_datos['fecha_registro']) and !empty($array_datos['fecha_registro']))
    $fecha = $array_datos['fecha_registro'];
 
  // si existe y no esta vacio
  if(isset($_POST['legajo']) and !empty($_POST['legajo']))
    $legajo = $_POST['legajo'];

  $id = 0;
  // si viene in id configurado update
  if(isset($array_datos["id_db_articulo_configurado"]) and !empty($array_datos["id_db_articulo_configurado"])){
    $id = $array_datos["id_db_articulo_configurado"];
  }


  $dia_inicial = explode('-', $fecha);
  $dia_fin = explode('-', $array_datos['fecha_registro_fin']);  
  $sql = '';

  
  if($id != 0 and !empty($id)){
  
    $sql .= "UPDATE calendario_agente SET id_articulo = $id_articulo, registro_modificado = 'now()', usuario_abm = '$usuario_abm' WHERE id = $id";
  
  }else{

    //recorro los dias para ir guardando el evento.-
    for ($i=$dia_inicial[2]; $i <= $dia_fin[2]; $i++) {

      //armo dia
      $dia = $dia_inicial[0] . '-' . $dia_inicial[1]  . '-' . $i;

      $sql .= "INSERT INTO calendario_agente (id_articulo, legajo, registro, fecha_abm, usuario_abm) VALUES ($id_articulo, $legajo, '$dia', 'now()', '$usuario_abm');";
    }
  
  }
  
  if(pg_query($con, $sql)){
    echo json_encode("Se actualizo el registro");
  }else{
    echo json_encode("Error al actualizar el registro");
  }
}

function eliminarArticulo($con){
  
  /**
   * Genera un borrado logico, no elimina el registro de la DB
   */


  // SESSION
  $usuario_abm = $_SESSION["usuario"];
  
  // POST
  $id = $_POST["id"];
  $id_articulo = $_POST["id_articulo"];

  $sql = "UPDATE calendario_agente SET borrado = 1, registro_modificado = 'now()', usuario_abm = '$usuario_abm' WHERE id = $id";
  if(pg_query($con, $sql)){
    echo json_encode("Se actualizo el registro");
  }else{
    echo json_encode("Error al actualizar el registro");
  }
}
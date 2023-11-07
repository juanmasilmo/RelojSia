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

  /**
   * obtengo el dato del usuario si tiene permiso para cargar_registro (issue 40)
   */ 
  $id_u = $_SESSION['userid'];
  $sql_cr = "SELECT carga_registro FROM usuarios WHERE id = $id_u";
  $rs_cr = pg_query($con,$sql_cr);
  $res = pg_fetch_array($rs_cr);
  $carga_registro = $res['carga_registro'];

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
      
      /**
       * Issue 39
       */
      // $sql_registro = "SELECT legajo
      //                        ,EXTRACT(DAY FROM fecha_abm) as fecha_abm
      //                        ,(SELECT nro_articulo FROM articulos WHERE id = id_articulo)
      //                        ,EXTRACT(DAY FROM registro) as dia
      //                        ,EXTRACT(DAY FROM registro_modificado) as dia_m
      //                    FROM calendario_agente
      //                    WHERE EXTRACT(YEAR FROM registro) = $anio and EXTRACT(MONTH FROM registro) = $mes and borrado is null and legajo in ($legajos)
      //                    ORDER BY legajo";

    $rs_registro = pg_query($con, $sql_registro);
    $res_registro = pg_fetch_all($rs_registro);
    
    $count = 0;
    //si tengo registros al menos 1, cargo el array
    if(pg_num_rows($rs_registro) > 0) {
    
      echo json_encode(['legajos' => $res_legajo, 'registros' => $res_registro, 'carga_registro' => $carga_registro]);

    }else{

      echo json_encode(['legajos' => $res_legajo, 'registros' => false, 'carga_registro' => $carga_registro]);
      
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

  /**
   * Llamada desde el btn "Agregar Registro" 
   * Si ingresa a esta funcion solo guardaria "FP" o "6:30 - 12:30"
   */
  
  $usuario_abm = $_SESSION['usuario'];
  $fecha = $_GET['fecha'];
  $opcion = $_GET['opcion'];
  $id_dependencia = $_GET['id_dependencia'];

  //traigo los legajos de la dependencia
  $sql = "SELECT legajo FROM personas WHERE id_dependencia = $id_dependencia ORDER BY legajo";
  $rs = pg_query($con, $sql);
  $res_legajos = pg_fetch_all($rs);

  
  if(pg_num_rows($rs) > 0){
    
    $query = '';
    // id_articulo Firma Planilla
    if($opcion == 'fp') {
      
      //ontego el id del articulo Firma Planilla
      $sql_art = "SELECT id FROM articulos WHERE nro_articulo = 'FP'";
      $rs_art = pg_query($con, $sql_art);
      $res_art = pg_fetch_array($rs_art);
      $id_articulo_fp = $res_art['id'];

      // creo un strin query con la fecha y el articulo x legajo
      foreach ($res_legajos as $legajo) {
        
        $legajo = $legajo['legajo'];

        //verifico si no existe articulos del leu
        $sql_leu = "SELECT * FROM calendario_agente WHERE legajo = $legajo and to_char(registro, 'YYYY-MM-DD') = '$fecha' order by leu";
        $rs = pg_query($con,$sql_leu);

        if(!pg_num_rows($rs) > 0) {
          // si noy registros inserto
          $query .= "INSERT INTO calendario_agente (legajo, registro, id_articulo, fecha_abm, usuario_abm) VALUES ($legajo, '$fecha 00:00:00', '$id_articulo_fp', now(), '$usuario_abm');";
        }else{
          //si hay registros del legajo verifico que no sea proveniente del leu
          $res = pg_fetch_array($rs);
          $leu = $res['leu'];
          if(!$leu == 1){
            // sino es del leu elimino el registro (6:30-12:30 ó FP)
            $query .= "DELETE FROM calendario_agente WHERE legajo = $legajo and to_char(registro, 'YYYY-MM-DD') = '$fecha';";
            $query .= "INSERT INTO calendario_agente (legajo, registro, id_articulo, fecha_abm, usuario_abm) VALUES ($legajo, '$fecha 00:00:00', '$id_articulo_fp', now(), '$usuario_abm');";
          } // fin leu

        } //fin pg_num_rows
  
      }

    }
    else{
      
      // creo un strin query con la fecha entrada y salida x legajo
      foreach ($res_legajos as $legajo) {
        
        $fecha1 = $fecha.' 6:30:00';
        $fecha2 = $fecha.' 12:30:00';
        $legajo = $legajo['legajo'];

        //verifico si no existe articulos del leu
        echo $sql_leu = "SELECT * FROM calendario_agente WHERE legajo = $legajo and to_char(registro, 'YYYY-MM-DD') = '$fecha' order by leu";
        $rs = pg_query($con,$sql_leu);

        if(!pg_num_rows($rs) > 0) {
        
          // si noy registros inserto sin drama
          $query .= "INSERT INTO calendario_agente (legajo, registro, fecha_abm, usuario_abm) VALUES ($legajo, '$fecha1', now(), '$usuario_abm');";
          $query .= "INSERT INTO calendario_agente (legajo, registro, fecha_abm, usuario_abm) VALUES ($legajo, '$fecha2', now(), '$usuario_abm');";
        
        }else{
          
          //si hay registros del legajo verifico que no sea proveniente del leu
          $res = pg_fetch_array($rs);
          $leu = $res['leu'];
          
          if(!$leu == 1){
           
            // sino es del leu elimino el registro (6:30-12:30 ó FP)
            $query .= "DELETE FROM calendario_agente WHERE legajo = $legajo and to_char(registro, 'YYYY-MM-DD') = '$fecha';";
            $query .= "INSERT INTO calendario_agente (legajo, registro, fecha_abm, usuario_abm) VALUES ($legajo, '$fecha1', now(), '$usuario_abm');";
            $query .= "INSERT INTO calendario_agente (legajo, registro, fecha_abm, usuario_abm) VALUES ($legajo, '$fecha2', now(), '$usuario_abm');";
        
          } // fin leu

        } //fin pg_num_rows

      } //fin foreach legajos
      
    } // fin if opfcion
    
    if(pg_query($con,$query)){
      echo json_encode("ok");
    }else{
      echo json_encode("error");
    }

  } // fin num_rows legajos result
  else{
    echo json_encode("no tiene usuarios");
  }
  
}


function modificar_registro_legajo($con){

  // var_dump($_REQUEST);
  // die();

  $d1 = $_REQUEST['d1'];
  $d2 = $_REQUEST['d2'];
  if($d2 == 'undefined'){
    $d2 = $d1;
  }
  $id_mes = $_REQUEST['m'];
  $id_anio = $_REQUEST['a'];
  $id_art = $_REQUEST['id_art'];

  $usuario_abm = $_SESSION['usuario'];

  $fecha = $_POST['input_modificacion_registro_fecha'];
  $legajo = $_POST['input_modificacion_registro_legajo'];

  // echo '<pre>';print_r($_REQUEST);die();

  /**
   * Recorro la/s fecha/s y elimino si hay articulo (no borro las marcas del reloj)
   */

  $str_query = '';

  for ($i=$d1; $i <= $d2; $i++) { 
    //configuro la fecha
    if($i < 10)
      $i = '0'.$i;
    $fecha = $id_anio.'-'.$id_mes.'-'.$i;

    /**
     * consulto si existe registro en la DB
     */
    $sql_select = "SELECT id,leu FROM calendario_agente WHERE TO_CHAR(registro, 'YYYY-MM-DD') = '$fecha' and legajo = $legajo order by leu";
    $rs = pg_query($con, $sql_select);
    $res = pg_fetch_array($rs);
    $id = $res['id'];
    $leu = $res['leu']; 
    // $id_articulo = $res['id_articulo']; 
  


    /**
     * si tiene registro por leu no permito guardar ni editar el dia
     */
    $insert = 1;
    if($id){
      if(!$leu){
        //elimino el articulo 
        $str_query .= "UPDATE calendario_agente SET id_articulo = null WHERE TO_CHAR(registro, 'YYYY-MM-DD') = '$fecha' and legajo = $legajo;";
      }else{
        $insert = 0;
      }
    }
    
    //si la fecha no tiene carga proveniente de leu inserto.-
    if($insert == 1){
      if(isset($_REQUEST['fecha_ingreso'])){
        $registro_inicio = $fecha . ' ' . $_REQUEST['fecha_ingreso'];
        $registro_fin = $fecha . ' ' . $_REQUEST['fecha_salida'];
        
        if($id){
          $str_query .= "DELETE FROM calendario_agente WHERE TO_CHAR(registro, 'YYYY-MM-DD') = '$fecha' and legajo = $legajo;";
        }
        $str_query .= "INSERT INTO calendario_agente (registro, legajo, fecha_abm, usuario_abm) VALUES ('$registro_inicio', $legajo, now(), '$usuario_abm');";
        $str_query .= "INSERT INTO calendario_agente (registro, legajo, fecha_abm, usuario_abm) VALUES ('$registro_fin', $legajo, now(), '$usuario_abm');";
      }else{
        if($id_art != 'null'){
          $str_query .= "INSERT INTO calendario_agente (id_articulo, legajo, registro, fecha_abm, usuario_abm) VALUES ($id_art, $legajo, '$fecha', 'now()','$usuario_abm');";
        }
      }
    }
  }
  if($str_query){
    pg_query($con,$str_query); 
    echo json_encode(["msj"=>"Guardado Correctamente...  Verificar la <strong><i>Planilla Mensual</i></strong> para registros de marcas.-","class_alert"=>"alert-success"]);
  }else{
    echo json_encode(["msj"=>"<strong>Error!</strong> en el guardado, consulte su administrador .-","class_alert"=>"alert-warning"]);
  }

  // $sql_art = "SELECT id FROM articulos WHERE nro_articulo = 'FP'";
  // $rs_art = pg_query($con, $sql_art);
  // $res_art = pg_fetch_array($rs_art);
  // $id_articulo = $res_art['id'];


  // $sql_ist = "INSERT INTO calendario_agente (id_articulo, registro, legajo, fecha_abm, usuario_abm) VALUES ($id_articulo, '$fecha 00:00:00', $legajo, now(), '$usuario_abm')";

    /**
   * SE comenta el else ya que no van a poder cargar registros solo articulos
   */
  // else{
    
  //   $registro_inicio = $fecha . ' ' . $_POST['fecha_ingreso'];
  //   $registro_fin = $fecha . ' ' . $_POST['fecha_salida'];
    
  //   $sql_ist = "INSERT INTO calendario_agente (id_articulo, registro, legajo, fecha_abm, usuario_abm) VALUES (null, '$registro_inicio', $legajo, now(), '$usuario_abm');";
  //   $sql_ist .= "INSERT INTO calendario_agente (id_articulo, registro, legajo, fecha_abm, usuario_abm) VALUES (null, '$registro_fin', $legajo, now(), '$usuario_abm')";
  
  // }
  // pg_query($con,$sql_ist);

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
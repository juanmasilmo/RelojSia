<?php

if (isset($_GET['f'])) {
  $function = $_GET['f'];
} else {
  $function = "";
}

session_start();
include("../../../inc/conexion.php");
$con = conectar();

if (function_exists($function)) {
  $function($con);
} else {
  echo "La funcion" . $function . "no exite...";
}

function get_agentes($con)
{

  $id_dependencia = $_GET['id_dependencia'];

  $sql = "SELECT legajo
                ,CONCAT(apellido,' ',nombres) nombre
          FROM personas
          WHERE id_dependencia = $id_dependencia
          ORDER BY apellido";
  $rs = pg_query($con, $sql);
  $res = pg_fetch_all($rs);

  if (pg_num_rows($rs) > 0) {

    echo json_encode($res);
  } else {

    echo json_encode(false);
  }
}


/**
 * Registros
 */
function get_reportes($con)
{
  $id_dependencia = $_GET['id_dependencia'];

  /**
   * Obtengo los legajos de la dependencia para filtrar los registros (columna 1 de la tabla)
   * Filtro los agentes con categoria superiores que no se lista en los reportes.-
   */
  $sql_legajo = "SELECT legajo
                        ,CONCAT(apellido,' ',nombres) as agente,
                      CASE WHEN (SELECT presentismo FROM categorias WHERE id = id_categoria) = 1 THEN 'SI'
                          WHEN (SELECT presentismo FROM categorias WHERE id = id_categoria) = 0 THEN 'NO'
                        END as presentismo,
                      CASE WHEN (SELECT pasajes FROM categorias WHERE id = id_categoria) = 1 THEN 'SI'
                          WHEN (SELECT pasajes FROM categorias WHERE id = id_categoria) = 0 THEN 'NO'
                        END as pasajes
                  FROM personas 
                  WHERE id_dependencia = $id_dependencia 
                        AND (SELECT listar_reporte FROM categorias WHERE id = id_categoria) = 1
                  ORDER BY apellido";
  $rs_legajo = pg_query($con, $sql_legajo);
  $res_legajo = pg_fetch_all($rs_legajo);

  $mes = $_GET['mes'];
  $anio = $_GET['anio'];

  /**
   * Creo una cadena para el sql
   */
  $legajos = '';

  //si la dependencia tiene agentes relacionados comienza todo el chimi
  if (pg_num_rows($rs_legajo) > 0) {

    for ($i = 0; $i < count($res_legajo); $i++) {
      if (isset($res_legajo[$i + 1])) {
        $legajos .= $res_legajo[$i]['legajo'] . ',';
      } else {
        $legajos .= $res_legajo[$i]['legajo'];
      }
    }
    
    // titulo de la tabla
    $cabecera = encabezado($con, $legajos, $mes, $anio);

    // control de hora 
    $hora_ingreso = 640;
    $hora_salida = 1230;

    // creo la matriz para el cuerpo de la tabla
    $cuerpoTabla = [];
    
    //por cada legajo
    foreach ($res_legajo as $legajo) { // => total 39
      
      $legajo_actual = $legajo['legajo'];
      //articulos - cantidad

      $dias_ausentes = dias_ausentes($con, $legajo_actual, $mes, $anio);
      $desc_pasajes = descuento_pasajes($con, $legajo_actual, $mes, $anio);
      $articulos = total_articulos_usados($con, $legajo_actual, $mes, $anio);
      $presentismo = cobra_presentismo($con, $legajo_actual, $mes, $anio);
      $vespertinos = dias_vespertinos($con, $legajo_actual, $mes, $anio);

      $cuerpoTabla[$legajo_actual]['agente'] =  $legajo['agente'].'('.$legajo['presentismo'].'/'.$legajo['pasajes'].')';
      $cuerpoTabla[$legajo_actual]['presentismo'] = $presentismo;
      $cuerpoTabla[$legajo_actual]['faltas'] = $dias_ausentes;
      $cuerpoTabla[$legajo_actual]['desc_pasajes'] = $desc_pasajes;
      $cuerpoTabla[$legajo_actual]['vespertinos'] = $vespertinos;
      $cuerpoTabla[$legajo_actual]['articulos'] = $articulos;

    } // FIN foreach($legajos)

    
    registros_html($con, $anio, $mes, $res_legajo, $cabecera, $cuerpoTabla);

  } else {

    echo json_encode(false);
  }
}


/**
 * ENCABEZADO TABLA
 */
function encabezado($con,$legajos,$mes,$anio){
  
  //obtengo los articulos y anexo dias vespertinos / descuento pasajes / presentismo

  $sql_encabezado = "SELECT id_articulo
                          ,(SELECT nro_articulo FROM articulos WHERE id = id_articulo) 
                          ,(SELECT descripcion FROM articulos WHERE id = id_articulo)
                        FROM calendario_agente as cagente 
                        WHERE EXTRACT(YEAR FROM registro) = $anio 
                            and EXTRACT(MONTH FROM registro) = $mes 
                            and borrado is null 
                            and legajo in ($legajos) 
                            and id_articulo is not null
                        GROUP BY id_articulo";
  $rs_encabezado = pg_query($con, $sql_encabezado);
  $res_encabezado = pg_fetch_all($rs_encabezado);
  
  // creo un array para el encabezado 
  $cabecera['agente'] = 'Agentes';
  $cabecera['colspan'] = pg_num_rows($rs_encabezado);
  $cabecera['presentismo'] = 'Presentismo';
  $cabecera['faltas'] = 'Faltas';
  $cabecera['desc_pasajes'] = 'Desc. Pasajes';
  $cabecera['vespertinos'] = 'Dias Vespertino';

  if(pg_num_rows($rs_encabezado) > 0){
    foreach ($res_encabezado as $value) {
      $cabecera[$value['id_articulo']] = $value['nro_articulo'];
    }
  } 
  // FIN encabezado

  return $cabecera;
}

/**
 * REGISTROS
 */
function registros($con, $legajos, $mes, $anio){

  
  if(strlen($legajos) > 6){
    $where_legajos = ' and legajo IN ('.$legajos.') ';
  }else{
    $where_legajos = ' and legajo = '.$legajos ;
  }

  $sql_registros_mes =  "SELECT legajo
                                ,EXTRACT(DAY FROM registro) as dia
                                ,EXTRACT(HOUR FROM registro) as hora
                                ,EXTRACT(MINUTE FROM registro) as minuto
                                ,id_articulo
                                ,leu 
                          FROM calendario_agente 
                          WHERE EXTRACT(MONTH FROM registro) = '$mes' 
                            and EXTRACT(YEAR FROM registro) = '$anio'
                            $where_legajos
                            and borrado is null
                          ORDER BY legajo,registro";
  $rs_registros_mes = pg_query($con, $sql_registros_mes);
  $res_registros_mes = pg_fetch_all($rs_registros_mes);
  (pg_num_rows($rs_registros_mes) > 0) ? $registros = $res_registros_mes : $registros = false;


  return $registros;

}

/**
 * FERIADOS
 */
function feriados($con, $mes, $anio){
  
  $sql_feriados = "SELECT EXTRACT(DAY FROM fecha_inicio) as dia 
                    FROM calendario_anual 
                    WHERE EXTRACT(MONTH FROM fecha_inicio) = $mes
                        and EXTRACT(YEAR FROM fecha_inicio) = $anio";
  $rs_feriados = pg_query($con, $sql_feriados);
  $res_feriados = pg_fetch_all($rs_feriados);
  (pg_num_rows($rs_feriados) > 0) ? $feriados = $res_feriados : $feriados = false;

  return $feriados;

}

/**
 * DIAS HABILES
 */
function dias_laborables($con, $mes, $anio){
  
  $total_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio); 
  $laborables = [];

  /**
   * Saco Sabados y Domingos del mes consultado
   */
  for ($i=1; $i < $total_dias+1; $i++) { 
    $d = $anio.'-'.$mes.'-'.$i;
    $x = intval(date_format(date_create($d), 'N'));
    
    if(!($x == '6') && !($x == '7') ){
      $laborables[] = $i;
    }
  }
  
  /**
   * Elimino los feriados del mes
   */
  $feriados = feriados($con, $mes, $anio);
  if($feriados){

    foreach ($feriados as $key => $value) {
      //saco los dias feriados del array
      if (array_search($value['dia'], $laborables)){
        $v_key = array_search($value['dia'], $laborables);
        unset($laborables[$v_key]);
      } 
    }
  
  }

  return $laborables;
}


/*--------------------------------------------------------------------*/
/**
 * POR CADA LEGAJOS NECESITO SABER
 * 
 * # Cantidad de articulos usados y su total
 * # Si cobra presentismo
 * # El total de dias a descontar pasajes
 * # Los dias que trabajo por la tarde
 * 
 */
/*-----------------------------------------------------------------------*/

/**
 * ARTICULOS 
 */
function total_articulos_usados($con, $legajo, $mes, $anio){

  $sql_leg_art_dep = "SELECT id_articulo
                          ,count(id_articulo)
                        FROM calendario_agente
                        WHERE EXTRACT(YEAR FROM registro) = $anio 
                          and EXTRACT(MONTH FROM registro) = $mes 
                          and borrado is null 
                          and legajo = $legajo
                          and id_articulo is not null 
                        GROUP BY legajo,id_articulo";
  $rs_leg_art_dep = pg_query($con, $sql_leg_art_dep);
  $res_leg_art_dep = pg_fetch_all($rs_leg_art_dep);                    
  (pg_num_rows($rs_leg_art_dep) > 0) ? $leg_art_dep = $res_leg_art_dep : $leg_art_dep = false;

  return $leg_art_dep;

}

/**
 *  PRESENTISMO
 */
function cobra_presentismo($con, $legajo, $mes, $anio){

    /**
     * NO -> Cobra Presentismo en los siguientes casos
     *  a) - Uso algun articulo que afecta al concepto.-
     *  b) - Falto en el mes y no justifico.-
     */
    
    // 0 = NO cobra / 1 = SI cobra
    
    /**
     * caso - a
     */  
    $sql_presentismo = "SELECT (SELECT cobra_presentismo FROM articulos WHERE id = id_articulo) as presentismo
                        FROM calendario_agente
                        WHERE EXTRACT(YEAR FROM registro) = $anio 
                          and EXTRACT(MONTH FROM registro) = $mes 
                          and id_articulo is not null
                          and borrado is null 
                          and legajo = $legajo
                        ORDER BY id asc
                        LIMIT 1"; 
    $rs_presentismo = pg_query($con, $sql_presentismo);
    $res_presentismo = pg_fetch_array($rs_presentismo);
    
    $presentismo = 'SI'; //por defecto cobra
    
    // si ya ocupo un articulo, finalizo consulta.-
    if(pg_num_rows($rs_presentismo) > 0 && $res_presentismo[0] == 0){
      $presentismo = 'NO';
    }
    else{ // sino, chequeo si no tiene dias sin registro/articulos
      
      /**
       * caso - b
       */ 
      $faltantes = 0;
      
      // traigo los dias que tiene registro.-
      $sql_faltantes = "SELECT EXTRACT(DAY FROM registro)
                          FROM calendario_agente
                          WHERE EXTRACT(YEAR FROM registro) = $anio
                              and EXTRACT(MONTH FROM registro) = $mes 
                              and borrado is null 
                              and legajo = $legajo
                          GROUP BY EXTRACT(DAY FROM registro)";
      $rs_faltantes = pg_query($con, $sql_faltantes);
      $res_faltantes = pg_fetch_array($rs_faltantes);    
      
      // si obtengo registros
      if(pg_num_rows($rs_faltantes) > 0){
        
        // obtengo el total trabajado
        $total = pg_num_rows($rs_faltantes);
        
        // obtengo la cantidad de dias habiles del mes - los feriados (estados)
        $x = count(dias_laborables($con, $mes, $anio));
        
        $faltantes = $x - $total;
        
        if($faltantes > 0)
          $presentismo = 'NO';
        
      } // FIN faltantes
      else {
        // sino, quiere decir que no tiene registros, por lo tanto no vino en todo el mes
        $presentismo = 'NO';
      }
    }
  return $presentismo;
} // FIN presentismo
  
/**
 *  REGISTRO DE FALTAS
 */
function dias_ausentes($con, $legajo, $mes, $anio){

  // obtengo la cantidad de dias que trabajo
  $sql_dias_ausentes = "SELECT distinct(extract (day from registro)) as day
                      FROM calendario_agente 
                      WHERE EXTRACT(YEAR FROM registro) = $anio
                        AND EXTRACT(MONTH FROM registro) = $mes
                        AND (id_articulo is null or id_articulo = (SELECT id FROM articulos WHERE nro_articulo = 'FP') or id_articulo not in (SELECT id FROM articulos WHERE desc_pasajes = 1))
                        AND borrado is null 
                        AND legajo = $legajo
                      GROUP BY registro";
  $rs_dias_ausentes = pg_query($con, $sql_dias_ausentes);
  $res_dias_ausentes = pg_fetch_all($rs_dias_ausentes); 
  $total_trabajados = pg_num_rows($rs_dias_ausentes);
  
  // Obtengo los dias habiles del mes en cuestion (menos los sabados/domingos/feriados)
  $dias_laborables = count(dias_laborables($con, $mes, $anio));
  
  //total_trabajados nunca podria superar a dias_laborables
  $dias_ausentes = $dias_laborables - $total_trabajados;
  
  return $dias_ausentes;
}

/**
 *  DESC. PASAJES
 */
function descuento_pasajes($con, $legajo, $mes, $anio){

  // verifico que la categoria del agente cobre pasajes
  $sql_categoria_pasajes = "SELECT (SELECT pasajes FROM categorias WHERE id = id_categoria) 
                              FROM personas 
                              WHERE legajo = '$legajo'";
  $rs_categoria_pasajes = pg_query($con, $sql_categoria_pasajes);
  $res_categoria_pasajes = pg_fetch_array($rs_categoria_pasajes);

  // si hay registro, y cobra pasajes
  if(pg_num_rows($rs_categoria_pasajes) > 0 && $res_categoria_pasajes['pasajes'] == 1){

    // obtengo la cantidad de dias que trabajo
    $sql_desc_pasajes = "SELECT distinct(extract (day from registro)) as day
                        FROM calendario_agente 
                        WHERE EXTRACT(YEAR FROM registro) = $anio
                          AND EXTRACT(MONTH FROM registro) = $mes
                          AND (id_articulo is null or id_articulo = (SELECT id FROM articulos WHERE nro_articulo = 'FP') or id_articulo not in (SELECT id FROM articulos WHERE desc_pasajes = 1))
                          AND borrado is null 
                          AND legajo = $legajo
                        GROUP BY registro";
    $rs_desc_pasajes = pg_query($con, $sql_desc_pasajes);
    $res_desc_pasajes = pg_fetch_all($rs_desc_pasajes); 
    $total_trabajados = pg_num_rows($rs_desc_pasajes);
    // $total_trabajados = $res_desc_pasajes[0];
    
    // Obtengo los dias habiles del mes en cuestion (menos los sabados/domingos/feriados)
    $dias_laborables = count(dias_laborables($con, $mes, $anio));
    
    //total_trabajados nunca podria superar a dias_laborables
    $desc_pasajes = $dias_laborables - $total_trabajados;
    
    // x2 (retorno la cantidad de pasajes a descontar, no la cantidad de dias)
    $desc_pasajes = $desc_pasajes * 2;
  }else{
    $desc_pasajes = 0;
  }

  return $desc_pasajes;
}

/**
 *  DIAS VESPERTINOS
 */
function dias_vespertinos($con, $legajo, $mes, $anio){

  $sql_vespertinos = "SELECT count(id)
                        FROM calendario_agente
                        WHERE EXTRACT(YEAR FROM registro) = $anio 
                          and EXTRACT(MONTH FROM registro) = $mes 
                          and (cast(EXTRACT(HOUR FROM registro) as integer) = 13) 
                          and legajo = $legajo
                          and borrado is null
                        GROUP BY legajo,id_articulo";
  $rs_vespertinos = pg_query($con, $sql_vespertinos);
  $res_vespertinos = pg_fetch_row($rs_vespertinos);

  (pg_num_rows($rs_vespertinos) > 0) ? $vespertinos = $res_vespertinos[0] : $vespertinos = 0;

  return $vespertinos;
}

function registros_html($con, $anio, $mes, $legajos, $cabecera, $cuerpoTabla) {
  

  // echo '<pre>';
  // var_dump($cuerpoTabla);
  // die();

  $colspan  = $cabecera['colspan'];
  unset($cabecera['colspan']);


  $dias_laborables = count(dias_laborables($con, $mes, $anio));
  $tabla = ' Dias habiles del mes : ' . $dias_laborables;
  $tabla .= '<br><table class="table table-hover" ><thead><tr><th class="bg-secondary text-white">ID</th>';
  
  foreach ($cabecera as $key => $value) {
    $tabla = $tabla . '<th class="bg-secondary text-white">' . $value . '</th>'; 
  }
 
  $tabla = $tabla . '</tr></thead><tbody>'; 
  $tr = '';

  unset($cabecera['agente']);
  unset($cabecera['presentismo']);
  unset($cabecera['faltas']);
  unset($cabecera['desc_pasajes']);
  unset($cabecera['vespertinos']);

  foreach ($legajos as $value) { // recorro los legajos
  
    $legajo = $value['legajo'];
  
    foreach ($cuerpoTabla as $agenteLegajo => $agenteRegistros) { // recorro los registros
      
      if($agenteLegajo == $legajo) /** si encuentra el agente */
      { 
        $tr = $tr . '<tr><td><b># ' . $legajo . '</b></td>';
        $tr = $tr . '<td><b>' . $agenteRegistros['agente'] . '</b></td>';
        $tr = $tr . '<td>' . $agenteRegistros['presentismo'] . '</td>';
        $tr = $tr . '<td>' . $agenteRegistros['faltas'] . '</td>';
        $tr = $tr . '<td>' . $agenteRegistros['desc_pasajes'] . '</td>';
        $tr = $tr . '<td>' . $agenteRegistros['vespertinos'] . '</td>';

        //articulos
        if($agenteRegistros['articulos']){ /** si tengo articulos  */
          
          $articulos_count = [];
          $posicion_columna = 0;
          
          foreach ($cabecera as $columnKey => $columnValue) {
            
            foreach ($agenteRegistros['articulos'] as $artKey => $artValue) { // recorro los aritculos

              if($artValue['id_articulo'] == $columnKey){ /** si encuentra el articulo cargo la cantidad */
                $articulos_count[$posicion_columna] = $artValue['count'];
              }

            }

            /** pregunto de cargo algo */
            if(count($articulos_count) > 0 && isset($articulos_count[$posicion_columna])){
              /** cargo si, entonces sumo la posicion */
            }else{
              /** sino cargo ningun articulo pongo 0 */
              $articulos_count[$posicion_columna] = 0;
            }
            $posicion_columna++;
      
          } // FIN foreach cabecera

          /** CARGO LOS ARTICULOS A LA TABLA */
          for ($iColumnTabla=0; $iColumnTabla < count($articulos_count); $iColumnTabla++) { 
            
            $tr = $tr . '<td>' . $articulos_count[$iColumnTabla]. '</td>';

          }

        }else{
          
          for ($iColumnTabla=0; $iColumnTabla < $colspan; $iColumnTabla++) { 
            
            $tr = $tr . '<td>0</td>';

          }
        }

        $tr = $tr . '</tr>';
  
      } // FIN if($legajo)
    }
  }
  
  $tabla = $tabla . $tr . '</tbody></table>';

  echo json_encode($tabla) ;

}
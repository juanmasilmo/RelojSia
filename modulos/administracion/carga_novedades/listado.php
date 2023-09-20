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
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Busqueda</h6>
  </div>
  <div class="content">
    <div class="card-body">

      <form action="" method="get">

        <div class="row">

        <!-- Dependencias -->

        <?php if(pg_num_rows($rs_dep) > 0){ ?>
            <div class="col-md-6">
              <label for="">Dependencia</label>
              <select class="form-control" onchange="listado_agentes(this.value)" name="id_dependencia" id="id_dependencia">
                <option value="0">Seleccionar ....</option>
                <?php foreach ($res_dependencia as $value) { ?>
                  <option value="<?php echo $value['id'] ?>"><?php echo $value['dep'] ?></option>  
                <?php } ?>
              </select>
            </div>
          <?php }else{ ?>
            <div class="col-md-6">
              <label for="">Dependencia</label>
              <input type="text" class="form-control" placeholder="buscar dependencia ..." name="dependencia"
                id="dependencia" size="60" onKeyUp="listar_dependencia(this.value);" value="" />
              <div id="lista_dependencia"></div>
            </div>

          <?php } ?>

         
          <!-- Filtro DATE -->
          <div class="col-md-2">
            <label for="">Mes</label>
            <select name="id_mes" id="id_mes" class="form-control">
              <option value="0" >Seleccionar...</option>
              <?php foreach($meses as $key => $mes) { ?>
                <option value="<?php echo $key ?>" <?php echo ($month == $key) ? "selected" : ""; ?> > <?php echo $mes ?> </option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-2">
            <label for="">Año</label>
            <select name="id_anio" id="id_anio" class="form-control" onchange="filtro_anio()">
              <option value="0">Seleccionar...</option>
              <?php if(!count($rs_anio) > 0){ ?>
                <option value="<?php echo $year ?>" ><?php echo $year ?></option>
              <?php } ?>

              <?php foreach ($res_anio as $anio) { ?>
                <option value="<?php echo $anio['anio'] ?>" <?php echo ($year == $anio['anio']) ? "selected" : ""; ?> > <?php echo $anio['anio'] ?> </option>
              <?php } ?>

            </select>
          </div>

          <div class="col-md-2">
            <label for=""></label>
            <input type="button" class="btn btn-primary form-control" value="Procesar" onclick="procesar()">            
          </div>

        </div>
        <div class="content">
          <div class="row" >
            <div class="col-md-12" id="div_tabla_agentes_registros"></div>
          </div>
        </div>

      </form>
      
    </div>
  </div>
</div>
<!-- FIN Formulario -->


<!-- MODAL -->
<div class="modal fade" id="modalCargaNovedades" tabindex="-1" role="dialog" aria-labelledby="modalCargaNovedadesLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCargaNovedadesLabel">Cargar Asistencia <small id='fecha_seleccionada'></small>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id="formulario_registros">

          <!-- agrego la fecha -->
          <div class="row">

            <div class="col-md-6">
              <label for="fecha"> Cargar Asistencia de la Fecha : </label>
              <input type="date" class="form-control" name="registro_fecha" min="<?php echo $min ?>" max="<?php echo $max ?>" id="registro_fecha" value="<?php echo $hoy; ?>">
              <div id="msj_registro_fecha" style="display:none;color:red">Campo Obligatorio</div>
            </div>

          </div>

          <br>
          <hr>

          <div class="row">
            <div class="col-md-12">
              <h5> Seleccionar marca </h5>
            </div>
          </div>
          
          <br>
          <!-- opciones a elegir -->
          <div class="row">
            
            <div class="col-md-6">
              <label for="fecha"> Firma Planilla (FP)</label>
              <input type="radio" class="form-control" checked name="registro_completo" id="registro_fp" value="fp">
            </div>
            
            <div class="col-md-6">
              <label for="fecha_inicio">Hora (6:30 - 12:30)</label>
              <input type="radio" class="form-control" name="registro_completo" id="registro_hora" value="hs">
            </div>
            <div class="col-md-12 ">
              <small><strong><i>PD: El registro impactara en todo el personal de la Dependencia.</i>-</strong></small>
            </div>
          </div>
          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" id="save-article-modal" onclick="guardarRegistroCompleto()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN MODAL -->



<!-- MODAL MODIFICACION REGISTRO LEGAJO-->
<div class="modal fade" id="modalModificacionNovedades" tabindex="-1" role="dialog" aria-labelledby="modalModificacionNovedadesLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalModificacionNovedadesLabel">Modificar Registro </h5>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id="formulario_modificacion_registros">

          <!-- agrego la fecha -->
          <div class="row">
              <div class="col-md-12">
                <p id="modificacion_registro_nombre_agente"></p>
                <input type="hidden" name="input_modificacion_registro_legajo" id="input_modificacion_registro_legajo">
                <p id="modificacion_registro_fecha"></p>
                <input type="hidden" name="input_modificacion_registro_fecha" id="input_modificacion_registro_fecha">
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-md-6">
              <label for="fecha"> Firma Planilla (FP)</label>
              <input type="checkbox" class="form-control" onclick="cambiarVisibilidadInputsFechas()"  name="checked_fp_modificacion_registro" id="checked_fp_modificacion_registro" value="fp">
            </div>
          </div>

          <hr>

          <div class="row" >
            <br>
            <div class="col-md-6">
              <label for="">Hora Ingreso</label>
              <input type="time" class="form-control inputs_fechas" name="fecha_ingreso" id="fecha_ingreso" value="06:30">
            </div>
            <div class="col-md-6">
              <label for="">Hora Salida</label>
              <input type="time" class="form-control inputs_fechas" name="fecha_salida" id="fecha_salida" value="12:30">
            </div>
          </div>
          
          <br>
          <!-- opciones a elegir -->
                    
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" id="save-article-modal" onclick="modificarRegistroCompleto()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN MODAL -->

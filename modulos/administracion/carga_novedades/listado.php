<?php
session_start();
include("../../../inc/conexion.php");
conectar();

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

//traigo el año actual para mostrar por defecto
$year = date('Y');
//traigo el mes actual para mostrar por defecto
$month = date('m');

$dt = new DateTime();

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
          <div class="col-md-6">
            <label for="">Dependencia</label>
            <input type="text" class="form-control" placeholder="buscar dependencia ..." name="dependencia"
              id="dependencia" size="60" onKeyUp="listar_dependencia(this.value);" value="" />
            <div id="lista_dependencia"></div>
          </div>

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
        <h5 class="modal-title" id="modalCargaNovedadesLabel">Cargar Articulo <small id='fecha_seleccionada'></small>
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
              <label for="fecha"> Cargar Asistencia de la Fecha : <h3><?php echo $dt->format('d-m-Y'); ?></h3></label>
              <input type="hidden" class="form-control" name="registro_fecha" id="registro_fecha" value="<?php echo $dt->format('Y-m-d'); ?>">

              <div id="msj_registro_fecha" style="display:none;color:red">Campo Obligatorio</div>
            </div>

          </div>

          <br>
          <hr>

          <div class="row">
            <div class="col-md-12">
              <h5> Seleccionar la opcion a cargar en el dia Completo </h5>
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

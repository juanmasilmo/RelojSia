<?php
session_start();
include("../../../inc/conexion.php");
conectar();



/**
 * Anios
 */
$sql_anio = "SELECT DISTINCT(TO_CHAR(registro, 'YYYY')) as anio
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
          <div class="row" id="div_tabla_agentes_registros"></div>
        </div>

      </form>
      
    </div>
  </div>
</div>
<!-- FIN Formulario -->

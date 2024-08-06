<script type="text/javascript" src="modulos/reportes/presentismos/funciones.js"></script>
<?php
// session_start();
// include("../../inc/conexion.php");
// conectar();

/**
 * Si recibo por GET la dependecia.-
 * En este caso la consulta viene por le lado de liquidaciones
 */
(isset($_GET['mes']) && $_GET['mes'] != 0) ? $id_mes = $_GET['mes'] : $id_mes = 11;
(isset($_GET['dep']) && $_GET['dep'] != 0) ? $id_dependencia = $_GET['dep'] : $id_dependencia = 285;
(isset($_GET['anio']) && $_GET['anio'] != 0) ? $id_anio = $_GET['anio'] : $id_anio = 2023;


if($id_dependencia != 0){ ?>

  <script>
    listado(<?php echo $id_dependencia; ?>,<?php echo $id_mes; ?>,<?php echo $id_anio; ?>);
  </script>

<?php } else{ 

$meses = [
            '1' => 'Enero',
            '2' => 'Febrero',
            '3' => 'Marzo',
            '4' => 'Abril',
            '5' => 'Mayo',
            '6' => 'Junio',
            '7' => 'Julio',
            '8' => 'Agosto',
            '9' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
          ];

//traigo el año actual para mostrar por defecto
$year = date('Y');
//traigo el mes actual para mostrar por defecto
$month = date('m');

?>



<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Presentismo</h1>
</div>

<div id="menu" class="form-group" align="left">

    <a class="btn btn-success btn-icon-split" onclick="window.history.go(-1)">
        <span class="icon text-white-50">
            <i class="fas fa-reply"></i>
        </span>
        <span class="text">Volver</span>
    </a>

    <br />

    <div id="over" class="spinner" style="display:none;"></div>
    <div id="fade" class="fadebox">&nbsp;</div>
    <hr>
</div>



<!-- Formulario -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Busqueda</h6>
  </div>
  <div class="content">
    <div class="card-body">

      <form action="" >

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
            <select name="id_anio" id="id_anio" class="form-control" >
              <option value="0">Seleccionar...</option>
              <?php if(!count($res_anio) > 0){ ?>
                <option value="<?php echo $year ?>" ><?php echo $year ?></option>
              <?php } ?>

              <?php foreach ($res_anio as $anio) { ?>
                <option value="<?php echo $anio['anio'] ?>" <?php echo ($year == $anio['anio']) ? "selected" : ""; ?> > <?php echo $anio['anio'] ?> </option>
              <?php } ?>

            </select>
          </div>

          <div class="col-md-2">
            <label for=""></label>
            <input type="button" class="btn btn-primary form-control" value="Procesar" onclick="listado(<?php echo $id_dependencia; ?>,<?php echo $id_mes; ?>,<?php echo $id_anio; ?>)">            
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
<?php } ?>
<div id="formulario" style="display: none;"></div>
<div id="mensaje" style="display: none;"></div>
<div id="listado" style="display: none;"></div>
<br>
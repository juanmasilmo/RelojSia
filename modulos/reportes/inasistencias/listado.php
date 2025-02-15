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
              <?php if(pg_num_rows($rs_dep) > 0){ ?>
              <!-- <select class="form-control" onchange="listado_agentes(this.value)" name="id_dependencia" id="id_dependencia"> -->
              <select class="form-control" name="id_dependencia" id="id_dependencia">
                <option value="0">Seleccionar ....</option>
                <?php foreach ($res_dependencia as $value) { ?>
                <option value="<?php echo $value['id'] ?>"><?php echo $value['dep'] ?></option>
                <?php } ?>
              </select>


              <?php }else{ ?>

              <input type="text" class="form-control" placeholder="buscar dependencia ..." name="dependencia"
                id="dependencia" size="60" onKeyUp="listar_dependencia(this.value);" value="" />
              <div id="lista_dependencia"></div>
              
              <?php } ?>

          </div>

          <!-- Filtro DATE -->
          <div class="col-md-2">
            <label for="">Mes</label>
            <select name="id_mes" id="id_mes" class="form-control">
              <option value="0">Seleccionar...</option>
              <?php foreach($meses as $key => $mes) { ?>
              <option value="<?php echo $key ?>" <?php echo ($month == $key) ? "selected" : ""; ?>> <?php echo $mes ?>
              </option>
              <?php } ?>
            </select>
          </div>

          <div class="col-md-2">
            <label for="">Año</label>
            <select name="id_anio" id="id_anio" class="form-control">
              <option value="0">Seleccionar...</option>
              <?php if(!count($res_anio) > 0){ ?>
              <option value="<?php echo $year ?>"><?php echo $year ?></option>
              <?php } ?>

              <?php foreach ($res_anio as $anio) { ?>
              <option value="<?php echo $anio['anio'] ?>" <?php echo ($year == $anio['anio']) ? "selected" : ""; ?>>
                <?php echo $anio['anio'] ?> </option>
              <?php } ?>
            </select>
          </div>

          <div class="col-md-2">
            <label for=""></label>
            <input type="button" class="btn btn-primary form-control" value="Procesar" onclick="procesar()">
          </div>

      </div>

      </form>
      <!-- FIN Formulario -->
      <br>

    </div>


  </div>

  <div class="col-md-12" id="div_tabla_agentes_registros"></div>


</div>
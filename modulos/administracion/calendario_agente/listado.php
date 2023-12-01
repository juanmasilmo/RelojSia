<?php
// session_start();

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
 * Articulos
 */
$sql_art = "SELECT id
                    ,nro_articulo
                    ,descripcion
            FROM articulos
            WHERE c_manual = 1
            ORDER BY nro_articulo";
$rs_art = pg_query($con, $sql_art);
$res_art = pg_fetch_all($rs_art);

/**
 * Estados
 */
$sql = "SELECT * FROM estados";
$rs = pg_query($sql);
$estados = pg_fetch_all($rs);

?>

<!-- Cabecera -->
<script src="modulos/administracion/calendario_agente/funciones.js"></script>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Calendario Agente</h1>
</div>

<div id="menu" class="form-group" align="left">
  <a class="btn btn-success btn-icon-split" onclick="window.history.go(-1)">
    <span class="icon text-white-50">
      <i class="fas fa-reply"></i>
    </span>
    <span class="text">Volver</span>
  </a>&nbsp;

  <br />
  <hr>
</div>
<!-- FIN Cabecera -->

<!-- Formulario -->
<?php include_once('inc/formulario.php') ?>
<!-- FIN Formulario -->

<!-- MODAL -->
<?php include_once('inc/carga_articulo.php') ?>
<!-- FIN MODAL -->


<!-- MODAL MODIFICACION REGISTRO LEGAJO-->
<?php include_once('inc/registro.php') ?>
<!-- FIN MODAL -->


<!-- Calendario -->
<style>
  .table-bordered td,
  .table-bordered th {
    border: 1px solid #9b9a9a;
  }
</style>

<div id="over" class="spinner" style="display:none"></div>
<div id="fade" class="fadebox" style="display:none">&nbsp;</div>

<!-- Tabla REFERENCIAS (costado del calendario) -->
<div class="conten">
  <div class="row">
    <div class="col-md-10" id='calendar'>

    </div>
    <div class="col-md-2" id="tabla_estados_calendario_agente" style="display:none">
      <table class="table table-striped">
        <thead>
          <tr>
            <th colspan="3" class="text-center">
              Referencias
            </th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($estados as $value) { ?>
        <tr>
            <td style="width:1%; background-color:<?php echo $value['color'] ?>">
            </td>
            <td>
              <strong>(<?php echo $value['letra'] ?>)</strong>
            </td>
            <td>
            <?php echo $value['descripcion'] ?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- FIN Calendario -->

<!-- Tabla Detalle Articulos -->
<?php include_once('inc/tabla_articulos.php') ?>
<!-- FIN Tabla Detalle Articulos -->
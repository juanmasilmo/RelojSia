<?php
// session_start();

$id_usuario = $_SESSION['userid'];


$id_mes = $_GET['mes'];
$id_dependencia = $_GET['dep'];

//obtengo los usuarios
$sql_personas = "SELECT legajo,apellido,nombres FROM personas WHERE id_dependencia = $id_dependencia";
$rs_personas = pg_query($con,$sql_personas);
$res_persnas = pg_fetch_all();


?>

<!-- Cabecera -->
<!-- <script src="modulos/reportes/presentismos/funciones.js"></script> -->

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

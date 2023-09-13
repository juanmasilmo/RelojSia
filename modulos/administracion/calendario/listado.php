
<?php
// session_start();
/**
 * Estados
 */
$sql = "SELECT * FROM estados";
$rs = pg_query($sql);
$estados = pg_fetch_all($rs);
?>


<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button> -->
<div id="prueba"></div>
<!-- Modal -->
<div class="modal fade" id="estadosModal" tabindex="-1" role="dialog" aria-labelledby="estadosModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="estadosModalLabel">Estados </h5>
        <h4 id=""> </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id="form_setting_calendar">
         
        <input type="hidden" name="evento_fecha_configurado" id="evento_fecha_configurado" value="0">

          <!-- Fecha       -->
          <label for="fecha"> Fecha: </label>
          <input type="hidden" name="input_fecha_inicio" id="input_fecha_inicio">
          <input type="hidden" name="input_fecha_fin" id="input_fecha_fin">
          <div class="row">
            <div class="col-md-6">
              <label for="fecha_inicio">Inicio</label>
              <b>
                <p id="modal_fecha_inicio"></p>
              </b>
            </div>
            <div class="col-md-6">
              <label for="fecha_fin">Fin</label>
              <b>
                <p id="modal_fecha_fin"></p>
              </b>
            </div>
          </div>
          <!-- Estados -->
          <label for="estados">(*) Estados</label>
          <select class="form-control" name="estados" id="id_estado">
            <option value="0">Seleccionar ...</option>
            <?php foreach ($estados as $value) { ?>
            <option value="<?php echo $value['id'] ?>"><?php echo $value['descripcion'] ?>
              (<?php echo $value['letra'] ?>)
            </option>
            <?php } ?>
          </select>
          <div class="div_msj_estados" id="div_msj_estados" style="display:none;color:red">Campo Obligatorio</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="save-modal" onclick="guardarEvento()" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- <div id="over" class="spinner"></div>
<div id="fade" class="fadebox">&nbsp;</div> -->
<div class="conten">
  <div class="row">
    <div class="col-md-10" id='calendar'>

    </div>
    <div class="col-md-2">
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
<script type="text/javascript" src="modulos/administracion/calendario/funciones.js"></script>
<script>
  
  
  /**
   * Fin Calendar
   */

  
</script>
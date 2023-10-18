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

          <!-- Cargo los ariticulos -->
          <div class="row">
            <div class="col-md-6">
              <label for="fecha"> Firma Planilla (FP)</label>
              <select name="id_art" id="id_art" class="form-control">
                <option value="null">Normal</option>
                <?php foreach ($res_art as $articulo) { ?>
                  <option value="<?php echo $articulo['id'] ?>"><?php echo $articulo['nro_articulo'] ?> <small>( <?php echo $articulo['descripcion'] ?>)</small> </option>
                <?php } ?>
              </select>
              <!-- <input type="checkbox" class="form-control" onclick="cambiarVisibilidadInputsFechas()"  name="checked_fp_modificacion_registro" id="checked_fp_modificacion_registro" value="fp"> -->
            </div>
          </div>

          
          <?php if($carga_registro == 1){ ?>
            <hr>

            <div class="row" >
              <div class="col-md-6 form-group inline">
                <label for="">Registrar Hora</label>
              </div>
              <div class="col-md-6 form-group inline">
                <input type="checkbox" class="form-control" onclick="cambiarVisibilidadInputsFechas()"  name="checked_fp_modificacion_registro" id="checked_fp_modificacion_registro" value="fp">
              </div>
              <br>
              <hr>
              
                <div class="col-md-6">
                  <label for="">Hora Ingreso</label>
                  <input type="time" disabled="disabled" class="form-control inputs_fechas" name="fecha_ingreso" id="fecha_ingreso" value="06:30">
                </div>
                <div class="col-md-6">
                  <label for="">Hora Salida</label>
                  <input type="time" disabled="disabled" class="form-control inputs_fechas" name="fecha_salida" id="fecha_salida" value="12:30">
                </div>
            </div>
          <?php } ?>
          
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
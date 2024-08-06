<div class="modal fade" id="estadosModalRegistro" tabindex="-1" role="dialog" aria-labelledby="estadosModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="estadosModalLabel">REGISTRO DE LA FECHA <p id='fecha_seleccionada'></p>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id="formulario_registros">

          <!-- id del registro -->
          <input type="hidden" name="id_registro" id="id_registro">
          <!-- fecha del registro -->
          <input type="hidden" name="fecha_registro" id="fecha_registro">

          <!-- Hora -->
          <label for="fecha"> Fecha: </label>
          <div class="row">
            <div class="col-md-6">
              <input type="time" class="form-control" name="hora_registro" id="hora_registro">
            </div>
            <div class="col-md-2">
              <input type="button" onclick="eliminar_registro()" class="btn btn-danger" value="x">
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="save-modal" onclick="modificar_registro()" class="btn btn-primary">Save
          changes</button>
      </div>
    </div>
  </div>
</div>
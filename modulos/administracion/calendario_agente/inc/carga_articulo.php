<div class="modal fade" id="modalSaveArticle" tabindex="-1" role="dialog" aria-labelledby="modalSaveArticleLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSaveArticleLabel">Cargar Articulo <small id='fecha_seleccionada'></small>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id="formulario_registros">

          <!-- id del registro -->
          <input type="hidden" name="id_db_articulo_configurado" id="id_db_articulo_configurado">
          <!-- fecha del registro -->
          <input type="hidden" name="fecha_registro" id="fecha_registro">
          <!-- fecha del registro -->
          <input type="hidden" name="fecha_registro_fin" id="fecha_registro_fin">

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


          <!-- Hora -->
          <label for="fecha"> Articulos: </label>
          <div class="row">
            <div class="col-md-6">
              <select name="id_articulo" id="id_articulo" class="form-control">
                <option value="0">Seleccionar ...</option>
                <?php foreach ($res_art as $articulo) { ?>
                  <option value="<?php echo $articulo['id'] ?>"><?php echo $articulo['nro_articulo'] ?> <small>( <?php echo $articulo['descripcion'] ?>)</small> </option>
                <?php } ?>
              </select>
            </div>
            <!-- <div class="col-md-2">
              <input type="button" onclick="eliminarEvento()" class="btn btn-danger" value="x">
            </div> -->
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="save-article-modal" onclick="guardarArticulo()" class="btn btn-primary">Save
          changes</button>
      </div>
    </div>
  </div>
</div>
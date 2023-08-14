<?php
// session_start();

/**
 * Dependencias
 */
$sql_dep = "SELECT id
                    ,CONCAT(descripcion, ' (', (SELECT descripcion FROM localidades WHERE id = id_localidad),')') as dep
            FROM dependencias";
$rs_dep = pg_query($con, $sql_dep);
$res_dep = pg_fetch_all($rs_dep);

/**
 * Articulos
 */
$sql_art = "SELECT id
                    ,nro_articulo
                    ,descripcion
            FROM articulos
            ORDER BY nro_articulo";
$rs_art = pg_query($con, $sql_art);
$res_art = pg_fetch_all($rs_art);

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
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Busqueda</h6>
  </div>
  <div class="card-body">

    <div class="content">
      <form action="" method="get">

        <div class="row">

          <!-- Dependencias -->
          <div class="col-md-6">
            <label for="">Dependencia</label>
            <input type="text" class="form-control" placeholder="buscar dependencia ..." name="dependencia"
              id="dependencia" size="60" onKeyUp="listar_dependencia(this.value);" value="" />
            <div id="lista_dependencia"></div>
          </div>

          <!-- Agentes -->
          <div class="col-md-6">
            <label for="">Agente</label>
            <select name="id_agente" id="id_agente" class="form-control" onchange="calendario_agente()">
              <!-- <option value="0">Seleccionar...</option> -->
            </select>
          </div>

        </div>

      </form>

    </div>
  </div>
</div>
<!-- FIN Formulario -->


<!-- MODAL -->
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
            <div class="col-md-2">
              <input type="button" onclick="eliminarEvento()" class="btn btn-danger" value="x">
            </div>
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

<!-- FIN MODAL -->

<!-- MODAL -->
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

<!-- FIN MODAL -->


<!-- Calendario -->
<style>
  .table-bordered td,
  .table-bordered th {
    border: 1px solid #9b9a9a;
  }
</style>
<div class="content">
  <div class="row">
    <div class=" col-md-10 offset-md-1">
      <div id='calendar'></div>
    </div>
  </div>
</div>
<!-- FIN Calendario -->

<!-- Tabla Detalle Articulos -->
<div class="content"  >
  <div class="row">
    <div class="col-md-12" id="div_msj_articulo_agente" style="display:none">
      <hr>
      <br>
      <div class="alert alert-warning">
        <strong id="msj_articulo_agente"></strong>
      </div>
    </div>
    <div class="col-md-12" id="div_articulos_agente" style="display:none">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>
              Articulo
            </th>
            <th>
              Descripcion
            </th>
            <th>
              Cantidad
            </th>
          </tr>
        </thead>
        <tbody id="articulos_agente">

        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- FIN Tabla Detalle Articulos -->
<!-- Cabecera -->
<script src="modulos/reportes/inasistencias/funciones.js"></script>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Reportes Inasistencias</h1>
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

<script>listado();</script>

<div id="formulario" style="display: none;"></div>
<div id="mensaje" style="display: none;"></div>
<div id="listado" style="display: none;"></div>
<div class="col-md-12" id="div_tabla_agentes_registros"></div>
<br>

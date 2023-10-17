<script type="text/javascript" src="modulos/administracion/sincronizar_leu/funciones.js"></script>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Sincronizar con Leu</h1>                        
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
  <div class="form-group row">
    <div class="col-md-2">  
       <label for="fecha_desde">Desde</label>
      <input class="form-control" type="date" name="fecha_desde" id="fecha_desde">
    </div>
    <div class="col-md-2">   
    <label for="fecha_hasta">Hasta</label> 
      <input class="form-control" type="date" name="fecha_hasta" id="fecha_hasta">
    </div>
    <div class="col-md-2">   
    <label for="Sincronizar">&nbsp;</label>  
      <a href="#" class="form-control btn btn-primary " onclick="actualizar(0);" id="Sincronizar">
        <span class="icon text-white-50">
          <i class="fas fa-sync-alt"></i>
        </span>
        <span class="text">Sincronizar LEU</span>
      </a>
    </div>
  </div>
<p>Seleccionar un rango de fecha que <b>no</b> sea superior a 31 días</p>

</div>

<div id="formulario" style="display: none;"></div>
<div id="mensaje" style="display: none;"></div>
<div id="listado" style="display: none;"></div>
<br>
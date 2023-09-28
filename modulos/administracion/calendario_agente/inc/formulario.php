<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Busqueda</h6>
  </div>
  <div class="card-body">

    <div class="content">
      <form action="" method="get">

        <div class="row">

          <!-- Dependencias -->

          <?php if(pg_num_rows($rs_dep) > 0){ ?>
            <div class="col-md-6">
              <label for="">Dependencia</label>
              <select class="form-control" onchange="listado_agentes(this.value)" name="id_dependencia" id="id_dependencia">
                <option value="0">Seleccionar ....</option>
                <?php foreach ($res_dependencia as $value) { ?>
                  <option value="<?php echo $value['id'] ?>"><?php echo $value['dep'] ?></option>  
                <?php } ?>
              </select>
            </div>
          <?php }else{ ?>
            <div class="col-md-6">
              <label for="">Dependencia</label>
              <input type="text" class="form-control" placeholder="buscar dependencia ..." name="dependencia"
                id="dependencia" size="60" onKeyUp="listar_dependencia(this.value);" value="" />
              <div id="lista_dependencia"></div>
            </div>

          <?php } ?>

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
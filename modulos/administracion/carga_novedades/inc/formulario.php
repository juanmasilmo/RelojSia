<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Busqueda</h6>
    </div>
    <div class="content">
        <div class="card-body">

            <form action="" method="get">

                <div class="row">

                    <!-- Dependencias -->
                    <?php if(pg_num_rows($rs_dep) > 0){ ?>
                        <div class="col-md-6">
                            <label for="">Dependencia</label>
                            <select class="form-control" name="id_dependencia" id="id_dependencia">
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


                    <!-- Filtro MES -->
                    <div class="col-md-2">
                        <label for="">Mes</label>
                        <select name="id_mes" id="id_mes" class="form-control">
                            <option value="0">Seleccionar...</option>
                            <?php foreach($meses as $key => $mes) { ?>
                            <option value="<?php echo $key ?>" <?php echo ($month == $key) ? "selected" : ""; ?>>
                                <?php echo $mes ?> </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Filtro Año -->
                    <div class="col-md-2">
                        <label for="">Año</label>
                        <!-- <select name="id_anio" id="id_anio" class="form-control" onchange="filtro_anio()"> -->
                        <select name="id_anio" id="id_anio" class="form-control" >
                            <option value="0">Seleccionar...</option>
                            <?php if(!count($res_anio) > 0){ ?>
                            <option value="<?php echo $year ?>"><?php echo $year ?></option>
                            <?php } ?>
                            <?php foreach ($res_anio as $anio) { ?>
                            <option value="<?php echo $anio['anio'] ?>"
                                <?php echo ($year == $anio['anio']) ? "selected" : ""; ?>> <?php echo $anio['anio'] ?>
                            </option>
                            <?php } ?>

                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for=""></label>
                        <input type="button" class="btn btn-primary form-control" value="Procesar" onclick="procesar()">
                    </div>

                </div>

                <div class="content">
                    <div class="row">
                        <div class="col-md-12" id="div_tabla_agentes_registros"></div>
                    </div>
                </div>


            </form>
        </div>
    </div>
</div>
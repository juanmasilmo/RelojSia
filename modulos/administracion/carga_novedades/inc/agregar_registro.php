<div class="modal fade" id="modalCargaNovedades" tabindex="-1" role="dialog" aria-labelledby="modalCargaNovedadesLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCargaNovedadesLabel">Cargar Asistencia <small
                        id='fecha_seleccionada'></small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="formulario_registros">

                    <!-- agrego la fecha -->
                    <div class="row">

                        <div class="col-md-6">
                            <label for="fecha"> Cargar Asistencia de la Fecha : </label>
                            <input type="date" class="form-control" name="registro_fecha" min="<?php echo $min ?>"
                                max="<?php echo $max ?>" id="registro_fecha" value="<?php echo $hoy; ?>">
                            <div id="msj_registro_fecha" style="display:none;color:red">No se puede modificar registros antiguos</div>
                        </div>

                    </div>

                    <br>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <h5> Seleccionar marca </h5>
                        </div>
                    </div>

                    <br>
                    <!-- opciones a elegir -->
                    <div class="row">

                        <div class="col-md-6">
                            <label for="fecha"> Firma Planilla (FP)</label>
                            <input type="radio" class="form-control" checked name="registro_completo" id="registro_fp"
                                value="fp">
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_inicio">Hora (6:30 - 12:30)</label>
                            <input type="radio" class="form-control" name="registro_completo" id="registro_hora"
                                value="hs">
                        </div>
                        <div class="col-md-12 ">
                            <small><strong><i>PD: El registro impactara en todo el personal de la
                                        Dependencia.</i>-</strong></small>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="save-article-modal" onclick="guardarRegistroCompleto()"
                    class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
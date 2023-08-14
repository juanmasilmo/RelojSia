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
        <form action="" id="form-setting-calendar">
          <input type="hidden" name="evento_fecha_configurado" id="evento_fecha_configurado" value="0">

          <!-- Fecha       -->
          <label for="fecha"> Fecha: </label>
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
          <select class="form-control" name="estados" id="estados">
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
        <button type="button" id="save-modal" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- <div id="over" class="spinner"></div>
<div id="fade" class="fadebox">&nbsp;</div> -->
<div id='calendar'></div>
<script type="text/javascript" src="modulos/administracion/calendario/funciones.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {


    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      // views: {
      //   listDay: { buttonText: 'list day' },
      //   listWeek: { buttonText: 'list week' }
      // },

      headerToolbar: {
        //left: 'prev,next today',
        left: '',
        center: 'title',
        right: ''
        //right: 'multiMonthYear,dayGridMonth,timeGridWeek'
      },
      selectMirror: true,
      // hiddenDays: [0], //ocultar dias
      allDayDefault: false,
      eventLimit: 1,
      selectable: true,
      select: function (arg) {
        /**
         * Verifico que NO se ingrese mas de 1 evento por fecha 
         */
        var fechaEventoActual = moment(arg.start).format("YYYY-MM-DD");
        $.ajax({
          url: "modulos/administracion/calendario/controlador.php?f=verificarDiaEventos&fecha=" +
            fechaEventoActual,
          type: 'post',
          dataType: 'JSON',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          success: function (response) {


            // si tiene evento pregunto que quiere hacer
            if (response.id) {
              optionsEvent(response,calendar);
            } else {
              // si no tiene evento levanto el modal

              $("#estadosModal").modal("show");

              $("#modal_fecha_inicio").text(moment(arg.start).format("DD-MM-YYYY"));
              $("#modal_fecha_fin").text(moment(arg.end).subtract(1).format("DD-MM-YYYY"));

              $("#save-modal").click(function () {
                guardarEvento(arg,calendar);
              });
            }
          }
        });
        calendar.unselect();
      },
      
      eventClick: function (arg) {
        optionsEvent(arg,calendar);
      },

      editable: true, //drag and drop  
      initialDate: '2023-01-01',
      // navLinks: true, // can click day/week names to navigate views
      businessHours: true, // display business hours
      droppable: true, // this allows things to be dropped onto the calendar
      initialView: 'multiMonthYear',
      themeSystem: 'bootstrap',
      locale: 'es', //
      dayMaxEvents: true, // allow "more" link when too many events
      // multiMonthMaxColumns: 1, // muestra los meses en una sola columna (no como almanaque)
      // showNonCurrentDates: true,
      // fixedWeekCount: false,
      weekends: false, // no muestra los Sab y Dom
      //events: getRegistros()
      /**
       * Traigo los registro de la DB
       */
      eventSources: [
        'modulos/administracion/calendario/controlador.php?f=registros'
      ]
    });
    calendar.render();
  });
  
  /**
   * Fin Calendar
   */

  
</script>
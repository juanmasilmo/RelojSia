<?php
session_start();
include("../../../inc/conexion.php");
conectar();


/**
 * Estados
 */
$sql = "SELECT * FROM estados";
$rs = pg_query($sql);
$estados = pg_fetch_all($rs);
?>

<div id='calendar'></div>
<script type="text/javascript" src="modulos/administracion/calendario/funciones.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {

 
   
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      // height: '100%',
      // expandRows: true,
      // slotMinTime: '08:00',
      // slotMaxTime: '20:00',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'multiMonthYear,dayGridMonth,timeGridWeek'
      },
      selectMirror: true,
      resources: {
        url: '.php',
        method: 'POST'
      },
      // select: function(arg) {
      //   var title = prompt('Event Title:');

      //   if (title) {
      //     calendar.addEvent({
      //       title: title,
      //       start: arg.start,
      //       end: arg.end,
      //       allDay: arg.allDay
      //     })
      //   }
      //   calendar.unselect()
      // },
      // eventClick: function(arg) {
      //   if (confirm('Are you sure you want to delete this event?')) {
      //     arg.event.remove()
      //   }
      // },
      editable: true,
      initialView: 'multiMonthYear',
      initialDate: '2023-01-01',
      // navLinks: true, // can click day/week names to navigate views
      businessHours: true, // display business hours
      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar
      initialView: 'multiMonthYear',
      themeSystem: 'bootstrap',
      selectable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      // multiMonthMaxColumns: 1, // muestra los meses en una sola columna (no como almanaque)
      // showNonCurrentDates: true,
      // fixedWeekCount: false,
      // weekends: false, // no muestra los Sab y Dom
      events: getRegistros()
    });

    calendar.render();
  });
</script>
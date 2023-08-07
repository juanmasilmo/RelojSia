let calendar;
let month;
let year

function cerrar_formulario()
{
    $("#formulario").css('display', 'none');
    $("#formulario").html('');
    $('#formulario').fadeOut('slow');
}

function cargar_icono()
{
    $("#icon").removeClass();
    $("#icon").addClass($("#icono").val());
}


function listar_dependencia(cadena) {
  $.get("sistema/dependencias/listado_dependencias.php?cadena=" + cadena, function (dato) {
    $("#lista_dependencia").html(dato);
  });
}
function seleccionar_dependencia(id) {
  $.get("sistema/dependencias/dependencia_seleccionada.php?id=" + id, function (dato) {
   
    $("#lista_dependencia").html(dato);
    
    // cargo el listado de agentes segun la dependencia seleccionada
    listado_agentes(id);
  });
}

function listado_agentes(id_dependencia) {
  $.ajax({
    type: 'get',
    url: "modulos/administracion/calendario_agente/controlador.php?f=listado_agentes&id_dependencia=" + id_dependencia,
    dataType: 'json',
    data: id_dependencia => id_dependencia,
    success: function(response) {
      // console.log(response[1].id);
      $("#id_agente").html('');
      for (i = 0; i < response.length; i++) {
          $("#id_agente").append(
              '<option value='+response[i].id+'>'+response[i].personal+'</option>'
          )
      }
    }
  });
}

function calendario_agente() {
  
  var id_agente = $("#id_agente").val();
  
  let calendarEl  = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarEl, {

    headerToolbar: {
      left: 'prev',
      center: 'title',
      right: 'next'
    },
    selectMirror: true,
    hiddenDays: [0], //ocultar dias
    // allDayDefault: false,
    // selectable: true,
    // editable: true, //drag and drop  
    // initialDate: '2023-01-01',
    // navLinks: true, // can click day/week names to navigate views
    businessHours: true, // display business hours
    // droppable: true, // this allows things to be dropped onto the calendar
    initialView: 'dayGridMonth',
    themeSystem: 'bootstrap',
    locale: 'es', 
    // dayMaxEvents: true, // allow "more" link when too many events
    // multiMonthMaxColumns: 1, // muestra los meses en una sola columna (no como almanaque)
    // showNonCurrentDates: true,
    // fixedWeekCount: false,
    // weekends: false, // no muestra los Sab y Dom
    //events: getRegistros()
    
    // eventClick: function (arg) {
    //   var tipo = arg.event.extendedProps.tipo;
    //   if(tipo === 'registro'){

    //     // muestro los horarios por si quiere modificar alguno a manopla (como anoche)
    //     $("#estadosModalRegistro").modal("show");
    //     $("#fecha_seleccionada").text(moment(arg.event.start).format("DD-MM-YYYY"));
    //     $("#fecha_registro").val(moment(arg.event.start).format("YYYY-MM-DD"));
    //     $("#id_registro").val(arg.event.id);
    //     $("#hora_registro").val(moment(arg.event.start).format("hh:mm"));

    //   }else{
    //     alert("No se puede modificar los eventos desde aca");
    //   }
    // },
    // dateClick: function (info){
    //   console.log(info.allDay);
    // },
    
    /**
     * Traigo los registro de la DB
     */
    eventSources: [
      {
        url: 'modulos/administracion/calendario/controlador.php?f=registros',
      },
      // {
      //   url: 'modulos/administracion/calendario_agente/controlador.php?f=registros&id_agente='+id_agente
      // },
      {
        url: 'modulos/administracion/calendario_agente/controlador.php?f=get_articulos&id_agente='+id_agente
      }
    ]
  });

  calendar.render();

  //muestro la table de articulos al pie del calendario
  $('#div_articulos_agente').css('display','block');

  

  $('body').on('click', 'button.fc-prev-button', function () {
    var tglCurrent = calendar.getDate();
    var date = new Date(tglCurrent);
    year = date.getFullYear();
    month = date.getMonth();
    get_articulos_agente(id_agente,month,year);
  });

  $('body').on('click', 'button.fc-next-button', function () {
    var tglCurrent = calendar.getDate();
    var date = new Date(tglCurrent);
    year = date.getFullYear();
    month = date.getMonth();
    get_articulos_agente(id_agente,month,year);
  });
  
  var tglCurrent = calendar.getDate();
  var date = new Date(tglCurrent);
  year = date.getFullYear();
  month = date.getMonth();
  get_articulos_agente(id_agente,month,year);

}


function modificar_registro(){

  var id_registro = $('#id_registro').val();

  $.post("modulos/administracion/calendario_agente/controlador.php?f=modificar_registro&id_registro=" + id_registro,$("#formulario_registros").serialize(),function(dato){
    $("#estadosModalRegistro").modal("hide");
    calendar.refetchEvents();
  });
}

function eliminar_registro(){

  var id_registro = $('#id_registro').val();
  $.ajax({
    type: 'get',
    url: "modulos/administracion/calendario_agente/controlador.php?f=eliminar_registro&id_registro=" + id_registro,
    dataType: 'json',
    success: function(response) {
      // console.log(response[1].id);
      $("#estadosModalRegistro").modal("hide");
      calendar.refetchEvents();
    }
  });
}

function get_articulos_agente(id_agente,month = null, year = null) {
  var month = month+1;
  $.ajax({
    type: 'post',
    url: "modulos/administracion/calendario_agente/controlador.php?f=get_articulos_agente&id_agente=" + id_agente + "&month=" + month + "&year=" + year,
    dataType: 'json',
    success: function(response) {
      
      if(!response == 0){
        var tabla = '';
        for (let index = 0; index < response.length; index++) {
          const element = response[index];
          tabla = tabla + "<tr><td>" + element.nro_articulo + "</td><td>" + element.title + "</td><td>" + element.cantidad  + "</td></tr>";
        }

        $("#div_msj_articulo_agente").css("display","none");
        $("#msj_articulo_agente").html("");
        
        $("#div_articulos_agente").css("display","block");
        $("#articulos_agente").html(tabla);

      }else{
        
        //oculto el div completo de la tabla
        $("#div_articulos_agente").css("display","none");
        //limpio la tabla
        $("#articulos_agente").html("");
        
        //muestro el div del msj
        $("#div_msj_articulo_agente").css("display","block");
        //agreo el mensaje del response
        $("#msj_articulo_agente").html('El Agente no presenta Articulos el mes actual');
      }
      
      // armo la tabla con los datos que obtengo de la funcion php
      // var tabla = '<tr><td>';
    }
  });
}

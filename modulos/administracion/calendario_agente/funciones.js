let calendar;
let month;
let year;
let clickedDayRef = [];
let countClicked = 0;

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
      $("#id_agente").append(
        '<option value="">Seleccionar...</option>'
      );
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
    unselectAuto: true,
    // allDayDefault: false,
    selectable: true,
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
 
    select: function (event) {

      $("#id_db_articulo_configurado").val("");
      // var fechaEventoActual = info.dateStr;
      var legajo = $("#id_agente").val();
      // document.getElementById("formulario_registros").reset();

      /**
       * Verifico que NO se ingrese mas de 1 evento por fecha 
       */
      var fechaInicioEventoActual = moment(event.start).format("YYYY-MM-DD");
      var fechaFinEventoActual = moment(event.end).subtract(1).format("YYYY-MM-DD");

      $.ajax({
        url: "modulos/administracion/calendario_agente/controlador.php?f=verificarDia&fecha=" + fechaInicioEventoActual + "&legajo=" + legajo + "&fecha_fin=" + fechaFinEventoActual,
        type: 'post',
        dataType: 'JSON',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        success: function (response) {
          // si tiene evento pregunto que quiere hacer
          if (response != 0) {
            // se envia el id / descripcion / fecha inicio / fecha fin
            optionsEvent(response);
          } else {
            // si no tiene evento levanto el modal

            $("#fecha_registro").val(fechaInicioEventoActual);
            // si no tiene evento levanto el modal
            $("#modalSaveArticle").modal("show");

            // $("#estadosModal").modal("show");

            $("#modal_fecha_inicio").text(moment(event.start).format("DD-MM-YYYY"));
            $("#modal_fecha_fin").text(moment(event.end).subtract(1).format("DD-MM-YYYY"));
            
            // // console.log(moment(event.start).format("YYYY-MM-DD"));
            $("#fecha_registro").val(fechaInicioEventoActual);
            $("#fecha_registro_fin").val(fechaFinEventoActual);

            // $("#save-modal").click(function () {
            //   guardarEvento(event);
            // });
          }
        }
      });
      calendar.unselect();
    },


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
        url: 'modulos/administracion/calendario_agente/controlador.php?f=get_articulos&id_agente='+id_agente,
      }
    ],
    eventOverlap: false
  });

  calendar.unselect();
  calendar.render();

  //muestro la table de articulos al pie del calendario
  $('#div_articulos_agente').css('display','block');
 

  $('body').on('click', 'button.fc-prev-button', function () {
    get_articulos_agente();
  });

  $('body').on('click', 'button.fc-next-button', function () {
    get_articulos_agente();
  });

  get_articulos_agente();
}


function modificar_registro(){

  var id_registro = $('#id_registro').val();

  $.post("modulos/administracion/calendario_agente/controlador.php?f=modificar_registro&id_registro=" + id_registro,$("#formulario_registros").serialize(),function(dato){
    $("#estadosModalRegistro").modal("hide");
   
    calendar.refetchEvents();
   
    get_articulos_agente();
  
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
      
      get_articulos_agente();
      
      calendar.refetchEvents();
    }
  });
}

function get_articulos_agente() {

  var id_agente = $("#id_agente").val();
  var tglCurrent = calendar.getDate();
  var date = new Date(tglCurrent);
  year = date.getFullYear();
  month = date.getMonth();
  

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

function optionsEvent(arg) {
  // si viene con evento preconfigurado

  $.confirm({
    title: 'Alertas!',
    content: "Evento <b><i> " + arg[1] +
      "</i></b> configurado!",
    icon: 'glyphicon glyphicon-question-sign',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
      'confirm': {
        text: 'REEMPLAZAR',
        btnClass: 'btn-green',
        // envio de datos SIN Dependencia
        action: function () {
          calendar.unselect();
          
          // agrego el id al form
          if (arg[2]) {
            $("#id_db_articulo_configurado").val(arg[2]);
          }

          // muestro el formulario 
          $("#modalSaveArticle").modal("show");
          document.querySelector('#id_articulo [value="' + arg[0] + '"]').selected = true;

        }
      },
      ELIMINAR: {
        btnClass: 'btn-red',
        action: function () {
          eliminarArticulo(arg,calendar);
        }
      },
      CANCELAR: {
        btnClass: 'btn-default',
        action: function () {
          //$.alert('hiciste clic en <strong>NO</strong>');
        }
      }
    }
  });
}

function guardarArticulo() {

  // guardo los datos del formulario
  var form = $("#formulario_registros").serialize();
  //la fecha ya esta seteada dentro del form

 
  // y el Legajo
  var legajo = $("#id_agente").val();
  
  if($("#id_articulo").val() == 0){
    $("#id_articulo").css("border","1px solid red");
    return false;
  }

  // var id_db_articulo_configurado = $("#id_db_articulo_configurado").val();
  // var id_articulo = $("#id_articulo").val();

  if (confirm('Desea guardar la configuración ?')) {
    $.ajax({
      url: 'modulos/administracion/calendario_agente/controlador.php?f=guardarArticulo',
      type: "POST",
      dataType: "JSON",
      data: {
        legajo,
        form
      },
      success: function (response) {
        calendar.refetchEvents();
        get_articulos_agente();
        $("#modalSaveArticle").modal("hide");
      }
    });
  } else {
    $("#modalSaveArticle").modal("hide");
  }
}

function eliminarArticulo(arg) {

  if (confirm('Eliminar Articulo actual  ' + arg[1] + ' ?')) {
    // console.log(arg.id);
    var id_articulo = arg[0];
    var id = arg[2];
    
    $.ajax({
      url: 'modulos/administracion/calendario_agente/controlador.php?f=eliminarArticulo',
      type: "POST",
      dataType: "JSON",
      data: {
        id_articulo,
        id
      },
      success: function (response) {
        calendar.refetchEvents();
        get_articulos_agente();
        $("#modalSaveArticle").modal("hide");
      }
    });
  } else {
    $("#modalSaveArticle").modal("hide");
  }

}


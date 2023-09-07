let calendar = '';


document.addEventListener('DOMContentLoaded', function () {


  var calendarEl = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarEl, {
    // views: {
    //   listDay: { buttonText: 'list day' },
    //   listWeek: { buttonText: 'list week' }
    // },
    headerToolbar: {
      left: 'prev,next',
      center: 'title',
      right: 'today'
    },
    selectMirror: true,
    // hiddenDays: [0], //ocultar dias
    allDayDefault: false,
    eventLimit: 1,
    selectable: true,
    locale: 'deLocale',
    select: function (event) {

      // console.log(event);

      /**
       * Verifico que NO se ingrese mas de 1 evento por fecha 
       */
      var fechaInicioEventoActual = moment(event.start).format("YYYY-MM-DD");
      var fechaFinEventoActual = moment(event.end).subtract(1).format("YYYY-MM-DD");

      $.ajax({
        url: "modulos/administracion/calendario/controlador.php?f=verificarDiaEventos&fechaInicio=" + fechaInicioEventoActual + "&fechaFin=" + fechaFinEventoActual,
        type: 'post',
        dataType: 'JSON',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        success: function (response) {
         
          // si tiene evento pregunto que quiere hacer
          if (response.id) {
            // se envia el id / descripcion / fecha inicio / fecha fin
            optionsEvent(response);
          } else {
            // si no tiene evento levanto el modal

            $("#estadosModal").modal("show");

            $("#modal_fecha_inicio").text(moment(event.start).format("DD-MM-YYYY"));
            $("#modal_fecha_fin").text(moment(event.end).subtract(1).format("DD-MM-YYYY"));
            
            // console.log(moment(event.start).format("YYYY-MM-DD"));
            $("#input_fecha_inicio").val(fechaInicioEventoActual);
            $("#input_fecha_fin").val(fechaFinEventoActual);

            // $("#save-modal").click(function () {
            //   guardarEvento(event);
            // });
          }
        }
      });
      calendar.unselect();
    },
    // dateClick: function(){
    //   alert();
    // },
    
    eventClick: function (arg) {
      // console.log(arg.event.extendedProps);
      //envio el id / descripcion
      optionsEvent(arg.event.extendedProps);
    },

    // editable: true, //drag and drop  
    // initialDate: '2023-01-01',
    // navLinks: true, // can click day/week names to navigate views
    businessHours: true, // display business hours
    // droppable: true, // this allows things to be dropped onto the calendar
    initialView: 'dayGridMonth',
    themeSystem: 'bootstrap',
    locale: 'es', //
    dayMaxEvents: true, // allow "more" link when too many events
    // multiMonthMaxColumns: 1, // muestra los meses en una sola columna (no como almanaque)
    // showNonCurrentDates: true,
    // fixedWeekCount: false,
    // weekends: false, // no muestra los Sab y Dom
    //events: getRegistros()
    /**
     * Traigo los registro de la DB
     */
    // eventRender: function(info) {
      //   alert();
      //   // var tooltip = new Tooltip(info.el, {
        //   //   title: info.event.extendedProps.description,//you can give data for tooltip
        //   //   placement: 'top',
        //   //   trigger: 'hover',
        //   //   container: 'body'
        //   // });
        // },
    eventSources: [
      'modulos/administracion/calendario/controlador.php?f=registros',
    ],
  });
  calendar.render();
});



function optionsEvent(event) {

  var descripcion = event.descripcion;
  if(event.id)
    var id  = event.id;
  if(event.event_id)
    var id = event.event_id;
  var fecha_inicio = event.fecha_inicio;
  var fecha_fin = event.fecha_fin;
  var id_estado = event.id_estado;
 
  $.confirm({
    title: 'Alertas!',
    content: "Evento <b><i> " + descripcion + "</i></b> configurado!",
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

          $("#evento_fecha_configurado").val(id);
          $("#id_estado").val(id_estado).change();

          // muestro el formulario 
          $("#estadosModal").modal("show");

          $("#modal_fecha_inicio").text(fecha_inicio);
          $("#modal_fecha_fin").text(fecha_fin);

          // console.log(moment(event.start).format("YYYY-MM-DD"));
          $("#input_fecha_inicio").val(fecha_inicio);
          $("#input_fecha_fin").val(fecha_fin);


          // $("#save-modal").click(function () {
          //   guardarEvento(arg);
          // });

        }
      },
      ELIMINAR: {
        btnClass: 'btn-red',
        action: function () {
          eliminarEvento(id,descripcion);
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


function guardarEvento() {

  var id_estado_configurado = $("#evento_fecha_configurado").val();
  // alert(id_estado_configurado);
  var id_estado = $("#id_estado").val();
  var start_date = $("#input_fecha_inicio").val();
  var end_date = $("#input_fecha_fin").val();

  if(id_estado != 0){
    $("#id_estado").css("border","1px solid ");
    $("#div_msj_estados").css("display","none");

    $.confirm({
      title: 'Alertas!',
      content: 'Desea guardar la configuración para la fecha seleccionada ?',
      icon: 'glyphicon glyphicon-question-sign',
      animation: 'scale',
      closeAnimation: 'scale',
      opacity: 0.5,
      buttons: {
        'confirm': {
          text: 'Si',
          btnClass: 'btn-green',
          // envio de datos SIN Dependencia
          action: function () {
            
            $.ajax({
              url: 'modulos/administracion/calendario/controlador.php?f=calendarioDia',
              type: "POST",
              dataType: "JSON",
              data: {
                id_estado_configurado,
                id_estado,
                start_date,
                end_date
              },
              success: function (response) {
                
                document.getElementById("form_setting_calendar").reset();
                
                calendar.refetchEvents();
                $("#estadosModal").modal("hide");
              }
            });
  
          }
        },
        No: {
          btnClass: 'btn-red',
          action: function () {
            $("#estadosModal").modal("hide");
          }
        }
      }
    });


    // if (confirm('Desea guardar la configuración para la fecha seleccionada ?')) {
      
    //   $.ajax({
    //     url: 'modulos/administracion/calendario/controlador.php?f=calendarioDia',
    //     type: "POST",
    //     dataType: "JSON",
    //     data: {
    //       id_estado_configurado,
    //       id_estado,
    //       start_date,
    //       end_date
    //     },
    //     success: function (response) {
          
    //       document.getElementById("form_setting_calendar").reset();
          
    //       calendar.refetchEvents();
    //       $("#estadosModal").modal("hide");
    //     }
    //   });
    // } else {
    //   $("#estadosModal").modal("hide");
    // }
    
  }else{
    $("#id_estado").css("border","1px solid red");
    $("#div_msj_estados").css("display","block");
  }
}



function editar(id)
{
     $('html').animate({
      scrollTop: $("html").offset().top
  }, 0);
    $.get("modulos/administracion/calendario/formulario.php?id="+id,function(dato){
        $("#formulario").css('display', 'block');
        $("#formulario").html(dato);
        $('#formulario').fadeIn('slow');
    });
}

// function showLightbox() {
//     document.getElementById('over').style.display = 'block';
//     document.getElementById('fade').style.display = 'block';
// }

// function hideLightbox() {
//     document.getElementById('over').style.display = 'none';
//     document.getElementById('fade').style.display = 'none';
// }

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

function listado()
{
     $('html').animate({
      scrollTop: $("html").offset().top
  }, 0);
    $.get("modulos/administracion/calendario/listado.php",function(dato){
        $("#listado").css('display', 'block');
        $("#listado").html(dato);
        $('#listado').fadeIn('slow');
   
    });
}

function getRegistros() {
    fetch('modulos/administracion/calendario/controlador.php?f=getRegistros',{
        type: 'post',
        dataType: 'JSON',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        success: function(response){

            return response.json();
            // for (var i = 0; i < response.length; i++) {
            //     window.registros.push(response[i]).json;
            //   }
        }
    });
}


function validar_formulario(){


    if ($("#descripcion").val().length < 3) {
        $("#descripcion").focus();          
        return 0;
    }  
    
}


function guardar()
{
  if(validar_formulario()==0){
      $("#form").addClass('was-validated');
      return;
  }
  $.confirm({
    title: 'Guardar!',
    content: 'Desea <b>Guardar</b> el <b> Registro</b>?',
    icon: 'far fa-question-circle',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
      'confirm': {
        text: 'SI',
        btnClass: 'btn-green',
        action: function(){
        //accion
          $.post("modulos/administracion/calendario/controlador.php?f=editar",$("#form").serialize(),function(dato){
            $("#mensaje").css('display', 'block');
            $("#mensaje").html(dato);
            $('#mensaje').fadeIn('slow');        
            listado();
          });
        //fin de accion
        }
      },
      NO: {
        btnClass: 'btn-red',
        action:function(){
              //$.alert('hiciste clic en <strong>NO</strong>');
          }
      },
    }
  });
}


function eliminar(id){
  $.confirm({
    title: 'Confirmar Acción',
    content: 'Desea eliminar el registro?',
    icon: 'far fa-question-circle',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
        confirm: {
            text: 'SI',
            btnClass: 'btn-green', 
            action: function () {         
                //accion de eliminar
                $.post("modulos/administracion/calendario/controlador.php?f=eliminar",{id:id},function(dato){
                  $("#mensaje").css('display', 'block');
                  $("#mensaje").html(dato);
                  $('#mensaje').fadeIn('slow');   
                  listado();                                  
              });
                //fin de accion eliminar
            }
        },
        cancel: {
            text: 'NO',
            btnClass: 'btn-red',
            action: function () {

            }
        }
    }
  });
}


function eliminarEvento(id,title) {

  var id_evento = id;

  $.confirm({
    title: 'Eliminar Evento  ' + title + ' ?',
    content: 'Desea eliminar el registro?',
    icon: 'far fa-question-circle',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
        confirm: {
            text: 'SI',
            btnClass: 'btn-green', 
            action: function () {         
                //accion de eliminar
                $.post("modulos/administracion/calendario/controlador.php?f=eliminarEvento",{id_evento:id_evento},function(dato){
                  calendar.refetchEvents();
                  $("#estadosModal").modal("hide");                    
                });
                //fin de accion eliminar
            }
        },
        cancel: {
            text: 'NO',
            btnClass: 'btn-red',
            action: function () {
              $("#estadosModal").modal("hide");
            }
        }
    }
  });

  // if (confirm()) {
  //   // console.log(arg.id);
    
  //   // arg.event.remove();
  //   $.ajax({
  //     url: 'modulos/administracion/calendario/controlador.php?f=eliminarEvento',
  //     type: "POST",
  //     dataType: "JSON",
  //     data: {
  //       id_evento
  //     },
  //     success: function (response) {
  //       calendar.refetchEvents();
  //       $("#estadosModal").modal("hide");
  //     }
  //   });
  // } else {
  //   $("#estadosModal").modal("hide");
  // }

}
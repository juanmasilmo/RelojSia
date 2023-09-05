let calendar = '';


function listado()
{
     $('html').animate({
      scrollTop: $("html").offset().top
  }, 0);
    $.get("modulos/administracion/planilla_mensual/listado.php",function(dato){
        $("#listado").css('display', 'block');
        $("#listado").html(dato);
        $('#listado').fadeIn('slow');
   
    });
}

function listar_dependencia(cadena) {
  $.get("sistema/dependencias/listado_dependencias.php?cadena=" + cadena, function (dato) {
    $("#lista_dependencia").html(dato);
  });
}
function seleccionar_dependencia(id) {
  $.get("sistema/dependencias/dependencia_seleccionada.php?id=" + id, function (dato) {
   
    $("#lista_dependencia").html(dato);
  
  });
}

function procesar() {
  //controlar input dependencia
  if(!$("#id_dependencia").val() || $("#id_dependencia").val() == 'undefined'){
    $("#dependencia").css("border","1px solid red");
    return false;
  }else{
    $("#dependencia").css("border","1px solid #6e707e");
  }
  
  var id_dependencia  = $("#id_dependencia").val();
  // console.log(id_dependencia);
  //cargar datos en la tabla 
  let colums = 31;
  let filas = 0;

  $.get("modulos/administracion/planilla_mensual/controlador.php?f=get_agentes&id_dependencia=" + id_dependencia, function(dato){
    
    // console.log(dato);
    if(dato != 'false'){
      
      arma_tabla();
    
    }else{
      
      //limpio la tabla de agentes
      $("#div_tabla_agentes_registros").html("");

      alert("La depedencia seleccionada no tiene agentes relacionados");
      
    }

  });

}



function arma_tabla() {
  
  var id_dependencia  = $("#id_dependencia").val();
  var mes = $("#id_mes").val();
  var anio = $("#id_anio").val();

  let url = 'modulos/administracion/planilla_mensual/controlador.php?f=get_registros_agentes&id_dependencia=' + id_dependencia + '&mes=' + mes + '&anio=' + anio;
  
  $.get(url, function(data) {

    if(data != 'false'){

      var marcas = JSON.parse(data);

      var agentes = marcas.legajos;
      var registros = marcas.registros;

      var total_dias = new Date(anio, mes, 0).getDate(); //obtengo la cantidad de dias del mes para armado de la tabla 
    
      /**
       * Cabecera Tabla
       */
      var tabla = "<table id='tabla_agentes_registros' class='table table-responsive table-bordered' ><thead><tr><th rowspan='2'>Agentes</th><th class='text-center' colspan='31'>Dias</th></tr><tr>";
      
      for (var i = 1; i < total_dias+1; i++){
        
          tabla += "<th style='font-size:8px'>" + i + "</th>";
        
      }
      tabla += "</tr></thead><tbody style='font-size:8.5px'>";
        
      /**
       * Cuerpo Tabla
       */
      agentes.forEach(function(agente){
        
        tabla += "<tr><td id='agentes'>" + agente.nombre + "</td>";

        if(!registros){

          for (var dia = 1; dia < total_dias+1; dia++){
            tabla += "<td id='legajo"+agente.legajo+"'> </td>";
          }
          tabla += "</tr>";

        }else{

          for (var dia = 1; dia < total_dias+1; dia++){
            
            tabla += "<td id='legajo"+agente.legajo+"' style='text-wrap: wrap' ";
            var registro_marca = '';
            var background_color = '';
  
            //por cada dia recorro los registros 
            registros.forEach(function(registro){
              
              // si el dia tiene registro muestro o articulo
              if(agente.legajo == registro.legajo && dia == registro.dia){

                //verifico que no este vacio el articulo o no sea null
                var nro_articulo = '';
                if(registro.nro_articulo){
                  
                  nro_articulo = registro.nro_articulo;
                  background_color = '#FF7777';
                 
                  if(nro_articulo == 293){
                    background_color = '#6BFF57';
                  }
                
                }
                
                //verifico que tenga registro
                if(registro.hora && registro.hora != 0){
                
                  //pregunto si es mayor a 6hs am
                  if((registro.hora > 6 && registro.minutos > 40) || ((registro.hora > 9) && (registro.hora < 12 && registro.minutos < 30)))
                    //llega tarde o sale temprano (justificar)
                    background_color = '#b3b300';

                  registro_marca += nro_articulo + ' ' + registro.hora +':'+ registro.minutos;
                
                }else{
                  if(nro_articulo){
                    registro_marca += nro_articulo + ' ';
                  }
                }
  
              }
              
              // tabla += nro_articulo + " <br> " + registro_marca + " <br> ";
              
            }); //fin foreach registros
            
            //cierro el td de apertura
            tabla += 'bgcolor="'+ background_color +'">';

            //imprimo los registros
            tabla += registro_marca;

            //cierro el td
            tabla +=  "</td>";
              
          } // fin for dias
  
          tabla += "</tr>";

        }
                   
      }); //fin foreach agentes        
             
      tabla += "</tbody></table>";
      // console.log(tabla);

      $("#div_tabla_agentes_registros").html(tabla);

      // $('#tabla_agentes_registros').DataTable();
      
    }

  }); // gin $.get

}


//--------------------*--------*---------------------

/**
 * FullCalendar
 */

document.addEventListener('DOMContentLoaded', function () {


  var calendarEl = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarEl, {
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
    select: function (event) {

      console.log(event);

      /**
       * Verifico que NO se ingrese mas de 1 evento por fecha 
       */
      var fechaInicioEventoActual = moment(event.start).format("YYYY-MM-DD");
      var fechaFinEventoActual = moment(event.end).format("YYYY-MM-DD");

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

            $("#modal_fecha_inicio").text(fechaInicioEventoActual);
            $("#modal_fecha_fin").text(fechaFinEventoActual);
            
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
    initialDate: '2023-01-01',
    // navLinks: true, // can click day/week names to navigate views
    businessHours: true, // display business hours
    // droppable: true, // this allows things to be dropped onto the calendar
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
 * 
 * FIN FullCalendar
 */


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
    if (confirm('Desea guardar la configuración del  ' + start_date + ' ?')) {
      
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
    } else {
      $("#estadosModal").modal("hide");
    }
    
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

    console.log(id,' ',title);
    if (confirm('Eliminar Evento actual  ' + title + ' ?')) {
      // console.log(arg.id);
      var id_evento = id;
      // arg.event.remove();
      $.ajax({
        url: 'modulos/administracion/calendario/controlador.php?f=eliminarEvento',
        type: "POST",
        dataType: "JSON",
        data: {
          id_evento
        },
        success: function (response) {
          calendar.refetchEvents();
          $("#estadosModal").modal("hide");
        }
      });
    } else {
      $("#estadosModal").modal("hide");
    }

  }
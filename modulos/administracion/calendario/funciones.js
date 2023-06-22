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

function guardarEvento(arg,calendar) {
    var id_estado_configurado = $("#evento_fecha_configurado").val();
    // alert(id_estado_configurado);
    var id_estado = $("#estados").val();
    var start_date = moment(arg.start).format("YYYY-MM-DD");
    var end_date = moment(arg.end).format("YYYY-MM-DD");

    if (confirm('Desea guardar la configuración del  ' + moment(arg.start).format("DD-MM-YYYY") + ' ?')) {
      // arg.event.remove();
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
          calendar.refetchEvents();
          $("#estadosModal").modal("hide");
        }
      });
    } else {
      $("#estadosModal").modal("hide");
    }

  }

  function optionsEvent(arg,calendar) {

    // si viene con evento preconfigurado
    if (arg.event) {
      var arg = arg.event;
    } else {
      // si viene un evento nuevo
      var arg = arg
    }

    // console.log(arg);
    $.confirm({
      title: 'Alertas!',
      content: "Evento <b><i> " + arg.title +
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

            if (arg.id) {
              $("#evento_fecha_configurado").val(arg.id);
            }

            // muestro el formulario 
            $("#estadosModal").modal("show");

            $("#modal_fecha_inicio").text(moment(arg.start).format("DD-MM-YYYY"));
            $("#modal_fecha_fin").text(moment(arg.end).subtract(1).format("DD-MM-YYYY"));

            $("#save-modal").click(function () {
              guardarEvento(arg,calendar);
            });

          }
        },
        ELIMINAR: {
          btnClass: 'btn-red',
          action: function () {
            eliminarEvento(arg,calendar);
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

  function eliminarEvento(arg,calendar) {

    if (confirm('Eliminar Evento actual  ' + arg.title + ' ?')) {
      // console.log(arg.id);
      var id_evento = arg.id;
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
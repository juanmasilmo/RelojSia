function editar(id)
{
    $('html').animate({
      scrollTop: $("html").offset().top
  }, 0);
    $.get("modulos/administracion/usuarios/formulario.php?id="+id,function(dato){
        $("#formulario").css('display', 'block');
        $("#formulario").html(dato);
        $('#formulario').fadeIn('slow');
    });
}

function cerrar_formulario()
{
    $("#formulario").css('display', 'none');
    $("#formulario").html('');
    $('#formulario').fadeOut('slow');
}

function listado()
{
    $('html').animate({
      scrollTop: $("html").offset().top
  }, 0);
    $.get("modulos/administracion/usuarios/listado.php",function(dato){
        $("#listado").css('display', 'block');
        $("#listado").html(dato);
        $('#listado').fadeIn('slow');

        $('#dataTable').DataTable({

          language: {
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sProcessing":     "Procesando...",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Siguiente",
              "sPrevious": "Anterior"
          }
      }
  });        
    });
}


function validar_formulario(){

    if ($("#usuario").val().length < 3) {
        $("#usuario").focus();          
        return 0;
    }
     if ($("#nombre_apellido").val().length < 3) {
        $("#nombre_apellido").focus();          
        return 0;
    }

    if ($("#id_grupo option:selected").text()==='Seleccionar') {
        $("#id_grupo").focus();          
        return 0;
    }    
}

function guardar()
{
    if(validar_formulario()==0){
        $("#formulario").addClass('was-validated');
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

              $.post("modulos/administracion/usuarios/controlador.php?f=editar",$("#form").serialize(),function(dato){
                 $("#mensaje").css('display', 'block');
                 $("#mensaje").html(dato);
                 $('#mensaje').fadeIn('slow');
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
                $.post("modulos/administracion/usuarios/controlador.php?f=eliminar",{id:id},function(dato){
                  $("#mensaje").css('display', 'block');
                  $("#mensaje").html(dato);
                  $('#mensaje').fadeIn('slow');
                  listado();      
                  cerrar_formulario();                            
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



function resetear_clave(id){
  $.confirm({
    title: 'Confirmar Acción',
    content: 'Desea <b>Resetear la Clave</b>?',
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
          $.post("modulos/administracion/usuarios/controlador.php?f=resetear_clave",{id:id},function(dato){
            $("#mensaje").html(dato);
            $('#mensaje').fadeIn('slow');
            listado();
            cerrar_formulario();
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

function bloquear_usuario(id){
  $.confirm({
    title: 'Confirmar Acción',
    content: 'Desea <b>Bloquear el usuario</b>?',
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
                  $.post("modulos/administracion/usuarios/controlador.php?f=bloquear_usuario",{id:id},function(dato){
                    $("#mensaje").html(dato);
                    $('#mensaje').fadeIn('slow');
                    listado();
                    cerrar_formulario();
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


function activar_usuario(id){
  $.confirm({
    title: 'Confirmar Acción',
    content: 'Desea <b>Activar el usuario</b>?',
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
                  $.post("modulos/administracion/usuarios/controlador.php?f=activar_usuario",{id:id},function(dato){
                    $("#mensaje").html(dato);
                    $('#mensaje').fadeIn('slow');
                    listado();
                    cerrar_formulario();
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
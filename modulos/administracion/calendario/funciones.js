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

window.registros = [];
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

//     $.ajax({ 
//             url:'modulos/administracion/calendario/controlador.php?f=getRegistros',
//             dataType:'json',
//             success: function
//         });

//    return  [{
//         title: 'All Day Event',
//         start: '2023-01-01'
//       },
//       {
//         title: 'Long Event',
//         start: '2023-01-07'
//       },
//       {
//         title: 'Click for Google',
//         url: 'http://google.com/',
//         start: '2023-01-28'
//       }];
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
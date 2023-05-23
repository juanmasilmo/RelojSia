function editar(id)
{
     $('html').animate({
      scrollTop: $("html").offset().top
  }, 0);
    $.get("modulos/administracion/categorias/formulario.php?id="+id,function(dato){
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
    $.get("modulos/administracion/categorias/listado.php",function(dato){
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

    if ($("#descripcion").val().length < 3) {
        $("#descripcion").focus();          
        return 0;
    }
  
   var cod_categoria = parseInt($("#cod_categoria").val());
    if (isNaN(cod_categoria)) {
        $("#cod_categoria").focus();          
        return 0;
    }    

     if ($("#descripcion_tipo_categoria").val().length < 3) {
        $("#descripcion_tipo_categoria").focus();          
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
          
          $.post("modulos/administracion/categorias/controlador.php?f=editar",$("#form").serialize(),function(dato){
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


function cambiar_estado(id){
  $.confirm({
    title: 'Confirmar Acción',
    content: 'Desea Modificar el Estado?',
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
                $.post("modulos/administracion/categorias/controlador.php?f=cambiar_estado",{id:id},function(dato){
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


function actualizar(){
  showLightbox();
  $.post("modulos/administracion/categorias/actualizar.php",function(dato){
    $("#mensaje").html(dato);
    $('#mensaje').fadeIn('slow');
    hideLightbox();
  });  
  
}
function confirmar(mensaje) {
  return confirm(mensaje);
}

function cerrar() {

  $('#popup').fadeOut('slow');
  $('.popup-overlay').fadeOut('slow');

}

function editar(id)
{
     $('html').animate({
      scrollTop: $("html").offset().top
  }, 0);
  $.get("modulos/administracion/instructivo/formulario.php?id="+id, function (dato) {
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

// function editar(id) {
//   $.get("modulos/administracion/instructivo/formulario.php?id="+id, function (dato) {
//     $("#popup").html(dato);
//     $('#popup').fadeIn('slow');
//     return false;
//   });
// }

function ver_video(url) {
  $.get("modulos/administracion/instructivo/play.php?url="+url, function (dato) {
    $("#popup").html(dato);
    $('#popup').fadeIn('slow');
    return false;
  });
}

function controlar(id) {

  if ($("#titulo").val() == "" || $("#titulo").val() == " " || $("#titulo").val() == "  " || $("#titulo").val() == "  ") {
    $("#mensaje_titulo").html("EL titulo es requerido").fadeIn('slow');
    return false;
  }
  console.log( $('#form'));

  var formData = new FormData(document.getElementById("form"));

  var input_file = $("#documento")[0];
  formData.append(input_file.name, input_file.files[0]);
  // console.log(formData);

  $.ajax({
    url: "modulos/administracion/instructivo/controlador.php?f=editar&id=" + id,
    type: "POST",
    data: formData,
    cache: false,
    contentType: false, 
    processData: false
  })
    .done(function (dato) {
      $("#mensaje").html(dato);
      $('#mensaje').fadeIn('slow');
      cerrar();
      listado();
    });
}

function listado() {
  $.get("modulos/administracion/instructivo/listado.php", function (dato) {
    $("#listado").html(dato);
    $('#listado').fadeIn('slow');
     $('#tabla').DataTable({
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
    return false;
  });
}


function getUrl() {
  $("#youtube-player").html(" ");
   video = '<br><br><iframe width="640" height="360" src="https://www.youtube.com/embed/'+$("#url").val()+'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
   $("#youtube-player").append(video);
}

function eliminar(id){
  $.confirm({
    title: 'Confirmar Acción',
    content: 'Desea eliminar el registro?',
    icon: 'glyphicon glyphicon-question-sign',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
      confirm: {
        text: 'SI',
        btnClass: 'btn-green', 
        action: function () {         
                //accion de eliminar                
                $.post("modulos/administracion/instructivo/controlador.php?f=eliminar",{id:id},function(dato){
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
                $.alert('Accion Cancelada');
              }
            }
          }
        });
}
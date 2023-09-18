function nuevo() {

  $("#nue").toggle( "slow" );
}

function confirmar ( mensaje ) {
 return confirm( mensaje );
}

function controlar(id){
$("#tag").css({"border": "none"});
$("#descripcion").css({"border": "none"});
$("#mensaje_tag").css('display', 'none');
$("#mensaje_descripcion").css('display', 'none');

  if($("#tag").val() == ""){
    $("#tag").css({"border": "1px solid red"}).focus();
    $("#mensaje_tag").html('<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡ADVERTENCIA!</strong> Completar el TAG</div>');
    $('#mensaje_tag').fadeIn('slow');
    return false;
  }

 CKEDITOR.instances.descripcion.updateElement();
  $.post("modulos/administracion/versiones/controlador.php?f=editar&id="+id,$("#formulario").serialize(),function(dato){
    $("#mensaje").html(dato);
    $('#mensaje').fadeIn('slow');
    cerrar();
  });
}

function cerrar(){
 $('#popup').fadeOut('slow');
 $('.popup-overlay').fadeOut('slow');
}

function editar(id)
{
  $.get("modulos/administracion/versiones/formulario.php?id="+id,function(dato){
  //mostrar formulario con los datos del usuario
  $("#popup").html(dato);
  $('#popup').fadeIn('slow');
  cargar_editor();
  return false;
});
}

function listado()
{
  $.get("modulos/administracion/versiones/listado.php",function(dato){
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

  });
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
                $.post("modulos/administracion/versiones/controlador.php?f=eliminar",{id:id},function(dato){
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


function cargar_editor(){
  CKEDITOR.replace('descripcion',
  {

    height  : '500px',
    width   : '100%',

    toolbar : [
    { name: 'document', items : [ 'Undo','Redo','-','NewPage','DocProps','Preview','Print'] },
    { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-' ] },
    { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },'/',
    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','Strike','Subscript','Superscript','-','RemoveFormat' ] },
    { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
    '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
    { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
    { name: 'insert', items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
    '/',
    { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
    { name: 'colors', items : [ 'TextColor','BGColor' ] },
    { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','Source'] },'/',
    { name: 'etiquetas', items : [ 'etiquetas','etiquetas_ti','etiquetas_proveedores','etiquetas_fechas'] },

    ],
    filebrowserUploadUrl: "upload.php",
    extraPlugins: 'etiquetas, etiquetas_ti, etiquetas_proveedores, etiquetas_fechas',
    allowedContent: true
  });
}
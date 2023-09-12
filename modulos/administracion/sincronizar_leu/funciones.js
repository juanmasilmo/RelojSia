

function actualizar(){
  showLightbox();
  $.post("modulos/administracion/sincronizar_leu/actualizar.php",function(dato){
    $("#mensaje").html(dato);
    $('#mensaje').fadeIn('slow');
    hideLightbox();
  });  
  
}
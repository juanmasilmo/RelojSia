

function actualizar(){
  showLightbox();
  $.post("modulos/administracion/sincronizar_leu/actualizar.php",function(dato){
    $("#mensaje").html(dato);
    $('#mensaje').fadeIn('slow');
    hideLightbox();
  });  
  
}

function log_sincronizar_leu(sql, co){
  console.log(co +'  - '+ sql);
}
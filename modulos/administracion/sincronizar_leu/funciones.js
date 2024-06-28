function actualizar(){
 $("#mensaje").css('display', 'none');
  var fecha_desde=$("#fecha_desde").val();
  var fecha_hasta=$("#fecha_hasta").val();

  
  // if(!fecha_desde) {

  //   $("#fecha_desde").css('border','1px solid red').focus();          
   
  //   $("#mensaje").html('<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡ADVERTENCIA!</strong> fecha Desde es obligatorio.-</div>');;
  //   $('#mensaje').fadeIn('slow');  
  //   return false;
  // }  else{
  //   $("#fecha_desde").css('border','');
  // }

    showLightbox();
    $.post("modulos/administracion/sincronizar_leu/actualizar.php",{fecha_desde:fecha_desde,fecha_hasta:fecha_hasta,},function(dato){
      $("#mensaje").html(dato);
      $('#mensaje').fadeIn('slow');
      hideLightbox();
    });
  
}

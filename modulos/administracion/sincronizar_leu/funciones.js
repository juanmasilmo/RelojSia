

function actualizar(){
 $("#mensaje").css('display', 'none');
  var fecha_desde=$("#fecha_desde").val();
  var fecha_hasta=$("#fecha_hasta").val();

  if ($("#fecha_desde").val().length < 1) {
    $("#fecha_desde").focus();          
    return 0;
  }  

  if ($("#fecha_hasta").val().length < 1) {
    $("#fecha_hasta").focus();          
    return 0;
  }  

  var fechaInicio = new Date($("#fecha_desde").val()).getTime();
  var fechaFin    = new Date($("#fecha_hasta").val()).getTime();

  var diff = (fechaFin - fechaInicio)/(1000*60*60*24);

  if(diff <=62){

    showLightbox();
    $.post("modulos/administracion/sincronizar_leu/actualizar.php",{fecha_desde:fecha_desde,fecha_hasta:fecha_hasta,},function(dato){
      $("#mensaje").html(dato);
      $('#mensaje').fadeIn('slow');
      hideLightbox();
    });
  }else{
    $("#mensaje").html('<div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-ok-sign"></i> <strong>¡ADVERTENCIA!</strong> la sincronización no puede ser mayor a 31 días</div>');;
    $('#mensaje').fadeIn('slow');
  }
}

function log_sincronizar_leu(sql, co){
  console.log(co +'  - '+ sql);
}
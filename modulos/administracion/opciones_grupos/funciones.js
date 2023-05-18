
function listado()
{
  $.get("modulos/administracion/opciones_grupos/listado.php",function(dato){
    $("#listado").html(dato);
    $('#listado').fadeIn('slow');
    return false;
  });
}


function actualizar(id_grupo,id_opcion,tipo)
{
  if(document.getElementById(tipo).checked==true)
  {

    $.get("modulos/administracion/opciones_grupos/controlador.php?id_grupo="+id_grupo+"&id_opcion="+id_opcion+"&tipo=add",function(dato){       
      $("#mensaje").html(dato);   
      $('#mensaje').fadeIn('slow');            
    });    
  }
  else
  {
    $.get("modulos/administracion/opciones_grupos/controlador.php?id_grupo="+id_grupo+"&id_opcion="+id_opcion+"&tipo=rm",function(dato){       
      $("#mensaje").html(dato);    
      $('#mensaje').fadeIn('slow');           
    });

  }
}
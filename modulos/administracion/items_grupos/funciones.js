
function listado()
{
  $.get("modulos/administracion/items_grupos/listado.php",function(dato){
    $("#listado").html(dato);
    $('#listado').fadeIn('slow');
    return false;
  });
}


function actualizar(id_grupo,id_item,tipo)
{
  if(document.getElementById(tipo).checked==true)
  {

    $.get("modulos/administracion/items_grupos/controlador.php?id_grupo="+id_grupo+"&id_item="+id_item+"&tipo=add",function(dato){       
      $("#mensaje").html(dato);   
      $('#mensaje').fadeIn('slow');            
    });        
  }
  else
  {
    $.get("modulos/administracion/items_grupos/controlador.php?id_grupo="+id_grupo+"&id_item="+id_item+"&tipo=rm",function(dato){       
      $("#mensaje").html(dato);    
      $('#mensaje').fadeIn('slow');           
    });

  }
}

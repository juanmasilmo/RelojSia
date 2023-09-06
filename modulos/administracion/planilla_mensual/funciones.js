let calendar = '';


function listado()
{
     $('html').animate({
      scrollTop: $("html").offset().top
  }, 0);
    $.get("modulos/administracion/planilla_mensual/listado.php",function(dato){
        $("#listado").css('display', 'block');
        $("#listado").html(dato);
        $('#listado').fadeIn('slow');
   
    });
}

function listar_dependencia(cadena) {
  $.get("sistema/dependencias/listado_dependencias.php?cadena=" + cadena, function (dato) {
    $("#lista_dependencia").html(dato);
  });
}
function seleccionar_dependencia(id) {
  $.get("sistema/dependencias/dependencia_seleccionada.php?id=" + id, function (dato) {
   
    $("#lista_dependencia").html(dato);
  
  });
}

function procesar() {
  //controlar input dependencia
  if(!$("#id_dependencia").val() || $("#id_dependencia").val() == 'undefined'){
    $("#dependencia").css("border","1px solid red");
    return false;
  }else{
    $("#dependencia").css("border","1px solid #6e707e");
  }
  
  var id_dependencia  = $("#id_dependencia").val();
  // console.log(id_dependencia);
  //cargar datos en la tabla 
  let colums = 31;
  let filas = 0;

  $.get("modulos/administracion/planilla_mensual/controlador.php?f=get_agentes&id_dependencia=" + id_dependencia, function(dato){
    
    // console.log(dato);
    if(dato != 'false'){
      
      arma_tabla();
    
    }else{
      
      //limpio la tabla de agentes
      $("#div_tabla_agentes_registros").html("");

      alert("La depedencia seleccionada no tiene agentes relacionados");
      
    }

  });

}



function arma_tabla() {
  
  var id_dependencia  = $("#id_dependencia").val();
  var mes = $("#id_mes").val();
  var anio = $("#id_anio").val();

  let url = 'modulos/administracion/planilla_mensual/controlador.php?f=get_registros_agentes&id_dependencia=' + id_dependencia + '&mes=' + mes + '&anio=' + anio;
  
  $.get(url, function(data) {

    if(data != 'false'){

      var marcas = JSON.parse(data);

      var agentes = marcas.legajos;
      var registros = marcas.registros;

      var total_dias = new Date(anio, mes, 0).getDate(); //obtengo la cantidad de dias del mes para armado de la tabla 
    
      /**
       * Cabecera Tabla
       */
      var tabla = "<table id='tabla_agentes_registros' class='table table-responsive table-bordered' ><thead><tr><th rowspan='2'>Agentes</th><th class='text-center' colspan='31'>Dias</th></tr><tr>";
      
      for (var i = 1; i < total_dias+1; i++){
        
          tabla += "<th style='font-size:8px'>" + i + "</th>";
        
      }
      tabla += "</tr></thead><tbody style='font-size:12px'>";
        
      /**
       * Cuerpo Tabla
       */
      agentes.forEach(function(agente){
        
        tabla += "<tr><td id='agentes'>"+agente.nombre+"</td>";

        if(!registros){

          for (var dia = 1; dia < total_dias+1; dia++){
            tabla += "<td id='legajo"+agente.legajo+"'> </td>";
          }
          tabla += "</tr>";

        }else{

          for (var dia = 1; dia < total_dias+1; dia++){
            
            tabla += "<td id='legajo"+agente.legajo+"' ";
            var registro_marca = '';
            var background_color = '';
  
            //por cada dia recorro los registros 
            registros.forEach(function(registro){
              
              // si el dia tiene registro muestro o articulo
              if(agente.legajo == registro.legajo && dia == registro.dia){

                //verifico que no este vacio el articulo o no sea null
                var nro_articulo = '';
                if(registro.nro_articulo){
                  
                  nro_articulo = registro.nro_articulo + '<br>';
                  // si es del grupo de los 200 (ej: 270)
                  background_color = '#FF7777';
                 
                  // si es compensacion de feria
                  if(nro_articulo == 293){
                    background_color = '#6BFF57';
                  }
                
                }
                
                //verifico que tenga registro
                if(registro.hora && registro.hora != 0){
                
                  //pregunto si es mayor a 6hs am
                  if((registro.hora > 6 && registro.minutos > 40) || ((registro.hora > 9) && (registro.hora < 12 && registro.minutos < 30)))
                    
                    //llega tarde o sale temprano (justificar)
                    background_color = '#b3b300';

                    //preparo un string para imprimir todo junto dsps
                    registro_marca += nro_articulo + registro.hora +':'+ registro.minutos + '<br>';
                
                }else{
                  if(nro_articulo){
                    registro_marca += nro_articulo;
                  }
                }
  
              }
              
              // tabla += nro_articulo + " <br> " + registro_marca + " <br> ";
              
            }); //fin foreach registros
            
            //cierro el td de apertura
            tabla += 'bgcolor="'+ background_color +'">';

            //imprimo los registros (string preparado)
            tabla += registro_marca;

            //cierro el td
            tabla +=  "</td>";
              
          } // fin for dias
  
          tabla += "</tr>";

        }
                   
      }); //fin foreach agentes        
             
      tabla += "</tbody></table>";
      // console.log(tabla);

      $("#div_tabla_agentes_registros").html(tabla);

      // $('#tabla_agentes_registros').DataTable();
      
    }

  }); // fin $.get

}

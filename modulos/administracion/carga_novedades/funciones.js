let calendar = '';


function listado()
{
     $('html').animate({
      scrollTop: $("html").offset().top
  }, 0);
    $.get("modulos/administracion/carga_novedades/listado.php",function(dato){
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

  $.get("modulos/administracion/carga_novedades/controlador.php?f=get_agentes&id_dependencia=" + id_dependencia, function(dato){
    
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


  let url = 'modulos/administracion/carga_novedades/controlador.php?f=get_registros_agentes&id_dependencia=' + id_dependencia + '&mes=' + mes + '&anio=' + anio;
  
  $.get(url, function(data) {

    if(data != 'false'){

      var marcas = JSON.parse(data);

      var agentes = marcas.legajos;
      var registros = marcas.registros;

      var total_dias = new Date(anio, mes, 0).getDate(); //obtengo la cantidad de dias del mes para armado de la tabla 
      
      /**
       * chequeo si el mes seleccionado es igual al mes corriente 
       * si es el mismo dejo agregar registro sino oculto el btn
       */
      var btn = '';
      const month = new Date().getMonth();
      
      if(month+1 == mes){
      
        //agrego el boton (y vo') al dia para procesar la fecha de todo el personal
        var btn = '<hr><div><a class="btn btn-success controlar" id="cargar_registros_personal" onclick="cargar_registros_personal()">Agregar Registro </a></div><br>';

      }
      
      /**
       * Cabecera Tabla
       */
      var tabla = btn + "<table id='tabla_agentes_registros'  class='table table-responsive table-striped' ><thead bgcolor='#E6DFCF'><tr><th style='border: 1px solid black' rowspan='2'>Agentes</th><th style='border: 1px solid black' class='text-center' colspan='31'>Dias</th></tr><tr>";
      
      for (var i = 1; i < total_dias+1; i++){
        
        //agrego los dias
        tabla += "<th style='border: 1px solid black;font-size:10px'>" + i + "</th>";
        
      }
      tabla += "</tr></thead><tbody style=''>";
        
      /**
       * Cuerpo Tabla
       */
      agentes.forEach(function(agente){
        
        tabla += "<tr><td style='border: 1px solid black;font-size:10px' id='agentes'>"+agente.nombre+"</td>";

        if(!registros){

          for (var dia = 1; dia < total_dias+1; dia++){
             
            //verifico si es fin de semana pinto de gris
             var fecha = anio+'/'+mes+'/'+dia;
             background_color = pinta_sabado_domingo(fecha);
            
            tabla += "<td style='border: 1px solid black' id='legajo"+agente.legajo+"' bgcolor='"+background_color+"'> </td>";
          }
          tabla += "</tr>";

        }else{

          for (var dia = 1; dia < total_dias+1; dia++){
            
            tabla += "<td style='border: 1px solid black' id='legajo"+agente.legajo+"' ";
            var registro_marca = '';
            var background_color = '';

            //verifico si es fin de semana pinto de gris
            var fecha = anio+'/'+mes+'/'+dia;
            background_color = pinta_sabado_domingo(fecha);
  
            //por cada dia recorro los registros 
            registros.forEach(function(registro){
              
              // si el dia tiene registro muestro o articulo
              if(agente.legajo == registro.legajo && dia == registro.dia){

                //verifico que no este vacio el articulo o no sea null
                var nro_articulo = '';
                if(registro.nro_articulo){
                  
                  nro_articulo = registro.nro_articulo;
                  
                  if(nro_articulo == '270'){
                    // si es del grupo de los 200 (ej: 270)
                    background_color = '#FF7777';
                  }
                  
                  // si es compensacion de feria
                  if(nro_articulo == 293){
                    background_color = '#6BFF57';
                  }

                  nro_articulo += '<br>';
                
                }
                
                //verifico que tenga registro
                if(registro.hora && registro.hora != 0){
                
                  //pregunto si es mayor a 6hs am
                  if((registro.hora >= 6 && registro.minutos > 40) || ((registro.hora > 9) && (registro.hora <= 12 && registro.minutos < 30)))
                    
                    //llega tarde o sale temprano (justificar)
                    background_color = '#b3b300';

                    //preparo un string para imprimir todo junto dsps
                    registro_marca += nro_articulo + registro.hora +':'+ registro.minutos + '<br />';
                
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
            tabla += registro_marca.trim();

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

function pinta_sabado_domingo(fecha) {

  var today = new Date(fecha);

  var bgcolor = '';
  if(today.getDay() == 0 || today.getDay() == 6){
    bgcolor = "#C0C0C0"
  }

  return bgcolor;
}


function cargar_registros_personal() {
  
  $("#modalCargaNovedades").modal("show");
}

function guardarRegistroCompleto() {
  
  var fecha =  $("#registro_fecha").val();
  //control de fecha vacia
  // if(!fecha){
  
  //   $("#registro_fecha").css('border', '1px solid red');
  //   $("#msj_registro_fecha").css('display', 'block');
  //   return false;
  
  // }else{
  
  //   $("#registro_fecha").css('border','1px solid #d1d3e2');
  //   $("#msj_registro_fecha").css('display', 'none');

  // }

  var opcion = $("input[type='radio'][name='registro_completo']:checked").val();
  var id_dependencia  = $("#id_dependencia").val();

  $.get("modulos/administracion/carga_novedades/controlador.php?f=guardar_registro_completo&fecha=" + fecha + "&opcion=" + opcion + "&id_dependencia=" + id_dependencia, function(dato){
    
    $("#modalCargaNovedades").modal("hide");
    arma_tabla();

  });
  

  
}
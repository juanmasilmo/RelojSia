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
      var feriados = marcas.feriados;
      var total_dias = new Date(anio, mes, 0).getDate(); //obtengo la cantidad de dias del mes para armado de la tabla 
    
      /**
       * Cabecera Tabla
       */
      var tabla = "<table id='tabla_agentes_registros'  class='table table-responsive table-striped' ><thead bgcolor='#E6DFCF'><tr><th style='border: 1px solid black' rowspan='2'>Agentes</th><th style='border: 1px solid black' class='text-center' colspan='31'>Dias</th></tr><tr>";
      
      for (var i = 1; i < total_dias+1; i++){
        
          tabla += "<th style='border: 1px solid black'>" + i + "</th>";
      }

      tabla += "</tr></thead><tbody style=''>";
        
      /**
       * Cuerpo Tabla
       */
      agentes.forEach(function(agente){
        
        tabla += "<tr><td style='border: 1px solid black' id='agentes'>"+agente.nombre+"</td>";

        if(!registros){

          for (var dia = 1; dia < total_dias+1; dia++){
             
            //verifico si es fin de semana pinto de gris
            var fecha = anio+'/'+mes+'/'+dia;
            background_color = pinta_sabado_domingo(fecha);
              
            tabla += "<td class='carga_feriado_td_"+dia+mes+"' style='border: 1px solid black' id='legajo"+agente.legajo+"' bgcolor='"+background_color+"'> </td>";
            // background_color = '';
          }
          tabla += "</tr>";

        }else{

          for (var dia = 1; dia < total_dias+1; dia++){
            
            tabla += "<td class='carga_feriado_td_"+dia+mes+"' style='border: 1px solid black' id='legajo"+agente.legajo+"' ";
            var registro_marca = '';
            var background_color = '';

            //verifico si es fin de semana pinto de gris
            var fecha = anio+'/'+mes+'/'+dia;
            background_color = pinta_sabado_domingo(fecha);

            //por cada dia recorro los registros 
            var band = 0;
            var title_dia = '';

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
                var r_hora = parseInt(registro.hora);
                
                //verifico que tenga registro
                if(r_hora && r_hora != 0){

                  //verifico cuantas veces ingresa por legajo/dia (tiene que tener 2 registros minimo entreda/salida)
                  band = band + 1;

                  var minutos = `${registro.minutos}`.padStart(2, '0');
                  // if(minutos < 10){
                  //   minutos = '0'+minutos;
                  // }
                  // console.log(minutos+'<br>');
                  //pregunto si es mayor a 6hs am
                  if(parseInt(`${r_hora}`+`${minutos}`) > 640 && parseInt(`${r_hora}`+`${minutos}`) < 1230 )
                    
                    //llega tarde o sale temprano (justificar)
                    background_color = '#b3b300';

                    //preparo un string para imprimir todo junto dsps
                    registro_marca += nro_articulo + r_hora +':'+ minutos + '<br />';
                
                }else{
                  // pregunto si tiene solo un registro con hora != 0
                  // if(r_hora == 0){
                  //   background_color = '#b3b300';
                  // }
                  if(nro_articulo){
                    registro_marca += nro_articulo;
                  }
                }
  
                
              }
              
            }); //fin foreach registros

            //cierro el td de apertura
            tabla += 'bgcolor="'+ background_color +'" title="'+title_dia+'">';

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
      cargo_feriados_tabla(feriados);
      
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

function cargo_feriados_tabla(feriados) {

  var mes = 0;

  if(feriados){

    feriados.forEach(function(feriado) {

      (feriado.feriado_mes < 10) ? mes = '0'+feriado.feriado_mes : mes = feriado.feriado_mes;

      $(".carga_feriado_td_"+feriado.feriado_dia+mes)
      .html('<strong>'+feriado.estado_letra+'</strong>')
        .css('background-color',feriado.estado_color)
        .attr('title',feriado.estado_descripcion);

    });

  }
}
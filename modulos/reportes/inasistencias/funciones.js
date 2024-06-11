let calendar = '';


function showLightbox() {
  document.getElementById('over').style.display='block';
  document.getElementById('fade').style.display='block';
}
function hideLightbox() {
  document.getElementById('over').style.display='none';
  document.getElementById('fade').style.display='none';
}

function listado()
{
     $('html').animate({
      scrollTop: $("html").offset().top
  }, 0);
    $.get("modulos/reportes/inasistencias/listado.php",function(dato){
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
  
  showLightbox();
  
  //controlar input dependencia
  if(!$("#id_dependencia").val() || $("#id_dependencia").val() == 'undefined'){
 
    $("#dependencia").css("border","1px solid red");
    return false;
 
  }else{
 
    $("#dependencia").css("border","1px solid #6e707e");
 
  }
  
  arma_tabla2();
}

function arma_tabla2() {
 
  var id_dependencia  = $("#id_dependencia").val();
  var mes = $("#id_mes").val();
  var anio = $("#id_anio").val();

  let url = 'modulos/reportes/inasistencias/controlador.php?f=get_reportes&id_dependencia=' + id_dependencia + '&mes=' + mes + '&anio=' + anio;
  $.getJSON(url, function(data) {
    console.log(data);
    $("#div_tabla_agentes_registros").html(data);
    hideLightbox();
  });


}

function arma_tabla() {
  
  var id_dependencia  = $("#id_dependencia").val();
  var mes = $("#id_mes").val();
  var anio = $("#id_anio").val();

  let url = 'modulos/reportes/inasistencias/controlador.php?f=get_registros_agentes&id_dependencia=' + id_dependencia + '&mes=' + mes + '&anio=' + anio;
  
  $.get(url, function(data) {

    if(data != 'false'){

      // console.log(data);
      var marcas = JSON.parse(data);
      
      var agentes = marcas.legajos; // PRIMER COLUMNA
      var registros = marcas.leg_art_dep; // CUERPO TABLA
      var inasistencias = marcas.inasistencias; // CUERPO TABLA
      var articulos_mes = marcas.articulos_mes; //CABECERA TABLAS
      var feriados = marcas.feriados; //CABECERA TABLAS
     
      if(articulos_mes.length){

        
        /**
         * Cabecera Tabla
         */
        var tabla = "<hr><table id='tabla_agentes_registros'  class='table table-responsive table-striped' ><thead bgcolor='#E6DFCF'><tr><th style='border: 1px solid black'>Agentes</th>";
        
        //cargo los numeros de articulos en la cabecera tabla
        articulos_mes.forEach(element => {
          tabla += "<th style='border: 1px solid black' title='"+element.descripcion+"'>art. " + element.nro_articulo + "</th>";
        });

        /**
         * Agrego las columnas restantes
         */
         tabla += "<th style='border: 1px solid black'>Dias Desc. Pasajes</th>";
         tabla += "<th style='border: 1px solid black'>Cobra Presentismo</th>";
         tabla += "<th style='border: 1px solid black'>Dias vespertino</th>";
        
        tabla += "</tr></thead><tbody style=''>";
        // FIN cabecera
        
       
        /**
         * Cuerpo Tabla
         */
        agentes.forEach(function(agente){
          
          // CARGO PRIMER COLUMNA
          tabla += "<tr><td style='border: 1px solid black' id='agentes'>"+agente.nombre+"</td>";
          
          articulos_mes.forEach(element => {
            tabla += "<td class='carga_legajo_count_articulo_td_"+agente.legajo+element.id_articulo+"' title='' style='border: 1px solid black' id='legajo"+agente.legajo+"'> </td>";
          });

          /**
           * Cargo contenido a las columnas restantes
           */
          tabla += "<td class='"+agente.legajo+"_descuento_pasaje' style='border: 1px solid black'> </td>";
          tabla += "<td class='"+agente.legajo+"_presentismo' style='border: 1px solid black'> </td>";
          tabla += "<td class='"+agente.legajo+"_vespertino' style='border: 1px solid black'> </td>";
          
        }); //fin foreach agentes
        
        tabla += "</tr></tbody></table>";
        
       // $("#div_tabla_agentes_registros").html(tabla);
        cargo_legajos_count_articulos_tabla(registros);
        
      }else{
        //limpio la tabla de agentes
        $("#div_tabla_agentes_registros").html("");
        alert("No se encontraron registros sobre el mes consultado...");
      }
    
    } //fin if data false
    
  }); // fin $.get
  
}

function cargo_legajos_count_articulos_tabla(registros) {

  if(registros){

    registros.forEach(function(registro) {

      $(".carga_legajo_count_articulo_td_"+registro.legajo+registro.id_articulo)
        .html('<strong>'+registro.count+'</strong>')
      

      if(registro.presentismo == 0){
        $("."+registro.legajo+"_presentismo")
          .html('<strong> No </strong>')
      }else{
        $("."+registro.legajo+"_presentismo")
          .html('<strong> Si </strong>')
      }
    });

  }
}



/**
 * Dias Laborales
 */
function dias_habiles(anio, mes, feriados) {

  //obtengo la cantidad de dias del mes cosultado
  var total_dias = new Date(parseInt(anio), parseInt(mes), 0).getDate();
  var today;
  var fecha;
  let dias_laborales = [];

  for (let i = 1; i < total_dias+1; i++) {
  
    fecha = anio+'/'+mes+'/'+i;
    today = new Date(fecha); //armo un dia a dia 
    
    // HAY QUE TENER EN CUENTA LAS OFICINAS QUE TRABAJAN LOS SADADOS
    if(today.getDay() != 0 && today.getDay() != 6){ //descarto sabado y domingo
      
      // console.log(i);
      dias_laborales.push(i);
  
    }
    
  }

  // si hay dias feriados en el mes
  if(feriados){

    // recorro los dias feriados
    feriados.forEach(feriado => {
      
      var i = parseInt(feriado.dia);
      
      // si encuentra el feriado dentro de los dias habiles
      if(dias_laborales.find((element) => element === i)){

        const index = dias_laborales.indexOf(i);

        // elimino el dia feriado de los habiles
        dias_laborales.splice(index, 1);      

      }

    });
  }

  return dias_habiles
}

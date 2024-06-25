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
      
      armaTabla();
    
    }else{
      
      //limpio la tabla de agentes
      $("#div_tabla_agentes_registros").html("");

      alert("La depedencia seleccionada no tiene agentes relacionados");
      
    }

  });

}



function armaTabla() {
  
  var id_dependencia  = $("#id_dependencia").val();
  var mes = parseInt($("#id_mes").val());
  var anio = $("#id_anio").val();


  let url = 'modulos/administracion/carga_novedades/controlador.php?f=get_registros_agentes&id_dependencia=' + id_dependencia + '&mes=' + mes + '&anio=' + anio;
  
  $.get(url, function(data) {
    // console.log(data);
    if(data != 'false'){

      var marcas = JSON.parse(data);

      var agentes = marcas.legajos;
      var registros = marcas.registros;
      var feriados = marcas.feriados;
      var carga_registro = marcas.carga_registro;

      var total_dias = new Date(anio, mes, 0).getDate(); //obtengo la cantidad de dias del mes para armado de la tabla 
      
      /**
       * chequeo si el mes seleccionado es igual al mes corriente 
       * si es el mismo dejo agregar registro sino oculto el btn
       */
      var btn = '<hr>';
      const month = new Date().getMonth();
      
      /**
       * Verifico si tiene permiso para cargar registro
       */
      if(carga_registro == 1){
        if(month+1 <= mes){
          
          //agrego el boton (y vo') al dia para procesar la fecha de todo el personal
          var btn = btn+'<div><a class="btn btn-success controlar" id="cargarRegistrosPersonal" onclick="cargarRegistrosPersonal()">Agregar Registro </a></div><br>';
          
        }
      }
      
      /**
       * Cabecera Tabla
       */
      var tabla = btn + "<div class='alert alert-info'><i class='fa fa-exclamation-triangle'> </i><strong> PD:</strong>La presente tabla solo muestra los Articulos relacionados al Agente, para verificar los registros de marcas del reloj, ver la Planilla Mensual</div><br><div class='table-responsive'><table style='' id='tabla_agentes_registros' class='table table-bordered table-striped' ><thead bgcolor='#E6DFCF'><tr><th rowspan='2'>Agentes</th><th class='text-center' colspan='31'>Dias</th></tr><tr>";
      
      for (var i = 1; i < total_dias+1; i++){
        
        //agrego los dias
        tabla += "<th style='font-size:10px'>" + i + "</th>";
        
      }
      tabla += "</tr></thead><tbody style=''>";
        
      /**
       * Cuerpo Tabla
       */
      agentes.forEach(function(agente){
        
        tabla += "<tr id='"+agente.legajo+"'><td style='font-size:10px; color:black; font-weight:bold' id='agentes_"+agente.legajo+"' >"+agente.nombre+"</td>";

        if(!registros){
          
          //sino hay registro para mostrar cargo las celdas con vacio

          for (var dia = 1; dia < total_dias+1; dia++){
             
            //verifico si es fin de semana pinto de gris
             var fecha = anio+'/'+mes+'/'+dia;
             background_color = pintaSabadoDomingo(fecha);
            
            tabla += "<td data-feriado='"+dia+mes+"_carga_feriado_td' style='font-size:10px' id='"+agente.legajo+"-"+dia+"' dia='"+dia+"' onclick='modificarRegistroLegajo("+ dia +", "+agente.legajo+")' bgcolor='"+background_color+"' > </td>";
          }
          tabla += "</tr>";

        }else{
          // si tiene al menos 1 registro muestro

          for (var dia = 1; dia < total_dias+1; dia++){
            
            //voy cargando las celdas
            tabla += "<td data-feriado='"+dia+mes+"_carga_feriado_td' style='font-size:10px; color:black' id='"+agente.legajo+"-"+dia+"' dia='"+dia+"' onclick='modificarRegistroLegajo("+ dia +", "+agente.legajo+")' ";
            var registro_marca = '';
            var background_color = '';

            //verifico si es fin de semana pinto de gris
            var fecha = anio+'/'+mes+'/'+dia;
            background_color = pintaSabadoDomingo(fecha);
  
            //por cada dia => recorro los registros 
            registros.forEach(function(registro){
              
              // si el dia tiene registro muestro o articulo
              if(agente.legajo == registro.legajo && dia == registro.dia){

                //verifico que no este vacio el articulo o no sea null
                var nro_articulo = '';
                if(registro.nro_articulo){
                  
                  nro_articulo = registro.nro_articulo;
                  if(registro.color)
                    background_color = registro.color;
                  nro_articulo += '<br>';
                
                }
                
                var r_hora = parseInt(registro.hora);
               
                //verifico que tenga registro
                if(r_hora && r_hora != 0){
                  
                  var minutos = `${registro.minutos}`.padStart(2, '0');
                    // if(minutos < 10){
                    //   minutos = '0'+`${minutos}`;
                    // }
                  //pregunto si es mayor a 6hs am
                  if(parseInt(`${r_hora}`+`${minutos}`) > 640 && parseInt(`${r_hora}`+`${minutos}`) < 1230 )
                    //llega tarde o sale temprano (justificar)
                    background_color = '#b3b300';

                    //preparo un string para imprimir todo junto dsps
                    registro_marca += nro_articulo + r_hora +':'+ minutos + '<br />';
                
                }else{
                  if(nro_articulo){
                    registro_marca += nro_articulo;
                  }
                }
  
                // registro_marca += nro_articulo;
              }
              // tabla += nro_articulo + " <br> " + registro_marca + " <br> ";
              
            }); //fin foreach registros

            //cierro el td de apertura
            tabla += 'bgcolor="'+ background_color +'">';
            background_color = '';

            //imprimo los registros (string preparado)
            tabla += registro_marca.trim();

            //cierro el td
            tabla +=  "</td>";
              
          } // fin for dias
  
          tabla += "</tr>";

        }
                   
      }); //fin foreach agentes        
             
      tabla += "</tbody></table></div>";
      // console.log(tabla);

      $("#div_tabla_agentes_registros").html(tabla);
      cargo_feriados_tabla(feriados);

      // $('#tabla_agentes_registros').DataTable();
      
    }

  }); // fin $.get

}

function pintaSabadoDomingo(fecha) {

  var today = new Date(fecha);

  var bgcolor = '';
  if(today.getDay() == 0 || today.getDay() == 6){
    bgcolor = "#C0C0C0"
  }

  return bgcolor;
}


function cargarRegistrosPersonal(legajo,dia) {
  
  $("#modalCargaNovedades").modal("show");
}

function guardarRegistroCompleto() {
  
  var fecha =  $("#registro_fecha").val();
  var fecha_min = $("#registro_fecha").attr('min');

  //controlo si la fecha agregada es menor al permitido
  if(fecha < fecha_min){
    $("#registro_fecha").css('border', '1px solid red');
    $("#msj_registro_fecha").css('display', 'block');
    return false;
  }

  var opcion = $("input[type='radio'][name='registro_completo']:checked").val();
  var id_dependencia  = $("#id_dependencia").val();

  $.get("modulos/administracion/carga_novedades/controlador.php?f=guardar_registro_completo&fecha=" + fecha + "&opcion=" + opcion + "&id_dependencia=" + id_dependencia, function(dato){
    
    $("#modalCargaNovedades").modal("hide");
    armaTabla();

  });
  

  
}

function modificarRegistroLegajo(dia,legajo){

  var clas = $("#"+legajo+"-"+dia).attr("class");

  if(clas != 'seleccionado'){

    $("#"+legajo+"-"+dia).toggleClass("seleccionado");
    if(dia < 10){
      dia = '0'+dia;
    }
    var mes = $("#id_mes").val();
    var anio = $("#id_anio").val();
    var agente = $("#agentes_"+legajo).text();
  
    $("#modificacion_registro_nombre_agente").text(agente);
    $("#input_modificacion_registro_legajo").val(legajo);
    
    $("#modificacion_registro_fecha").text(dia+'-'+mes+'-'+anio);
    $("#input_modificacion_registro_fecha").val(anio+'-'+mes+'-'+dia);
  
    $("#modalModificacionNovedades").modal("show");
  }else{
    $("#"+legajo+"-"+dia).toggleClass("seleccionado");
  }

}

function cambiarVisibilidadInputsFechas() {

  var checkeds = document.getElementById('checked_fp_modificacion_registro');
  //si checked disabled sino enabled
  if (checkeds.checked){
    $(".inputs_fechas").removeAttr('disabled');
  }else{
    $(".inputs_fechas").attr('disabled','disabled');
  }
}

function modificarRegistroCompleto() {

  /**
   * Recorrer los td para obtener rango de dias
   */
  var legajo = $("#input_modificacion_registro_legajo").val();

  var tr = $("#"+legajo);

  const ds = [];
  $("#"+legajo).each(function (index) {
    $(this).children("td").each(function (index2) {
      if(this.className && this.className == 'seleccionado'){
        ds.push(this.getAttribute('dia'));
      };
    });
  });
  
  var id_mes = $('#id_mes').val(); 
  var id_anio = $('#id_anio').val(); 
  var d1 = ds[0];
  var d2 = ds[1];

  $.confirm({
    title: 'Alertas!',
    content: "Guardar articulos en las fechas seleccionadas?",
    icon: 'glyphicon glyphicon-question-sign',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
      'confirm': {
        text: 'Guardar',
        btnClass: 'btn-green',
        // envio de datos SIN Dependencia
        action: function () {
          $.ajax({
            type: 'post',
            url: "modulos/administracion/carga_novedades/controlador.php?f=modificar_registro_legajo&d1="+d1+"&d2="+d2+"&m="+id_mes+"&a="+id_anio,
            dataType: 'json',
            data : $('#formulario_modificacion_registros').serialize(),
            success: function(response) {
              $("#modalModificacionNovedades").modal("hide");
              armaTabla();
              $("#mensaje").html('<div class=" alert '+response.class_alert+' alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="far fa-check-circle"></i> '+response.msj+' </div>');
            }
          });

        }
      },
      CANCELAR: {
        btnClass: 'btn-default',
        action: function () {
          //$.alert('hiciste clic en <strong>NO</strong>');
        }
      }
    }
  });

  

}


function cargo_feriados_tabla(feriados) {
 
  // var mes = 0;
 
  if(feriados){

    
    feriados.forEach(function(feriado) {
      var x = feriado.feriado_dia+feriado.feriado_mes+"_carga_feriado_td";
      // console.log(x);
      // $("table tr td[data-feriado="+x+"]").each(function(index) {
      //   console.log('controlar');
      //   //console.log($(this).attr("data-feriado"));
      //     $(this).css("background-color", "red");
      // });
      // var h = $("[data-feriado='carga_feriado_td_" + feriado.feriado_dia+feriado.feriado_mes + "']");
      // console.log(h);

      // (feriado.feriado_mes < 10) ? mes = '0'+feriado.feriado_mes : mes = feriado.feriado_mes;

      $("table tr td[data-feriado="+x+"]")
        .html('<strong>'+feriado.estado_letra+'</strong>')
        .css('background-color',feriado.estado_color)
        .attr('title',feriado.estado_descripcion);
    });

  }
}
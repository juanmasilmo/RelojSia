let calendar;
let month;
let year;
let clickedDayRef = [];
let countClicked = 0;

function showLightbox() {
  document.getElementById('over').style.display = 'block';
  document.getElementById('fade').style.display = 'block';
}

function hideLightbox() {
  document.getElementById('over').style.display = 'none';
  document.getElementById('fade').style.display = 'none';
}


function cerrar_formulario()
{
    $("#formulario").css('display', 'none');
    $("#formulario").html('');
    $('#formulario').fadeOut('slow');
}

function cargar_icono()
{
    $("#icon").removeClass();
    $("#icon").addClass($("#icono").val());
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

function listado(id_dependencia,id_mes,id_anio) {

  if((id_dependencia && id_mes) == 0){

    id_dependencia = $("#id_dependencia").val();

    id_mes = $("#id_mes").val();

    id_anio = $("#id_anio").val();

  }

  $.ajax({

    type: 'get',

    url: "modulos/reportes/presentismos/controlador.php?f=listado&id_dependencia="+id_dependencia+"&id_mes="+id_mes+"&id_anio="+id_anio,

    success: function(response) {
      
      var personal = JSON.parse(response);
      
      var agentes = personal.personal;

      // arma_tabla_presentismo(agentes);
      
    }

  });

}

function arma_tabla_presentismo(agentes){

  var listado_personal = ''; 

  var tabla = '<table><thead><tr><th>Agente</th></tr><thead>';

  agentes.forEach(function(agente){
   
    listado_personal += agente.apellido+' '+agente.nombres+'<br>';
  
  });

  $("#listado").html(tabla).css("display","block");

}

  // $.confirm({
  //   title: 'Alertas!',
  //   content: 'Desea guardar la configuración ?',
  //   icon: 'glyphicon glyphicon-question-sign',
  //   animation: 'scale',
  //   closeAnimation: 'scale',
  //   opacity: 0.5,
  //   buttons: {
  //     'confirm': {
  //       text: 'Si',
  //       btnClass: 'btn-green',
  //       // envio de datos SIN Dependencia
  //       action: function () {
          
  //         $.ajax({
  //           url: 'modulos/administracion/calendario_agente/controlador.php?f=guardarArticulo',
  //           type: "POST",
  //           dataType: "JSON",
  //           data: {
  //             legajo,
  //             form
  //           },
  //           success: function (response) {
  //             calendar.refetchEvents();
  //             get_articulos_agente();
  //             $("#id_db_articulo_configurado").val("")
  //             $("#modalSaveArticle").modal("hide");
  //           }
  //         });

  //       }
  //     },
  //     No: {
  //       btnClass: 'btn-red',
  //       action: function () {
  //         $("#id_db_articulo_configurado").val("")
  //         $("#modalSaveArticle").modal("hide");
  //       }
  //     }
  //   }
  // });

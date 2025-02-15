<?php
@session_start();
include("core/env.php");
include("inc/conexion.php");
conectar();

if (!isset($_SESSION['userid'])) {
 echo "<script>document.location='login.php';</script>";
 exit();
}
/* Holis master*/
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registro de Asistencia Digital</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="inc/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="inc/css/datatables.min.css" rel="stylesheet">
    <link href="inc/css/estilo.css" rel="stylesheet">

    <!--css de alert-->
    <link rel="stylesheet" href="inc/css/jquery-confirm.min.css">
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="inc/js/jquery.js"></script>
    <script src="inc/js/jquery-confirm.js"></script>
    <script src="inc/js/jquery.dataTables.min.js"></script>


    <!--css de calendar-->
    <!-- <link href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="inc/css/js-year-calendar.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/bootstrap-datepicker@1.8.0/dist/css/bootstrap-datepicker.standalone.min.css"> -->
    <!-- Bootstrap Date-Picker Plugin -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" /> -->


    <script type="text/javascript">
        function cerrar() {
            $('#popup').fadeOut('slow');
            $('.popup-overlay').fadeOut('slow');
        }

        function alertas(msj) {
            $.alert({
                title: 'Alerta!',
                content: msj,
                icon: 'fas fa-bell',
                animation: 'scale',
                closeAnimation: 'scale',
                buttons: {
                    okay: {
                        text: 'OK!',
                        btnClass: 'btn-blue'
                    }
                }
            });
        }

        function cambiar_clave_pass() {
            $.get("modulos/administracion/cambiar_clave/formulario.php", function (dato) {

                $("#popup").html(dato);
                $('#popup').fadeIn('slow');
                return false;
            });
        }

        function cerrar_pass() {

            $('#popup').fadeOut('slow');
            $('.popup-overlay').fadeOut('slow');
        }

        //fin Abrir configuracion
        function abrir_version(tag) {
            localStorage.setItem("version", tag);
            $.get("versiones.php", function (dato) {
                $('#popup').html(dato);
                $('#popup').fadeIn('slow');
                controlar_version(tag);
            });
        }

        function validar_pass() {

            if ($("#clave_actual").val().length < 3) {
                $("#clave_actual").focus();
                return 0;
            }
            if ($("#clave_nueva").val().length < 3) {
                $("#clave_nueva").focus();
                return 0;
            }
            if ($("#clave_nueva_1").val().length < 3) {
                $("#clave_nueva_1").focus();
                return 0;
            }

            if ($("#clave_nueva_1").val() != $("#clave_nueva").val()) {
                alertas("Las claves nuevas no coinciden");
                $("#clave_nueva").focus();
                clave_nueva.value = "";
                clave_nueva_1.value = "";
                return 0;
            }
        }

        function controlar_pass() {
            if (validar_pass() == 0) {
                $("#formulario").addClass('was-validated');
                return;
            }
            $.post("modulos/administracion/cambiar_clave/controlador.php", $("#formulario").serialize(), function (
                dato) {
                $("#mensaje").html(dato);
                $('#mensaje').fadeIn('slow');
            });
        }
        $.post("modulos/administracion/cambiar_clave/controlador.php", $("#formulario").serialize(), function (dato) {
            $("#mensaje").html(dato);
            $('#mensaje').fadeIn('slow');
        });


        function showLightbox() {
            $('#over').css('display', 'block');
            $('#fade').css('display', 'block');
        }

        function hideLightbox() {
            $('#over').css('display', 'none');
            $('#fade').css('display', 'none');
        }
    </script>

</head>

<body id="page-top">
    <div id="popup" style="display: none;"></div>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include('menu.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <?php include('header.php'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->


                    <?php
                    if(isset($_GET['pagina'])){
                        $enlace=base64_decode($_GET['pagina']);  
                    }else{
                        
                        /**
                         * aca es el index sin pagina
                         */
                        
                         //por defecto muestro la planilla mensual
                        $enlace="administracion/planilla_mensual";
                    }   

                     $sqlc="SELECT i.*
                     FROM items i
                     join grupos_items g on g.id_item=i.id and g.id_grupo=".$_SESSION['id_grupo'] ."
                     WHERE i.enlace='".$enlace."' 
                     ORDER BY i.orden";

                     $sqlc = pg_query($con, $sqlc);
                     if(pg_num_rows($sqlc)!=0){
                        //echo "<span class='breadcrums'>".$enlace."</span>";
                         include("modulos/".$enlace.".php");
                         $it=str_replace('/', '_',$enlace);
                         if(!isset($_GET['op']))
                         {
                            $_GET['op']=0;
                        }
                        echo "<script>$('#op_".$_GET['op']."').removeClass('collapse').addClass('collapse show');</script>";
                        echo "<script>$('#".$it."').addClass('item_seleccionado');</script>"; 
                    }
                    else{
                        include("403.php");
                    }

               
                ?>

                </div>
                <!-- End of Main Content -->
            </div>

            <!-- Footer -->
            <?php include('footer.php'); ?>
            <!-- End of Footer -->

            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Salir?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su
                        sesión actual.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <a class="btn btn-primary" href="logout.php">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->



        <script src="inc/js/popper.min.js"></script>
        <script src="inc/js/moment.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="inc/js/sb-admin-2.min.js"></script>
        <!-- <script src="inc/js/bootstrap4.min.js"></script> -->
        <script src="vendor/bootstrap/js/bootstrap.js"> </script>
        <script src="vendor/fullcalendar/dist/index.global.js"></script>
        <script src="vendor/fullcalendar/packages/bootstrap4/index.global.js"></script>
        <!-- <script src="inc/js/js-year-calendar.js"></script> -->
        <script src="inc/js/bootstrap-datepicker.min.js">
        </script>
</body>

</html>
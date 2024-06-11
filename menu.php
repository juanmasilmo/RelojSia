<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <i class='far fa-clock'></i>
        </div>
        <div class="sidebar-brand-text mx-3">Reloj <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="fas fa-home"></i>
            <span>Inicio</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Interface
        </div>
  <?php
        /////CARGO LAS OPCIONES
        $sql_o = "SELECT o.* 
        FROM opciones o
        join grupos_opciones g on g.id_opcion=o.id and g.id_grupo=".$_SESSION['id_grupo'] ."
        WHERE o.estado=1
        ORDER BY o.orden";
        $sql_o = pg_query($con, $sql_o);
        while ($row_o = pg_fetch_array($sql_o)) { 
            $descripcion=str_replace(' ', '_',$row_o['descripcion']); 
            ?>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#op_<?php echo $row_o['id']?>"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="<?php echo $row_o['icono']; ?>"></i>
                    <span><?php echo $row_o['descripcion']; ?></span>
                </a>
                <div id="op_<?php echo $row_o['id']?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">

                    <div class="bg-light py-2 collapse-inner rounded">                       
                        <h6 class="collapse-header"><?php echo $row_o['titulo']; ?></h6>

                        <?php 

                        /////CARGO LOS ITEMS DE CADA OPCION
                        $sql_i = "SELECT i.* 
                        FROM items i
                        join grupos_items g on g.id_item=i.id and g.id_grupo=".$_SESSION['id_grupo'] ."
                        WHERE i.id_opcion=".$row_o['id']." and i.estado=1
                        ORDER BY i.orden";
                        $sql_i = pg_query($con, $sql_i);

                        while ($row_i = pg_fetch_array($sql_i)) {                        
                            $cadena_codificada = base64_encode($row_i['enlace']);
                            ?>
                            <a class="collapse-item" id="<?php echo str_replace('/', '_',$row_i['enlace']);?>" href="#" onClick="window.location='index.php?pagina=<?php echo $cadena_codificada; ?>&op=<?php echo $row_i['id_opcion']; ?>'"><?php echo $row_i['descripcion']; ?></a>
                            
                        <?php } ?>   
                        

                    </div>

                </div>
            </li>

        <?php } ?>
    
        <!-- FIN VERSIONES -->
        <?php
            $sql = "SELECT tag FROM sistema_versiones WHERE id=(select max(id) from sistema_versiones)";
            $rs=pg_query($con,$sql);
            $row=pg_fetch_array($rs);
        ?>

        <!-- VERSIONES -->
        <script>

            // function controlar_version(tag) {    
            //     storageversion = localStorage.getItem("version");
            //     if(storageversion != tag){           	
            //         // $(".version_tag").css('color','#ffc107');
            //         document.getElementById('version_tag').className ='parpadea';
            //         $('.parpadea').tooltip('show');
            //     }else{
            //         // $(".parpadea").css('color','#ffffff');
            //         document.getElementById('version_tag').className ='sin_efecto';
            //         $('.sin_efecto').tooltip('disable');

            //     }		
            // }
        </script>
        <!-- FIN VERSIONES -->

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

        <!-- Sidebar Message -->
        <div class="sidebar-card d-none d-lg-flex">
            <span class="fa fa-exclamation-circle"></span>
            <a class="version_tag " onclick="abrir_version('<?php echo $row['tag'];?>')" href="#" data-toggle="tooltip" data-html="true" data-placement="top" title="Nueva Actualización Disponible" style="margin-left: 10px; color: #1cc88a" id="version_tag" ></i> <strong>Versión <?php if (pg_num_rows($rs) > 0) echo $row['tag']; ?></strong></a>
        </div>

    </ul>
<!-- End of Sidebar -->

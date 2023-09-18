<?php
session_start();
include("inc/conexion.php");
$con = conectar();

$sql=pg_query($con,"SELECT tag,descripcion,to_char(fecha,'DD/MM/YYYY') as fecha FROM sistema_versiones WHERE id=(select max(id) from sistema_versiones)");
$row=pg_fetch_array($sql);

?>


<div class="modal-popup" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header bg-info">
        	<h5 class="modal-title">Versión <?php if (pg_num_rows($sql) > 0) echo $row['tag']; ?></h5>
			<button type="button" class="btn btn-secondary" onclick="cerrar()" data-dismiss="modal">x</button>
      	</div>
		<div class="modal-body">
			<div class="col-md-12">
				<h6><?php if (pg_num_rows($sql) > 0) echo $row['fecha']; ?></h6>
			</div>
			<div class="form-group col-md-12 col-lg-12 borde">
				<div class="col-md-12 ">						
					<?php if (pg_num_rows($sql) > 0) echo $row['descripcion']; ?>
				</div>
			</div>
		</div>
		<div class="modal-footer"></div>
    </div>
  </div>
</div>


 

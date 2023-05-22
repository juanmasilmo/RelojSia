<?php
session_start();
include("../../../inc/conexion.php");
conectar();
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>id</th>
						<th>Descripción</th>
						<th>Letra</th>
						<th>Color</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>id</th>
						<th>Descripción</th>
						<th>Letra</th>
						<th>Color</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php 
					$sql="select * from estados order by descripcion";
					$sql=pg_query($con,$sql);
					while($row=pg_fetch_array($sql)){?>
					<tr>
						<td align="center"><?php echo $row['id']; ?></td>
						<td><?php echo $row['descripcion']; ?></td>
						<td class="font-weight-bold"><?php echo $row['letra']; ?></td>
						<td><div class="form-control col-md-6" style="background:<?php echo $row['color']; ?>">&nbsp;</div></td>
						<td>
							<a onclick="editar(<?php echo $row['id']; ?>)" class="btn btn-primary btn-icon-split"
								title="Editar">
								<span class="icon text-white-70">
									<i class="fas fa-edit"></i>
								</span>
								<!--<span class="text">Editar</span>-->
							</a>
							<a onclick="eliminar(<?php echo $row['id']; ?>)" class="btn btn-danger btn-icon-split"
								title="Eliminar">
								<span class="icon text-white-70">
									<i class="fas fa-trash"></i>
								</span>
								<!--<span class="text">Eliminar</span>-->
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
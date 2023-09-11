<?php
session_start();
include("../../../inc/conexion.php");
conectar();
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado de Usuarios</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered " id="dataTable" width="100%" cellspacing="0">
				<thead  >
					<tr>
						<th>id</th>
						<th>Usuario</th>
						<th>Nombre y Apellido</th>
						<th>Grupo</th>
						<th>Alta</th>
						<th>Dependencias</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>id</th>
						<th>Usuario</th>
						<th>Nombre y Apellido</th>
						<th>Grupo</th>
						<th>Alta</th>
						<th>Dependencias</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php 
					$sql="select u.*,to_char(u.fecha_alta,'DD/MM/YYYY hh:mi') as fecha,g.descripcion as grupo from usuarios u join grupos g on g.id=u.id_grupo order by u.usuario";
					$sql=pg_query($con,$sql);
					while($row=pg_fetch_array($sql)){?>
						<tr <?php if($row['estado']==0){?> class="bg-gray-500" <?php }?>>
							<td align="center"><?php echo $row['id']; ?></td>
							<td><?php echo $row['usuario']; ?></td>
							<td><?php echo $row['nombre_apellido']; ?></td>
							<td><?php echo $row['grupo']; ?></td>
							<td align="center"><?php echo $row['fecha']; ?></td>
							<td>
								<?php 
								$sql1="select d.id,d.descripcion from dependencias d join usuario_dependencias ud on ud.id_dependencia=d.id where ud.id_usuario=".$row['id']." order by d.descripcion";
					$sql1=pg_query($con,$sql1);
					while($row1=pg_fetch_array($sql1)){?>
								<?php echo $row1['descripcion']; ?><br>
					<?php } ?>
							</td>
							<td>
								<?php if($row['estado']==0){?> 
									<a onclick="activar_usuario(<?php echo $row['id']; ?>)" class="btn btn-success btn-icon-split" title="Activar Usuario">
										<span class="icon text-white-50">
											<i class="fas fa-user-alt"></i>
										</span>									
									</a>
								<?php } else { ?>
									<a onclick="editar(<?php echo $row['id']; ?>)" class="btn btn-primary btn-icon-split" title="Editar">
										<span class="icon text-white-50">
											<i class="fas fa-edit"></i>
										</span>
										<!--<span class="text">Editar</span>-->
									</a>

									<a onclick="vincular_dependencias(<?php echo $row['id']; ?>)" class="btn btn-primary btn-icon-split" title="Vincular Dependencias">
										<span class="icon text-white-50">
											<i class="fas fa-link"></i>
										</span>
										<!--<span class="text">Editar</span>-->
									</a>

									<a onclick="eliminar(<?php echo $row['id']; ?>)" class="btn btn-danger btn-icon-split" title="Eliminar">
										<span class="icon text-white-50">
											<i class="fas fa-trash"></i>
										</span>
										<!--<span class="text">Eliminar</span>-->
									</a>

									<a onclick="resetear_clave(<?php echo $row['id']; ?>)" class="btn btn-success btn-icon-split" title="Resetear la Clave">
										<span class="icon text-white-50">
											<i class="fas fa-undo-alt"></i>
										</span>
										<!--<span class="text">Resetea Clave</span>-->
									</a>

									<a onclick="bloquear_usuario(<?php echo $row['id']; ?>)" class="btn btn-danger btn-icon-split" title="Bloquear Usuario">
										<span class="icon text-white-50">
											<i class="fas fa-user-alt-slash"></i>
										</span>
										<!--<span class="text">Resetea Clave</span>-->
									</a>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
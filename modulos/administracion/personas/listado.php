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
			<table class="table table-striped table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>ID</th>
							<th align="left">Apellido, Nombres</th>
							<th>Legajo</th>
							<th align="left">Correo</th>
							<th align="left">Categoría</th>							
							<th align="left">Dependencia</th>
							<th width="10%">Acciones</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>#</th>
							<th>ID</th>
							<th align="left">Apellido, Nombres</th>
							<th>Legajo</th>							
							<th align="left">Correo</th>
							<th align="left">Categoría</th>							
							<th align="left">Dependencia</th>
							<th width="10%">Acciones</th>
						</tr>
					</tfoot>
					<tbody>
						<?php   
						$orden=1;         
						$sql="select 
						p.id, 
						concat(p.Apellido,', ',p.nombres) as apellido_nombres,
						p.legajo, 
						p.nro_documento,
						to_char(p.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento,      
						p.domicilio,
						p.correo,
						c.descripcion as descripcion_categoria,
						(case when p.activo=1 then 'SI' else 'NO' end) AS activo,
						d.descripcion as dependencias,
						(select l.descripcion from dependencias d, localidades l WHERE d.id_localidad=l.id and d.id=p.id_dependencia) as localidad
						from personas p 
						join categorias c on c.id=p.id_categoria 
						join dependencias d on d.id=p.id_dependencia
						order by dependencias,apellido_nombres";
						$sql=pg_query($con,$sql);                                        
						while($row=pg_fetch_array($sql))
						{
							?>                                 
							<tr <?php if($row['activo']=='NO') { echo 'style="color:#A9A9A9;"'; } ?>>
								<td align="center"><?php echo $orden++;?></td>
								<td align="center"><?php echo $row['id'];?></td>
								<td align="left" ><?php echo $row['apellido_nombres'];?></td>
								<td align="center"><?php echo $row['legajo'];?></td>
								<td align="left" ><?php echo $row['correo'];?></td>
								<td align="left" ><?php echo $row['descripcion_categoria'];?></td>											
								<td align="left" ><?php echo $row['dependencias'];?> - <b><i><?php echo $row['localidad'];?></i></b></td>
								<td align="center">
									<a onclick="editar(<?php echo $row['id']; ?>)" class="btn btn-primary btn-icon-split" title="Editar">
									<span class="icon text-white-50">
										<i class="fas fa-edit"></i>
									</span>
									<span class="text">Editar</span>
								</a>		
								<?php if($row['activo']=='NO'){?>

									<a onclick="cambiar_estado(<?php echo $row['id']; ?>)" class="btn btn-success btn-icon-split" title="Editar">
									<span class="icon text-white-50">
										<i class="fas fa-toggle-on"></i>
									</span>
									<span class="text">Activar</span>
								</a>									

								<?php } else {?>

									<a onclick="cambiar_estado(<?php echo $row['id']; ?>)" class="btn btn-danger btn-icon-split" title="Editar">
									<span class="icon text-white-50">
										<i class="fas fa-ban"></i>
									</span>
									<span class="text">Desactivar</span>
								</a>								

								
								<?php }?>
								</td>  
							</tr>
							<?php  
						}?>
					</tbody>
					<tfoot>					
				</table>
			</div>
		</div>
	</div>
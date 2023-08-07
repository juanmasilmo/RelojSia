<?php
session_start();
include("../../inc/conexion.php");
conectar();
$cadena=$_GET['cadena'];

if(strlen($cadena)>2)
{

	
	$sql = "SELECT d.id
					,d.descripcion
					,l.descripcion as localidad
			FROM dependencias d, localidades l
			WHERE d.id_localidad=l.id 
				and d.estado=1 
				and ((UPPER(d.descripcion) like UPPER(('%".$cadena."%')))or (UPPER(l.descripcion) like UPPER(('%".$cadena."%'))) )
			ORDER BY d.descripcion";
	$sql=pg_query($con,$sql);
	if(pg_num_rows($sql)==0) {
		?>
		<p><div class="alert alert-warning alert-dismissable"> <button type="button" class="close" data-dismiss="alert">&times;</button> <i class="glyphicon glyphicon-warning-sign"></i> No Se encontraron Resultados</div></p><br />
	<?php }
	else
	{
		?>

		<div align="center">
			<table>
				<thead>
					<tr>
						<th align="left">Descripci&oacute;n</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while($row=pg_fetch_array($sql))
					{
						?>
						<tr>
							<td align="left" ><?php echo $row['descripcion'];?> - <i><b><?php echo $row['localidad'];?></b></i></td>
							<td>
								<a class="btn btn-primary" onClick="seleccionar_dependencia(<?php echo $row['id']; ?>);" title="Seleccionar Dependencia..."><span class="glyphicon glyphicon-ok-circle"></span> Seleccionar</a>
							</td>
						</tr>
						<?php
					}?>
				</tbody>
			</table>
		</div>
	<?php }
}?>

<?php
session_start();
include("../../../inc/conexion.php");
conectar();

$sql="select * from grupos order by descripcion";  
$sql=pg_query($con,$sql);                                       
while($row=pg_fetch_array($sql))
	{?>    

		<div class="card shadow mb-4">
			<!-- Card Header - Accordion -->
			<a href="#opcion_<?php echo $row['id'];?>" class="d-block card-header py-3" data-toggle="collapse"
				role="button" aria-expanded="true" aria-controls="opcion_<?php echo $row['id'];?>">
				<h6 class="m-0 font-weight-bold text-primary"><?php echo $row['descripcion'];?></h6>
			</a>
			<!-- Card Content - Collapse -->
			<div class="collapse" id="opcion_<?php echo $row['id'];?>">
				<div class="card-body">


					<?php $sql1="select * from opciones order by orden";  
					$sql1=pg_query($con,$sql1);   
					while($row1=pg_fetch_array($sql1))
					{
						$sql2="select * from grupos_opciones where id_grupo=".$row['id']." and id_opcion=".$row1['id'];  
						$sql2=pg_query($con,$sql2);    
						$row2=pg_fetch_array($sql2)
						?>                           
						<input style="margin-left:20px;" type="checkbox" name="<?php echo $row['id'];?>_<?php echo $row1['id'];?>" id="<?php echo $row['id'];?>_<?php echo $row1['id'];?>"  
						<?php if($row2['id_grupo']==$row['id']) { ?> checked="checked" <?php } ?>
						onclick="actualizar(<?php echo $row['id'];?>, <?php echo $row1['id'];?>,'<?php echo $row['id'];?>_<?php echo $row1['id'];?>')">  
						<?php echo $row1['descripcion'];?>                                  
					<?php } ?>                

				</div>
			</div>
		</div>
		<?php } ?>
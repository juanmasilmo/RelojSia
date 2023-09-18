<?php
session_start();
include_once "../../../inc/conexion.php";
$con = conectar();
?>
<br>
<div align="center"> 
 <table id="tabla" class="cell-border compact stripe hover">
    <thead>
    <tr>
     <th>Orden</th>
     <th>ID</th>
     <th align="left">TAG</th>
     <th align="left">Descripción</th>
     <th>Fecha</th>
     <th>Acciones</th>
   </tr>
 </thead>
 <tfoot>
      <tr>
     <th>Orden</th>
     <th>ID</th>
     <th align="left">TAG</th>
     <th align="left">Descripción</th>
     <th>Fecha</th>
     <th>Acciones</th>
   </tr>
 </tfoot>
 <tbody>
   <?php
   $orden=1;
   $sql="SELECT
            id,
            tag,
            descripcion,
            to_char(fecha,'DD/MM/YYYY') as fecha
          FROM
            sistema_versiones
          ORDER BY
            fecha
         ";
   $sql=pg_query($con,$sql);
   while($row=pg_fetch_array($sql))
    {?>
      <tr>
        <td><?php echo $orden++;?></td>
        <td><?php echo $row['id'];?></td>
        <td align="left"><?php echo $row['tag'];?></td>
        <td align="left"><?php echo $row['descripcion'];?></td>
        <td><?php echo $row['fecha'];?></td>
        <td style="width:15%">
          <a class="btn btn-primary" onclick="editar(<?php echo $row['id'];?>)"> <span class="glyphicon glyphicon-edit"></span> Editar</a>
          <a class="btn btn-error" onclick="eliminar(<?php echo $row['id'];?>)"><span class="glyphicon glyphicon-trash"></span> Eliminar</a>
        </td>
      </tr>
     <?php }?>
     </tbody>
     </table>
     
 </div>

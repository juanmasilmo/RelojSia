<?php
session_start();
include("../../../inc/conexion.php");
include_once("../../../core/env.php");
conectar();

// busco los instructivos
$sql = "SELECT * FROM instructivo ORDER BY id";
$query = pg_query($con, $sql);
$orden = 0;
?>
<br />
<div align="center">
  <table id="tabla" class="cell-border compact stripe hover">
    <thead>
      <tr>
        <th>Orden</th>
        <th>ID</th>
        <th align="left">Titulo</th>
        <th>Descripcion</th>
        <th>URL</th>
        <th>archivos</th>
        <th class="controlar" id="editar_instructivo" data-column="6">
      </tr>
    </thead>
    <tbody>
      <?php
      while ($row = pg_fetch_array($query)) {
        $orden++;
      ?>
        <tr>
          <td>
            <?php echo $orden; ?>
          </td>
          <td>
            <?php echo $row['id']; ?>
          </td>
          <td>
            <?php echo $row['titulo']; ?>
          </td>
          <td>
            <?php echo $row['descripcion']; ?>
          </td>
          <td>
            <?php
            if (!empty($row['url_video'])) { ?>
              <a title="Ver video" 
                  
                  onclick="ver_video('<?php echo $row['url_video']; ?>')">
                    <img src="imagenes/yt.png" width="50" height="50" title="Ver video" />
            </a>
            <?php } ?>
          </td>
          <td>
            <?php
            if (!empty($row['url_documento'])) { ?>
         <a class="btn btn-primary" target="_blank" href="<?php echo DOCUMENTOS.$row['url_documento'];?>" title="Ver Documentación"><span class="fa fa-file-pdf-o"></span> Documento</a>
            <?php
            } 
            ?>
          </td>
          <td>
            <a href="#" onclick="editar(<?php echo $row['id']; ?>)" class="btn btn-primary"><span class="fas fa-edit"></span> Editar</a>
            <a class="btn btn-error" onclick="eliminar(<?php echo $row['id'];?>)">
              <span class="icon text-white-50"></span> Eliminar
            </a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <th>Orden</th>
        <th>ID</th>
        <th align="left">Titulo</th>
        <th>Descripcion</th>
        <th>URL</th>
        <th>archivos</th>
        <th width="10%">Acciones</th>
      </tr>
    </tfoot>
  </table>
</div>

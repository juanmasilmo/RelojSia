<?php
session_start();
include("../../../inc/conexion.php");
include_once("../../../core/env.php");
conectar();

$grupo = $_SESSION['id_grupo'];

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
        <?php if($grupo == 1){ ?>
          <th class="controlar" id="editar_instructivo" data-column="6">
        <?php } ?>
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
         <a class="btn btn-primary" target="_blank" href="modulos/administracion/instructivo/ver_documento.php?documento=<?php echo $row['url_documento'];?>" title="Ver Documentación"><span class="fa fa-file-pdf-o"></span> Documento</a>
            <?php
            } 
            ?>
          </td>
          <?php if($grupo == 1){ ?>
          <td>
            <a href="#" onclick="editar(<?php echo $row['id']; ?>)" class="btn btn-primary"><span class="fas fa-edit"></span> Editar</a>
            <a class="btn btn-danger" onclick="eliminar(<?php echo $row['id'];?>)">
              <span class="icon text-white-50"></span> Eliminar
            </a>
          </td>
          <?php } ?>
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
        <?php if($grupo == 1){ ?>
          <th width="10%">Acciones</th>
        <?php } ?>
      </tr>
    </tfoot>
  </table>
</div>

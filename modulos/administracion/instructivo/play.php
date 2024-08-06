<?php
session_start();
include("../../../inc/conexion.php");
conectar();
?>
<div class="content-popup">
  <div class="close"><a class="popup-cerrar" onclick="cerrar();">X</a></div>
<h2>Video</h2>
  <iframe width="640" height="360" src="https://www.youtube.com/embed/<?php echo $_GET['url']; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>



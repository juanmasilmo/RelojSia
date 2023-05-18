<?php
session_start();
include("../../../inc/conexion.php");
conectar();
if(isset($_POST['clave_actual']))
{
  $sql="select u.clave from usuarios u where u.id='".$_SESSION['userid']."' ";  
  $sql=pg_query($con,$sql);                     
  if(pg_num_rows($sql)!=0)
  {         
    $row=pg_fetch_array($sql);          
    if (password_verify($_POST['clave_actual'], $row['clave'])) 
    {
      $clave_nueva = password_hash($_POST['clave_nueva'], PASSWORD_DEFAULT);  
      $sql="UPDATE usuarios set clave='".$clave_nueva."' where id=".$_SESSION['userid'];
      $sql=pg_query($con,$sql);
      if(!pg_result_error($sql))
      {                                 
        echo "<script>alert('Clave actualizada con exito');cerrar_pass();</script>";    
      }
      else
      {
        echo "<script>alert('Error: No se pudo Actualizar el registro.');</script>";
      }
    }
    else
    {        
      echo "<script>
      alert('Error: La clave actual no coincide.'); 
      clave_actual.value = '';
      $('#clave_actual').focus();</script>";
    }
  }
}
?>


<?php
include_once("connection.php");
$con = connection();

 if (isset($_POST['delete'])) {

echo $sql2 = "DELETE FROM `record` WHERE id = id";
$con->query($sql2) or die($con->error);
  	  echo header("Location: ../admin/current.php")  ;
 }
?>
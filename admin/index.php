<?php
  if(isset($_SESSION["userID"]))
    header("Location: /admin/dashboard.php");
  else
    header("Location: /admin/login.php");
?>

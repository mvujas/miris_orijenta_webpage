<?php
  session_start();
  $current_script = basename($_SERVER['SCRIPT_FILENAME']);
  if($current_script !== "login.php" && !isset($_SESSION["userID"]))
    header("Location: login.php");

  function loginUser() {
    $_SESSION["userID"] = "Pera";
  }

  function logoutUser() {
    unset($_SESSION["userID"]);
    header("Location: login.php");
  }
?>

<?php
  session_start();

  require_once 'database/db_utils.php';
  $current_script = basename($_SERVER['SCRIPT_FILENAME']);
  if($current_script !== "login.php" && !isset($_SESSION["userID"]))
    header("Location: login.php");

  function loginUser($username, $password) {
    list($user_id, $password_hash) = getUserByUsername($username);
    if($user_id && password_verify($password, $password_hash)) {
      $_SESSION["userID"] = 123;
      return true;
    }
    return false;
  }

  function logoutUser() {
    unset($_SESSION["userID"]);
    header("Location: login.php");
  }
?>

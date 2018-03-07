<?php
  session_start();

  require_once 'database/db_utils.php';
  $current_script = basename($_SERVER['SCRIPT_FILENAME']);
  if($current_script !== "login.php" && !isset($_SESSION["userID"]))
    header("Location: login.php");

  $logged_users_username;
  if(isset($_SESSION["userID"]))
    $logged_users_username = getUsernameByID($_SESSION["userID"]);

  function loginUser($username, $password) {
    list($user_id, $password_hash) = getUserByUsername($username);
    if($user_id && password_verify($password, $password_hash)) {
      $_SESSION["userID"] = $user_id;
      return true;
    }
    return false;
  }

  function logoutUser() {
    unset($_SESSION["userID"]);
    header("Location: login.php");
  }
?>

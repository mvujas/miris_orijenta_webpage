<?php
  define('DB_SERVER', 'localhost');
  define('DB_USER', 'testuser');
  define('DB_PASSWORD', '');
  define('DB_DATABASE', 'miris_orijenta');

  function connect_to_db() {
    return mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
  }

  function close($connection) {
    mysqli_close($connection);
  }

  function getUserByUsername($username) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "SELECT UID, Password FROM User WHERE Username = ?");
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);
    mysqli_stmt_bind_result($statement, $id, $password);
    mysqli_stmt_fetch($statement);
    close($connection);
    return array($id, $password);
  }

  function countCategories() {
    $connection = connect_to_db();
    $result = mysqli_query($connection, "SELECT COUNT(1) as N FROM Category");
    $row = mysqli_fetch_assoc($result)["N"];
    close($connection);
    return $row | 0;
  }

  function getUsernameByID($ID) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "SELECT Username FROM User WHERE UID = ?");
    mysqli_stmt_bind_param($statement, "i", $ID);
    mysqli_stmt_execute($statement);
    mysqli_stmt_bind_result($statement, $username);
    mysqli_stmt_fetch($statement);
    close($connection);
    return $username;
  }
?>

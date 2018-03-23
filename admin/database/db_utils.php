<?php
  define('DB_SERVER', 'localhost');
  define('DB_USER', 'testuser');
  define('DB_PASSWORD', '');
  define('DB_DATABASE', 'miris_orijenta');

  define('INT_KEY_UPPER_LIMIT', 2000000000);


  /**
   * Following functions manipulate database connection
   */

  function connect_to_db() {
    return mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
  }

  function close($connection) {
    mysqli_close($connection);
  }

  /**
   * Helper functions
   */
  function generateRandomIntKey() {
    return rand(-INT_KEY_UPPER_LIMIT, INT_KEY_UPPER_LIMIT);
  }

  /**
   * Following functions manipulate products and categories
   */

  function doesProductExist($ID) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "SELECT 1 FROM Product WHERE PID = ?");
    mysqli_stmt_bind_param($statement, "i", $ID);
    mysqli_stmt_execute($statement);
    mysqli_stmt_bind_result($statement, $result);
    mysqli_stmt_fetch($statement);
    close($connection);
    return $result === 1;
  }

  function doesCategoryExist($ID) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "SELECT 1 FROM Category WHERE CID = ?");
    mysqli_stmt_bind_param($statement, "i", $ID);
    mysqli_stmt_execute($statement);
    mysqli_stmt_bind_result($statement, $result);
    mysqli_stmt_fetch($statement);
    close($connection);
    return $result === 1;
  }

  function countCategories() {
    $connection = connect_to_db();
    $result = mysqli_query($connection, "SELECT COUNT(1) as N FROM Category");
    $row = mysqli_fetch_assoc($result)["N"];
    close($connection);
    return $row | 0;
  }

  function countProducts() {
    $connection = connect_to_db();
    $result = mysqli_query($connection, "SELECT COUNT(1) as N FROM Product");
    $row = mysqli_fetch_assoc($result)["N"];
    close($connection);
    return $row | 0;
  }

  function addCategory($name) {
    $ID;
    while(doesCategoryExist($ID = generateRandomIntKey()));
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "INSERT INTO Category(CID, Name) VALUES (?, ?)");
    mysqli_stmt_bind_param($statement, "is", $ID, $name);
    $result = mysqli_stmt_execute($statement);
    close($connection);
    return $result;
  }

  function addProduct($name, $category, $imageName) {
    $ID;
    while(doesProductExist($ID = generateRandomIntKey()));
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "INSERT INTO Product(PID, Name, Image, CID) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($statement, "issi", $ID, $name, $imageName, $category);
    $result = mysqli_stmt_execute($statement);
    close($connection);
    return $result;
  }

  function deleteAllProductsFromCategory($ID) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "DELETE FROM Product WHERE CID = ?");
    mysqli_stmt_bind_param($statement, "i", $ID);
    $result = mysqli_stmt_execute($statement);
    close($connection);
    return $result;
  }

  function deleteCategory($ID) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "DELETE FROM Category WHERE CID = ?");
    mysqli_stmt_bind_param($statement, "i", $ID);
    $result = mysqli_stmt_execute($statement);
    close($connection);
    return $result;
  }

  function deleteProduct($ID) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "DELETE FROM Product WHERE PID = ?");
    mysqli_stmt_bind_param($statement, "i", $ID);
    $result = mysqli_stmt_execute($statement);
    close($connection);
    return $result;
  }

  function getAllCategories() {
    $connection = connect_to_db();
    $query = mysqli_query($connection, "SELECT CID, Name FROM Category");
    $result = array();
    while($row = mysqli_fetch_assoc($query))
      $result[] = $row;
    close($connection);
    return $result;
  }

  function getAllProducts() {
    $connection = connect_to_db();
    $query = mysqli_query($connection, "SELECT PID, Name, CID, Choosen FROM Product");
    $result = array();
    while($row = mysqli_fetch_assoc($query))
      $result[] = $row;
    close($connection);
    return $result;
  }

  function getChoosenProducts() {
    $connection = connect_to_db();
    $query = mysqli_query($connection, "SELECT P.PID, P.Name FROM ChoosenProduct CP, Product P WHERE CP.PID = P.PID LIMIT 8");
    $result = array();
    while($row = mysqli_fetch_assoc($query))
      $result[] = $row;
    close($connection);
    return $result;
  }

  function getCategoryByID($ID) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "SELECT Name FROM Category WHERE CID = ?");
    mysqli_stmt_bind_param($statement, "i", $ID);
    mysqli_stmt_execute($statement);
    mysqli_stmt_bind_result($statement, $result);
    mysqli_stmt_fetch($statement);
    close($connection);
    return $result;
  }

  function getProductByID($ID) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "SELECT Name, CID FROM Product WHERE PID = ?");
    mysqli_stmt_bind_param($statement, "i", $ID);
    mysqli_stmt_execute($statement);
    mysqli_stmt_bind_result($statement, $name, $cid);
    mysqli_stmt_fetch($statement);
    close($connection);
    return array($name, $cid);
  }

  function saveCategoryChanges($ID, $name) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "UPDATE Category SET Name = ? WHERE CID = ?");
    mysqli_stmt_bind_param($statement, "si", $name, $ID);
    $result = mysqli_stmt_execute($statement);
    close($connection);
    return $result;
  }

  function saveProductChanges($ID, $name, $category) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "UPDATE Product SET Name = ?, CID = ? WHERE PID = ?");
    mysqli_stmt_bind_param($statement, "sii", $name, $category, $ID);
    $result = mysqli_stmt_execute($statement);
    close($connection);
    return $result;
  }

  function countChoosenProducts() {
    $connection = connect_to_db();
    $result = mysqli_query($connection, "SELECT COUNT(1) as N FROM ChoosenProduct");
    $row = mysqli_fetch_assoc($result)["N"];
    close($connection);
    return $row | 0;
  }

  function addProductToChoosen($ID) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "UPDATE Product SET Choosen = 1 WHERE PID = ?");
    mysqli_stmt_bind_param($statement, "i", $ID);
    $result = mysqli_stmt_execute($statement);
    if($result) {
      $statement = mysqli_prepare($connection, "INSERT INTO ChoosenProduct(PID) VALUES (?)");
      mysqli_stmt_bind_param($statement, "i", $ID);
      $result &= mysqli_stmt_execute($statement);
    }
    return $result;
  }

  function removeProductFromChoosen($ID) {
    $connection = connect_to_db();
    $statement = mysqli_prepare($connection, "DELETE FROM ChoosenProduct WHERE PID = ?");
    mysqli_stmt_bind_param($statement, "i", $ID);
    $result = mysqli_stmt_execute($statement);
    if($result) {
      $statement = mysqli_prepare($connection, "UPDATE Product SET Choosen = 0 WHERE PID = ?");
      mysqli_stmt_bind_param($statement, "i", $ID);
      $result &= mysqli_stmt_execute($statement);
    }
    return $result;
  }

  /**
   * Following functions manipulate users
   */

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

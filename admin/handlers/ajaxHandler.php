<?php
  session_start();
  define('MIRIS_ORIJENTA_REFERER', 'localhost');
  define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
  define('IS_RIGHT_REFERER', isset($_SERVER['HTTP_REFERER']) && strcmp(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST), MIRIS_ORIJENTA_REFERER) === 0);
  if(!(IS_AJAX && IS_RIGHT_REFERER && isset($_SESSION["userID"]) && isset($_POST["action"])))
    die();

  $result = array(
    "success" => true,
    "data" => null
  );

  require_once '../config.php';
  require_once '../database/db_utils.php';

  switch($_POST["action"]) {
    case "deleteCategory":
      $cid;
      if(!isset($_POST["cid"]))
        $result["success"] = false;
      else
        $cid = (int)$_POST["cid"];
      if($result["success"])
        $result["success"] = deleteAllProductsFromCategory($cid) && deleteCategory($cid);
      break;
    case "addCategory":
      $result["data"] = $_POST["name"];
      $result["data"] = array(
        "error" => null
      );

      if(!isset($_POST["name"]) || strlen($_POST["name"]) < CATEGORY_MIN_NAME_LENGTH)
        $result["data"]["error"] = "Ime kategorije mora imati bar " .CATEGORY_MIN_NAME_LENGTH. " karaktera.";
      else {
        $new_name = htmlspecialchars($_POST["name"]);
        $query_result = addCategory($new_name);
        if(!$query_result)
          $result["data"]["error"] = "Došlo je do greške prilikom dodavanja , pokušajte ponovo kasnije. Postoji mogućnost da već postoji kategorija sa ovim imenom.";
      }
      break;
    case "deleteProduct":
      $pid;
      if(!isset($_POST["pid"]))
        $result["success"] = false;
      else
        $pid = (int)$_POST["pid"];
      if($result["success"])
        $result["success"] = deleteProduct($pid);
      break;
    default:
      $result["success"] = false;
  }


  exit(json_encode($result));
?>

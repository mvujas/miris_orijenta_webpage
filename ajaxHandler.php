<?php
  define('MIRIS_ORIJENTA_REFERER', 'localhost');
  define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
  define('IS_RIGHT_REFERER', isset($_SERVER['HTTP_REFERER']) && strcmp(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST), MIRIS_ORIJENTA_REFERER) === 0);
  if(!(IS_AJAX && IS_RIGHT_REFERER && isset($_POST["action"])))
    die();

  require_once 'admin/database/db_utils.php';

  $result = null;

  switch($_POST["action"]) {
    case "getChoosenProducts":
      $result = getChoosenProducts();
      break;
  }

  exit(json_encode($result));
?>

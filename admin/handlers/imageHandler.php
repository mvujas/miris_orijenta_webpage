<?php
  define('PRODUCT_IMAGES_DIRECTORY', $_SERVER["DOCUMENT_ROOT"] . "/products_images/");
  define('IMAGE_BASE_NAME_LEN', 50);

  function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  function saveImage($file) {
    $fileName;
    $pathToFile;
    do {
      $fileName = generateRandomString(IMAGE_BASE_NAME_LEN) . ".jpg";
      $pathToFile = PRODUCT_IMAGES_DIRECTORY . $fileName;
    } while(file_exists($pathToFile));
    move_uploaded_file(
      $file,
      $pathToFile
    );
    return $fileName;
  }

  function deleteImage($imageName) {
    $path = PRODUCT_IMAGES_DIRECTORY . $imageName;
    return file_exists($path) && unlink($path);
  }
?>

<?php
  require_once 'parts.php';
  define('COOKIE_EXP_TIME', 315360000);
  $errors = array();
  $username = isset($_COOKIE["rememberedUser"]) ? $_COOKIE["rememberedUser"] : "";
  $rememberMe = isset($_COOKIE["rememberedUser"]);
  if(isset($_POST["login"])) {
    $password;
    if(empty($_POST["username"]))
      $errors[] = "Korisničko ime je obavezno da se unese.";
    else
      $username = htmlspecialchars(trim($_POST["username"]));

    if(empty($_POST["password"]))
      $errors[] = "Šifra je obavezna da se unese.";
    else
      $password = htmlspecialchars(trim($_POST["password"]));

    $rememberMe = isset($_POST["remember"]);

    if(empty($errors) && !loginUser($username, $password))
      $errors[] = "Korisnik ili ne postoji u bazi ili ste uneli pogrešnu šifru.";

    if(empty($errors))
      if($rememberMe)
        setcookie("rememberedUser", $username, time() + COOKIE_EXP_TIME);
      else
        setcookie("rememberedUser", $username, time() - 3600);
  }

  if(isset($_SESSION["userID"]))
    header("Location: dashboard.php");

  function printErrors() {
    global $errors;
    foreach($errors as $error)
      echo "
      <div class=\"alert alert-danger\">
        <strong>Greška!</strong> $error
      </div>
      ";
  }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php printIncludes('Prijavljivanje') ?>
    </head>
    <body>

        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Admin panel</h3>
                        </div>
                        <div class="panel-body">
                            <?php printErrors() ?>
                            <form role="form" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Korisničko ime" name="username" type="text" value="<?php echo $username ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Šifra" name="password" type="password" value="" required>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="remember" type="checkbox" value="Remember Me"<?php if($rememberMe) echo " checked"; ?>>Zapamti me
                                        </label>
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <input type="submit" class="btn btn-lg btn-success btn-block" name="login" value="Prijavi se">
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php printJavascript() ?>

    </body>
</html>

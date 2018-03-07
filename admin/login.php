<?php
  require_once 'parts.php';
  if(isset($_POST["login"]))
    $_SESSION["userID"] = "Pera";

  if(isset($_SESSION["userID"]))
    header("Location: index.php");
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
                            <form role="form" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Korisničko ime" name="email" type="email" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Šifra" name="password" type="password" value="">
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="remember" type="checkbox" value="Remember Me">Zapamti me
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

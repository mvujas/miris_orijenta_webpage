<?php
  require_once 'parts.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php printIncludes('Kategorije') ?>
        <!-- DataTables CSS -->
        <link href="css/dataTables/dataTables.bootstrap.css" rel="stylesheet">

        <!-- DataTables Responsive CSS -->
        <link href="css/dataTables/dataTables.responsive.css" rel="stylesheet">
    </head>
    <body>

        <div id="wrapper">

            <?php printNavigation() ?>

            <div id="page-wrapper">
                <?php
                  if(isset($_GET["edit"])) {
                    $category = getCategoryByID($_GET["edit"]);
                    var_dump($category);
                ?>
                  <div class="row">
                      <div class="col-lg-12">
                          <h1 class="page-header">Izmena kategorije</h1>
                      </div>
                      <!-- /.col-lg-12 -->
                  </div>
                  <!-- /.row -->
                  <?php
                    if($category) {
                      if(isset($_POST["saveChanges"])) {
                        if(!isset($_POST["name"]) || strlen($_POST["name"]) < 5)
                          echo "<div class=\"alert alert-danger alert-dismissable\">
                              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                              Ime kategorije mora imati bar 5 karaktera.
                          </div>";
                        else if(strcmp($_POST["name"], $category) === 0)
                          echo "<div class=\"alert alert-danger alert-dismissable\">
                              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                              Niste napravili nikakvu izmenu.
                          </div>";
                        else {
                          $new_name = htmlspecialchars($_POST["name"]);
                          $result = changeCategoryName($_GET["edit"], $new_name);
                          if($result) {
                            $category = $new_name;
                            echo "<div class=\"alert alert-success alert-dismissable\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                                Izmene su uspešno sačuvane. Vratite se na <a href=\"categories.php\" class=\"alert-link\">pregled kategorija</a>.
                            </div>";
                          }
                          else
                            echo "<div class=\"alert alert-danger alert-dismissable\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                                Došlo je do greške prilikom čuvanja napravljenih izmena, pokušajte ponovo kasnije ili se vratite na <a href=\"categories.php\" class=\"alert-link\">pregled kategorija</a>.
                            </div>";


                        }
                      }

                  ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Podaci o kategoriji
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form role="form" action="categories.php?edit=<?php echo htmlspecialchars($_GET["edit"]) ?>" method="post">
                                                <div class="form-group">
                                                    <label>Naziv</label>
                                                    <input class="form-control" name="name" value="<?php echo $category ?>">
                                                </div>
                                                <input type="submit" name="saveChanges" value="Sačuvaj" class="btn btn-success">
                                                <a href="categories.php" class="btn btn-default">Povratak</a>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.row (nested) -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    <!-- /.row -->
                  <?php } else { ?>
                    <div class="alert alert-danger">
                        Data kategorija ne postoji u bazi. Vratite se na <a href="categories.php" class="alert-link">pregled kategorija</a>.
                    </div>
                <?php
                    }
                  }
                  else {
                    $categories = getAllCategories();
                ?>
                  <div class="row">
                      <div class="col-lg-12">
                          <h1 class="page-header">Kategorije</h1>
                      </div>
                      <!-- /.col-lg-12 -->
                  </div>
                  <!-- /.row -->
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  Kategorije
                              </div>
                              <!-- /.panel-heading -->
                              <div class="panel-body">
                                  <div class="dataTable_wrapper">
                                      <table class="table table-striped table-bordered table-hover" id="categoriesTable">
                                          <thead>
                                              <tr>
                                                  <th>Naziv kategorije</th>
                                                  <th>Opcije</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                            <?php
                                              foreach($categories as &$category)
                                                echo "
                                                  <tr>
                                                    <td>{$category["Name"]}</td>
                                                    <td>
                                                      <center>
                                                        <a class=\"btn btn-primary\" href=\"categories.php?edit={$category["CID"]}\"><i class=\"fa fa-pencil\"></i> Izmeni</a>
                                                        <a class=\"btn btn-danger\"><i class=\"fa fa-trash-o\"></i> Obriši</a>
                                                      </center>
                                                    </td>
                                                  </tr>";
                                            ?>
                                          </tbody>
                                      </table>
                                  </div>
                                  <!-- /.table-responsive -->
                              </div>
                              <!-- /.panel-body -->
                          </div>
                          <!-- /.panel -->
                      </div>
                      <!-- /.col-lg-12 -->
                  </div>
                  <!-- /.row -->
                <?php } ?>
            </div>
            <!-- /#page-wrapper -->
        </div>
        <!-- /#wrapper -->


        <?php printJavascript() ?>
        <!-- DataTables JavaScript -->
        <script src="js/dataTables/jquery.dataTables.min.js"></script>
        <script src="js/dataTables/dataTables.bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
              $('#categoriesTable').DataTable({
                responsive: true
              });
            });
        </script>

    </body>
</html>

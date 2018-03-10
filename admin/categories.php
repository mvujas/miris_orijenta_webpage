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
                        if(!isset($_POST["name"]) || strlen($_POST["name"]) < CATEGORY_MIN_NAME_LENGTH)
                          echo "<div class=\"alert alert-danger alert-dismissable\">
                              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                              Ime kategorije mora imati bar " .CATEGORY_MIN_NAME_LENGTH. " karaktera.
                          </div>";
                        else if(strcmp($_POST["name"], $category) === 0)
                          echo "<div class=\"alert alert-danger alert-dismissable\">
                              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                              Niste napravili nikakvu izmenu.
                          </div>";
                        else {
                          $new_name = htmlspecialchars($_POST["name"]);
                          $result = saveCategoryChanges($_GET["edit"], $new_name);
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
                                                  <tr id=\"category-{$category["CID"]}\">
                                                    <td>{$category["Name"]}</td>
                                                    <td>
                                                      <center>
                                                        <a class=\"btn btn-primary\" href=\"categories.php?edit={$category["CID"]}\"><i class=\"fa fa-pencil\"></i> Izmeni</a>
                                                        <a class=\"btn btn-danger categoryDeleteButton\" data-cid=\"{$category["CID"]}\" data-cname=\"{$category["Name"]}\"><i class=\"fa fa-trash-o\"></i> Obriši</a>
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

                          <div id="add-category">
                            <div class="add-category-error-box"></div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Dodaj kategoriju
                                </div>
                                <div class="panel-body">
                                    <form role="form" action="categories.php#add-category" method="post">
                                      <div class="form-group">
                                        <label>Naziv kategorije</label>
                                        <input class="form-control" name="name" value="">
                                      </div>
                                      <input type="hidden" name="action" value="addCategory">
                                      <input type="submit" value="Dodaj" class="btn btn-success">
                                    </form>
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                          </div>
                          <!-- /#add-category -->
                      </div>
                      <!-- /.col-lg-12 -->
                  </div>
                  <!-- /.row -->



                  <!-- Modal -->
                  <div id="categoryMainModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Brisanje kategorije</h4>
                        </div>
                        <div class="modal-body">
                          <div class="modal-error-box"></div>
                          <p class="warningDeletionMessage"></p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger" id="finishCategoryDeletion">Obriši</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Otkaži</button>
                        </div>
                      </div>

                    </div>
                  </div>
                <?php } ?>
            </div>
            <!-- /#page-wrapper -->
        </div>
        <!-- /#wrapper -->

        <?php printJavascript() ?>
        <!-- DataTables JavaScript -->
        <script src="js/dataTables/jquery.dataTables.min.js"></script>
        <script src="js/dataTables/dataTables.bootstrap.min.js"></script>
        <?php if (!isset($_GET["edit"])): ?>
        <script>
            var table;
            $(document).ready(function() {
              table = $('#categoriesTable').DataTable({
                responsive: true
              });
            });

            var modal = {
              target: $("#categoryMainModal"),
              bodyText: $("#categoryMainModal .warningDeletionMessage"),
              errorBox: $("#categoryMainModal .modal-error-box"),
              setBodyMessage: function(message) {
                this.bodyText.html(message);
              },
              setError: function(content) {
                this.errorBox.html(content);
              },
              show: function() {
                this.target.modal("show");
              },
              close: function() {
                this.target.modal("hide");
              }
            };

            var deletion = {
              targetID: undefined,
              prepare: function(ID, name) {
                this.targetID = ID;
                modal.setBodyMessage("Da li ste sigurni da zelite da obrišete kategoriju \"" + name + "\"?<br>Sa njom ćete obrisati i sve proizvode unutar nje.");
                modal.show();
              },
              finish: function() {
                var del = this;
                var categoryID = this.targetID;
                if(categoryID === undefined) {
                  modal.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                    "Ni jedna kategorija ne čeka na brisanje ili je kategorija već obrisana." +
                    "</div>");
                  return;
                }
                $.ajax({
                  url: 'handlers/ajaxHandler.php',
                  type: 'post',
                  dataType: 'json',
                  data: 'action=deleteCategory&cid=' + categoryID,
                  success: function(result) {
                    if(result.success) {
                      table.row($("#category-" + categoryID)).remove().draw();
                      del.targetID = undefined;
                      modal.setError("<div class=\"alert alert-success alert-dismissable\">" +
                        "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                        "Kategorija je uspešno obrisana." +
                        "</div>");
                    }
                    else
                      modal.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                        "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                        "Došlo je do greške prilikom brisanja kategorije." +
                        "</div>");
                  },
                  error: function() {
                    modal.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                      "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                      "Došlo je do greške prilikom brisanja kategorije." +
                      "</div>");
                  }
                });
              },
              reset: function() {
                this.targetID = undefined;
                modal.setBodyMessage("");
                modal.setError("");
              }
            };
            modal.target.on('hidden.bs.modal', function () {
              deletion.reset();
            });

            $("#finishCategoryDeletion").click(function() {
              deletion.finish();
            });


            $(".categoryDeleteButton").click(function() {
              var categoryID = $(this).data("cid");
              var categoryName = $(this).data("cname");
              deletion.prepare(categoryID, categoryName);
            });

            var addCategoryErrorBox = {
              target: $(".add-category-error-box"),
              setError: function(result) {
                this.target.html(result);
              }
            };

            $("#add-category form").submit(function(e) {
              e.preventDefault();
              var data = $(this).serialize();
              $.ajax({
                url: 'handlers/ajaxHandler.php',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(result) {
                  if(result.success) {
                    if(result.data.error)
                      addCategoryErrorBox.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                        "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                        result.data.error +
                        "</div>");
                    else {
                      location.reload();
                       $(window).scrollTop(0);
                    }
                  }
                  else
                    addCategoryErrorBox.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                      "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                      "Došlo je do greške prilikom dodavanja kategorije." +
                      "</div>");
                },
                error: function() {
                  addCategoryErrorBox.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                    "Došlo je do greške prilikom dodavanja kategorije." +
                    "</div>");
                }
              });

            });
        </script>
        <?php endif; ?>

    </body>
</html>

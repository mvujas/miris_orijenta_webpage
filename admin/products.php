<?php
  require_once 'parts.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php printIncludes('Proizvodi') ?>
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
                          <h1 class="page-header">Izmena proizvoda</h1>
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
                    $products = getAllProducts();
                    $categories_tmp = getAllCategories();
                    $categories = array();
                    foreach($categories_tmp as &$ctg)
                      $categories[$ctg["CID"]] = $ctg["Name"];
                ?>
                  <div class="row">
                      <div class="col-lg-12">
                          <h1 class="page-header">Proizvodi</h1>
                      </div>
                      <!-- /.col-lg-12 -->
                  </div>
                  <!-- /.row -->
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  Proizvodi
                              </div>
                              <!-- /.panel-heading -->
                              <div class="panel-body">
                                  <div class="dataTable_wrapper">
                                      <table class="table table-striped table-bordered table-hover" id="productsTable">
                                          <thead>
                                              <tr>
                                                  <th>Naziv proizvoda</th>
                                                  <th>Kategorija</th>
                                                  <th>Opcije</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                            <?php
                                              foreach($products as &$product)
                                                echo "
                                                  <tr id=\"product-{$product["PID"]}\">
                                                    <td>{$product["Name"]}</td>
                                                    <td>{$categories[$product["CID"]]}</td>
                                                    <td>
                                                      <center>
                                                        <a class=\"btn btn-primary\" href=\"products.php?edit={$product["PID"]}\"><i class=\"fa fa-pencil\"></i> Izmeni</a>
                                                        <a class=\"btn btn-danger productDeleteButton\" data-pid=\"{$product["PID"]}\" data-pname=\"{$product["Name"]}\"><i class=\"fa fa-trash-o\"></i> Obriši</a>
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

                          <div id="add-product">
                            <div class="add-product-error-box"></div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Dodaj proizvod
                                </div>
                                <div class="panel-body">
                                    <form role="form" action="categories.php#" method="post">
                                      <div class="form-group">
                                        <label>Naziv kategorije</label>
                                        <input class="form-control" name="name" value="">
                                      </div>
                                      <input type="hidden" name="action" value="addProduct">
                                      <input type="submit" value="Dodaj" class="btn btn-success">
                                    </form>
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                          </div>
                          <!-- /#add-product -->
                      </div>
                      <!-- /.col-lg-12 -->
                  </div>
                  <!-- /.row -->



                  <!-- Modal -->
                  <div id="productMainModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Brisanje proizvoda</h4>
                        </div>
                        <div class="modal-body">
                          <div class="modal-error-box"></div>
                          <p class="warningDeletionMessage"></p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger" id="finishProductDeletion">Obriši</button>
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
        <script>
            var table;
            $(document).ready(function() {
              table = $('#productsTable').DataTable({
                responsive: true
              });
            });

            var modal = {
              target: $("#productMainModal"),
              bodyText: $("#productMainModal .warningDeletionMessage"),
              errorBox: $("#productMainModal .modal-error-box"),
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
                modal.setBodyMessage("Da li ste sigurni da zelite da obrišete proizvod \"" + name + "\"?");
                modal.show();
              },
              finish: function() {
                var del = this;
                var productID = this.targetID;
                if(productID === undefined) {
                  modal.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                    "Ni jedan proizvod ne čeka na brisanje ili je proizvod već obrisan." +
                    "</div>");
                  return;
                }
                $.ajax({
                  url: 'handlers/ajaxHandler.php',
                  type: 'post',
                  dataType: 'json',
                  data: 'action=deleteProduct&pid=' + productID,
                  success: function(result) {
                    console.log(result);
                    if(result.success) {
                      table.row($("#product-" + productID)).remove().draw();
                      del.targetID = undefined;
                      modal.setError("<div class=\"alert alert-success alert-dismissable\">" +
                        "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                        "Proizvod je uspešno obrisan." +
                        "</div>");
                    }
                    else
                      modal.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                        "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                        "Došlo je do greške prilikom brisanja proizvoda." +
                        "</div>");
                  },
                  error: function() {
                    modal.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                      "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                      "Došlo je do greške prilikom brisanja proizvoda." +
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

            $("#finishProductDeletion").click(function() {
              deletion.finish();
            });


            $(".productDeleteButton").click(function() {
              var productID = $(this).data("pid");
              var productName = $(this).data("pname");
              deletion.prepare(productID, productName);
            });

            var addProductErrorBox = {
              target: $(".add-product-error-box"),
              setError: function(result) {
                this.target.html(result);
              }
            };

            $("#add-product form").submit(function(e) {
              e.preventDefault();
              var data = $(this).serialize();
              /*$.ajax({
                url: 'handlers/ajaxHandler.php',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(result) {
                  console.log(result);
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
                  modal.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                    "Došlo je do greške prilikom dodavanja kategorije." +
                    "</div>");
                },
                error: function() {
                  modal.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                    "Došlo je do greške prilikom dodavanja kategorije." +
                    "</div>");
                }
              });*/

            });
        </script>

    </body>
</html>

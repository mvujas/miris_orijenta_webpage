<?php
  require_once 'parts.php';
  if(isset($_GET["addToChoosenProducts"])) {
    addProductToChoosen($_GET["addToChoosenProducts"]);
    header("Location: products.php");
  }

  if(isset($_GET["removeFromChoosenProducts"])) {
    removeProductFromChoosen($_GET["removeFromChoosenProducts"]);
    header("Location: products.php");
  }
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
                  $categories_tmp = getAllCategories();
                  $categories = array();
                  foreach($categories_tmp as &$ctg)
                    $categories[$ctg["CID"]] = $ctg["Name"];
                  if(isset($_GET["edit"])) {
                    list($product_name, $product_cid) = getProductByID($_GET["edit"]);
                ?>
                  <div class="row">
                      <div class="col-lg-12">
                          <h1 class="page-header">Izmena proizvoda</h1>
                      </div>
                      <!-- /.col-lg-12 -->
                  </div>
                  <!-- /.row -->
                  <?php
                    if($product_name) {

                      // Save changes to database
                      if(isset($_POST["saveChanges"])) {
                        if(!isset($_POST["name"]) || strlen($_POST["name"]) < PRODUCT_MIN_NAME_LENGTH)
                          echo "<div class=\"alert alert-danger alert-dismissable\">
                              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                              Ime proizvoda mora imati bar " .PRODUCT_MIN_NAME_LENGTH. " karaktera.
                          </div>";
                        else if(!isset($_POST["category"]) || !isset($categories[$_POST["category"]]))
                          echo "<div class=\"alert alert-danger alert-dismissable\">
                              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                              Uneta kategorija ne postoji.
                          </div>";
                        else if(strcmp($_POST["name"], $product_name) === 0 && $_POST["category"] == $product_cid)
                          echo "<div class=\"alert alert-danger alert-dismissable\">
                              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                              Niste napravili nikakvu izmenu.
                          </div>";
                        else {
                          $new_name = htmlspecialchars($_POST["name"]);
                          $category = htmlspecialchars($_POST["category"]);
                          $result = saveProductChanges($_GET["edit"], $new_name, $category);
                          if($result) {
                            $product_name = $new_name;
                            $product_cid = $category;
                            echo "<div class=\"alert alert-success alert-dismissable\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                                Izmene su uspešno sačuvane. Vratite se na <a href=\"products.php\" class=\"alert-link\">pregled proizvoda</a>.
                            </div>";
                          }
                          else
                            echo "<div class=\"alert alert-danger alert-dismissable\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                                Došlo je do greške prilikom čuvanja napravljenih izmena, pokušajte ponovo kasnije ili se vratite na <a href=\"products.php\" class=\"alert-link\">pregled proizvoda</a>.
                            </div>";
                        }
                      }

                  ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Podaci o proizvodu
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form role="form" action="products.php?edit=<?php echo htmlspecialchars($_GET["edit"]) ?>" method="post">
                                                <div class="form-group">
                                                    <label>Naziv</label>
                                                    <input class="form-control" name="name" value="<?php echo $product_name ?>">
                                                </div>
                                                <div class="form-group">
                                                  <label>Kategorija</label>
                                                  <select name="category" class="form-control">
                                                    <?php foreach ($categories as $cid => $name) { ?>
                                                        <option value="<?php echo $cid ?>"<?php if($product_cid == $cid) echo " selected" ?>><?php echo $name ?></option>"
                                                    <?php } ?>
                                                  </select>
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
                                                  <th>Status</th>
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
                                                    <td>".($product["Choosen"] ? "Preporučen" : "Regularan")."</td>
                                                    <td>
                                                      <center>
                                                        <a class=\"btn btn-primary\" href=\"products.php?edit={$product["PID"]}\"><i class=\"fa fa-pencil\"></i> Izmeni</a>
                                                        ". (($product["Choosen"]) ?
                                                        ("<a class=\"btn btn-warning\" href=\"products.php?removeFromChoosenProducts={$product["PID"]}\"><i class=\"fa fa-remove\"></i> Ukloni iz preporučenih</a>") :
                                                        ("<a class=\"btn btn-success\" href=\"products.php?addToChoosenProducts={$product["PID"]}\"><i class=\"fa fa-plus\"></i> Dodaj u preporučene</a>")) . "
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
                                        <label>Naziv proizvoda</label>
                                        <input class="form-control" name="name" value="">
                                      </div>
                                      <div class="form-group">
                                        <label>Slika</label>
                                        <button type="button" style="padding: 15px; border: 1px solid gray; border-radius: 5px; color: gray" name="button">Ucitaj sliku</button>
                                      </div>
                                      <div class="form-group">
                                        <label>Kategorija</label>
                                        <select name="category" class="form-control">
                                          <?php
                                            foreach ($categories as $cid => $name)
                                              echo "<option value=\"$cid\">$name</option>"
                                          ?>
                                        </select>
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
        <?php if (!isset($_GET["edit"])): ?>
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
              $.ajax({
                url: 'handlers/ajaxHandler.php',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(result) {
                  console.log(result);
                  if(result.success) {
                    if(result.data.error)
                      addProductErrorBox.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                        "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                        result.data.error +
                        "</div>");
                    else {
                      location.reload();
                       $(window).scrollTop(0);
                    }
                  }
                  else
                    addProductErrorBox.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                      "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                      "Došlo je do greške prilikom dodavanja proizvoda." +
                      "</div>");
                },
                error: function() {
                  addProductErrorBox.setError("<div class=\"alert alert-danger alert-dismissable\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"\>&times;</button>" +
                    "Došlo je do greške prilikom dodavanja proizvoda." +
                    "</div>");
                }
              });

            });
        </script>
        <?php endif; ?>

    </body>
</html>

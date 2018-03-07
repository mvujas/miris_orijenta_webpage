<?php
  require_once 'parts.php';
  $categories = getAllCategories();
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
                                                      <a class=\"btn btn-primary\"><i class=\"fa fa-trash-o\"></i> Izmeni</a>
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

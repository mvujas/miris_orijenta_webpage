<?php
  require_once 'handlers/userHandler.php';

  function printNavigation() {
    global $current_script;
    global $logged_users_username;
    ?>
      <!-- Navigation -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
          <div class="navbar-header">
              <a class="navbar-brand" href="dashboard.php">Startmin</a>
          </div>

          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
          </button>

          <ul class="nav navbar-nav navbar-left navbar-top-links">
              <li><a href="../index.html"><i class="fa fa-home fa-fw"></i> Web sajt</a></li>
          </ul>

          <ul class="nav navbar-right navbar-top-links">
              <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                      <i class="fa fa-user fa-fw"></i> <?php echo $logged_users_username; ?> <b class="caret"></b>
                  </a>
                  <ul class="dropdown-menu dropdown-user">
                      <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Odjavi se</a>
                      </li>
                  </ul>
              </li>
          </ul>
          <!-- /.navbar-top-links -->

          <div class="navbar-default sidebar" role="navigation">
              <div class="sidebar-nav navbar-collapse">
                  <ul class="nav" id="side-menu">
                      <li>
                          <a href="dashboard.php"<?php if($current_script === "dashboard.php") echo " class=\"active\"" ?>><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                      </li>
                      <li>
                          <a href="categories.php"<?php if($current_script === "categories.php") echo " class=\"active\"" ?>><i class="fa fa-tasks fa-fw"></i> Kategorije</a>
                      </li>
                  </ul>
              </div>
          </div>
      </nav>
    <?php
  }

  function printIncludes($title) {
    ?>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">

      <title><?php echo $title ?></title>

      <!-- Bootstrap Core CSS -->
      <link href="css/bootstrap.min.css" rel="stylesheet">

      <!-- MetisMenu CSS -->
      <link href="css/metisMenu.min.css" rel="stylesheet">

      <!-- Timeline CSS -->
      <link href="css/timeline.css" rel="stylesheet">

      <!-- Custom CSS -->
      <link href="css/startmin.css" rel="stylesheet">

      <!-- Morris Charts CSS -->
      <link href="css/morris.css" rel="stylesheet">

      <!-- Custom Fonts -->
      <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
    <?php
  }

  function printJavascript() {
    ?>
      <!-- jQuery -->
      <script src="js/jquery.min.js"></script>

      <!-- Bootstrap Core JavaScript -->
      <script src="js/bootstrap.min.js"></script>

      <!-- Metis Menu Plugin JavaScript -->
      <script src="js/metisMenu.min.js"></script>

      <!-- Custom Theme JavaScript -->
      <script src="js/startmin.js"></script>
    <?php
  }
?>

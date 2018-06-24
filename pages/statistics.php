<?php
include("../inc/botstats.php");
include '../inc/geo/geoip.inc';
$gi = geoip_open("../inc/geo/GeoIP.dat", "");
  
  
$osel = $odb->query("SELECT operatingsys, COUNT(*) AS cnt FROM bots GROUP BY operatingsys ORDER BY cnt DESC LIMIT 5");

$csel = $odb->query("SELECT country, COUNT(*) AS cnt FROM bots GROUP BY country ORDER BY cnt DESC LIMIT 5");
$usel = $odb->query("SELECT privileges, COUNT(*) AS cnt FROM bots GROUP BY privileges ORDER BY cnt");


?>
<!DOCTYPE html>
<html lang="en">

<head>

  
  
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['OS', 'Total Bots'],
          <?php
  while ($o = $osel->fetch())
									{
                    
                        echo "['".$o["operatingsys"]."', ".$o["cnt"]."],";
									
  }
          ?>
        ]);

        var options = {
          title: 'Top 5 Operating Systems',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('top5os'));
        chart.draw(data, options);
      }
    </script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
 <script type="text/javascript" >
         google.charts.load("current", {packages:["corechart"]});

        google.charts.setOnLoadCallback(drawChart);
        function drawChart(){
 
            var data = google.visualization.arrayToDataTable([
                ['Country','Total Bots'],
                <?php
                    while($row = $csel->fetch()){
                        $countrycode = geoip_country_name_by_id($gi, $row["country"]);
                        echo "['".$countrycode."', ".$row["cnt"]."],";
                    }
                ?>
               ]);

            var options = {
          title: 'Top 5 Operating Countries',
          is3D: true,
        };

            var chart = new google.visualization.PieChart(document.getElementById('top5country'));
            chart.draw(data, options);
        }

    </script>
  
  
  
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Permissions', 'Total Bots'],
          <?php
  while ($u = $usel->fetch())
									{

                    echo "['".$u["privileges"]."', ".$u["cnt"]."],";
									
  }
          ?>
        ]);

        var options = {
          title: 'User Permissions',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('adminvsusr'));
        chart.draw(data, options);
      }
    </script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Tera HTTP</title>

  <!-- Bootstrap Core CSS -->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- MetisMenu CSS -->
  <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

  <!-- Morris Charts CSS -->
  <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  
  <!-- Container CSS -->
  <link href="container.css" rel="stylesheet">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

  <div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
        <a class="navbar-brand" href="index.html">Terra HTTP</a>
      </div>
      <!-- /.navbar-header -->

      <ul class="nav navbar-top-links navbar-right">

        <!-- /.dropdown -->
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
          <ul class="dropdown-menu dropdown-user">
            <li><a href="settings.php"><i class="fa fa-gear fa-fw"></i> Settings</a>
            </li>
            <li class="divider"></li>
            <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </li>
          </ul>
          <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
      </ul>
      <!-- /.navbar-top-links -->

      <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
          <ul class="nav" id="side-menu">
            <li class="sidebar-search">
              <div class="input-group custom-search-form">
                <input type="text" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
              </div>
              <!-- /input-group -->
            </li>
             <li>
              <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>

            </li>
            <li>
              <a href="bots.php"><i class="fa fa-table fa-fw"></i> Bots</a>
            </li>
            <li>
              <a href="statistics.php"><i class="fa fa-th-list"></i> Statistics</a>
            </li>
            <li>
              <a href="tasks.php"><i class="fa fa-edit fa-fw"></i> Tasks </a>
            </li>
             <li>
              <a href="settings.php"><i class="fa fa-gears"></i> Settings </a>
            </li>


            <!-- /.nav-second-level -->
            </li>

          </ul>
        </div>
        <!-- /.sidebar-collapse -->
      </div>
      <!-- /.navbar-static-side -->
    </nav>

    <div id="page-wrapper">
      <div class="row">
        
        <!-- /.col-lg-12 -->
      </div>








<br>
<br>
      <style>
  .left{float:left; width:33%;}
</style>
<div class="containter">
<div class="left" id="top5os" style="width: 500px; height: 300px;"></div>

<div class="left" id="top5country" style="width: 500px; height: 300px;"></div>
<div class="left" id="adminvsusr" style="width: 500px; height: 300px;"></div>
<div id="vmap" style="width: 600px; height: 400px;"></div>
</div>

    
 
            <!-- /.list-group -->

  
      
    
            <!-- /.panel-body -->
          </div>
          <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
      </div>
    </div>
    <!-- /.col-lg-4 -->
  </div>
  <!-- /.row -->
  </div>
  <!-- /#page-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- jQuery -->
  <script src="../vendor/jquery/jquery.min.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

  <!-- Metis Menu Plugin JavaScript -->
  <script src="../vendor/metisMenu/metisMenu.min.js"></script>

  <!-- Morris Charts JavaScript -->
  <script src="../vendor/raphael/raphael.min.js"></script>
  <script src="../vendor/morrisjs/morris.min.js"></script>
  <script src="../data/morris-data.js"></script>

  <!-- Custom Theme JavaScript -->
  <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
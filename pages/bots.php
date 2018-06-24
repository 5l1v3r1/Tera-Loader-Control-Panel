<?php
include("../inc/botstats.php");
include '../inc/geo/geoip.inc';
$gi = geoip_open("../inc/geo/GeoIP.dat", "");
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

    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
        
        <!-- /.col-lg-12 -->
      </div>








<br>
<br>


    
            <!-- /.panel-heading -->
           
              <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th>Bot HWID</th>
                    <th>IP Address</th>
                    <th>Country</th>
                    <th>Last Response</th>
                    <th>Current Task</th>
                    <th>Operating System</th>
                    <th>Bot Version</th>
                    <th>Mark</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                 <?php
								$bots = $odb->query("SELECT * FROM bots ORDER BY lastresponse DESC");
								$unix = $odb->query("SELECT UNIX_TIMESTAMP()")->fetchColumn(0);
								while ($b = $bots->fetch(PDO::FETCH_ASSOC))
								{
									$bid = $b['bothwid'];
									$id = $b['id'];
									$ip = $b['ipaddress'];
									$cn = geoip_country_name_by_id($gi, $b['country']);
									$fl = strtolower(geoip_country_code_by_id($gi, $b['country']));
									$lrd = $b['lastresponse'];
									$lr = date("m-d-Y, h:i A", $lrd);
									$ct = $b['currenttask'];
									$os = $b['operatingsys'];
									$bv = $b['botversion'];
									$st = "";
									$mk = "";
									if (($lrd + ($knock + 120)) > $unix)
									{
										$st = '<small class="badge bg-green">Online</small>';
									}else{
										if ($lrd + $deadi < $unix)
										{
											$st = '<small class="badge bg-red">Dead</small>';
										}else{
											$st = '<small class="badge bg-yellow">Offline</small>';
										}
									}
									if ($b['mark'] == "1")
									{
										$mk = '<small class="badge bg-green">Clean</small>';
									}else{
										$mk = '<small class="badge bg-red">Dirty</small>';
									}
									echo '<tr><td>'.$bid.'</td><td><a id="details" data-toggle="tooltip" title="View Details" href="details.php?&id='.$id.'">'.$ip.'</a></td><td>'.$cn.'&nbsp;&nbsp;<img src="img/flags/'.$fl.'.png" /></td><td data-order="'.$lrd.'">'.$lr.'</td><td>#'.$ct.'</td><td>'.$os.'</td><td>'.$bv.'</td><td><center>'.$mk.'</center></td><td><center>'.$st.'</center></td></tr>';
								}
								?>
            
                </tbody>
              </table>

              <!-- /.table-responsive -->
            
            <!-- /.panel-body -->
     
          <!-- /.panel -->

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

    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>

</body>

</html>
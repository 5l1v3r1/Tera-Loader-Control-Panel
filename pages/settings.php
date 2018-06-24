<?php
include("../inc/botstats.php");

						if ($userperms != "admin")
						{
							echo '<div class="alert alert-danger">You do not have permission to view this page.</div>';
							die();
						}
						if (isset($_GET['clear']))
						{
							$clear = strtolower($_GET['clear']);
							$safe = array("dead", "offline", "dirty", "all", "tasklogs");
							if (in_array($clear, $safe))
							{
								if ($clear == "dead")
								{
									$d = $odb->prepare("DELETE FROM bots WHERE lastresponse + :d < UNIX_TIMESTAMP()");
									$d->execute(array(":d" => $deadi));
									$i = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, 'Cleared dead bots from table', UNIX_TIMESTAMP())");
									$i->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR']));
								}else if ($clear == "offline"){
									$o = $odb->prepare("DELETE FROM bots WHERE (lastresponse + :o < UNIX_TIMESTAMP()) AND (lastresponse + :d > UNIX_TIMESTAMP())");
									$o->execute(array(":o" => $knock + 120, ":d" => $deadi));
									$i = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, 'Cleared offline bots from table', UNIX_TIMESTAMP())");
									$i->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR']));
								}else if ($clear == "dirty"){
									$odb->query("DELETE FROM bots WHERE mark = '2'");
									$i = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, 'Cleared dirty bots from table', UNIX_TIMESTAMP()");
									$i->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR']));
								}else if ($clear == "tasklogs"){
									$odb->query("TRUNCATE tasks_completed");
									$i = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, 'Cleared task execution logs from table', UNIX_TIMESTAMP()");
									$i->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR']));
								}else{
									$odb->query("TRUNCATE bots");
									$i = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, 'Cleared all bots from table', UNIX_TIMESTAMP()");
									$i->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR']));
								}
								echo '<div class="alert alert-success">Successfully cleared entries. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=settings">';
							}else{
								echo '<div class="alert alert-danger">Invalid clear option. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=settings">';
							}
						}
						if (isset($_POST['updateSettings']))
						{
							$newknock = $_POST['knock'];
							$newdead = $_POST['dead'];
							$newgate = $_POST['gstatus'];
							if (!ctype_digit($newknock) || !ctype_digit($newdead) || !ctype_digit($newgate))
							{
								echo '<div class="alert alert-danger">One of the parameters was not a digit. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=settings">';
							}else{
								$up = $odb->prepare("UPDATE settings SET knock = :k, dead = :d, gate_status = :g LIMIT 1");
								$up->execute(array(":k" => $newknock, ":d" => $newdead, ":g" => $newgate));
								echo '<div class="alert alert-success">Settings successfully updated. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=settings">';
								
							}
						}


						
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
        <div class="col-lg-12">
          <h1 class="page-header">Settings</h1>
        </div>
        
        
        <form action="" method="POST" class="col-lg-6">
										<label>Knock time (Minutes)</label>
										<div class="input-group">
											<input type="text" name="knock" class="form-control" value="<?php echo $odb->query("SELECT knock FROM settings LIMIT 1")->fetchColumn(0); ?>">

										</div>
										<br>
										<label>Days till marked dead</label>
										<div class="input-group">
											<input type="text" name="dead" class="form-control" value="<?php echo $odb->query("SELECT dead FROM settings LIMIT 1")->fetchColumn(0); ?>">
										</div>
										<br>
										<label>Gate Status</label>
										<select name="gstatus" class="form-control">
											<?php
											$val = $odb->query("SELECT gate_status FROM settings LIMIT 1")->fetchColumn(0);
											if ($val == "1")
											{
												echo '<option value="1" selected>Enabled</option><option value="2">Disabled</option>';
											}else{
												echo '<option value="1">Enabled</option><option value="2" selected>Disabled</option>';
											}
											?>
										</select>
										<br>
										<center><input type="submit" name="updateSettings" class="btn btn-primary" value="Update Settings"></center>
									</form>
        <!-- /.col-lg-12 -->
      
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
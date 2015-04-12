<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "student" )
	{
		header("Location: ../../index.php");
	}
	extract($_GET);
	require_once "../../sql_connect.php";
	$query = "SELECT * FROM programming_exercise WHERE id=" . $id;
	$result = mysql_query($query);
	$exercise_row = mysql_fetch_assoc($result, MYSQL_ASSOC);
	$today = date('Y-m-d H:i:s');
	if( $exercise_row["end_time"] < $today )
	{
		header("Location: ./studentProgramming.php?course=" . $course);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Course Connect</title>
		<link rel="stylesheet" type="text/css" href="../../bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../bootstrap/css/mynav.css">
		<script language="Javascript" type="text/javascript" src="../../edit_area/edit_area_full.js"></script>
		<script type="text/javascript" src = "./js/studentAttemptProgramming.js"></script>
	</head>
	<body style="padding-top:50px;">
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="row">
					<div class="col-sm-7">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<div class="row">
								<div class="col-sm-2"><div class="pull-right"><img src="../../images/pesit-logo.png" height="50px" /></div></div>
								<div class="col-sm-10">
									<a class="navbar-brand" href = "">Course Connect</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-5">
						<div id="navbar" class="navbar-collapse collapse">
							<ul class="nav navbar-nav navbar-right">
								<li><a href="./student.php"><span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;Home</a></li>
								<li><a href="">Announcements</a></li>
								<li><a href="./studentRegisterCourses.php">Courses</a></li>
								<li class = "dropdown active">
								<a href="#" class = "dropdown-toggle" id = "username" data-toggle = "dropdown">USERNAME <b class = "caret"></b></a>
								<ul class = "dropdown-menu">
									<li><a href="./studentChangePassword.php">Change Password</a></li>
									<li><a href="../../authentication/logout.php">Log Out</a></li>
								</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</nav>

		<div class="">
			<div class="row">
				<div class="col-sm-2">
					<?php
						$query = "SELECT * FROM course WHERE course_code='" . $course . "'";
						$result = mysql_query($query);
						$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
					?>
					<center><div style="padding-top:10px">
						<img <?php echo "src= \"../server/getCourseImage.php?course_code=" . $row["course_code"] . "\""; ?> alt = "No image" height="80px">
					</div></center>
				</div>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-12">
							<div class="page-header">
							<?php
									$num = mysql_num_rows($result);
									if( $num == 0 )
									{
										echo "<h2 style = \"padding-left:5%;color:red;\">";
										echo "Server Error Occured. Try again later.";
										echo "</h2>";
									}
									else
									{
										echo "<h3 style = \"color:#B22222\">";
										echo $row["course_name"] . " - " . $course;
										echo "&nbsp;&nbsp;";
										echo "<small>";
										echo "by " . $row["course_incharge"];
										echo "</small>";
										echo "</h3>";
									}
									
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-2">
					<div class="sidebar-nav">
						<div class="navbar navbar-default" role="navigation">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
							<div class="navbar-collapse collapse sidebar-navbar-collapse">
								<ul class="nav navbar-nav">
									<li><a <?php echo "href=\"./studentCourse.php?course=" . $course . "\""; ?> >Lectures</a></li>
									<li><a <?php echo "href=\"./studentSyllabus.php?course=" . $course . "\""; ?> >Syllabus</a></li>
									<li><a <?php echo "href=\"./studentSchedule.php?course=" . $course . "\""; ?> >Schedule</a></li>
									<li><a href="#">Assignments</a></li>
									<?php
										if( $num != 0 )
										{
											if( $row["course_type"] == "PROGRAMMING" )
											{
									?>
									<li class="active"><a <?php echo "href=\"./studentProgramming.php?course=" . $course . "\""; ?> >Programming Exercises</a></li>
									<?php
											}
										}
									?>
									<li><a <?php echo "href=\"./studentQuiz.php?course=" . $course . "\""; ?> >Quiz</a></li>
									<li><a <?php echo "href=\"./studentAdaptiveQuiz.php?course=" . $course . "\""; ?> >Adaptive Quiz</a></li>
									<li><a href="#">Assessments</a></li>
									<li><a <?php echo "href=\"./studentSelfAssessment.php?course=" . $course . "\""; ?> >Self Assessments</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-10">
					<?php
						$query = "SELECT * FROM student_programming WHERE usn='" . $_SESSION["username"] . "' and assign_id=" . $id . " and course_code='" . $course . "'";
						$result = mysql_query($query);
						$student_row = mysql_fetch_assoc($result, MYSQL_ASSOC);
						$query = "SELECT sem, language FROM course WHERE course_code='" . $course . "'";
						$res = mysql_query($query);
						$row = mysql_fetch_assoc($res, MYSQL_ASSOC);
						$sem = $row["sem"];
						$lang = $row["language"];
						$language = explode(";", $lang);
						echo "<script type=\"text/javascript\">set_data('" . $language[0] . "', '" . $id . "', '" . $course . "', '" . $sem . "')</script>";
						$problem_stmt = file_get_contents("../../questionData/sem" . $sem . "/" . $course . "/" . $id . "/problem.txt");
					?>
					<div class="row">
						<div class="col-sm-10">
							<h4 style="font-size:25px;font-family: arial;">Exercise - <?php echo $exercise; ?></h4>
						</div>
						<div class="col-sm-2">
							<h4><small>My Score : <?php echo $student_row["marks"]; ?></small></h4>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-10">
							<p style="padding-left:2%;padding-right:3%;font-size:22px;font-family:courier;color:#0099FF;">Problem Statement</p>
						</div>
						<div class="col-sm-2">
							<div><button class="btn btn-info"><span class = "glyphicon glyphicon-upload"></span>&nbsp;Upload Solution</button></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<p style="padding-left:3%;padding-right:3%;font-size:17px;font-family:arial"><?php echo $problem_stmt; ?></p>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-sm-2">
							<div>
								<select class="form-control" id="language" onchange="change_language();">
									<?php
										for( $i = 0; $i < count($language) - 1; $i++ )
										{
											if( $language[$i] == "cpp" )
											{
												echo "<option>C++</option>";
											}
											else
											{
												echo "<option>" . $language[$i] . "</option>";
											}
										}
									?>
								</select>
							</div>
						</div>
						<div class="col-sm-2 col-sm-offset-6">
							<div class="pull-right"><button class="btn btn-success" onclick="create_file();"><span class = "glyphicon glyphicon-plus"></span>&nbsp;New File</button></div>
						</div>
						<div class="col-sm-2">
							<div><button class="btn btn-danger" onclick="delete_file();"><span class = "glyphicon glyphicon-minus"></span>&nbsp;Delete File</button></div>
						</div>
						
					</div>
					<br>
					<div class="row" id = "execution_data" style="display:none;padding-right:2%;">
						<div class="col-sm-9">
							<ul class="nav nav-tabs" id = "filetabs"></ul>
							<br>
							<div id="tabContent" class="tab-content">
							</div>
						</div>
						<div class="col-sm-3">
							<br><br><br>
							<div class="row">
								<div class="col-sm-12">
									<label style="padding-left:3%;">Select files for execution</label>
								</div>
							</div>
							<div class="row" style="padding-left:3%;padding-right:3%">
								<div class="col-sm-12" id = "comp_files">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-sm-12">
									<input type = "text" placeholder = "Enter file name with main function" id = "main_file" class="form-control" />
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-sm-6">
									<button class="btn btn-default btn-block" onclick="execute();">Run Code</button>
								</div>
								<div class="col-sm-6">
									<button class="btn btn-success btn-block">Submit</button>
								</div>
							</div>
							<br>
							<div class="panel panel-default">
								<div class="panel-heading">
									<center><h4 class="panel-title">Find Bugs in Code</h4></center>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-12">
											<button class="btn btn-primary btn-block" onclick="find_bugs();">Find Bugs</button>
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="panel panel-default">
								<div class="panel-heading">
									<center><h4 class="panel-title">Find Repeated Code</h4></center>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-12">
											<input type = "number" placeholder = "Enter minimum number of tokens" id = "num_tokens" class="form-control" />
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-sm-12">
											<button class="btn btn-primary btn-block" onclick="find_repeated_code();">Find Repeated Code</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<br>
					<div class="row" id = "output_row" style="display:none;padding-right:2%;">
						<div class="col-sm-9">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="page-header">
										<h3 id="heading">Result</h3>
									</div>
									<div class="row">
										<div class="col-sm-12" id = "output"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row" style="display:none;padding-right:2%;" id = "repeated_code_window">
						<div class="col-sm-9">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-8">
											<h2>Summary of Repeated Code</h2>
										</div>
										<div class="col-sm-4">
											<div class="pull-right">
												<button class="btn btn-info" onclick="minimize_repeated_div();">--</button>
												<button class="btn btn-danger" onclick="close_repeated_div();">X</button>
											</div>
										</div>
									</div>
									<div class="row" id = "frame_repeated_div">
										<div class="col-sm-12">
											<iframe src="#" id = "repeated" width="100%" height="500px"></iframe>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row" style="padding-right:2%;display:none;" id = "analysis_window">
						<div class="col-sm-9">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="page-header" style="padding-left:5%;padding-right:5%;">
										<h3>Bugs</h3>
									</div>
									<div class="row" style="padding-left:3%;padding-right:3%;">
										<div class="col-sm-12" id = "analysis"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row" style="display:none;">
						<div class="col-sm-12">
							<a href="#output_row" id="show_output"></a>
							<a href="#repeated_code_window" id="show_repeated"></a>
							<a href="#analysis_window" id="show_bugs"></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br><br><br>
		<script type="text/javascript" src = "../../bootstrap/js/jquery.min.js"></script>
		<script type="text/javascript" src = "../../bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			window.onload = function ()
			{
			    var username = document.getElementById("username");
			    username.innerHTML = "<span class = \"glyphicon glyphicon-user\"></span> " + <?php echo  json_encode($_SESSION["username"])  ?> + "&nbsp;<b class = \"caret\"></b>";
				fetch_files();
			}
		</script>
	</body>
</html>

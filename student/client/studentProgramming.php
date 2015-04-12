<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "student" )
	{
		header("Location: ../../index.php");
	}
	extract($_GET);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Course Connect</title>
		<link rel="stylesheet" type="text/css" href="../../bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../bootstrap/css/mynav.css">
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
						require_once "../../sql_connect.php";
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
						$query = "SELECT * FROM programming_exercise WHERE course_code='" . $course . "'";
						$result = mysql_query($query);
						$num = mysql_num_rows($result);
						if( $num == 0 )
						{
					?>
							<div class="row">
								<div class="col-sm-12">
									<center><h1><span class = "glyphicon glyphicon-flash"></span></h1></center><br>
									<center><h3 style="color:#B0B0B0;">No Exercises</h3></center>
								</div>
							</div>
					<?php
						}
						else
						{
							$query = "SELECT sem FROM course WHERE course_code='" . $course . "'";
							$res = mysql_query($query);
							$row = mysql_fetch_assoc($res, MYSQL_ASSOC);
							$sem = $row["sem"];
							$today = date('Y-m-d H:i:s');
							for( $i = 0; $i < $num; $i++ )
							{
								$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
								if( $row["start_time"] < $today )
								{
									$query = "SELECT * FROM student_programming WHERE usn='" . $_SESSION["username"] . "' and assign_id=" . $row["id"] . " and course_code='" . $course . "'";
									$student_result = mysql_query($query);
									if( mysql_num_rows($student_result) == 0 )
									{
										$student_marks = 0;
									}
									else
									{
										$student_row = mysql_fetch_assoc($student_result, MYSQL_ASSOC);
										$student_marks = $student_row["marks"];
									}
									$exer = $i + 1;
									$problem_stmt = file_get_contents("../../questionData/sem" . $sem . "/" . $course . "/" . $row["id"] . "/problem.txt");
					?>
									<div class="row">
										<div class="col-sm-12">
											<h4>Exercise - <?php echo $exer; ?></h4>
										</div>
									</div>
									<div class="row" style="padding-top:10px;">
										<div class="col-sm-12">
											<p style="padding-left:2%;padding-right:3%;"><?php echo $problem_stmt; ?></p>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-3">
											<h4 style="padding-left:8%;"><small>Due Date : <?php echo $row["end_time"]; ?></small></h4>
										</div>
										<div class="col-sm-2">
											<h4><small>Marks : <?php echo $row["marks"]; ?></small></h4>
										</div>
										<div class="col-sm-2">
											<h4><small>My Score : <?php echo $student_marks; ?></small></h4>
										</div>
										<div class="col-sm-4">
											<div class="pull-right">
					<?php
									if( $row["end_time"] > $today )
									{
					?>
										<a <?php echo "href=\"./studentAttemptProgramming.php?course=" . $course . "&id=" . $row["id"] . "&exercise=" . $exer . "\""; ?> class="btn btn-md" style="background-color:#0000CD;color:white">Attempt Exercise</a>
					<?php
									}
									else
									{
					?>
										<a <?php echo "href=\"./studentAttemptProgramming.php?course=" . $course . "&id=" . $row["id"] . "\""; ?> disabled class="btn btn-md" style="background-color:#0000CD;color:white">Attempt Exercise</a>
					<?php
									}
					?>
												
											</div>
										</div>
									</div>
									<hr>
					<?php
								}
							}
						}
					?>
				</div>
			</div>
		</div>
		<script type="text/javascript" src = "../../bootstrap/js/jquery.min.js"></script>
		<script type="text/javascript" src = "../../bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			window.onload = function ()
			{
			    var username = document.getElementById("username");
			    username.innerHTML = "<span class = \"glyphicon glyphicon-user\"></span> " + <?php echo  json_encode($_SESSION["username"])  ?> + "&nbsp;<b class = \"caret\"></b>";
			}
		</script>
	</body>
</html>

<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "student" )
	{
		header("Location: ../../index.php");
	}
	extract($_GET);
	require_once "../../sql_connect.php";
	$query = "SELECT * FROM course WHERE course_code='" . $course . "'";
	$res = mysql_query($query);
	$row = mysql_fetch_assoc($res,MYSQL_ASSOC);
	$course_name = $row["course_name"];
	$course_code = $course;
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
									<li><a <?php echo "href=\"./studentSchedule.php?course=" . $course . "\""; ?>>Schedule</a></li>
									<li><a href="#">Assignments</a></li>
									<?php
										if( $num != 0 )
										{
											if( $row["course_type"] == "PROGRAMMING" )
											{
									?>
									<li><a <?php echo "href=\"./studentProgramming.php?course=" . $course . "\""; ?> >Programming Exercises</a></li>
									<?php
											}
										}
									?>
									<li class="active"><a <?php echo "href=\"./studentQuiz.php?course=" . $course . "\""; ?> >Quiz</a></li>
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
						$query= "SELECT quiz.quiz_id, quiz.question_order, quiz.quiz_name, quiz.time_period, quiz.enable, quiz.due_date, quiz_student.marks, quiz_student.started FROM quiz_student JOIN quiz ON quiz.quiz_id = quiz_student.quiz_id WHERE course_code='" . $course_code . "' AND usn='" . $_SESSION["username"] . "' AND enable=1";
						$result = mysql_query($query);
						$num = mysql_num_rows($result);
						if( $num == 0 )
						{
					?>
							<center><h1><span class = "glyphicon glyphicon-flash"></span></h1></center><br>
							<center><h3 style="color:#B0B0B0;">No Quizzes</h3></center>
					<?php
						}
						else
						{
							$today = date('Y-m-d H:i:s');
							for( $var = 0; $var < $num; $var++ )
							{
								$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
								$questions = explode(",", $row["question_order"]);
								$noOfQuestions = count($questions);
					?>
								<div class="row">
									<div class="col-sm-12">
										<h4 style="padding-left:3%;color:#383838"><?php echo $row["quiz_name"]; ?></h4>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3"><h4 style="padding-left:5%;"><small>Due Date : <?php echo $row["due_date"]; ?></small></h4></div>
									<div class="col-sm-2">
										<h4><small>Time : <?php echo ($row["time_period"]/60) . "&nbsp;mins"; ?></small></h4>
									</div>
									<div class="col-sm-2">
										<h4><small>Your Score : <?php echo $row["marks"] . "/" . $noOfQuestions; ?></small></h4>
									</div>
									<div class="col-sm-2">
					<?php
										if( $row["started"] == 2 )
										{
											echo "<h4><small style=\"color:green;\">Attempted</small></h4>";
										}
										else if( ( $row["started"] == 0 || $row["started"] == 1 ) && ( $row["due_date"] > $today ))
										{
											echo "<h4><small style=\"color:#0099FF;\">Not Attempted</small></h4>";
										}
										else if( ( $row["started"] == 0 || $row["started"] == 1 ) && ( $row["due_date"] < $today ) )
										{
											echo "<h4><small style=\"color:red;\">Not Attempted</small></h4>";
										}
					?>
									</div>
									<div class="col-sm-2">
										<div class="pull-right">
					<?php
											if( $row["started"] == 2 || $row["due_date"] < $today )
											{
					?>
												<a href=<?php echo "\"javascript:attempt_quiz('" . $course_code . "', '" . $row["quiz_id"] . "')\"" ; ?> disabled class="btn btn-md" style="background-color:#0000CD;color:white">Attempt Quiz</a>
					<?php
											}
											else
											{
					?>
												<a href=<?php echo "\"javascript:attempt_quiz('" . $course_code . "', '" . $row["quiz_id"] . "')\"" ; ?> class="btn btn-md" style="background-color:#0000CD;color:white">Attempt Quiz</a>
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
					?>
					
				</div>
			</div>
		</div>
		<script type="text/javascript" src = "../../bootstrap/js/jquery.min.js"></script>
		<script type="text/javascript" src = "../../bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src = "./js/studentQuiz.js"></script>
		<script type="text/javascript">
			window.onload = function ()
			{
			    var username = document.getElementById("username");
			    username.innerHTML = "<span class = \"glyphicon glyphicon-user\"></span> " + <?php echo  json_encode($_SESSION["username"])  ?> + "&nbsp;<b class = \"caret\"></b>";
			}
		</script>
	</body>
</html>

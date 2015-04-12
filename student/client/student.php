<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "student" )
	{
		header("Location: ../../index.php");
	}
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
								<li class="active"><a href="./student.php"><span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;Home</a></li>
								<li><a href="">Announcements</a></li>
								<li><a href="./studentRegisterCourses.php">Courses</a></li>
								<li class = "dropdown">
								<a href="#" class = "dropdown-toggle" id = "username" data-toggle = "dropdown" >USERNAME <b class = "caret"></b></a>
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
		<div class="container-fluid">
			<div class = "row">
				<div class = "col-sm-11 col-sm-offset-1">
							<div class="row">
								<div class="col-sm-12">
									<h2 style="color:#808080;">My Courses</h2>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-8"><hr></div>
							</div>
							<div class="row">
								<div class="col-sm-8">
								<?php
									require_once "../../sql_connect.php";
									$query = "SELECT * FROM course_reg WHERE usn='" . $_SESSION["username"] . "'";
									$result = mysql_query($query);
									$num = mysql_num_rows($result);
									if( $num == 0 )
									{
								?>
										<div class="row">
											<div class="col-sm-12">
												<center><h1><span class = "glyphicon glyphicon-flash"></span></h1></center>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
								<?php
										echo "<center><h3 style = \"color:#B0B0B0;\">No Courses</h3></center>";
								?>
											</div>
										</div>
								<?php
									}
									else
									{
										for( $var = 0; $var < $num; $var++ )
										{
											$row = mysql_fetch_assoc($result,MYSQL_ASSOC);
											$query = "SELECT course_code, course_name, course_incharge FROM course WHERE course_code='" . $row["course_code"] . "'";
											$res = mysql_query($query);
											$row = mysql_fetch_assoc($res,MYSQL_ASSOC);
								?>

										<div class="row">
											<div class="col-sm-12">
												<div class="panel panel-default courseHover" >
													<div class="panel-body">
														<div class="row">
															<div class="col-sm-3">
																<img <?php echo "src= \"../server/getCourseImage.php?course_code=" . $row["course_code"] . "\""; ?> alt = "No image" height="80px">
															</div>
															<div class="col-sm-7">
								<?php
																echo "<h4><a href=\"./studentCourse.php?course=" . $row["course_code"] . "\" id=\"sem\">". $row["course_code"] . " - " . $row["course_name"] . "</a><br><small>By " . $row["course_incharge"] . "</small></h4>";
								?>
															</div>
															<div class="col-sm-2">
																<div style="padding-top:50%">
																	<a <?php echo "href=\"./studentCourse.php?course=" . $row["course_code"] . "\"";?>class="btn btn-success pull-right">Go to course</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
								<?php
										}
									}
								?>
								</div>
							</div>
							
						
				</div>
			</div>
		</div>
		<script type="text/javascript" src = "../../bootstrap/js/jquery.min.js"></script>
		<script type="text/javascript" src = "../../bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src = "./js/student.js"></script>
		<script type="text/javascript">
			window.onload = function ()
			{
			    var username = document.getElementById("username");
			    username.innerHTML = "<span class = \"glyphicon glyphicon-user\"></span> " + <?php echo  json_encode($_SESSION["username"])  ?> + "&nbsp;<b class = \"caret\"></b>";
			}
		</script>
	</body>
</html>

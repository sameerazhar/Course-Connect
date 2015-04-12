<?php
	session_start();
	if( isset($_SESSION["username"]) && isset($_SESSION["usertype"]) )
	{
		if( $_SESSION["usertype"]=="admin")
		{
			header("Location: admin/client/admin.php");
		}
		elseif( $_SESSION["usertype"]=="student")
		{
			header("Location: student/client/student.php");
		}
		elseif( $_SESSION["usertype"]=="faculty")
		{
			header("Location: faculty/client/faculty.php");
		}
	}
	
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Course Connect</title>
		<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="bootstrap/css/main.css">
	</head>
	<body style="background-color:#f8f8f8;padding-top:50px;">
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
				        		<div class="col-sm-2"><div class="pull-right"><img src="images/pesit-logo.png" height="50px" /></div></div>
				        		<div class="col-sm-10">
				        			<a class="navbar-brand" href = "">Course Connect</a>
				        		</div>
				        	</div>
				        </div>
	    			</div>
	    			<div class="col-sm-5">
	    				<div id="navbar" class="navbar-collapse collapse">
							<ul class="nav navbar-nav  navbar-right">
								<li><a href="#popularCourses">Courses</a></li>
								<li><a href="#">About</a></li>
								<li><a href="#signinModal" data-target="#signinModal" data-toggle="modal">Sign In</a></li>
							</ul>
						</div>
	    			</div>
	    		</div>
	    	</div>
    	</nav>
    	<div class="homepage-slider">
        	<div id="sequence">
				<ul class="sequence-canvas">
					<!-- Slide 1 -->
					<li class="bg1">
						<!-- Slide Title -->
						<h1 class="title">Course Connect</h1>
						<!-- Slide Text -->
						<h2 class="subtitle">Take best online courses here</h2>
						<!-- Slide Image -->
						<img class="slide-img" src="images/mrd.jpg" alt="Slide 1" />
						
					</li>
					<!-- End Slide 1 -->
					<!-- Slide 2 -->
					<li class="bg2">
						<!-- Slide Title -->
						<h1 class="title">PESIT</h1>
						<!-- Slide Text -->
						<h2 class="subtitle">Department of Computer Science and Engineering</h2>
						<!-- Slide Image -->
						<img class="slide-img" src="images/techpark.jpg" alt="Slide 2" />
						
					</li>
					<!-- End Slide 2 -->
					<!-- Slide 3 -->
					<li class="bg3">
						<!-- Slide Title -->
						<h1 class="title">PES - University</h1>
						<!-- Slide Text -->
						<h2 class="subtitle">Online Programming Courses.</h2>
						<!-- Slide Image -->
						<img class="slide-img" src="images/mech.jpg" alt="Slide 3" />
						
					</li>
					<!-- End Slide 3 -->
				</ul>
				<div class="sequence-pagination-wrapper">
					<ul class="sequence-pagination">
						<li>1</li>
						<li>2</li>
						<li>3</li>
					</ul>
				</div>
			</div>
        </div>
        <div class="container" id = "popularCourses">
        	<div class="row">
        		<div class="col-sm-12">
        			<center><h3 style="color:#B22222">Most Popular Courses</h3></center>
        		</div>
        	</div>
        	<hr>
        	<div class="row">
        		<div class="col-sm-3">
        			<div><center><img src="images/algorithms.jpg" height="100px"></center></div>
        			<div><center><h4>Algorithms</h4></center></div>
        		</div>
        		<div class="col-sm-3">
        			<div><center><img src="images/c.jpg" height="100px"></center></div>
        			<div><center><h4>C Programming</h4></center></div>
        		</div>
        		<div class="col-sm-3">
        			<div><center><img src="images/C++.png" height="100px"></center></div>
        			<div><center><h4>C++ Programming</h4></center></div>
        		</div>
        		<div class="col-sm-3">
        			<div><center><img src="images/Java.jpg" height="100px"></center></div>
        			<div><center><h4>Java Programming</h4></center></div>
        		</div>
        	</div>
        	<br>
        	<div class="row">
        		<div class="col-sm-3">
        			<div><center><img src="images/perl.png" height="100px"></center></div>
        			<div><center><h4>Perl Programming</h4></center></div>
        		</div>
        		<div class="col-sm-3">
        			<div><center><img src="images/python.jpg" height="100px"></center></div>
        			<div><center><h4>Python Programming</h4></center></div>
        		</div>
        		<div class="col-sm-3">
        			<div><center><img src="images/dbms.jpg" height="100px"></center></div>
        			<div><center><h4>DataBase Management</h4></center></div>
        		</div>
        		<div class="col-sm-3">
        			<div><center><img src="images/linux.jpg" height="100px"></center></div>
        			<div><center><h4>Linux</h4></center></div>
        		</div>
        	</div>
        </div>
        <br><br>
		<div class="modal fade" id="signinModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog large">
				<div class="modal-content" style="background-color:#f8f8f8">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<div class="row">
							<div class="col-sm-12">
								<center><h3 class="modal-title" id="myModalLabel">Course Connect</h3></center>
							</div>
						</div>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" onsubmit="return false;">
							<div class="form-group has-feedback">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-3">
										<i class="glyphicon glyphicon-user form-control-feedback"></i>
										<input type="text" autofocus id = "username" class="form-control" placeholder = "Enter Username" />
									</div>
								</div>
							</div>
							<div class="form-group has-feedback">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-3">
										<i class="glyphicon glyphicon-lock form-control-feedback"></i>
										<input type="password" id = "password" class="form-control" placeholder = "Enter Password" />
									</div>
								</div>
							</div>
							<div class="form-group has-feedback">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-3">
										<select class="form-control" id = "userType">
											<option value="student">Student</option>
											<option value="faculty">Faculty</option>
											<option value="admin">Admin</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group has-feedback">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-3">
										<button class="btn btn-block btn-info" onclick="login();">LOG IN</button>
									</div>
								</div>
							</div>
							<div class="form-group has-feedback" style="display:none;color:red;" id = "error_msg_div">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-3">
										<center><div id = "error_msg"></div></center>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src = "bootstrap/js/jquery.min.js"></script>
		<script type="text/javascript" src = "bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src = "authentication/login.js"></script>
		<script src="bootstrap/js/jquery.fitvids.js"></script>
        <script src="bootstrap/js/jquery.sequence-min.js"></script>
        <script src="bootstrap/js/jquery.bxslider.js"></script>
        <script src="bootstrap/js/main-menu.js"></script>
        <script src="bootstrap/js/template.js"></script>
	</body>
</html>

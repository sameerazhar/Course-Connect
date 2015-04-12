<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "faculty" )
	{
		header("Location: ../../index.php");
	}
	extract($_GET);
	require_once "../../sql_connect.php";
	$query = "SELECT * FROM course WHERE course_code='" . $course . "'";
	$res = mysql_query($query);
	$row = mysql_fetch_assoc($res,MYSQL_ASSOC);
	$course_code = $course;
	$course_name = $row["course_name"];
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../bootstrap/css/mynav.css">
	</head>
	<body style="background-color:#F0F0F0;">
		<nav class="navbar navbar-inverse navbar-fixed-top">
	    	<div class="container">
		        <div class="navbar-header">
		        	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			            <span class="sr-only">Toggle navigation</span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
		        	</button>
		        </div>
		        <div id="navbar" class="navbar-collapse collapse">
		        	<ul class="nav navbar-nav  navbar-right">
		            	<li><a href="./faculty.php" style="color:white"><span class="glyphicon glyphicon-home"></span> &nbsp;&nbsp;Home</a></li>
						<li class = "dropdown">
						<a href="#" class = "dropdown-toggle" id = "username" data-toggle = "dropdown" style="color:white">USERNAME <b class = "caret"></b></a>
						<ul class = "dropdown-menu">
							<li><a href="#">Change Username</a></li>
							<li><a href="#">Change Password</a></li>
							<li><a href="../../authentication/logout.php">Log Out</a></li>
						</ul>
						</li>
		          	</ul>
		        </div>
	    	</div>
    	</nav>

		<br>
		<div class="container" style="padding-top:4%" ng-app = "facultyApp">
			<div class = "row">
				<div class = "col-sm-12">
					<div class="panel panel-default">
						<div class = "panel-body" ng-controller = "facultyController">
							<div class = "page-header">
								<h2 style = "padding-left:5%"><?php  echo $course_code . " - " . $course_name ?></h2>
							</div>
							<br>
							<br>
							<div class="row">
								<div class="col-sm-3">
									<ul class="nav nav-pills nav-stacked">
										<li class="active"><a href=<?php echo "./facultyUploadLab.php?course=" . $course_code; ?> >Lab Assignments</a></li>
										<li><a href="./facultyCourseRegAssign.php">Assignments</a></li>
										<li><a href="./facultyCourseExam.php">Exams</a></li>
										<li><a href=<?php echo "./facultyQuizzes.php?course=" . $course_code; ?> >Quiz</a></li>
									</ul>
								</div>
								<div class="col-sm-9">
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="row">
												<div class="col-sm-12">
													<ul class="nav nav-tabs">
														<li class="active"><a href=<?php echo "./facultyUploadLab.php?course=" . $course_code; ?> >Upload new Assignment</a></li>
														<li><a href=<?php echo "./facultyEvaluateLab.php?course=" . $course_code; ?> >Evaluate Assignment</a></li>
													</ul>
												</div>
											</div>
											<br><br>
											<div class="row">
												<div class="col-sm-12">
													<form onsubmit="return false;">
													<div class="row">
														<div class="col-sm-2">
															<label style="padding-top:4%;">Week Number</label>
														</div>
														<div class="col-sm-3">
															<input type = "number" min = "1" id = "week_num" autofocus placeholder="Week Number" class="form-control" />
														</div>
														<div class="col-sm-3">
															<label style="padding-top:4%;">Assignment Number</label>
														</div>
														<div class="col-sm-3">
															<input type="number" min = "1" id = "assign_num" placeholder = "Assignment Number" class="form-control" />
														</div>
													</div>
													<br>
													<div class="row">
														<div class="col-sm-12">
															<textarea rows = "10" id = "que" style = "resize:none" class="form-control" placeholder = "Enter the Problem Statement here...."></textarea>
														</div>
													</div>
													<br>
													<div class="row">
														<div class="col-sm-3">
															<label style="padding-top:4%;">Total Marks</label>
														</div>
														<div class="col-sm-5">
															<input type="number" id = "total_marks" min = "0" class="form-control" placeholder = "Enter total marks" />
														</div>
													</div>
													<br>
													<div class="row">
														<div class="col-sm-3">
															<label style="padding-top:4%;">Number of test cases</label>
														</div>
														<div class="col-sm-5">
															<input type="number" id = "num_t_c" ng-model = "data.val" min = "0" class="form-control"  placeholder = "Enter Number of Test Cases">
														</div>
													</div>
													<br>
													
													<div ng-repeat = "x in [] | range:data.val">
														<div class="row">
															<div class="col-sm-2">
																<center><label style="padding-top:4%;">Test Case {{x}}:</label></center>
															</div>
															<div class="col-sm-2">
																<label style="padding-top:4%;">Input File</label>
															</div>
															<div class="col-sm-8" id = "infile{{x}}">
																<input type = "file" id = "inputfile{{x}}">
															</div>
														</div>
														
														<div class="row">
															<div class="col-sm-2 col-sm-offset-2">
																<label style="padding-top:4%;">Output File</label>
															</div>
															<div class="col-sm-8" id = "outfile{{x}}">
																<input type = "file" id = "outputfile{{x}}">
															</div>
														</div>
														<div class="row">
															<div class="col-sm-2 col-sm-offset-2">
																<label style="padding-top:4%;">Marks</label>
															</div>
															<div class="col-sm-4">
																<input type="number" id = "tmarks{{x}}" class="form-control" placeholder = "Marks" />
															</div>
														</div>
														<br>
														<div class="row">
															<div class="col-sm-2 col-sm-offset-2">
																<label style="padding-top:4%;">Time of execution</label>
															</div>
															<div class="col-sm-4">
																<input type="text" id="execution_time{{x}}" class="form-control" placeholder = "Time of execution in seconds">
															</div>
														</div>
														<br>
													</div>
													<br>
													<div class="row">
														<div class="col-sm-4">
															<div class="checkbox">
																<label><input type = "checkbox" id = "exact_output" value="disbled" onchange="show_output_div();" /> Compare exact output</label>
															</div>
														</div>
													</div>
													<br>
													<br>
													<div id = "output_para">
														<div class="row">
															<div class="col-sm-12">
																<div class="panel panel-default">
																	<div class="panel-body">
																		<ul id="outputTab" class="nav nav-tabs">
																			<li class="active" onclick="set('textNumber');">
																				<a href="#textNumber" data-toggle="tab">Text with Digits</a>
																			</li>
																			<li onclick="set('text');">
																				<a href="#text" data-toggle="tab">Text</a>
																			</li>
																			<li  onclick="set('number');">
																				<a href="#number" data-toggle="tab">Digits</a>
																			</li>
																			<li onclick="set('exactWithDelimit');">
																				<a href="#exactWithDelimit" data-toggle="tab">Exact match with delimter</a>
																			</li>
																		</ul>
																		<br>
																		<div id="outputTabContent" class="tab-content">
																			<div class="tab-pane fade in" id="text">
																				<div class="row">
																					<div class="col-sm-2">
																						<div class="checkbox">
																							<label><input type = "checkbox" id = "textOrdered" /> Ordered</label>
																						</div>
																					</div>
																					<div class="col-sm-2">
																						<div class="checkbox">
																							<label><input type = "checkbox" id = "textNoise" /> Noise Words</label>
																						</div>
																					</div>
																					<div class="col-sm-3">
																						<div class="checkbox">
																							<label><input type = "checkbox" id = "textCaseSensitive" /> Case Sensitive</label>
																						</div>
																					</div>
																					<div class="col-sm-5">
																						<div class="checkbox">
																							<label><input type = "checkbox" id = "textFirstChar" /> Match First Character (T / True / N / no )</label>
																						</div>
																					</div>
																				</div>
																				<br>
																				<div class="row">
																					<div class="col-sm-2">
																						<label style="padding-top:4%;">Delimiter: </label>
																					</div>
																					<div class="col-sm-4">
																						<input type="text" id = "textDelimit" class="form-control" placeholder = "Enter Delimiter" />
																					</div>
																				</div>
																			</div>
																			<div class="tab-pane fade in" id="number">
																				<div class="row">
																					<div class="col-sm-2">
																						<div class="checkbox">
																							<label><input type = "checkbox" id = "numberOrdered" /> Ordered</label>
																						</div>
																					</div>
																					<div class="col-sm-3">
																						<div class="checkbox">
																							<label><input type = "checkbox" id = "numberNoise" /> Noise Words</label>
																						</div>
																					</div>
																				</div>
																				<div class="row">
																					<div class="col-sm-2">
																						<div>
																							<label><input type = "radio" name = "digit" id = "numberFloat" /> Floating</label>
																						</div>
																					</div>
																					<div class="col-sm-4">
																						<div>
																							<label><input type = "radio" name = "digit" id = "numberBase" /> Number with any base</label>
																						</div>
																					</div>
																					<div class="col-sm-3">
																						<div>
																							<label><input type = "radio" name = "digit" id = "numberRange" /> Range compare</label>
																						</div>
																					</div>
																				</div>
																				<br>
																				<div class="row">
																					<div class="col-sm-2">
																						<label style="padding-top:4%;">Delimiter: </label>
																					</div>
																					<div class="col-sm-4">
																						<input type="text" id = "numberDelimit" class="form-control" placeholder = "Enter Delimiter" />
																					</div>
																					<div class="col-sm-2">
																						<label style="padding-top:4%;">Difference: </label>
																					</div>
																					<div class="col-sm-4">
																						<input type="text" value="0" id = "numberDiffrence" class="form-control" placeholder = "Enter Difference" />
																					</div>
																				</div>
																			</div>
																			<div class="tab-pane fade in active" id="textNumber">
																				<div class="row">
																					<div class="col-sm-2">
																						<div class="checkbox">
																							<label><input type = "checkbox" id = "textNumberOrdered" value="disbled" /> Ordered</label>
																						</div>
																					</div>
																					<div class="col-sm-3">
																						<div class="checkbox">
																							<label><input type = "checkbox" id = "textNumberNoise" value="disbled" /> Noise Words</label>
																						</div>
																					</div>
																					<div class="col-sm-3">
																						<div class="checkbox">
																							<label><input type = "checkbox" id = "textNumberCaseSensitive" value="disbled" /> Case Sensitive</label>
																						</div>
																					</div>
																					<div class="col-sm-2">
																						<div class="checkbox">
																							<label><input type = "checkbox" id = "textNumberFloat" value="disbled" /> Floating</label>
																						</div>
																					</div>
																				</div>
																				<br>
																				<div class="row">
																					<div class="col-sm-2">
																						<label style="padding-top:4%;">Delimiter: </label>
																					</div>
																					<div class="col-sm-4">
																						<input type="text" id = "textNumberDelimit" class="form-control" placeholder = "Enter Delimiter" />
																					</div>
																					<div class="col-sm-2">
																						<label style="padding-top:4%;">Difference: </label>
																					</div>
																					<div class="col-sm-4">
																						<input type="text" value="0" id = "textNumberDiffrence" class="form-control" placeholder = "Enter Difference" />
																					</div>
																				</div>
																			</div>
																			<div class="tab-pane fade in" id="exactWithDelimit">
																				<div class="row">
																					<div class="col-sm-2">
																						<label style="padding-top:4%;">Delimiter: </label>
																					</div>
																					<div class="col-sm-4">
																						<input type="text" id = "exactWithDelimitDelimit" class="form-control" placeholder = "Enter Delimiter" />
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<br>
													<div class="row">
														<div class="col-sm-2">
															<label style="padding-top:4%;padding-left:5%;">Due Date :</label>
														</div>
													</div>
													<div class="row">
														<div class="col-sm-2">
															<label style="padding-top:4%;padding-left:5%;">From :</label>
														</div>
														<div class="col-sm-3">
															<input type="date" id = "start_date" class="form-control">
														</div>
														<div class="col-sm-3 col-sm-offset-1">
															<input type="time" id = "start_time" class="form-control">
														</div>
													</div>
													<br>
													<div class="row">
														<div class="col-sm-2">
															<label style="padding-top:4%;padding-left:5%;">To :</label>
														</div>
														<div class="col-sm-3">
															<input type="date" id = "end_date" class="form-control">
														</div>
														<div class="col-sm-3 col-sm-offset-1">
															<input type="time" id = "end_time" class="form-control">
														</div>
													</div>
													<br>
													<br>
													<div class="row">
														<div class="col-sm-3 col-sm-offset-2">
															<input type="submit" value="Submit" onclick="upload_labAssign();" class="btn btn-primary btn-block" />
														</div>
														<div class="col-sm-3 col-sm-offset-2">
															<input type="reset" value="Reset" class="btn btn-primary btn-block">
														</div>
													</div>
													</form>
												</div>
											</div>
											<br><br>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src = "../../bootstrap/js/jquery.min.js"></script>
		<script type="text/javascript" src = "../../bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src = "../../bootstrap/js/angular.js"></script>
		<script type="text/javascript" src = "./js/facultyUploadLab.js"></script>
		<script type="text/javascript">
			window.onload = function ()
			{
			    var username = document.getElementById("username");
			    username.innerHTML = <?php echo  json_encode($_SESSION["username"]);  ?> + "&nbsp;<b class = \"caret\"></b>";
			    course_code = <?php echo "'$course_code'"; ?>;
			}
		</script>
	</body>
</html>

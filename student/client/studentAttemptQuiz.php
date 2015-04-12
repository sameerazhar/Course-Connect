<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "student" )
	{
		header("Location: ../../index.php");
	}
	extract($_GET);
	$course = trim($course);
	$quiz_id = trim($quiz_id);
	require_once "../../sql_connect.php";
	$query = "SELECT * FROM quiz_student WHERE quiz_id=" . $quiz_id . " AND usn='" . $_SESSION["username"] . "'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
	$hasstarted = $row["started"];
	$time_period = $row["remaining_time"];
	$course_code = $course;
	$query = "SELECT * FROM quiz WHERE quiz_id=" . $quiz_id;
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
	if( $hasstarted == 2 || $row["enable"] == 0 )
	{
		header("Location: ./studentQuiz.php?course=$course");
	}
	$quiz_name = $row["quiz_name"];
	$query = "SELECT course_name FROM course WHERE course_code='" . $course . "'";
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	if( $num == 0 )
	{
		echo "<h2 style = \"padding-left:5%;color:red;\">";
		echo "Server Error Occured.";
	}
	else
	{
		$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
		$course_name = $row["course_name"];
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../bootstrap/css/mynav.css">
		<script type="text/javascript">
			quiz_id = <?php echo "$quiz_id";?>;
			course = <?php echo "'$course'"; ?>;
			quiz_name = <?php echo "'$quiz_name'";?>;
			time = <?php echo $time_period; ?>;
			min = Math.floor(time/60);
			sec = time - (min * 60);
			min = min.toString();
			if( min.length == 1 )
			{
				min = "0" + min;
			}
			sec = sec.toString();
			if( sec.length == 1 )
			{
				sec = "0" + sec;
			}
		</script>
	</head>
	<body style="padding-top:60px;">
		<nav class="navbar navbar-default navbar-fixed-top">
	    	<div class="container">
		        <div class="row">
		        	<div class="col-sm-4">
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
					<div class="col-sm-8">
						<div id="navbar" class="navbar-collapse collapse">
							<div class="row">
								<div class="col-sm-6">
									<div class="navbar-brand"><?php echo $course_name . " - " . $course; ?></div>
								</div>
								<div class="col-sm-4">
									<div class="navbar-brand pull-right" id = "timer">
										Time - <?php echo $duration . ":00"; ?>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="pull-right" style="padding-top:8px;">
										<button class="btn btn-success" id = "submit_btn" onclick="submit_quiz();">SUBMIT</button>
									</div>
								</div>
							</div>
						</div>
					</div>
		        	<script type="text/javascript">
		        		document.getElementById("timer").innerHTML = "Time - " + min + ":" + sec;
		        	</script>
		        </div>
	    	</div>
    	</nav>
		<br>
		<div class="container">
			<div class = "row">
				<div class = "col-sm-12">
					<div class="panel panel-default">
						<div class = "panel-body">
							<div class="page-header">
								<h3 style="color:#B22222;padding-left:5%;"><?php echo $quiz_name; ?></h3>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<?php
										$query = "SELECT * FROM quiz_student WHERE quiz_id=" . $quiz_id . " AND usn='" . $_SESSION["username"] . "'";
										$result = mysql_query($query);
										$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
										$started_now = false;
										$attempted_answers = "";
										if( $row["started"] == 0 )
										{
											$query = "SELECT * FROM quiz WHERE quiz_id=" . $quiz_id;
											$result = mysql_query($query);
											$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
											$questions = explode(",", $row["question_order"]);
											shuffle($questions);
											$que = implode(",", $questions);
											$query = "UPDATE quiz_student SET question_order='" . $que . "', started=1, remaining_time=".$row["time_period"]." WHERE usn='" . $_SESSION["username"] . "' AND quiz_id='" . $quiz_id . "'";
											$result = mysql_query($query);
											$started_now = true;
										}
										else
										{
											$questions = explode(",", $row["question_order"]);
											$attempted_answers = $row["attempted_answers"];
										}

										
										

										$num = count($questions);
										$i = 1;


										foreach( $questions as $question_id )
										{
											$j = 1;
											$query = "SELECT question FROM quiz_qstn_repository WHERE qstn_id=" . $question_id;
											$result = mysql_query($query);
											$row = mysql_fetch_assoc($result,MYSQL_ASSOC);
											$question= utf8_decode($row["question"]);
											echo "<div id='$question_id' name='question' oncopy=\"return false;\" oncut=\"return false;\" onpaste=\"return false;\">";
											echo "<div class=\"row\">";
											echo "<div class=\"col-sm-10 col-sm-offset-1\">";
											echo $i . ".  " . $question;
											echo "</div>";
											echo "</div>";
											echo "<div name='question_options'>";
											$query = "SELECT * FROM question_option WHERE qstn_id=$question_id ORDER BY option_no";
											$result = mysql_query($query);
											while( $row = mysql_fetch_assoc($result,MYSQL_ASSOC) )
											{
												$option_value = utf8_decode($row["qstn_id"])."_". utf8_decode($row["option_no"]);
												$option = utf8_decode($row["ques_option"]);
												$optionNo = utf8_decode($row["option_no"]);
												echo "<div class=\"row\">";
												echo "<div class=\"col-sm-9 col-sm-offset-2\">";
												echo "<div class=\"checkbox\">";
												if(strpos($attempted_answers,$option_value)!== false)
												{
													echo "<label><input type='checkbox' name='option' value='$option_value' checked />$optionNo. $option</label>";
												}
												else
												{
													echo "<label><input type='checkbox' name='option' value='$option_value'/>$optionNo. $option</label>";
												}
												echo "</div>";
												echo "</div>";
												echo "</div>";
												$j++;
											}
											echo "</div>";
											echo "</div>";
											echo "<hr>";
											$i++;
										}
									?>
								</div>
							</div>
							<br><br>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="display:none;">
			<form action="./studentQuizSubmit.php" method="POST">
				<input type="text" name="course" id="course_code" />
				<input type="text" name="score" id = "score" />
				<input type="text" name="quiz_name" id = "quiz_name" />
				<input type="submit" id="form_submit" />
			</form>
		</div>
		<script type="text/javascript" src = "../../bootstrap/js/jquery.min.js"></script>
		<script type="text/javascript" src = "../../bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src = "./js/studentAttemptQuiz.js"></script>
	</body>
</html>
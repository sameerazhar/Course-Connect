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
	if( $difficulty == 4 )
	{
		$query="select * from quiz_qstn_repository where course_code='$course' and qstn_summary REGEXP '$topic'";
	}
	else
	{
		$query="select * from quiz_qstn_repository where course_code='$course' and qstn_summary REGEXP '$topic' and difficulty = ". $difficulty;
	}

	$result = mysql_query($query);
	$num = mysql_num_rows($result);

	$qstn_id = array();
	$qstn_stmt = array();

	for( $i = 0; $i < $num; $i++ )
	{
		$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
		$qstn_id[$i] = $row["qstn_id"];
		$qstn_stmt[$row["qstn_id"]] = $row["question"];
	}

	shuffle($qstn_id);
	$question_id = $qstn_id;
	$qstn_id = array();
	for( $i = 0; $i < $no_qstns; $i++ )
	{
		$qstn_id[$i] = $question_id[$i];
	}
	$qstn_order=implode(",",$qstn_id);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Course Connect</title>
		<link rel="stylesheet" type="text/css" href="../../bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../bootstrap/css/mynav.css">
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
				</div>
			</div>
		</nav>
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="page-header">
								<h3 style="color:#B22222;padding-left:5%;">Self Assessment Quiz on <?php echo $topic; ?></h3>
							</div>
							<?php
								for( $i = 0; $i < $no_qstns; $i++ )
								{
							?>
									<div <?php echo "id=\"" . $qstn_id[$i] . "\""; ?> name = "question" oncopy = "return false;" oncut = "return false;" onpaste = "return false;">
										<div class="row">
											<div class="col-sm-10 col-sm-offset-1">
												<?php
													$qno = $i + 1;
													echo $qno . ". " . utf8_decode($qstn_stmt[$qstn_id[$i]]);
												?>
											</div>
										</div>
										<div name = "question_options">
							<?php
									$query = "SELECT * FROM question_option WHERE qstn_id=" . $qstn_id[$i] . " ORDER BY option_no";
									$result = mysql_query($query);
									while( $row = mysql_fetch_assoc($result,MYSQL_ASSOC) )
									{
										$option_value = utf8_decode($row["qstn_id"])."_". utf8_decode($row["option_no"]);
										$option = utf8_decode($row["ques_option"]);
										$optionNo = utf8_decode($row["option_no"]);
							?>
										<div class="row">
											<div class="col-sm-9 col-sm-offset-2">
												<div class="checkbox">
													<label><input type = "checkbox" name="option" value= <?php echo "\"" . $option_value . "\""; ?> /><?php echo $optionNo . ". " . $option; ?></label>
												</div>
											</div>
										</div>
							<?php
									}
							?>
										</div>
									</div>
									<hr>
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="display:none;">
			<form action="./studentSelfAssessmentSubmit.php" method="POST">
				<input type="text" name="answers" id = "student_answers"/>
				<input type="text" name="questions" id = "question_order"/>
				<input type="text" name="course" id = "course_code"/>
				<input type="text" name="topic" id = "course_topic"/>
				<input type="submit" id="form_submit" />
			</form>
		</div>
		<script type="text/javascript" src = "../../bootstrap/js/jquery.min.js"></script>
		<script type="text/javascript" src = "../../bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			time = <?php echo $duration * 60; ?>;
			questionorder = <?php echo "'$qstn_order'"; ?>;
			course = <?php echo "'$course'"; ?>;
			topic = <?php echo "'$topic'"; ?>;
		</script>
		<script type="text/javascript" src = "./js/studentAttemptSelfAssessment.js"></script>
	</body>
</html>

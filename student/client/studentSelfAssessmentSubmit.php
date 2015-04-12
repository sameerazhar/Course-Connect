<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "student" )
	{
		header("Location: ../../index.php");
	}
	extract($_POST);
	require_once "../../sql_connect.php";
	$query = "SELECT * FROM course WHERE course_code='" . $course . "'";
	$res = mysql_query($query);
	$row = mysql_fetch_assoc($res,MYSQL_ASSOC);
	$course_name = $row["course_name"];
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
								<div class="col-sm-6">
									<div class="pull-right" style="padding-top:8px;">
										<a <?php echo "href=\"./studentSelfAssessment.php?course=" . $course . "\""; ?> class="btn btn-success">OK</a>
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
								<div class="row">
									<div class="col-sm-10">
										<h3 style="color:#B22222;padding-left:5%;">Result - Self Assessment Quiz on <?php echo $topic; ?></h3>
									</div>
									<div class="col-sm-2">
										<h3><small id = "marks">My Score : </small></h3>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<?php
														$questions = explode(",",$questions);
														$noOfQuestions=0;
														$marks=0;
														foreach ($questions as $question_id)
														{
													   		$query = "SELECT correct_answer FROM quiz_qstn_repository WHERE qstn_id=$question_id ";
															$result = mysql_query($query);
															$row = mysql_fetch_assoc($result,MYSQL_ASSOC);
															$correct_answer[$question_id]= $row["correct_answer"];
															$att_answer[$question_id]=array();
															$noOfQuestions++;
														}
														$attempted_answers = explode(",",$answers);
														foreach($attempted_answers as $value)
														{
															$values=explode("_",$value);
															if( count($values) == 2 )
															{
																$att_answer[$values[0]][]=$values[1];
															}
														}
														foreach($att_answer as $key => $value)
														{
															$att_answer[$key]=implode(",",$value);
														}
														foreach($att_answer as $key => $value)
														{
															if(strcmp($value, $correct_answer[$key])== 0 )
															{
																$marks++;
															}
														}
														
													?>
													<script type="text/javascript">
														document.getElementById("marks").innerHTML = <?php echo "\"My Score : " . $marks . "/" . $noOfQuestions . "\""; ?>;
													</script>
													<?php
														$num = sizeof($questions);
														for( $i = 0; $i < $num; $i++ )
														{
													?>
													<div class="row">
														<div class="col-sm-8 col-sm-offset-1">
													<?php
															$qstn = $questions[$i];
															$query= "SELECT question, correct_answer FROM quiz_qstn_repository WHERE qstn_id='" . $qstn . "'";
															$result = mysql_query($query);
															$row = mysql_fetch_assoc($result,MYSQL_ASSOC);						
															$question_string = $row["question"];
															$correct_answer=$row["correct_answer"];
															$temp = $i + 1;
															echo $temp . ".  " . utf8_decode($row["question"]);
															?>
														</div>
														<div class="col-sm-2">
													<?php
														if($att_answer[$qstn]=="")
														{
															echo "<label style = \"color:orange\">Not attempted</label>";
														}
														else if( $correct_answer == $att_answer[$qstn] )
														{
															echo "<label style = \"color:green\">Correct</label>";
														}
														else
														{
															echo "<label style = \"color:red\">Wrong</label>";
														}
													?>
														</div>
													</div>
													<br>
													<?php
															$query= "SELECT option_no,ques_option FROM question_option WHERE qstn_id= $qstn ORDER BY option_no";
															$result = mysql_query($query);
															while($row = mysql_fetch_assoc($result,MYSQL_ASSOC))
															{
													?>
													<div class="row">
														<div class="col-sm-9 col-sm-offset-2">
															<?php echo utf8_decode($row["option_no"]) . ".  " . utf8_decode($row["ques_option"]); ?>
														</div>
													</div>
													<?php
															}
													?>
													<br>
													<div class="row">
														<div class="col-sm-2 col-sm-offset-1">
															<div class="pull-right"><?php echo "Correct answer:  " . utf8_decode($correct_answer); ?>
															</div>
														</div>
														<div class="col-sm-3">
															<?php
																if($att_answer[$qstn]=="")
																{
																	echo "Your answer: Not attempted ";
																}
																else
																{
																	echo "Your answer:  " . $att_answer[$qstn]; 
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
			</div>
		</div>
		<script type="text/javascript" src = "../../bootstrap/js/jquery.min.js"></script>
		<script type="text/javascript" src = "../../bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>

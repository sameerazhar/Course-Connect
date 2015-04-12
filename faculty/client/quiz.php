<?php
	extract($_GET);
	$course = trim($course);
	require_once "../../sql_connect.php";
?>

<div class="row">
	<div class="col-sm-9">
		<div class="row">
			<div class="col-sm-6">
				<h3 style="padding-left:3%;">Quiz</h3>
			</div>
			<br>
			<div class="col-sm-4 col-sm-offset-2">
				<input type="text" class="form-control" placeholder="Search Quiz..." />
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12"><hr></div>
		</div>
		<div style="display:none;" id="new_quiz_form">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<h3>Create New Quiz</h3>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-3">
					<label style="padding-top:5%;">Quiz Name</label>
				</div>
				<div class="col-sm-4">
					<input type="text" autofocus id="quiz_name" class="form-control" placeholder = "Enter Quiz Name" />
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-3">
					<label style="padding-top:5%;">Enter topic</label>
				</div>
				<div class="col-sm-4">
					<input type="text" id="qstn_summary" class="form-control" placeholder = "Enter topic" autocomplete="off" data-provide="typeahead" data-items="20"/>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-3">
					<label style="padding-top:5%;">Easy Questions</label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" min="0" id="easy" placeholder = "Enter number of easy questions" />
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-3">
					<label style="padding-top:5%;">Medium Questions</label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" min="0" id="med" placeholder = "Enter number of medium questions" />
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-3">
					<label style="padding-top:5%;">Difficult Questions</label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" min="0" id="hard" placeholder = "Enter number of difficult questions" />
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-3">
					<label style="padding-top:5%;">Quiz Duration</label>
				</div>
				<div class="col-sm-4">
					<input type = "number" min = "1" placeholder = "Enter quiz duration in minutes" class="form-control" id = "time_period" />
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-3">
					<label style="padding-top:5%;">Due Date</label>
				</div>
				<div class="col-sm-4">
					<input type = "date" class="form-control" id = "due_date" />
				</div>
				<div class="col-sm-4">
					<input type = "time" class="form-control" id = "due_time" />
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-3">
					<div class="checkbox"><label><input type = "checkbox" id = "enabled" value="disbled" /> Enable</label></div>
				</div>
				<div class="col-sm-2">
					<button class="btn btn-primary btn-block" onclick = <?php echo "generate_quiz('" . $course . "');" ?> >Generate quiz</button>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<span  id = "msg"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><hr></div>
			</div>
		</div>
		<div style="display:none;" id="new_quiz_questions_form">
			<div class="row">
				<div class="col-sm-12">
					<h4>Upload Quiz Questions<small>  (Only .xls, .xlsx, .ods or .xml format)</small></h4>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-6">
					<input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/xml" class="form-control" name="quiz" id="quiz"/>
				</div>
				<div class="col-sm-2">
					<input type="button" class="btn btn-primary btn-block" value="Upload file" onclick=<?php echo "upload_file('". $course . "')" ?> />
				</div>
			</div>
			<br>
			<div class="row">
				<div class = "col-sm-12">
					<span id="status" style = "padding-left:2%"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><hr></div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<?php
					$query = "SELECT * FROM quiz WHERE course_code='" . $course . "' group by quiz_name";
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
						for( $var = 0; $var < $num; $var++ )
						{
							$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
							$questions = explode(",", $row["question_order"]);
							$quiz_marks = count($questions);
				?>
							<div name = "quiz_div" <?php echo "id=\"quiz" . $row["quiz_id"] . "\""; ?> >
								<div class="row">
									<div class="col-sm-12">
										<h4><?php echo $row["quiz_name"]; ?></h4>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<h4><small>Due Date : <?php echo $row["due_date"]; ?></small></h4>
									</div>
									<div class="col-sm-2">
										<h4><small>Marks : <?php echo $quiz_marks; ?></small></h4>
									</div>
									<div class="col-sm-2">
										<h4><small>Duration : <?php echo $row["time_period"]/60 . " min"; ?></small></h4>
									</div>
									<div class="col-sm-1 col-sm-offset-1" style="padding-top:1%;">
										<a href="">View</a>
									</div>
									<div class="col-sm-1" style="padding-top:1%;">
										<a href="">Evaluate</a>
									</div>
									<?php
										$enable = "Enable";
										if( $row["enable"] == 1 )
										{
											$enable = "Disable";
										}
									?>
									<div class="col-sm-1" style="padding-top:1%;">
										<a href=<?php echo "\"javascript:disable_quiz('" . $row["quiz_id"] . "')\"  id = \"enable" . $row["quiz_id"] . "\" " ?> ><?php echo $enable; ?></a>
									</div>
									<div class="col-sm-1" style="padding-top:1%;">
										<a href=<?php echo "\"javascript:delete_quiz('" . $row["quiz_id"] . "')\"  id = \"delete" . $row["quiz_id"] . "\" " ?> >Delete</a>
									</div>
								</div>
								<hr>
							</div>
				<?php
						}
					}
				?>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="row">
			<div class="col-sm-11">
				<div class="panel panel-default">
					<div class="panel-heading">
						<center><h3 class="panel-title">Create new quiz</h3></center>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-8 col-sm-offset-2">
								<button class="btn btn-success btn-block" onclick="show_new_quiz_form();"><span class="glyphicon glyphicon-plus"></span>&nbsp;New Quiz</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-11">
				<div class="panel panel-default">
					<div class="panel-heading">
						<center><h3 class="panel-title">Upload Quiz Questions</h3></center>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12">
								<center><button class="btn btn-success" onclick="show_quiz_questions_form();"><span class="glyphicon glyphicon-plus"></span>&nbsp;Upload Quiz File</button></center>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#qstn_summary").typeahead({
		source: function(query, process) {
			$.ajax({
				url: '../server/facultyGetTopicQuiz.php',
				type: 'POST',
				data: 'qstn_summary=' + query + "&course=" + course,
				dataType: 'JSON',
				minlength:0,
				items:1000,
				async: true,
				success: function(data) {
					process(data);
				}
			});
		}
	});
</script>
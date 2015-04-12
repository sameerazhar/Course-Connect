<?php
	extract($_GET);
	$course = trim($course);
	require_once "../../sql_connect.php";
?>

<div class="row">
	<div class="col-sm-9">
		<div class="row">
			<div class="col-sm-6">
				<h3 style="padding-left:3%;">Programming Exercises</h3>
			</div>
			<br>
			<div class="col-sm-4 col-sm-offset-2">
				<input type="text" class="form-control" placeholder="Search Programming Exercise..." />
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12"><hr></div>
		</div>
		<div id = "new_exercise_form" style="display:none;">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<h3>New Exercise</h3>
				</div>
			</div>
			<br>
			<form>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-12">
							<textarea class="form-control" rows="10" style="resize:none;" id="problem_stmt" placeholder = "Enter problem statement..."></textarea>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-3">
							<label style="padding-top:5%;">Enter total marks</label>
						</div>
						<div class="col-sm-4">
							<input type="number" name="total_marks" min="0" class="form-control" id="total_marks" placeholder = "Enter total marks" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-3">
							<label style="padding-top:5%;">Number of test cases</label>
						</div>
						<div class="col-sm-4">
							<input type="number" id = "num_t_c" min = "0" class="form-control"  placeholder = "Enter Number of Test Cases">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-12">
							<div class="checkbox">
								<label><input type="checkbox" id="exact_compare" onchange="show_output_div();" /> Compare exact output</label>
							</div>
						</div>
					</div>
				</div>
				<div id="output_data">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-3">
								<label style="padding-top:5%;">Delimiter</label>
							</div>
							<div class="col-sm-4">
								<input type="text" id="delimiter" class="form-control" placeholder = "Enter delimiter" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-3">
								<label style="padding-top:5%;">Max difference in number</label>
							</div>
							<div class="col-sm-4">
								<input type="text" id="difference" class="form-control" placeholder = "Enter difference" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-2">
								<div class="checkbox">
									<label><input type="checkbox" id="ordered" /> Ordered</label>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="checkbox">
									<label><input type="checkbox" id="caseSensitive" /> Case Sensitive</label>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="checkbox">
									<label><input type="checkbox" id="noiseWords" /> Noise Words</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-5">
								<label><input type="radio" name="special_compare" id="firstMatch" /> Match First Character (T / True / N / no )</label>
							</div>
							<div class="col-sm-4">
								<label><input type="radio" name="special_compare" id="anyBase" /> Number with any base</label>
							</div>
							<div class="col-sm-3">
								<label><input type="radio" name="special_compare" id="rangeCompare" /> Range Compare</label>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label style="padding-top:5%;">Due Date</label>
						</div>
						<div class="col-sm-1">
							<label style="padding-top:5%;">From</label>
						</div>
						<div class="col-sm-3">
							<input type="date" id="start_date" class="form-control" />
						</div>
						<div class="col-sm-3 col-sm-offet-1">
							<input type="time" id="start_time" class="form-control" />
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-sm-1 col-sm-offset-2">
							<label style="padding-top:5%;">To</label>
						</div>
						<div class="col-sm-3">
							<input type="date" id="end_date" class="form-control" />
						</div>
						<div class="col-sm-3 col-sm-offet-1">
							<input type="time" id="end_time" class="form-control" />
						</div>
					</div>
				</div>
				<br>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2 col-sm-offset-4">
							<input type="submit" value="SUBMIT" class="btn btn-primary btn-block" />
						</div>
						<div class="col-sm-2">
							<input type="reset" value="RESET" class="btn btn-primary btn-block" />
						</div>
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-sm-12"><hr></div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<?php
					$query = "SELECT * FROM programming_exercise WHERE course_code='" . $course . "'";
					$result = mysql_query($query);
					$num = mysql_num_rows($result);
					if( $num == 0 )
					{
				?>
						<center><h1><span class = "glyphicon glyphicon-flash"></span></h1></center><br>
						<center><h3 style="color:#B0B0B0;">No Exercises</h3></center>
				<?php
					}
					else
					{
						$query = "SELECT sem FROM course WHERE course_code='" . $course . "'";
						$res = mysql_query($query);
						$row = mysql_fetch_assoc($res, MYSQL_ASSOC);
						$sem = $row["sem"];
						for( $i = 0; $i < $num; $i++ )
						{
							$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
							$exer = $i + 1;
							$problem_stmt = file_get_contents("../../questionData/sem" . $sem . "/" . $course . "/" . $row["id"] . "/problem.txt");
				?>
							<div class="row">
								<div class="col-sm-6">
									<h4>Exercise - <?php echo $exer; ?></h4>
								</div>
								<div class="col-sm-6">
									<h4 class="pull-right"><small>Marks : <?php echo $row["marks"]; ?></small></h4>
								</div>
							</div>
							<div class="row" style="padding-top:10px;">
								<div class="col-sm-12">
									<p style="padding-left:2%;padding-right:3%;"><?php echo $problem_stmt; ?></p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<h4><small>Start Date : <?php echo $row["start_time"]; ?></small></h4>
								</div>
								<div class="col-sm-4">
									<h4><small>End Date : <?php echo $row["end_time"]; ?></small></h4>
								</div>
								<div class="col-sm-2">
									<button class="btn pull-right">EVALUATE</button>
								</div>
								<div class="col-sm-1">
									<button class="btn">EDIT</button>
								</div>
								<div class="col-sm-2">
									<button class="btn btn-danger pull-right">DELETE</button>
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
	<div class="col-sm-3">
		<div class="row">
			<div class="col-sm-11">
				<div class="panel panel-default">
					<div class="panel-heading">
						<center><h3 class="panel-title">Add new exercise</h3></center>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12">
								<center><button class="btn btn-success" onclick="show_new_exercise_form();"><span class="glyphicon glyphicon-plus"></span>&nbsp;New Exercise</button></center>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	extract($_GET);
	$course = trim($course);
	require_once "../../sql_connect.php";
?>
					<div class="row">
						<div class="col-sm-9">
							<div class="row">
								<div class="col-sm-2 ">
									<h3 style="padding-left:3%;">Lectures</h3>
								</div>
								<br>
								<div class="col-sm-4 col-sm-offset-6">
									<input type="text" class="form-control" placeholder="Search Lectures..." />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12"><hr></div>
							</div>
							<div style="display:none;" id = "new_lecture_form">
								<div class="row">
									<div class="col-sm-10 col-sm-offset-1">
										<h4>New Lecture</h4>
									</div>
									<div class="col-sm-1">
										<h4 class="pull-right"><a href="javascript:show_new_lecture_form();">X</a></h4>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-sm-12">
										<form action="../server/addNewLecture.php" method="POST" onsubmit="return validate_lecture();">
											<div class="form-group">
												<div class="row">
													<div class="col-sm-2">
														<label style="padding-top:5%;">Lecture Title</label>
													</div>
													<div class="col-sm-4">
														<input type="text" name="lName" id="lName" class="form-control" placeholder = "Enter lecture title" />
													</div>
													<div class="col-sm-6">
														<div id="name_msg" style="padding-top:2%;color:red;"></div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-sm-2">
														<label style="padding-top:5%;">Lecture Date</label>
													</div>
													<div class="col-sm-4">
														<input type="date" name="lDate" id="lDate" class="form-control" placeholder = "Enter lecture title" />
													</div>
													<div class="col-sm-6">
														<div id="date_msg" style="padding-top:2%;color:red;"></div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-sm-2">
														<label style="padding-top:5%;">Lecture File</label>
													</div>
													<div class="col-sm-4">
														<input type="file" name="lFile" id="lFile" class="form-control" />
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-sm-12">
														<textarea class="form-control" name="lDescription" id="lDescription" style = "resize:none" placeholder = "Add description about the lecture..." rows="10"></textarea>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-sm-12">
														<div id="description_msg" style="padding-top:2%;color:red;"></div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-sm-2 col-sm-offset-10">
														<input type="submit" class="btn btn-success btn-block pull-right" value="Submit" />
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12"><hr></div>
								</div>
							</div>
					<?php
						$query = "SELECT * FROM lectures WHERE course_code='" . $course . "'";
						$result = mysql_query($query);
						$num = mysql_num_rows($result);
						if( $num == 0 )
						{
					?>
							<div class="row">
								<div class="col-sm-12">
									<center><h1><span class = "glyphicon glyphicon-flash"></span></h1></center><br>
									<center><h3 style="color:#B0B0B0;">No Lectures</h3></center>
								</div>
							</div>
					<?php
						}
						else
						{
					?>
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-hover">
					<?php
											for( $i = 0; $i < $num; $i++ )
											{
												$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
												echo "<tr>";
												echo "<td>";
												echo "<a  style=\"padding-left:2%;\" href=\"javascript:load_lecture('" . $course . "', '" . $row["lecture_id"] . "')\">" . $row["lecture_name"] . "</a>";
												echo "</td>";
												echo "<td>";
												echo "<div class=\"pull-right\">Date : " . $row["lecture_date"] . "</div>";
												echo "</td>";
												echo "<td>";
												echo "<a href=\"\" class=\"pull-right\">Delete</a>";
												echo "</td>";
												echo "</tr>";
											}
					?>
										</table>
									</div>
								</div>
							</div>
					<?php
						}
					?>
						</div>
						<div class="col-sm-3">
							<div class="row">
								<div class="col-sm-11">
									<div class="panel panel-default">
										<div class="panel-heading">
											<center><h3 class="panel-title">Add new lecture</h3></center>
										</div>
										<div class="panel-body">
											<center><button class="btn btn-success" onclick="show_new_lecture_form();"><span class="glyphicon glyphicon-plus"></span>&nbsp;New Lecture</button></center>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
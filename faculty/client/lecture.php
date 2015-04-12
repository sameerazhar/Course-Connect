<?php
	extract($_GET);
	require_once "../../sql_connect.php";
?>
			<div class="row">
				<div class="col-sm-9">
					<?php
						$query = "SELECT * FROM lectures WHERE lecture_id=" . $id;
						$result = mysql_query($query);
						$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
					?>
					<div class="row">
						<div class="col-sm-12">
							<h4 style="color:#0099FF;padding-left:2%;"><?php echo $row["lecture_name"]; ?></h4>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-12">
							
					<?php
						$notes = file_get_contents("../../lectures/" . $course . "/" . $id . "/notes.txt");
						echo "<pre>" . utf8_decode($notes) . "</pre>";
					?>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="row">
						<div class="col-sm-11">
							<h4><small>Date : <?php echo $row["lecture_date"]; ?></small></h4>
						</div>
					</div>
					<?php
						if( $row["lecture_files"] != "" || $row["lecture_files"] != null )
						{
					?>
					<br>
					<div class="row">
						<div class="col-sm-11">
							<div class="panel panel-default">
								<div class="panel-heading">
									<center><h3 class="panel-title">Download Lecture Notes</h3></center>
								</div>
								<div class="panel-body">
									<center><a href= <?php echo "\"../../lectures/" . $course . "/" . $id . "/" . $row["lecture_files"] . "\""; ?> download class="btn btn-success"><span class = "glyphicon glyphicon-download-alt"></span> Download</a></center>
								</div>
							</div>
						</div>
					</div>
					<?php
						}
					?>
					<br>
					<div class="row">
						<div class="col-sm-11">
							<div class="panel panel-default">
								<div class="panel-heading">
									<center><h3 class="panel-title">Edit Lecture</h3></center>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6 col-sm-offset-3">
											<button class="btn btn-success btn-block">Edit</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
					
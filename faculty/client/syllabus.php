<?php
	extract($_GET);
	$course = trim($course);
?>

<div class="row">
	<div class="col-sm-9">
		<div class="row">
			<div class="col-sm-12">
				<h3 style="padding-left:3%;">Course Syllabus</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12"><hr></div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<?php
					$file_exist = file_exists("../../lectures/" . $course . "/syllabus.txt");
					if( $file_exist )
					{
						$syllabus = file_get_contents("../../lectures/" . $course . "/syllabus.txt");
						echo "<pre>" . $syllabus . "</pre>";
					}
					else
					{
			?>
						<center><h1><span class = "glyphicon glyphicon-flash"></span></h1></center><br>
						<center><h3 style="color:#B0B0B0;">No Syllabus</h3></center><br>
						<center><button class="btn">Upload Syllabus</button></center>
			<?php
					}
				?>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<?php
			if( $file_exist )
			{
		?>
				<div class="row">
					<div class="col-sm-11">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><h3 class="panel-title">Edit Syllabus</h3></center>
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
		<?php
			}
		?>
	</div>
</div>
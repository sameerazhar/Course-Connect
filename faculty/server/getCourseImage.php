<?php
	extract($_GET);
	require_once "../../sql_connect.php";
	$query = "SELECT course_code, image, image_type FROM course WHERE course_code='" . $course_code . "'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	header('Content-Type: '. $row["image_type"]);
	echo $row['image'];
?>
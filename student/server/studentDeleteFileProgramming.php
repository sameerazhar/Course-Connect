<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "student" )
	{
		header("Location: ../../index.php");
	}
	extract($_GET);
	$usn = $_SESSION["username"];
	$course = trim($course);
	$exercise = trim($exercise);
	$file = trim($file);
	$lang = trim($lang);
	$sem = trim($sem);
	require_once '../../sql_connect.php';

	function myremove($arr, $elem)
	{
		$temp = array();
		for( $i = 0; $i < sizeof($arr); $i++ )
		{
			if( $arr[$i] != $elem )
			{
				array_push($temp, $arr[$i]);
			}
		}
		return $temp;
	}

	$query = "SELECT * FROM student_programming WHERE usn='" . $usn . "' and assign_id=" . $exercise .  " and course_code='" . $course . "'";
	
	$result = mysql_query($query);
	
	$num = mysql_num_rows($result);
	if( $num == 0 )
	{
		echo "ERROR";
		return;
	}
	$row = mysql_fetch_assoc($result,MYSQL_ASSOC);

	unlink("../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . $file) or die("ERROR");
	$files = $row["files"];
	$list = explode(";", $files);
	$list = myremove($list, $file);
	$files = join(";", $list);
	$query = "UPDATE student_programming SET files='" . $files . "' WHERE usn='" . $usn . "' and course_code='" . $course . "' and assign_id=" . $exercise;
	$result = mysql_query($query);

	echo "../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . $file;
?>

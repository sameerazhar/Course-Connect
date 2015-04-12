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

	$query = "SELECT * FROM student_programming WHERE usn='" . $usn . "' and assign_id=" . $exercise .  " and course_code='" . $course . "'";
	
	$result = mysql_query($query);
	
	$num = mysql_num_rows($result);
	if( $num == 0 )
	{
		echo "ERROROK";
		return;
	}
	$row = mysql_fetch_assoc($result,MYSQL_ASSOC);
	$files = $row["files"];

	if( strcasecmp($lang, "C") == 0 )
	{
		$fd = fopen("../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . $file, "w") or die("ERROR");
		$files = $files . $file . ";";
		fclose($fd);
	}
	if( strcasecmp($lang, "cpp") == 0 )
	{
		$fd = fopen("../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . $file, "w") or die("ERROR");
		$files = $files . $file . ";";
		fclose($fd);
	}
	if( strcasecmp($lang, "Java") == 0 )
	{
		$fd = fopen("../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . $file, "w") or die("ERROR");
		$files = $files . $file . ";";
		fclose($fd);
	}
	if( strcasecmp($lang, "Python") == 0 )
	{
		$fd = fopen("../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . $file, "w") or die("ERROR");
		$files = $files . $file . ";";
		fclose($fd);
	}
	$query = "UPDATE student_programming SET files='" . $files . "' WHERE usn='" . $usn . "' and course_code='" . $course . "' and assign_id=" . $exercise;
	$result = mysql_query($query);
	echo "../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . $file;

?>

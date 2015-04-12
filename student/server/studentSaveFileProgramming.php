<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "student" )
	{
		header("Location: ../../index.php");
	}
	extract($_POST);
	$usn = $_SESSION["username"];
	$course = trim($course);
	$exercise = trim($exercise);
	$content = trim($content);
	$file = trim($file);
	$sem = trim($sem);
	
	$fd = fopen("../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . $file, "w") or die("ERROR");
	if( strcasecmp($content, "") == 0 )
	{
		fwrite($fd, " ") or die("ERROR");
	}
	else
	{
		fwrite($fd, $content) or die("ERROR");
	}
	fclose($fd);
	echo "OK";
	
?>

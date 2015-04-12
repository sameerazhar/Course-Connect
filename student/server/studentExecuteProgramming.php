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
	$lang = trim($lang);
	$main_file = trim($main_file);
	$cmplfiles = trim($cmplfiles);
	$sem = trim($sem);
	$find_bugs = trim($find_bugs);
	
	
	$cwd = getcwd();
	chdir("../../python/");
	//echo "python3 execute.py " . $usn . " " . $course . " " . $exercise . " " . $lang . " " . $main_file . " " . $cmplfiles . " " . $sem . " 2>&1";
	//return;
	$resource = popen("python3 execute.py " . $usn . " " . $course . " " . $exercise . " " . $lang . " " . $main_file . " " . $cmplfiles . " " . $sem . " " . $find_bugs . " 2>&1", "r");
	if (is_resource($resource))
	{
		while( !feof($resource) )
		{
			echo fgets($resource);
		}
		return;
	}
	
	chdir($cwd);
	echo "ERROR";
?>

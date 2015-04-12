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
	$lang = trim($language);
	$sem = trim($sem);

	require_once "../../sql_connect.php";
	$query = "SELECT * FROM student_programming WHERE usn='" . $usn . "' and course_code='" . $course . "' and assign_id=" . $exercise;
	
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	
	if( $num == 0 )
	{
		$query = "INSERT INTO student_programming VALUES('" . $usn . "', " . $exercise . ", '" . $course . "', 0, 1, '')";
		$result = mysql_query($query);
		$old_umask = umask(0);
		mkdir("../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise, 0777, true);
		umask($old_umask);
		echo "NF";
		return;
	}
	$row = mysql_fetch_assoc($result,MYSQL_ASSOC);
	if( $row["files"] == "" || $row["files"] == null )
	{
		echo "NF";
		return;
	}
	$data = array();
	$files = array();
	if( strcasecmp($lang, "C") == 0 )
	{
		$cfiles = glob( "../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . "*.c");
		$hfiles = glob( "../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . "*.h");
		foreach( $cfiles as $file )
		{
			$files[] = $file;
		}

		foreach ($hfiles as $file)
		{
			$files[] = $file;
		}
	}
	else if( strcasecmp($lang, "cpp") == 0 )
	{
		$cfiles = glob( "../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . "*.cpp");
		$hfiles = glob( "../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . "*.h");
		$files = array();
		foreach( $cfiles as $file )
		{
			$files[] = $file;
		}

		foreach ($hfiles as $file)
		{
			$files[] = $file;
		}
	}
	else if( strcasecmp($lang, "Java") == 0 )
	{
		$jfiles = glob( "../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . "*.java");
		$files = array();
		foreach( $jfiles as $file )
		{
			$files[] = $file;
		}
	}
	else if( strcasecmp($lang, "Python") == 0 )
	{
		$pfiles = glob( "../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . "*.py");
		$files = array();
		foreach( $pfiles as $file )
		{
			$files[] = $file;
		}
	}

	

	foreach( $files as $file )
	{
		$data[$file] = file_get_contents($file);
	}

	$ret = array($files, $data);
	echo json_encode($ret);
?>

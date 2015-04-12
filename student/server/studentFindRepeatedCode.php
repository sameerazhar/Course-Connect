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
	$sem = trim($sem);
	$lang = trim($lang);
	$num_tokens = trim($num_tokens);

	require_once "../../sql_connect.php";
	$query = "SELECT * FROM student_programming WHERE usn='" . $usn . "' and course_code='" . $course . "' and assign_id=" . $exercise;
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	if( $num == 0 )
	{
		echo "ERROR";
		return;
	}
	$row = mysql_fetch_assoc($result,MYSQL_ASSOC);
	//$cwd = getcwd();



	$cmd = "../../pmd-bin-5.2.3/bin/run.sh cpd --minimum-tokens " . $num_tokens . " --language " . strtolower($lang) . " --files ../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . " --format net.sourceforge.pmd.cpd.XMLRenderer > ../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . "repeated_code_result.xml";
	//echo $cmd;
	$res = shell_exec($cmd);
	if( !is_null($res) )
	{
		echo "ERROR";
		return;
	}

	$cmd_gen = "xsltproc ../../test.xsl ../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . "repeated_code_result.xml > ../../studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . "repeated_code_result.html";

	$res = shell_exec($cmd_gen);
	if( !is_null($res) )
	{
		echo "ERROR";
		return;
	}

	echo "/courseconnect/studentData/sem" . $sem . "/" . $usn . "/" . $course . "/" . $exercise . "/" . "repeated_code_result.html";

	//echo $res;
?>
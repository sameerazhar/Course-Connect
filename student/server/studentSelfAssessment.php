<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "student" )
	{
		header("Location: ../../index.php");
	}
	extract($_GET);

	require_once "../../sql_connect.php";
	if( $difficulty == 4 )
	{
		$query="select qstn_id from quiz_qstn_repository where course_code='$course' and qstn_summary REGEXP '$topic'";
	}
	else
	{
		$query="select qstn_id from quiz_qstn_repository where course_code='$course' and qstn_summary REGEXP '$topic' and difficulty = ". $difficulty;
	}
	
	$result = mysql_query($query);
	$num = mysql_num_rows($result);

	if( $num < $no_qstns )
	{
		echo $num;
	}
	else
	{
		echo "OK";
	}
?>
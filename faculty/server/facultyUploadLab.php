<?php
	session_start();
	if( !isset($_SESSION["username"]) || !isset($_SESSION["usertype"]) || $_SESSION["usertype"]!= "faculty" )
	{
		header("Location: ../../index.php");
	}

	extract($_POST);
	require_once "../../sql_connect.php";

	$assign_id = "week_" . $week_num . "_" . $assign_num;
	$query = "SELECT assign_id FROM assignment WHERE assign_id='" . $assign_id . "' and course_code='" . $course_code . "'";
	$result = mysql_query($query);
	if( mysql_num_rows($result) != 0 )
	{
		echo "EXISTS";
		return;
	}
	$query = "SELECT * FROM course WHERE course_code='" . $course_code . "'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result, MYSQL_ASSOC);
	$sem = $row["sem"];

	if( $outputType == "exactMatch" )
	{
		$query = "INSERT INTO assignment (assign_id, course_code, que_path, time_type, time_period, marks, start_time, end_time, exact_match) VALUES ('" . $assign_id . "', '" . $course_code . "', 'questionData/sem" . $sem . "/". $course_code . "/". $assign_id . "/problem.txt', 1, '00:00:00', " . $total_marks . ", '" . $start_date . " " . $start_time . ":00', '" . $end_date . " " . $end_time . ":00', 1)";
	}
	else if( $outputType == "textNumber" )
	{
		$query = sprintf("INSERT INTO assignment (assign_id, course_code, que_path, time_type, time_period, marks, start_time, end_time, exact_match, comparison_type, delimiter, floating, float_diff, noise_words, case_sensitive, ordered) VALUES ('" . $assign_id . "', '" . $course_code . "', 'questionData/sem" . $sem . "/". $course_code . "/". $assign_id . "/problem.txt', 1, '00:00:00', " . $total_marks . ", '" . $start_date . " " . $start_time . ":00', '" . $end_date . " " . $end_time . ":00', 0, 0, '" . mysql_real_escape_string($textNumberDelimit) . "', " . $textNumberFloat . ", '" . $textNumberDiffrence . "', " . $textNumberNoise . ", " . $textNumberCaseSensitive . ", " . $textNumberOrdered . ")");
	}
	else if( $outputType == "text" )
	{
		$query = sprintf("INSERT INTO assignment (assign_id, course_code, que_path, time_type, time_period, marks, start_time, end_time, exact_match, comparison_type, delimiter, noise_words, case_sensitive, ordered, first_char) VALUES ('" . $assign_id . "', '" . $course_code . "', 'questionData/sem" . $sem . "/". $course_code . "/". $assign_id . "/problem.txt', 1, '00:00:00', " . $total_marks . ", '" . $start_date . " " . $start_time . ":00', '" . $end_date . " " . $end_time . ":00', 0, 1, '" . mysql_real_escape_string($textDelimit) . "', " . $textNoise . ", " . $textCaseSensitive . ", " . $textOrdered . ", " . $textFirstChar . ")");
	}
	else if( $outputType == "number")
	{
		$query = sprintf("INSERT INTO assignment (assign_id, course_code, que_path, time_type, time_period, marks, start_time, end_time, exact_match, comparison_type, delimiter, floating, float_diff, noise_words, range_comparison, ordered, any_base) VALUES ('" . $assign_id . "', '" . $course_code . "', 'questionData/sem" . $sem . "/". $course_code . "/". $assign_id . "/problem.txt', 1, '00:00:00', " . $total_marks . ", '" . $start_date . " " . $start_time . ":00', '" . $end_date . " " . $end_time . ":00', 0, 2, '" . mysql_real_escape_string($numberDelimit) . "', " . $numberFloat . ", '" . $numberDiffrence . "', " . $numberNoise . ", " . $numberRange . ", " . $numberOrdered . ", " . $numberBase . ")");
	}
	else if( $outputType == "exactWithDelimit" )
	{
		$query = sprintf("INSERT INTO assignment (assign_id, course_code, que_path, time_type, time_period, marks, start_time, end_time, exact_match, comparison_type, delimiter) VALUES ('" . $assign_id . "', '" . $course_code . "', 'questionData/sem" . $sem . "/". $course_code . "/". $assign_id . "/problem.txt', 1, '00:00:00', " . $total_marks . ", '" . $start_date . " " . $start_time . ":00', '" . $end_date . " " . $end_time . ":00', 0, 3, '" . mysql_real_escape_string($exactWithDelimitDelimit) . "')");
	}

	$result = mysql_query($query);

	$old_umask = umask(0);
	mkdir("../../questionData/sem" . $sem . "/". $course_code . "/". $assign_id , 0777, true) or die("ERROR");
	
	$mypath = "../../questionData/sem" . $sem . "/". $course_code . "/". $assign_id;
	$file = fopen( $mypath . "/problem.txt", "w");
	fwrite($file, $que);
	fclose($file);

	$path = "questionData/sem" . $sem . "/". $course_code . "/". $assign_id;


	$tmarks_array = json_decode($tmarks_array);
	$execution_time_array = json_decode($execution_time);
	for( $var = 1; $var <= $no_test_cases; $var++ )
	{
		move_uploaded_file($_FILES["inputfile" . strval($var)]["tmp_name"], $mypath . "/input" . strval($var) . ".txt");
		move_uploaded_file($_FILES["outputfile" . strval($var)]["tmp_name"], $mypath . "/output" . strval($var) . ".txt");
		$query = "INSERT INTO test_case (assign_id, course_code, input_path, output_path, marks, execution_time) VALUES ('" . $assign_id . "', '" . $course_code . "', '" . $path . "/input" . strval($var) . ".txt', '" . $path . "/output" . strval($var) . ".txt', " . $tmarks_array[$var-1] . ", " . $execution_time_array[$var-1] . ")";
		$result = mysql_query($query);
	}
	umask($old_umask);
	echo "OK";
?>
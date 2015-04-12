
function encodeNameAndValue(sName, sValue)
{
	var sParam = encodeURIComponent(sName);
	sParam += "=";
	sParam += encodeURIComponent(sValue);
	return sParam;
}

function getAttemptedAnswers()
{
	var input = document.getElementsByTagName("input");
	var attempted_answers=""
	for( var i = 0; i < input.length; i++ )
	{
		if( input[i].type == "checkbox" && input[i].checked )
		{
			attempted_answers += ","+input[i].value;
		}
	}
	return attempted_answers;
}


function submit_answer()
{
	var attempted_answers = getAttemptedAnswers();
	var encodedAnswers = encodeNameAndValue("answers", attempted_answers.substring(1));
	var encodedQuiz_id = encodeNameAndValue("viva_id", viva_id);
	var encodedCourse = encodeNameAndValue("course", course);
	var encodedTopics = encodeNameAndValue("topics", topics);
	var encodedQuestions = encodeNameAndValue("questions", questions);
	var encodedCurrentLevel = encodeNameAndValue("current_level", current_level);
	var encodedWrong = encodeNameAndValue("wrong_answers", wrong_answers);
	var encodedTotal = encodeNameAndValue("total_attempted", total_attempted);    
	window.location="./studentsecondadaptivequiz.php?"+encodedAnswers + "&" + encodedQuiz_id  + "&" + encodedCourse + "&" + encodedTopics + "&" + encodedQuestions + "&" + encodedCurrentLevel + "&" + encodedWrong + "&" + encodedTotal ;
}

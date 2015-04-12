function getXmlHttpObject()
{
	var xmlhttp;
	if(window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} 
	return xmlhttp;		
}



function encodeNameAndValue(sName, sValue)
{
	var sParam = encodeURIComponent(sName);
	sParam += "=";
	sParam += encodeURIComponent(sValue);
	return sParam;
}

var topic_msg = document.getElementById("topic_msg");
var topic_flag = true;
$("#qstn_summary").typeahead({
	source: function(query, process) {
		$.ajax({
			url: '../server/studentGetTopicQuiz.php',
			type: 'POST',
			data: 'qstn_summary=' + query + "&course=" + course,
			dataType: 'JSON',
			minlength:0,
			items:1000,
			async: true,
			success: function(data) {
				if( data == "" )
				{
					topic_msg.innerHTML = "No questions are available on \"" + query + "\"";
					topic_flag = false;
					setTimeout(clear, 3000);
				}
				else
				{
					topic_msg.innerHTML = "";
					process(data);
					topic_flag = true;
				}
			}
		});
	}
});
function start_quiz()
{
	var topic = document.getElementById("qstn_summary").value;
	var duration = document.getElementById("quiz_duration").value;
	if( topic == "" || topic == null )
	{
		topic_msg.innerHTML = "Please enter topic.";
		setTimeout(clear, 3000);
	}
	else
	{
		if( topic_flag == false )
		{
			topic_msg.innerHTML = "Please enter available topic only.";
		}
		else
		{
			var no_qstns = document.getElementById("no_qstns").value;
			var qstns_msg = document.getElementById("qstns_msg");
			if( no_qstns == "" || no_qstns == null )
			{
				qstns_msg.innerHTML = "Please enter number of questions.";
				setTimeout(clear, 3000);
			}
			else if( no_qstns < 1 )
			{
				qstns_msg.innerHTML = "Number of questions must be at least one.";
				setTimeout(clear, 3000);
			}
			else
			{
				var duration_msg = document.getElementById("duration_msg");
				if( duration == "" || duration == null )
				{
					duration_msg.innerHTML = "Please enter quiz duration.";
					setTimeout(clear, 3000);
				}
				else if( duration < 1 )
				{
					duration_msg.innerHTML = "Quiz duration must be at least one minute.";
					setTimeout(clear, 3000);
				}
				else
				{
					var difficulty;
					if( document.getElementById("easy").checked )
					{
						difficulty = 1;
						difficulty_section = "EASY";
					}
					else if( document.getElementById("medium").checked )
					{
						difficulty = 2;
						difficulty_section = "MEDIUM";
					}
					else if( document.getElementById("difficult").checked )
					{
						difficulty = 3;
						difficulty_section = "DIFFICULT";
					}
					else if( document.getElementById("mixed").checked )
					{
						difficulty = 4;
						difficulty_section = "MIXED";
					}
					else
					{
						var difficulty_msg = document.getElementById("difficulty_msg");
						difficulty_msg.innerHTML = "Please select difficulty level.";
						setTimeout(clear, 3000);
						return;
					}
					encodedCourse = encodeNameAndValue("course", course);
					encodedQstns = encodeNameAndValue("no_qstns", no_qstns);
					encodedLevel = encodeNameAndValue("difficulty", difficulty);
					encodedTopic = encodeNameAndValue("topic", topic);
					encodedDuration = encodeNameAndValue("duration", duration);
					xhr_self_assess = getXmlHttpObject();
					xhr_self_assess.onreadystatechange = start_quiz_response;
					xhr_self_assess.open("GET", "../server/studentSelfAssessment.php?" + encodedCourse + "&" + encodedTopic + "&" + encodedQstns + "&" + encodedLevel, true);
					xhr_self_assess.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhr_self_assess.send();
				}
			}
		}
	}
}

function start_quiz_response()
{
	if( xhr_self_assess.readyState == 4 )
	{
		if( xhr_self_assess.status == 200 || xhr_self_assess.status == 304 )
		{
			var no_qstns_avail = xhr_self_assess.responseText.trim();
			if( no_qstns_avail == "OK" )
			{
				window.location = "./studentAttemptSelfAssessment.php?" + encodedCourse + "&" + encodedTopic + "&" + encodedQstns + "&" + encodedLevel + "&" + encodedDuration;
			}
			else
			{
				document.getElementById("qstns_msg").innerHTML = "Only " + no_qstns_avail + " questions are available in " + difficulty_section + " section.";
				setTimeout(clear, 6000);
			}
		}
	}
}

function clear()
{
	topic_msg.innerHTML = "";
	document.getElementById("qstns_msg").innerHTML = "";
	document.getElementById("duration_msg").innerHTML = "";
	document.getElementById("difficulty_msg").innerHTML = "";
}
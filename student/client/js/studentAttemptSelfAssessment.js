var w = null; // initialize variable
// function to start the timer
function startTimer()
{
	// First check whether Web Workers are supported
	if( typeof( Worker) !== "undefined" )
	{
		// Check whether Web Worker has been created. If not, create a new Web Worker based on
		if( w == null)
		{
			w = new Worker("./js/timerWorker.js");
		}
		// Update timer div with output from Web Worker
		w.onmessage = function (event)
		{
			if(event.data=="timeout")
			{
				w.terminate();
				w=null;
				document.getElementById("submit_btn").click();
				document.getElementById( "timer" ).innerHTML = "Time - 00:00";
			}
			else
			{
				document.getElementById( "timer" ).innerHTML = "Time - " + event.data;
			}
		};
		w.postMessage(time);
	}
	else
	{
		// Web workers are not supported by your browser
		document.getElementById( "timer" ).innerHTML = "Sorry, your browser does not support Web worker";
	}
}
startTimer();

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


function submit_quiz()
{
	var attempted_answers = getAttemptedAnswers();
	document.getElementById("student_answers").value = attempted_answers.substring(1);
	document.getElementById("question_order").value = questionorder;
	document.getElementById("course_code").value = course;
	document.getElementById("course_topic").value = topic;
	document.getElementById("form_submit").click();
}
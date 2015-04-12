jContent = $("#content");
lectureNav = document.getElementById("lectureNav");
syllabusNav = document.getElementById("syllabusNav");
scheduleNav = document.getElementById("scheduleNav");
assignmentsNav = document.getElementById("assignmentsNav");
programmingNav = document.getElementById("programmingNav");
quizNav = document.getElementById("quizNav");
adaptiveQuizNav = document.getElementById("adaptiveQuizNav");
assessmentsNav = document.getElementById("assessmentsNav");

function set_active_nav(nav)
{
	lectureNav.setAttribute("class", "");
	syllabusNav.setAttribute("class", "");
	scheduleNav.setAttribute("class", "");
	assessmentsNav.setAttribute("class", "");
	programmingNav.setAttribute("class", "");
	quizNav.setAttribute("class", "");
	adaptiveQuizNav.setAttribute("class", "");
	assessmentsNav.setAttribute("class", "");

	nav.setAttribute("class", "active");
}

function load_lecture_list(course)
{
	jContent.load("./lectureList.php?course=" + course);
	set_active_nav(lectureNav);
}

function load_syllabus(course)
{
	jContent.load("./syllabus.php?course=" + course);
	set_active_nav(syllabusNav);
}

function load_schedule(course)
{
	jContent.load("./schedule.php?course=" + course);
	set_active_nav(scheduleNav);
}

var load_programming_flag = true;
var load_quiz_flag = true;
function load_programming(course)
{
	if( load_programming_flag )
	{
		var script = document.createElement("script");
		script.src = "./js/programming.js";
		document.body.appendChild(script);
		load_programming_flag = false;
	}
	jContent.load("./programming.php?course=" + course);
	set_active_nav(programmingNav);
}

function load_quiz(course)
{
	if( load_quiz_flag )
	{
		var script = document.createElement("script");
		script.src = "./js/quiz.js";
		document.body.appendChild(script);
		var script = document.createElement("script");
		script.src = "../../bootstrap/js/bootstrap3-typeahead.js";
		document.body.appendChild(script);
		load_quiz_flag = false;
	}
	jContent.load("./quiz.php?course=" + course);
	set_active_nav(quizNav);
}

function show_new_lecture_form()
{
	$("#new_lecture_form").slideToggle("slow");
}

function validate_lecture()
{
	var name = document.getElementById("lName").value;
	if( name == "" || name == null )
	{
		document.getElementById("name_msg").innerHTML = "* Please enter lecture title.";
		setTimeout(clear, 3000);
		return false;
	}

	var date = document.getElementById("lDate").value;
	if( date == "" || date == null )
	{
		document.getElementById("date_msg").innerHTML = "* Please select lecture date.";
		setTimeout(clear, 3000);
		return false;
	}

	var description = document.getElementById("lDescription").value;
	if( description == "" || description == null )
	{
		document.getElementById("description_msg").innerHTML = "* Please add description about the lecture.";
		setTimeout(clear, 3000);
		return false;
	}

	return true;
}

function clear()
{
	document.getElementById("name_msg").innerHTML = "";
	document.getElementById("date_msg").innerHTML = "";
	document.getElementById("description_msg").innerHTML = "";
}

function load_lecture(course, lecture_id)
{
	jContent.load("./lecture.php?course=" + course + "&id=" + lecture_id);
}
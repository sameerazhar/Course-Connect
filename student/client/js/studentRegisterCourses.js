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

function register()
{
	var course_reg = document.getElementsByTagName("input");

	var course = "";
	var f = true;
	for( i = 0; i < course_reg.length; i++ )
	{
		if( course_reg[i].value == "Unroll" )
		{
			if( f )
			{
				course = course_reg[i].id;
				f = false;
			}
			else
			{
				course = course + "," + course_reg[i].id;
			} 
		}
	}
	xhr_register = getXmlHttpObject();
	xhr_register.onreadystatechange = register_response;
	xhr_register.open("POST", "../server/studentRegisterCourses.php", true);
	xhr_register.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr_register.send("courses=" + course);
}


function register_response()
{
	if(xhr_register.readyState == 4)
	{
		if(xhr_register.status == 200 || xhr_register.status == 304)
		{
			if( xhr_register.responseText == "ERROR" )
			{
				alert("Some error occured, try after some time.");
			}
			else
			{
				alert("Courses registered successfully.");
			}
		}
	}
}

function set_enroll(course_code)
{
	var enrollbtn = document.getElementById(course_code);
	if( enrollbtn.value == "Enroll" )
	{
		enrollbtn.setAttribute("class", "btn");
		enrollbtn.value = "Unroll";
	}
	else
	{
		enrollbtn.setAttribute("class", "btn btn-success");
		enrollbtn.value = "Enroll";
	}
}
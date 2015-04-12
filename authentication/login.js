function getXmlHttpObject()
{
	var xmlhttp;
	if(window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
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

function login()
{
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	var elem = document.getElementById("userType");
	userType = elem.options[elem.selectedIndex].value;
	if( username == "" || username == null || password == "" || password == null )
	{
		var error_msg = document.getElementById("error_msg");
		var error_msg_div = document.getElementById("error_msg_div");
		error_msg_div.style.display = "block";
		error_msg.innerHTML = "Fields can't be left empty.";
	}
	else
	{
		xhr_login = getXmlHttpObject();
		var encodedName = encodeNameAndValue("user_id", username);
		var encodedPass = encodeNameAndValue("user_pwd", password);
		var encodedType = encodeNameAndValue("user_type", userType);
		xhr_login.open("POST","./authentication/login.php",true);
		xhr_login.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr_login.onreadystatechange = login_response;
		xhr_login.send(encodedName + "&" + encodedPass + "&" + encodedType);
	}
}

function login_response()
{
	if( xhr_login.readyState == 4 )
	{
		if( xhr_login.status == 200 || xhr_login.status == 304 )
		{
			if( xhr_login.responseText.trim() == "valid" )
			{
				if( userType === "admin" )
				{
					location.href = "./admin/client/admin.php";
				}
				else if( userType === "student" )
				{
					location.href = "./student/client/student.php";
				}
				else if( userType === "faculty" )
				{
					location.href = "./faculty/client/faculty.php";
				}
			}
			else
			{
				var error_msg = document.getElementById("error_msg");
				error_msg.innerHTML = "Wrong username or password.";
				var error_msg_div = document.getElementById("error_msg_div");
				error_msg_div.style.display = "block";
			}
		}
	}
}
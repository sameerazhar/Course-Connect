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

var app = angular.module("facultyApp", []);


var _t_c = 0;
var outputType = "textNumber";
function set(data)
{
	outputType = data;
	//alert(data)
}

function upload_labAssign()
{
	try
	{
		xhr_labAssign = getXmlHttpObject();
		form = new FormData();
		var week_num = document.getElementById("week_num").value;
		var assign_num = document.getElementById("assign_num").value;
		var total_marks = document.getElementById("total_marks").value;
		var que = document.getElementById("que").value;
		form.append("week_num", week_num);
		form.append("assign_num", assign_num);
		form.append("que", que);
		tmarks_array = new Array();
		execution_time_array = new Array();
		for( var i = 1; i <= _t_c; i++ )
		{
			var inputfile = document.getElementById("inputfile" + i);
			var outputfile = document.getElementById("outputfile" + i);
			var execution_time = document.getElementById("execution_time" + i).value;
			var marks = document.getElementById("tmarks" + i).value;
			form.append("inputfile" + i, inputfile.files[0]);
			form.append("outputfile" + i, outputfile.files[0]);
			tmarks_array.push(marks);
			execution_time_array.push(execution_time);
		}
		tmarks_array = JSON.stringify(tmarks_array);
		execution_time_array = JSON.stringify(execution_time_array);
		start_date = document.getElementById("start_date").value;
		start_time = document.getElementById("start_time").value;
		end_date = document.getElementById("end_date").value;
		end_time = document.getElementById("end_time").value;
		var exact = document.getElementById("exact_output");
		if( exact.checked == false )
		{
			if( outputType == "textNumber" )
			{
				textNumberDelimit = document.getElementById("textNumberDelimit").value;
				textNumberOrdered = 0;
				textNumberNoise = 0;
				textNumberCaseSensitive = 0;
				textNumberFloat = 0;

				if( document.getElementById("textNumberOrdered").checked == true )
				{
					textNumberOrdered = 1;
				}
				if( document.getElementById("textNumberNoise").checked == true )
				{
					textNumberNoise = 1;
				}
				if( document.getElementById("textNumberCaseSensitive").checked == true )
				{
					textNumberCaseSensitive = 1;
				}
				if( document.getElementById("textNumberFloat").checked == true )
				{
					textNumberFloat = 1;
				}
				textNumberDiffrence = document.getElementById("textNumberDiffrence").value;
				form.append("textNumberDelimit", textNumberDelimit);
				form.append("textNumberOrdered", textNumberOrdered);
				form.append("textNumberNoise", textNumberNoise);
				form.append("textNumberCaseSensitive", textNumberCaseSensitive);
				form.append("textNumberFloat", textNumberFloat);
				form.append("textNumberDiffrence", textNumberDiffrence);
			}
			else if( outputType == "text" )
			{
				textOrdered = 0;
				textNoise = 0;
				textCaseSensitive = 0;
				textFirstChar = 0;
				if( document.getElementById("textOrdered").checked == true )
				{
					textOrdered = 1;
				}
				if( document.getElementById("textNoise").checked == true )
				{
					textNoise = 1;
				}
				if( document.getElementById("textCaseSensitive").checked == true )
				{
					textCaseSensitive = 1;
				}
				if( document.getElementById("textFirstChar").checked == true )
				{
					textFirstChar = 1;
				}
				textDelimit = document.getElementById("textDelimit").value;
				form.append("textOrdered", textOrdered);
				form.append("textNoise", textNoise);
				form.append("textCaseSensitive", textCaseSensitive);
				form.append("textFirstChar", textFirstChar);
				form.append("textDelimit", textDelimit);
			}
			else if( outputType == "number" )
			{
				numberOrdered = 0;
				numberNoise = 0;
				numberFloat = 0;
				numberBase = 0;
				numberRange = 0;
				if( document.getElementById("numberOrdered").checked == true )
				{
					numberOrdered = 1;
				}
				if( document.getElementById("numberNoise").checked == true )
				{
					numberNoise = 1;
				}
				if( document.getElementById("numberFloat").checked == true )
				{
					numberFloat = 1;
				}
				if( document.getElementById("numberBase").checked == true )
				{
					numberBase = 1;
				}
				if( document.getElementById("numberRange").checked == true )
				{
					numberRange = 1;
				}
				numberDelimit = document.getElementById("numberDelimit").value;
				numberDiffrence = document.getElementById("numberDiffrence").value;
				form.append("numberOrdered", numberOrdered);
				form.append("numberNoise", numberNoise);
				form.append("numberFloat", numberFloat);
				form.append("numberBase", numberBase);
				form.append("numberRange", numberRange);
				form.append("numberDelimit", numberDelimit);
				form.append("numberDiffrence", numberDiffrence);
			}
			else if( outputType == "exactWithDelimit" )
			{
				exactWithDelimitDelimit = document.getElementById("exactWithDelimitDelimit").value;
				form.append("exactWithDelimitDelimit", exactWithDelimitDelimit);
			}
		}
		else
		{
			outputType = "exactMatch";
		}
		form.append("execution_time", execution_time_array);
		form.append("tmarks_array", tmarks_array);
		form.append("outputType", outputType);
		form.append("course_code", course_code);
		form.append("start_date", start_date);
		form.append("end_date", end_date);
		form.append("start_time", start_time);
		form.append("end_time", end_time);
		form.append("total_marks", total_marks);
		form.append("no_test_cases", _t_c);
		xhr_labAssign.onreadystatechange = upload_labAssign_response;
		xhr_labAssign.open("POST", "../server/facultyUploadLab.php", true);
		xhr_labAssign.send(form);
	}
	catch(e)
	{
		alert(e);
	}
}

function upload_labAssign_response()
{
	if( xhr_labAssign.readyState == 4 )
	{
		if( xhr_labAssign.status == 200 || xhr_labAssign.status == 304 )
		{
			if( xhr_labAssign.responseText == "OK" )
			{
				alert("Uploaded Successfully.");
			}
			else if( xhr_labAssign.responseText == "EXISTS" )
			{
				alert("Assignment number already exists, change assignment number.")
			}
			else
			{
				alert("Sorry, error occured.");
			}
		}
	}
}


app.controller("facultyController", function($scope) {
	$scope.data = {};
});

app.filter("range", function() {
	return function(arr, high) {
		_t_c = parseInt(high);
		for(var i = 1; i <= high; i++)
		{
			arr.push(i);
		}
		return arr;
	}
});

function show_output_div()
{
	var exact_output = document.getElementById("exact_output");
	var output_div = document.getElementById("output_para");
	if( exact_output.checked )
	{
		output_div.style.display = "none";
	}
	else
	{
		output_div.style.display = "block";
	}
}
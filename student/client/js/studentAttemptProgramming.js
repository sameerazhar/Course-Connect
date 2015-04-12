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

function set_data(l, eid, c, s)
{
	lang = l;
	course = c;
	exercise = eid;
	sem = s;
}

function change_lang()
{
	var language_selected = document.getElementById("language");
	//alert(select.options[select.selectedIndex].value);
	//lang = language_selected.options[select.selectedIndex].value;
}

var f_id = new Array();
var files = new Array();
var num_of_files = 0;

function set_w_h()
{
	var f = document.getElementById( "frame_t" + f_id[0]);
	var w = f.style.width;
	var h = f.style.height;
	for( var i = 1; i < f_id.length; i++ )
	{
		var f = document.getElementById( "frame_t" + f_id[i]);
		f.style.width = w;
		f.style.height = h;
	}
}

function myremove(arr, elem)
{
	var temp = new Array();
	for( var i = 0; i < arr.length; i++ )
	{
		if( arr[i] != elem )
		{
			temp.push(arr[i]);
		}
	}
	return temp;
}

function fetch_files()
{
	filetabs = document.getElementById("filetabs");
	tabContent = document.getElementById("tabContent");
	xhr_get_file = getXmlHttpObject();
	xhr_get_file.onreadystatechange = display_files;
	xhr_get_file.open("GET", "../server/studentFetchFilesProgramming.php?language=" + lang + "&exercise=" + exercise + "&course=" + course + "&sem=" + sem);
	xhr_get_file.send();
}

function display_files()
{
	if(xhr_get_file.readyState == 4)
	{
		if(xhr_get_file.status == 200 || xhr_get_file.status == 304)
		{
			if( xhr_get_file.responseText == "ERROR" )
			{
				alert("Some ERROR occured, try reloading the page.");
			}
			else if( xhr_get_file.responseText == "NF" )
			{
				//alert("NF");
			}
			else
			{
				var execution_data = document.getElementById("execution_data");
				execution_data.style.display = "block";
				var ret = JSON.parse(xhr_get_file.responseText);
				files = ret[0];
				var data = ret[1];
				var flag = true;
				var cmpltable = document.getElementById("comp_files");
				num_of_files = files.length;
				for( var i = 0; i < num_of_files; i++ )
				{
					var ftab = document.createElement("li");
					var fdiv = document.createElement("div");
					fdiv.setAttribute("class", "tab-pane fade");
					var fanchor = document.createElement("a");
					var temp = files[i].split("/");
					var file = temp[temp.length - 1];
					var id = file.replace(".", "");
					fanchor.href = "#" + id;
					fanchor.setAttribute("data-toggle","tab");
					fanchor.innerHTML = file;
					ftab.appendChild(fanchor);
					ftab.setAttribute("id", "tab" + id);
					filetabs.appendChild(ftab);
					
					fdiv.setAttribute("id", id);
					f_id.push( id );
					var ftext = document.createElement("textarea");
					ftext.setAttribute("class", "form-control");
					ftext.setAttribute("rows", "25");
					ftext.style.resize = "none";
					ftext.setAttribute("id", "t" + id);
					
					ftext.innerHTML = data[files[i]];
					if( flag )
					{
						ftab.setAttribute("class", "active");
						fdiv.setAttribute("class", "tab-pane fade active in");
						flag = false;
					}
					ftab.setAttribute("onclick", "set_w_h();");
					fdiv.appendChild(ftext);
					tabContent.appendChild(fdiv);
					editAreaLoader.init({
						id: "t" + id
						,start_highlight: true
						,allow_toggle: false
						,font_size: "14"
						,language: "en"
						,syntax: lang
						,save_callback: "save_code"
						,toolbar: "search, |,  go_to_line,  |, save, |, select_font"
					});

					// compilation files
					var cmpldiv = document.createElement("div");
					cmpldiv.setAttribute("class", "checkbox");
					cmpldiv.setAttribute("style", "padding-left:3%");
					cmpldiv.setAttribute("id", "cmpldiv" + id);
					var cmpllabel = document.createElement("label");
					var cmplch = document.createElement("input");
					cmplch.setAttribute("type", "checkbox");
					cmplch.setAttribute("checked", "checked");
					cmplch.setAttribute("id", "cmplfile" + id);
					cmplch.setAttribute("value", file);
					cmpllabel.appendChild(cmplch);
					cmpllabel.innerHTML += file;
					cmpldiv.appendChild(cmpllabel);
					cmpltable.appendChild(cmpldiv);
				}
			}
		}
		else
		{
			alert("Error");
		}
	}
}

function get_file_name(file_id)
{
	var temp = file_id.slice(1, file_id.length);
	var file;
	if( lang == "C" )
	{
		file = temp.slice(0, temp.length - 1);
		if( temp.slice(temp.length - 1, temp.length) == "c" )
		{
			file = file + ".c";
		}
		else
		{
			file = file + ".h";
		}
	}
	else if( lang == "cpp" )
	{
		file = temp.slice(0, temp.length - 3);
		if( temp.slice(temp.length - 3, temp.length) == "cpp" )
		{
			file = file + ".cpp";
		}
		else
		{
			file = file + ".h";
		}
	}
	else if( lang == "Java" )
	{
		file = temp.slice(0, temp.length - 4) + ".java";
	}
	else if( lang == "Python" )
	{
		file = temp.slice(0, temp.length - 2) + ".py";
	}
	return file;
}

function save_code(id, content)
{
	xhr_save_file = getXmlHttpObject();
	xhr_save_file.onreadystatechange = save_code_response;
	xhr_save_file.open("POST", "../server/studentSaveFileProgramming.php", true);
	xhr_save_file.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	var file = get_file_name(id);
	encodedContent = encodeNameAndValue("content", content);
	encodedCourse = encodeNameAndValue("course", course);
	encodedExcercise = encodeNameAndValue("exercise", exercise);
	encodedFile = encodeNameAndValue("file", file);
	encodeSem = encodeNameAndValue("sem", sem);
	xhr_save_file.send( encodedCourse + "&" + encodedExcercise + "&" + encodedContent + "&" + encodedFile + "&" + encodeSem);
}

function save_code_response()
{
	if(xhr_save_file.readyState == 4)
	{
		if(xhr_save_file.status == 200 || xhr_save_file.status == 304)
		{
			if( xhr_save_file.responseText == "ERROR" )
			{
				alert("Some error occured, save again");
			}
			else
			{
				alert("File saved successfully");
			}
		}
	}
}

function create_file()
{
	var file_name = prompt("Enter the file name.");
	if( file_name == null || file_name == "" )
	{
		return;
	}
	xhr_create_file = getXmlHttpObject();
	xhr_create_file.onreadystatechange = create_file_response;
	encodedCourse = encodeNameAndValue("course", course);
	encodedFile = encodeNameAndValue("file", file_name);
	encodedLang = encodeNameAndValue("lang", lang);
	encodedExcercise = encodeNameAndValue("exercise", exercise);
	encodeSem = encodeNameAndValue("sem", sem);
	xhr_create_file.open("GET", "../server/studentCreateFileProgramming.php?" + encodedFile + "&" + encodedCourse + "&" + encodedExcercise + "&" + encodedLang + "&" + encodeSem, true);
	xhr_create_file.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr_create_file.send();
}


function create_file_response()
{
	if(xhr_create_file.readyState == 4)
	{
		if(xhr_create_file.status == 200 || xhr_create_file.status == 304)
		{
			if( xhr_create_file.responseText === "ERROR")
			{
				alert("Error while creating file.");
			}
			else
			{
				num_of_files++;
				if( num_of_files == 0 )
				{
					var execution_data = document.getElementById("execution_data");
					execution_data.style.display = "none";
				}
				else if( num_of_files > 0 )
				{
					var execution_data = document.getElementById("execution_data");
					execution_data.style.display = "block";
				}
				var cmpltable = document.getElementById("comp_files");
				var ftab = document.createElement("li");
				ftab.setAttribute("class", "active");
				var fdiv = document.createElement("div");
				fdiv.setAttribute("class", "tab-pane fade active in");
				var fanchor = document.createElement("a");
	
				var temp = xhr_create_file.responseText.split("/");
				var file = temp[temp.length - 1];
				var id = file.replace(".", "");
				fanchor.href = "#" + id;
				fanchor.setAttribute("data-toggle","tab");
				fanchor.innerHTML = file;
				ftab.appendChild(fanchor);
				ftab.setAttribute("id", "tab" + id);
				filetabs.appendChild(ftab);
				
				fdiv.setAttribute("id", id);

				for( var i = 0; i < f_id.length; i++ )
				{
					var t1 = document.getElementById("tab" + f_id[i]);
					var t2 = document.getElementById(f_id[i]);
					t1.setAttribute("class", "");
					t2.setAttribute("class", "tab-pane fade");
				}
				
				f_id.push( id );
				var ftext = document.createElement("textarea");
				ftext.setAttribute("class", "form-control");
				ftext.setAttribute("rows", "30");
				ftext.style.resize = "none";
				ftext.setAttribute("id", "t" + id);
				ftab.setAttribute("onclick", "set_w_h();");
				
				
				
				fdiv.appendChild(ftext);
				tabContent.appendChild(fdiv);
				files.push(xhr_create_file.responseText);
				editAreaLoader.init({
					id: "t" + id
					,start_highlight: true
					,allow_toggle: false
					,font_size: "14"
					,language: "en"
					,syntax: lang
					,save_callback: "save_code"
					,toolbar: "search, |,  go_to_line,  |, save, |, select_font"
				});


				var cmpldiv = document.createElement("div");
				cmpldiv.setAttribute("class", "checkbox");
				cmpldiv.setAttribute("style", "padding-left:3%");
				cmpldiv.setAttribute("id", "cmpldiv" + id);
				var cmpllabel = document.createElement("label");
				var cmplch = document.createElement("input");
				cmplch.setAttribute("type", "checkbox");
				cmplch.setAttribute("checked", "checked");
				cmplch.setAttribute("id", "cmplfile" + id);
				cmplch.setAttribute("value", file);
				cmpllabel.appendChild(cmplch);
				cmpllabel.innerHTML += file;
				cmpldiv.appendChild(cmpllabel);
				cmpltable.appendChild(cmpldiv);

				setTimeout(set_w_h, 1000);
			}
		}
	}
}

function delete_file()
{
	var file_name = prompt("Enter the file name.");

	if( file_name == null || file_name == "" )
	{
		return;
	}
	xhr_delete_file = getXmlHttpObject();
	xhr_delete_file.onreadystatechange = delete_file_response;
	encodedCourse = encodeNameAndValue("course", course);
	encodedFile = encodeNameAndValue("file", file_name);
	encodedLang = encodeNameAndValue("lang", lang);
	encodedExcercise = encodeNameAndValue("exercise", exercise);
	encodeSem = encodeNameAndValue("sem", sem);
	xhr_delete_file.open("GET", "../server/studentDeleteFileProgramming.php?" + encodedFile + "&" + encodedCourse + "&" + encodedExcercise + "&" + encodedLang + "&" + encodeSem, true);
	xhr_delete_file.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr_delete_file.send();
}

function delete_file_response()
{
	if(xhr_delete_file.readyState == 4)
	{
		if(xhr_delete_file.status == 200 || xhr_delete_file.status == 304)
		{
			if( xhr_delete_file.responseText === "ERROR")
			{
				alert("Error while deleting file.");
			}
			else
			{
				set_w_h();
				num_of_files--;
				if( num_of_files == 0 )
				{
					var execution_data = document.getElementById("execution_data");
					execution_data.style.display = "none";
				}
				else if( num_of_files > 0 )
				{
					var execution_data = document.getElementById("execution_data");
					execution_data.style.display = "block";
				}
				var temp = xhr_delete_file.responseText.split("/");
				var cmpltable = document.getElementById("comp_files");
				var file = temp[temp.length - 1];
				var id = file.replace(".", "");
				if( files.length == 1 )
				{
					$("#" + id).remove();
					$("#" + "tab" + id).remove();
					f_id = myremove(f_id, id);
					files = myremove(files, xhr_delete_file.responseText);
					var cmpldiv = document.getElementById("cmpldiv" + id);
					cmpltable.removeChild(cmpldiv);
				}
				else
				{
					var flag = false;
					for( var i = 0; i < f_id.length; i++ )
					{
						var t1 = document.getElementById("tab" + f_id[i]);
						var t2 = document.getElementById(f_id[i]);
						if( f_id[i] == id && t1.getAttribute("class") == "active" )
						{
							flag = true;
						}
					}

					$("#" + id).remove();
					$("#" + "tab" + id).remove();
					f_id = myremove(f_id, id);
					files = myremove(files, xhr_delete_file.responseText);
					var cmpldiv = document.getElementById("cmpldiv" + id);
					cmpltable.removeChild(cmpldiv);

					if( flag )
					{
						var temp = files[0].split("/");
						var file = temp[temp.length - 1];
						var temp_id = file.replace(".", "");
						var ftab = document.getElementById("tab" + temp_id);
						var fdiv = document.getElementById(temp_id);
						ftab.setAttribute("class", "active");
						fdiv.setAttribute("class", "tab-pane fade active in");
					}
				}
				
				
			}
		}
		else
		{
			alert("Some error occured.")
		}
	}
}

function getCmplFiles()
{
	var cmplfiles = "";
	var cmplflag = true;
	for( var i = 0; i < f_id.length; i++ )
	{
		var cmplf = document.getElementById( "cmplfile" + f_id[i]);
		if( cmplf.checked )
		{
			if( cmplflag == true )
			{
				cmplfiles = cmplf.value;
				cmplflag = false;
			}
			else
			{
				cmplfiles = cmplfiles + "," + cmplf.value;
			}
		}
	}
	return cmplfiles;
}

function execute()
{
	var main_file = document.getElementById("main_file").value;
	var cmplfiles = getCmplFiles();

	if( main_file == "" )
	{
		alert("Enter name of the file with main function.");
		return;
	}
	xhr_execute = getXmlHttpObject();
	encodedCourse = encodeNameAndValue("course", course);
	encodedFile = encodeNameAndValue("main_file", main_file);
	encodedLang = encodeNameAndValue("lang", lang);
	encodedExcercise = encodeNameAndValue("exercise", exercise);
	encodedSem = encodeNameAndValue("sem", sem);
	encodeCmplFiles = encodeNameAndValue("cmplfiles", cmplfiles);
	encodedBugs = encodeNameAndValue("find_bugs", 0);
	xhr_execute.onreadystatechange = execute_response;
	xhr_execute.open("GET", "../server/studentExecuteProgramming.php?" + encodedCourse + "&" + encodedExcercise + "&" + encodedLang + "&" + encodedFile + "&" + encodeCmplFiles + "&" + encodedSem + "&" + encodedBugs, true);
	xhr_execute.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr_execute.send();
}

function execute_response()
{
	if(xhr_execute.readyState == 4)
	{
		if(xhr_execute.status == 200 || xhr_execute.status == 304)
		{
			if( xhr_execute.responseText === "ERROR")
			{
				alert("Error while executing code, please contact your administrator.");
			}
			else
			{
				var res = xhr_execute.responseText;
				var index = res.indexOf("compile_error");
				var output = document.getElementById("output");
				var heading = document.getElementById("heading");
				if( index === 0 )
				{
					heading.style.color = "red";
					heading.innerHTML = "Compiler Error";
					var error = res.substring(13, res.length);
					error = error.replace(/\n/g, "<br>");
					output.innerHTML = "<b>" + error + "</b>";
				}
				else
				{
					heading.style.color = "black";
					heading.innerHTML = "Result";
					res = res.split("@#$$#@");
					var ouput_str = "";
					for( var i = 1; i < res.length; i++ )
					{
						var runtime_index = res[i-1].indexOf("RUNTIME_ERROR");
						if( runtime_index == 0 )
						{
							ouput_str = ouput_str + "<b style=\"color:red;\">Test Case " + i + ": Runtime Error</b><br><br>";
							var error = res[i-1].substring(13, res[i-1].length);
							var new_line = error.replace(/\n/g, "<br>");
							ouput_str = ouput_str + new_line + "<br><br>";
						}
						else
						{
							var timeout_index = res[i-1].indexOf("@#$@TIMEOUT");
							if( timeout_index == 0 )
							{
								ouput_str = ouput_str + "<b style=\"color:red;\">Test Case " + i + ": TIMEOUT</b><br><br>";
								var timout_error = res[i-1].substring(11, res[i-1].length);
								var new_line = timout_error.replace(/\n/g, "<br>");
								ouput_str = ouput_str + new_line + "<br><br>";
							}
							else
							{
								var not_match_index = res[i-1].indexOf("ERROR@#$@");
								if( not_match_index == 0 )
								{
									ouput_str = ouput_str + "<b style=\"color:red;\">Test Case " + i + ": Wrong Output</b><br><br>";
									var not_match_error = res[i-1].substring(9, res[i-1].length);
									var new_line = not_match_error.replace(/\n/g, "<br>");
									ouput_str = ouput_str + new_line + "<br><br>";
								}
								else
								{
									ouput_str = ouput_str + "<b style=\"color:green;\">Test Case " + i + ":</b><br><br>";
									var new_line = res[i-1].replace(/\n/g, "<br>");
									ouput_str = ouput_str + new_line + "<br><br>";
								}
							}
						}
					}
					output.innerHTML = ouput_str;
				}
				$("#output_row").slideDown("slow");
				document.getElementById("show_output").click();
			}
		}
	}
}

function find_repeated_code()
{
	var num_tokens = parseInt(document.getElementById("num_tokens").value);
	if( num_tokens < 10 )
	{
		num_tokens = 10;
	}
	xhr_repeated_code = getXmlHttpObject();
	xhr_repeated_code.onreadystatechange = find_repeated_code_response;
	encodedCourse = encodeNameAndValue("course", course);
	encodedExcercise = encodeNameAndValue("exercise", exercise);
	encodedSem = encodeNameAndValue("sem", sem);
	encodedLang = encodeNameAndValue("lang", lang);
	encodedNumTokens = encodeNameAndValue("num_tokens", num_tokens);
	xhr_repeated_code.open("GET", "../server/studentFindRepeatedCode.php?" + encodedCourse + "&" + encodedExcercise + "&" + encodedSem + "&" + encodedLang + "&" + encodedNumTokens, true);
	xhr_repeated_code.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr_repeated_code.send();
}


function find_repeated_code_response()
{
	if(xhr_repeated_code.readyState == 4)
	{
		if(xhr_repeated_code.status == 200 || xhr_repeated_code.status == 304)
		{
			if( xhr_repeated_code.responseText === "ERROR")
			{
				alert("Something went wrong, try later.");
			}
			else
			{
				var fram = document.getElementById("repeated");
				fram.src = "http://localhost" + xhr_repeated_code.responseText;
				$("#repeated_code_window").slideDown("slow");
				document.getElementById("show_repeated").click();
			}
		}
		else
		{
			alert("Something went wrong, try later.");
		}
	}
}

function minimize_repeated_div()
{
	$("#frame_repeated_div").slideToggle("slow");
}

function close_repeated_div()
{
	var frame = document.getElementById("repeated");
	frame.src = "";
	$("#repeated_code_window").slideUp("slow");
}

function find_bugs()
{
	xhr_find_bugs = getXmlHttpObject();
	var cmplfiles = getCmplFiles();
	var cmplfiles_array = cmplfiles.split(",");
	encodedCourse = encodeNameAndValue("course", course);
	encodedFile = encodeNameAndValue("main_file", cmplfiles_array[0]);
	encodedLang = encodeNameAndValue("lang", lang);
	encodedExcercise = encodeNameAndValue("exercise", exercise);
	encodedSem = encodeNameAndValue("sem", sem);
	encodeCmplFiles = encodeNameAndValue("cmplfiles", cmplfiles);
	encodedBugs = encodeNameAndValue("find_bugs", 1);
	xhr_find_bugs.onreadystatechange = find_bugs_response;
	xhr_find_bugs.open("GET", "../server/studentExecuteProgramming.php?" + encodedCourse + "&" + encodedExcercise + "&" + encodedLang + "&" + encodedFile + "&" + encodeCmplFiles + "&" + encodedSem + "&" + encodedBugs, true);
	xhr_find_bugs.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr_find_bugs.send();
}

function find_bugs_response()
{
	if( xhr_find_bugs.readyState == 4 )
	{
		if( xhr_find_bugs.status == 200 || xhr_find_bugs.status == 304 )
		{
			var res = xhr_find_bugs.responseText.trim();
			var analysis_div = document.getElementById("analysis");
			if( res == "" )
			{
				analysis_div.innerHTML = "No Bugs Found in the code.";
			}
			else
			{
				analysis_div.innerHTML = res.replace(/\n/g, "<br>");
			}
			$("#analysis_window").slideDown("slow");
			document.getElementById("show_bugs").click();
		}
		else
		{
			alert("Some error occured, contact your administrator.");
		}
	}
}
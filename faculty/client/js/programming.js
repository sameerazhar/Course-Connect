var _t_c = 0;

function show_new_exercise_form()
{
	$("#new_exercise_form").slideToggle("slow");
}

function show_output_div()
{
	var exact_output = document.getElementById("exact_compare");
	var output_div = document.getElementById("output_data");
	if( exact_output.checked )
	{
		output_div.style.display = "none";
	}
	else
	{
		output_div.style.display = "block";
	}
}
from Program import *

class Python(Program):
	def __init__(self, usn, course, assign, file_path, files, main_file, sem):
		Program.__init__(self, usn, course, assign, file_path, files, main_file, sem);

	def display(self):
		Program.display(self);

	def getFilePath(self):
		return self.file_path;

	def compile(self):
		return "COMPILED";

	def staticAnalysis(self):
		analyzed = False;
		output = "";

		try:
			cmd = "pychecker " + self.main_file + " ";
			for f in self.files:
				cmd = cmd + f + " ";
			cmd = cmd + " < ../../../../../questionData/sem" + self.sem + "/" + self.course + "/" + str(self.assign) + "/input1.txt 1>analysis_error.txt";
			cmd = cmd.strip();
			#print(cmd); exit(0);
			#fin = open("../../../../../questionData/sem" + self.sem + "/" + self.course + "/" + str(self.assign) + "/input1.txt", "r");
			#print("../../../../../questionData/sem" + self.sem + "/" + self.course + "/" + str(self.assign) + "/input1.txt");
			#exit(0)
			#print(cmd);
			#exit(0);
			output = os.system(cmd);
		except Exception as e:
			print("Some error occured, contact your administrator.");
			exit(0);
		fd = open("analysis_error.txt", "r");
		error = "";
		line = fd.read();
		while line:
			#line = re.sub(r'/var/www/html/courseconnect/studentData/.*?/.*?/.*?/.*?/(.*?\.py)', r'\1', line.rstrip())
			error = error + line;
			line = fd.read();
		os.remove("analysis_error.txt");
		error = re.sub(r'.*?Warnings\.\.\.(.*)', r'\1', error.strip());
		return error;
		#return "";

	def execute(self):
		conn = pymysql.connect(host="localhost", user="root",passwd="mysql");
		cur = conn.cursor();
		cur.execute("use course_connect");

		query = "SELECT * FROM programming_test_case WHERE course_code=\'" + self.course + "\' and assign_id=" + str(self.assign);
		cur.execute(query);
		test_data = cur.fetchall();

		return_val = "";
		query = "SELECT * FROM programming_exercise WHERE course_code=\'" + self.course + "\' and id=" + str(self.assign);
		cur.execute(query);
		data = cur.fetchall();
		compare_data = data[0];

		for test in test_data:
			try:
				cmd = "python3 " + self.main_file + " 1>student_output.txt 2>runtime_error.txt";

				fin = open("../../../../../questionData/sem" + self.sem + "/" + self.course + "/" + str(self.assign) + "/" + test[3], "r");
				out = check_output(cmd, stdin=fin, shell=True);

				output = "";
				output_path = "questionData/sem" + self.sem + "/" + self.course + "/" + str(self.assign) + "/" + test[4];

				ferr = open("runtime_error.txt", "r");
				fout = open("student_output.txt", "r");
				ferr_output = ferr.read();
				ferr_output = ferr_output.strip();
				if( ferr_output != "" ):
					output = "RUNTIME_ERROR";
					output = output + fout.read();
					output = output + ferr_output;

				student_output = fout.read();
				ffout = open("../../../../../" + output_path, "r");
				expected_output = ffout.read();

				ordered = False;
				noise = False;
				case_sensitive = False;
				delimiter = compare_data[6];
				float_diff = compare_data[7];

				if( compare_data[8] == 1 ):
					noise = True;
				if( compare_data[9] == 1 ):
					case_sensitive = True;
				if( compare_data[10] == 1 ):
					ordered = True;

				if( compare_data[5] == 1 ):
					res = self.exact_compare(student_output, expected_output);
					if( res == True ):
						output = student_output;
					else:
						output = "ERROR@#$@" + student_output;

				elif( compare_data[5] == 2 ):
					res = self.float_text_without_noise_words(expected_output, student_output, float_diff, ordered, case_sensitive, delimiter);
					if( res == True ):
						output = student_output;
					else:
						output = "ERROR@#$@" + student_output;

				elif( compare_data[5] == 3 ):
					res = self.float_text_with_noise_words(expected_output, student_output, float_diff, ordered, case_sensitive, delimiter);
					if( res == True ):
						output = student_output;
					else:
						output = "ERROR@#$@" + student_output;

				elif( compare_data[5] == 4 ):
					res = self.first_char_compare(expected_output, student_output, ordered, case_sensitive, delimiter);
					if( res == True ):
						output = student_output;
					else:
						output = "ERROR@#$@" + student_output;

				elif( compare_data[5] == 5 ):
					res = self.number_any_base(expected_output, student_output, ordered, delimiter);
					if( res == True ):
						output = student_output;
					else:
						output = "ERROR@#$@" + student_output;

				elif( compare_data[5] == 6):
					res = self.number_range_comare(expected_output, student_output, delimiter);
					if( res == True ):
						output = student_output;
					else:
						output = "ERROR@#$@" + student_output;

				return_val = return_val + output;
				
				return_val = return_val + "@#$$#@";
			except CalledProcessError as e:
				fout = open("student_output.txt", "r");
				ferr = open("runtime_error.txt", "r");
				return_val = return_val + "RUNTIME_ERROR";
				return_val = return_val + fout.read();
				return_val = return_val + ferr.read();
				return_val = return_val + "@#$$#@";
			except Exception as e:
				fout = open("student_output.txt", "r");
				ferr = open("runtime_error.txt", "r");
				return_val = return_val + "RUNTIME_ERROR";
				return_val = return_val + fout.read();
				return_val = return_val + ferr.read();
				return_val = return_val + Exception.__str__(e);
				return_val = return_val + "@#$$#@";

			os.remove("student_output.txt");
			os.remove("runtime_error.txt");

		return return_val;

		return output;

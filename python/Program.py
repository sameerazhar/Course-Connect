from subprocess import check_output, TimeoutExpired, CalledProcessError, STDOUT
import pymysql
import os
import re
from decimal import Decimal

class Program:

	
	def __init__(self, usn, course, assign, file_path, files, main_file, sem):
		self.usn = usn;
		self.course = course;
		self.assign = assign;
		self.file_path = file_path;
		self.main_file = main_file;
		files = files.split(",");
		files.remove(main_file);
		self.files = files;
		self.sem = sem;

	def display(self):
		print(self.usn);
		print(self.course);
		print(self.assign);
		print(self.file_path);
		print(self.files);
		print(self.main_file);

	def exact_compare(self, student_output, expected_output):
		student_output = student_output.strip();
		expected_output = expected_output.strip();
		if( student_output == expected_output ):
			return True;
		else:
			return False;

	def float_text_without_noise_words(self, expected_output, student_output, difference, ordered, caseSensitive, delimiter):
		try:
			difference = Decimal(difference).quantize(Decimal(difference));

			if(not caseSensitive):
				expected_output=expected_output.lower();
				student_output=student_output.lower();
			
			delimit = "[" + delimiter + "]*";

			expected_list=re.split(delimit, expected_output.strip(delimiter));
			student_list=re.split(delimit, student_output.strip(delimiter));

			if( len(expected_list)!= len(student_list)):
				return False;

			re_onlyFloat = re.compile(r'^-?\d*\.?\d+$');
			expected_floating_num = [];
			expected_text = [];
			student_floating_num = [];
			student_text = [];


			if(ordered):
				for i in range(len(expected_list)):
					if(re.search(re_onlyFloat, expected_list[i])):
						if(re.search(re_onlyFloat, student_list[i])):
							expected_floating_num = expected_floating_num + [ Decimal(expected_list[i]) ];
							student_floating_num = student_floating_num + [ Decimal(student_list[i]) ];
						else:
							return False;
					else:
						expected_text = expected_text + [ expected_list[i] ];
						if(re.search(re_onlyFloat, student_list[i])):
							return False;
						else:
							student_text = student_text + [ student_list[i] ];
			else:
				for i in expected_list:
					if(re.search(re_onlyFloat, i)):
						expected_floating_num = expected_floating_num + [ Decimal(i) ];
					else:
						expected_text = expected_text + [ i ];
				for i in student_list:
					if(re.search(re_onlyFloat, i)):
						student_floating_num = student_floating_num + [ Decimal(i) ];
					else:
						student_text = student_text + [ i ];
				expected_floating_num.sort();
				expected_text.sort();
				student_text.sort();
				student_floating_num.sort();
			if(expected_text != student_text):
				return False;
			for i in range(len(expected_floating_num)):
				diff=abs(expected_floating_num[i] - student_floating_num[i]).quantize(Decimal(difference));
				if( diff > difference):
					return False;
			return True;
		except:
			return False;

	def float_text_with_noise_words(self, expected_output, student_output, difference = "0.0000001", ordered=True, caseSensitive=True, delimiter=""):
		try:	
			difference = Decimal(difference).quantize(Decimal(difference));
			if(not caseSensitive):
				expected_output=expected_output.lower();
				student_output=student_output.lower();
			delimit = "[" + delimiter + "]*";

			expected_list=re.split(delimit, expected_output.strip(delimiter));

			expected_floating_num = [];

			
			re_float = re.compile(r'-?\d*\.?\d+');
			re_onlyFloat = re.compile(r'^-?\d*\.?\d+$');
			if(ordered):
				for i in expected_list:
					if(re.search(re_onlyFloat, i)):
						m=re.search(re_float,student_output)
						if(m):
							expected_num = Decimal(i);
							student_num = Decimal(m.group(0));
							diff=abs(expected_num - student_num).quantize(Decimal(difference));
							if( diff > difference):
								return False;
							student_output = student_output[m.end():];
						else:
							return False;
					else:
						result = student_output.find(i);
						if(result==-1):
							return False
						else:
							student_output = student_output[result + len(i):];
			else:	
				student_float = re.findall(re_float, student_output);
				for i in expected_list:
					if(re.search(re_onlyFloat, i)):
						expected_floating_num = expected_floating_num + [ i ];
					else:
						result = student_output.find(i);
						if(result==-1):
							return False
				if(len(expected_floating_num) != len(student_float)):
					return False;
				for i in range(len(expected_floating_num)):
					expected_floating_num[i] = Decimal(expected_floating_num[i]);
					student_float[i] = Decimal(student_float[i]);
				expected_floating_num.sort();
				student_float.sort();
				for i in range(len(expected_floating_num)):
					diff=abs(expected_floating_num[i] - student_float[i]).quantize(Decimal(difference));
					if( diff > difference):
						return False
			return True
		except:
			return False

	def first_char_compare(self, expected_output, student_output, ordered=True, caseSensitive=False, delimiter=""):
		try:
			if( not caseSensitive):
				expected_output=expected_output.lower();
				student_output=student_output.lower();
			delimit = "[" + delimiter + "]*";
			expected_list = re.split(delimit,expected_output.strip(delimiter));
			student_list = re.split(delimit,student_output.strip(delimiter));
			if(len(expected_list) != len(student_list)):
				return False;
			for i in range(len(expected_list)):
				expected_list[i] =  expected_list[i][0];
				student_list[i] = student_list[i][0];
			if(ordered==False):
				expected_list = expected_list.sort();
				student_list = student_list.sort();
			if(expected_list==student_list):
				return True
			else:
				return False
		except:
			return False


	def number_any_base(self, expected_output, student_output, ordered=True, delimiter=""):
		try:
			delimit = "[" + delimiter + "]*";
			expected_list = re.split(delimit,expected_output.strip(delimiter));
			student_list = re.split(delimit,student_output.strip(delimiter));
			if(len(expected_list) != len(student_list)):
				return False;
			for i in range(len(expected_list)):
				if(len(expected_list[i])>1 and expected_list[i][0]=="0" and expected_list[i][1]!="x" and expected_list[i][1]!="X" and expected_list[i][1]!="b" and expected_list[i][1]!="B" and expected_list[i][1]!="."):
					expected_list[i] = int( expected_list[i] , 8);
				else:
					expected_list[i] = int( expected_list[i] , 0);
				if(len(student_list[i])>1 and student_list[i][0]=="0" and student_list[i][1]!="x" and student_list[i][1]!="X" and student_list[i][1]!="b" and student_list[i][1]!="B" and student_list[i][1]!="."):
					student_list[i] = int( student_list[i] , 8);
				else:
					student_list[i] = int( student_list[i] , 0); 
			if(ordered==False):
				expected_list = expected_list.sort();
				student_list = student_list.sort();
			if(expected_list==student_list):
				return True
			else:
				return False
		except:
			return False

	def number_range_comare(self, expected_output, student_output, delimiter=""):
		try:
			re_float = re.compile(r'-?\d*\.?\d+');
			delimit = "[" + delimiter + "]*";
			expected_list=re.split(delimit,expected_output.strip(delimiter));
			student_list = re.findall(re_float, student_output);
			index=0;
			for i in range(len(expected_list)):
				limits = expected_list[i].split("..")
				if(len(limits)==2):
					if ( limits[0]!= "" and limits[1]!= ""):
						if Decimal(student_list[i]) < Decimal(limits[0]) or Decimal(student_list[i]) >= Decimal(limits[1]):
							return False
					elif ( limits[0]!= "" ):
						if Decimal(student_list[i]) < Decimal(limits[0]):
							return False
					elif ( limits[1]!= "" ):
						if Decimal(student_list[i]) >= Decimal(limits[1]):
							return False
				elif(len(limits)==1):
					if Decimal(student_list[i]) >= Decimal(limits[0]):
						return False
			return True
		except:
			return False;
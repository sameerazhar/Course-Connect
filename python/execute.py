import sys

from C import *
from Java import *
from Python import *
from Cpp import *



usn = sys.argv[1];
course = sys.argv[2];
assign = sys.argv[3];
lang = sys.argv[4];
main_file = sys.argv[5];
cmplfiles = sys.argv[6];
sem = sys.argv[7];
find_bugs = sys.argv[8];
conn = pymysql.connect(host="localhost", user="root",passwd="mysql");
cur = conn.cursor();
cur.execute("use course_connect");
query = "SELECT * FROM student_programming WHERE usn=\'" + usn + "\' and course_code=\'" + course + "\' and assign_id=\'" + assign + "\'";

cur.execute(query);

data = cur.fetchall()[0];

if lang == "C":
	prog = C(data[0], data[2], data[1], "studentData/sem" + sem +"/" + usn + "/" + course + "/" + assign + "/", cmplfiles, main_file, sem);
elif lang == "cpp":
	prog = Cpp(data[0], data[2], data[1], "studentData/sem" + sem +"/" + usn + "/" + course + "/" + assign + "/", cmplfiles, main_file, sem);
elif lang == "Java":
	prog = Java(data[0], data[2], data[1], "studentData/sem" + sem +"/" + usn + "/" + course + "/" + assign + "/", cmplfiles, main_file, sem);
elif lang == "Python":
	prog = Python(data[0], data[2], data[1], "studentData/sem" + sem +"/" + usn + "/" + course + "/" + assign + "/", cmplfiles, main_file, sem);


cwd = os.getcwd();
os.chdir("../" + prog.getFilePath());
if( find_bugs == "1" ):
	result = prog.staticAnalysis();
	print(result);
else:
	compiled = prog.compile();
	if( compiled == "COMPILED" ):
		output = prog.execute();
		print(output);
	else:
		print( compiled );
os.chdir(cwd);



cur.close();
conn.close();

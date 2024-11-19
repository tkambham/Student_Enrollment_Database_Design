drop table usersession cascade constraints;
drop table studentuser cascade constraints;
drop table adminuser cascade constraints;
drop table graduateStudent cascade constraints;
drop table underGraduateStudent cascade constraints;
drop table enroll cascade constraints;
drop table prerequisiteCourse cascade constraints;
drop table section cascade constraints;
drop table course;
drop table usertable;

CREATE TABLE usertable (
    username VARCHAR2(20) PRIMARY KEY,
    password VARCHAR2(12) NOT NULL,
    firstname VARCHAR2(20) NOT NULL,
    lastname VARCHAR2(20) NOT NULL,
    usertype VARCHAR2(12) NOT NULL
);


CREATE TABLE usersession (
    sessionid VARCHAR2(32) PRIMARY KEY,
    username VARCHAR2(20) NOT NULL,
    sessiondate DATE,
    FOREIGN KEY (username) REFERENCES usertable(username)
);


CREATE TABLE adminuser (
    username VARCHAR2(20) PRIMARY KEY,
    startdate DATE NOT NULL,
    FOREIGN KEY (username) REFERENCES usertable(username) ON DELETE CASCADE
);

CREATE TABLE studentuser (
    studentID NUMBER PRIMARY KEY,
    age NUMBER(2) NOT NULL,
    address VARCHAR2(50) NOT NULL,
    studenttype VARCHAR2(13) NOT NULL,
    status VARCHAR2(1) NOT NULL,
    username VARCHAR2(20) NOT NULL,
    admissiondate DATE NOT NULL,
    FOREIGN KEY (username) REFERENCES usertable(username) ON DELETE CASCADE
);

CREATE TABLE graduateStudent (
    studentID NUMBER PRIMARY KEY,
    concentration VARCHAR2(20) NOT NULL,
    FOREIGN KEY (studentID) REFERENCES studentuser(studentID) ON DELETE CASCADE
);

CREATE TABLE underGraduateStudent (
    studentID NUMBER PRIMARY KEY,
    standing VARCHAR2(20) NOT NULL,
    FOREIGN KEY (studentID) REFERENCES studentuser(studentID) ON DELETE CASCADE
);

CREATE TABLE course (
    coursenumber NUMBER PRIMARY KEY,
    courseTitle VARCHAR2(35) NOT NULL,
    creditHours NUMBER(1) NOT NULL
);

CREATE TABLE section (
    sectionID VARCHAR2(4) PRIMARY KEY,
    coursenumber NUMBER NOT NULL,
    schedule VARCHAR2(20) NOT NULL,
    semester VARCHAR2(20) NOT NULL,
    enrollmentDeadline DATE NOT NULL,
    capacity NUMBER(3) NOT NULL,
    FOREIGN KEY (coursenumber) REFERENCES course(coursenumber) ON DELETE CASCADE
);

CREATE TABLE enroll (
    studentID NUMBER NOT NULL,
    sectionID VARCHAR2(4) NOT NULL,
    grade VARCHAR2(1) NOT NULL,
    PRIMARY KEY (studentID, sectionID),
    FOREIGN KEY (studentID) REFERENCES studentuser(studentID) ON DELETE CASCADE,
    FOREIGN KEY (sectionID) REFERENCES section(sectionID) ON DELETE CASCADE
);

CREATE TABLE prerequisiteCourse (
    coursenumber NUMBER NOT NULL,
    prerequisitecoursenumber NUMBER NOT NULL,
    PRIMARY KEY (coursenumber, prerequisitecoursenumber),
    FOREIGN KEY (coursenumber) REFERENCES course(coursenumber) ON DELETE CASCADE,
    FOREIGN KEY (prerequisitecoursenumber) REFERENCES course(coursenumber) ON DELETE CASCADE
);



INSERT INTO usertable VALUES ('jdeep', '1234', 'Jane', 'Deep', 'admin');
INSERT INTO usertable VALUES ('ssmith', '2345', 'Steven', 'Smith', 'studentadmin');
INSERT INTO usertable VALUES ('llivingstone', '3456', 'Liam', 'Livingstone', 'student');
INSERT INTO usertable VALUES ('dwarner', '4567', 'David', 'Warner', 'student');
INSERT INTO usertable VALUES ('mlabuschagne', '5678', 'Marnus', 'Labuschagne', 'student');


INSERT INTO adminuser VALUES ('jdeep', to_date('07/25/2023', 'mm/dd/yyyy'));
INSERT INTO studentuser VALUES ('12345678','22','20 S Bryant Ave, Edmond, OK 73034','Undergraduate','N','llivingstone', to_date('08/15/2023', 'mm/dd/yyyy'));
INSERT INTO studentuser VALUES ('12345679','25','320 E Edwards, Edmond, OK 73034','Graduate','N','ssmith', to_date('01/15/2024', 'mm/dd/yyyy'));
INSERT INTO adminuser VALUES ('ssmith', to_date('08/11/2024', 'mm/dd/yyyy'));
INSERT INTO studentuser VALUES ('12345680','23','100 W Campbell St, Edmond, OK 73034','Undergraduate','N','dwarner', to_date('08/15/2023', 'mm/dd/yyyy'));
INSERT INTO studentuser VALUES ('12345681','24','200 N Fretz Ave, Edmond, OK 73034','Graduate','N','mlabuschagne', to_date('01/15/2024', 'mm/dd/yyyy'));


INSERT INTO underGraduateStudent VALUES ('12345678','Junior');
INSERT INTO graduateStudent VALUES ('12345679','Intelligent Systems');
INSERT INTO underGraduateStudent VALUES ('12345680','Senior');
INSERT INTO graduateStudent VALUES ('12345681','Full Stack');


INSERT INTO course VALUES ('1001', 'Algo Design and Implementation', '3');
INSERT INTO course VALUES ('1002', 'Data Structures', '3');
INSERT INTO course VALUES ('1101', 'Database Management', '3');
INSERT INTO course VALUES ('1201', 'Operating Systems', '3');
INSERT INTO course VALUES ('1202', 'Computer Networks', '3');
INSERT INTO course VALUES ('1301', 'Software Engineering I', '3');
INSERT INTO course VALUES ('1302', 'Software Engineering II', '3');
INSERT INTO course VALUES ('1401', 'Graduate Project', '3');
INSERT INTO course VALUES ('1402', 'Thesis', '6');
INSERT INTO course VALUES ('1501', 'Front End Web Development', '3');
INSERT INTO course VALUES ('1502', 'Cloud Web Apps Development', '3');
INSERT INTO course VALUES ('1503', 'Mobile Apps Development', '3');
INSERT INTO course VALUES ('1601', 'Concepts of AI', '3');
INSERT INTO course VALUES ('1602', 'Algos of Machine Learning', '3');
INSERT INTO course VALUES ('1603', 'Computer Application in Statistics', '3');
INSERT INTO course VALUES ('1604', 'Introduction to Robotics', '3');


INSERT INTO section VALUES ('S101', '1001', 'MWF 10:00-11:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S102', '1002', 'MWF 08:00-09:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S103', '1101', 'MWF 13:00-14:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S104', '1201', 'MW 16:00-17:30', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S105', '1202', 'TR 09:00-10:30', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S106', '1301', 'MWF 09:00-10:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S107', '1302', 'TR 10:00-11:30', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S109', '1402', 'W 14:00-16:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S110', '1501', 'TR 12:00-13:30', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S112', '1503', 'MWF 15:00-16:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S114', '1602', 'TR 08:30-10:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');
INSERT INTO section VALUES ('S115', '1603', 'MWF 09:30-10:30', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), '40');

INSERT INTO section VALUES ('S201', '1001', 'TF 09:00-10:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');
INSERT INTO section VALUES ('S202', '1002', 'MW 10:30-12:00', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');
INSERT INTO section VALUES ('S203', '1101', 'TF 12:00-13:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');
INSERT INTO section VALUES ('S204', '1201', 'MWF 08:00-09:00', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');
INSERT INTO section VALUES ('S205', '1202', 'MW 14:00-15:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');
INSERT INTO section VALUES ('S206', '1301', 'TF 10:00-11:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');
INSERT INTO section VALUES ('S209', '1402', 'W 14:00-16:00', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');
INSERT INTO section VALUES ('S210', '1501', 'TR 08:00-09:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');
INSERT INTO section VALUES ('S211', '1502', 'MWF 11:00-12:00', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');
INSERT INTO section VALUES ('S213', '1601', 'MW 12:00-13:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');
INSERT INTO section VALUES ('S116', '1604', 'TF 14:00-15:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), '35');

INSERT INTO section VALUES ('S302', '1002', 'MTr 09:00-10:30', 'Summer 2025', to_date('06/01/2025', 'mm/dd/yyyy'), '20');
INSERT INTO section VALUES ('S310', '1501', 'TF 11:00-12:30', 'Summer 2025', to_date('06/01/2025', 'mm/dd/yyyy'), '20');


INSERT INTO enroll VALUES (12345678, 'S101', 'A');  
INSERT INTO enroll VALUES (12345678, 'S102', 'B'); 
INSERT INTO enroll VALUES (12345678, 'S103', 'A'); 
INSERT INTO enroll VALUES (12345679, 'S101', 'B');  
INSERT INTO enroll VALUES (12345679, 'S106', 'A');  
INSERT INTO enroll VALUES (12345679, 'S114', 'A');
INSERT INTO enroll VALUES (12345680, 'S102', 'C');
INSERT INTO enroll VALUES (12345680, 'S107', 'B');
INSERT INTO enroll VALUES (12345680, 'S110', 'A'); 
INSERT INTO enroll VALUES (12345681, 'S101', 'A');
INSERT INTO enroll VALUES (12345681, 'S110', 'B'); 
INSERT INTO enroll VALUES (12345681, 'S115', 'A');





COMMIT;

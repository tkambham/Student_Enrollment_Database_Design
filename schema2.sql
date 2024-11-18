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
    courseTitle VARCHAR2(20) NOT NULL,
    creditHours NUMBER(1) NOT NULL
);

CREATE TABLE section (
    sectionID NUMBER PRIMARY KEY,
    coursenumber NUMBER NOT NULL,
    schedule VARCHAR2(20) NOT NULL,
    semester VARCHAR2(20) NOT NULL,
    enrollmentDeadline DATE NOT NULL,
    capacity NUMBER(3) NOT NULL,
    FOREIGN KEY (coursenumber) REFERENCES course(coursenumber) ON DELETE CASCADE
);

CREATE TABLE enroll (
    studentID NUMBER NOT NULL,
    sectionID NUMBER NOT NULL,
    grade VARCHAR2(2) NOT NULL,
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




INSERT INTO usertable VALUES ('Jhon23', '1234', 'Jhon', 'Doe', 'student');
INSERT INTO usertable VALUES ('Jane90', '2345', 'Jane', 'Deep', 'admin');
INSERT INTO usertable VALUES ('Mike96', '3456', 'Mike', 'Smith', 'studentadmin');


INSERT INTO studentuser VALUES ('12345678','22','20 S Bryant Ave, Edmond, OK 73034','Undergraduate','N','Jhon23', to_date('12/01/2010', 'mm/dd/yyyy'));
INSERT INTO adminuser VALUES ('Jane90', to_date('10/31/2019', 'mm/dd/yyyy'));
INSERT INTO studentuser VALUES ('12345679','25','320 E Edwards, Edmond, OK 73034','Graduate','N','Mike96', to_date('5/30/2020', 'mm/dd/yyyy'));
INSERT INTO adminuser VALUES ('Mike96', to_date('5/30/2020', 'mm/dd/yyyy'));

COMMIT;

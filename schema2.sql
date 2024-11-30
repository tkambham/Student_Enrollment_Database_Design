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
    studentID VARCHAR2(8) PRIMARY KEY,
    age NUMBER(2) NOT NULL,
    address VARCHAR2(50) NOT NULL,
    studenttype VARCHAR2(13) NOT NULL,
    status VARCHAR2(1) NOT NULL,
    username VARCHAR2(20) NOT NULL,
    admissiondate DATE NOT NULL,
    FOREIGN KEY (username) REFERENCES usertable(username) ON DELETE CASCADE
);

CREATE TABLE graduateStudent (
    studentID VARCHAR2(8) PRIMARY KEY,
    concentration VARCHAR2(20) NOT NULL,
    FOREIGN KEY (studentID) REFERENCES studentuser(studentID) ON DELETE CASCADE
);

CREATE TABLE underGraduateStudent (
    studentID VARCHAR2(8) PRIMARY KEY,
    standing VARCHAR2(20) NOT NULL,
    FOREIGN KEY (studentID) REFERENCES studentuser(studentID) ON DELETE CASCADE
);

CREATE TABLE course (
    coursenumber NUMBER PRIMARY KEY,
    courseTitle VARCHAR2(35) NOT NULL,
    creditHours NUMBER(1) NOT NULL
);

CREATE TABLE section (
    sectionID VARCHAR2(6) PRIMARY KEY,
    coursenumber NUMBER NOT NULL,
    schedule VARCHAR2(20) NOT NULL,
    semester VARCHAR2(20) NOT NULL,
    enrollmentDeadline DATE NOT NULL,
    capacity NUMBER(3) NOT NULL,
    seatsAvailable NUMBER(3) DEFAULT NULL,
    FOREIGN KEY (coursenumber) REFERENCES course(coursenumber) ON DELETE CASCADE
);

CREATE TABLE enroll (
    studentID VARCHAR2(8) NOT NULL,
    sectionID VARCHAR2(6) NOT NULL,
    grade VARCHAR2(1),
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

CREATE OR REPLACE TRIGGER add_seats_available
BEFORE INSERT ON section
FOR EACH ROW
WHEN (new.seatsAvailable IS NULL)
BEGIN
    :new.seatsAvailable := :new.capacity;
END;
/

CREATE OR REPLACE TRIGGER insert_seats_available
BEFORE INSERT ON enroll
FOR EACH ROW
BEGIN
    IF INSERTING THEN
        UPDATE section SET seatsAvailable = seatsAvailable - 1 WHERE sectionID = :new.sectionID;
    END IF;
END;
/

CREATE OR REPLACE TRIGGER delete_seats_available
AFTER DELETE ON enroll
FOR EACH ROW
BEGIN
    IF DELETING THEN
        UPDATE section SET seatsAvailable = seatsAvailable + 1 WHERE sectionID = :old.sectionID;
    END IF;
END;
/

CREATE OR REPLACE PROCEDURE create_student_id(
    lastname IN VARCHAR2,
    studentID OUT VARCHAR2
)
AS
  v_sequence VARCHAR2(8);
  v_initials VARCHAR2(2);
  v_number_part NUMBER;
BEGIN
  v_initials := UPPER(SUBSTR(lastname, 1, 2));
  
  SELECT MAX(studentID) INTO v_sequence FROM studentuser;
  
  IF v_sequence IS NULL THEN
    v_sequence := '123456';
    v_number_part := TO_NUMBER(SUBSTR(v_sequence, 3)) + 1;
  ELSE
    v_number_part := TO_NUMBER(REGEXP_SUBSTR(v_sequence, '\d+', 1, 1)) + 1;
  END IF;
  
  studentID := v_initials || TO_CHAR(v_number_part);
  DBMS_OUTPUT.PUT_LINE('Generated studentID: ' || studentID);
END;
/

COMMIT;

INSERT INTO usertable VALUES ('jdeep', '1234', 'Jane', 'Deep', 'admin');
INSERT INTO usertable VALUES ('ssmith', '2345', 'Steven', 'Smith', 'studentadmin');
INSERT INTO usertable VALUES ('llivingstone', '3456', 'Liam', 'Livingstone', 'student');
INSERT INTO usertable VALUES ('dwarner', '4567', 'David', 'Warner', 'student');
INSERT INTO usertable VALUES ('mlabuschagne', '5678', 'Marnus', 'Labuschagne', 'student');


INSERT INTO adminuser VALUES ('jdeep', to_date('07/25/2023', 'mm/dd/yyyy'));
INSERT INTO studentuser VALUES ('LI123456','22','20 S Bryant Ave, Edmond, OK 73034','Undergraduate','N','llivingstone', to_date('08/15/2023', 'mm/dd/yyyy'));
INSERT INTO studentuser VALUES ('SM123457','25','320 E Edwards, Edmond, OK 73034','Graduate','N','ssmith', to_date('01/15/2024', 'mm/dd/yyyy'));
INSERT INTO adminuser VALUES ('ssmith', to_date('08/11/2024', 'mm/dd/yyyy'));
INSERT INTO studentuser VALUES ('WA123458','23','100 W Campbell St, Edmond, OK 73034','Undergraduate','N','dwarner', to_date('08/15/2023', 'mm/dd/yyyy'));
INSERT INTO studentuser VALUES ('LA123459','24','200 N Fretz Ave, Edmond, OK 73034','Graduate','N','mlabuschagne', to_date('01/15/2024', 'mm/dd/yyyy'));


INSERT INTO underGraduateStudent VALUES ('LI123456','Junior');
INSERT INTO graduateStudent VALUES ('SM123457','Intelligent Systems');
INSERT INTO underGraduateStudent VALUES ('WA123458','Senior');
INSERT INTO graduateStudent VALUES ('LA123459','Full Stack');


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

INSERT INTO prerequisiteCourse VALUES ('1602', '1603');
INSERT INTO prerequisiteCourse VALUES ('1604', '1603');
INSERT INTO prerequisiteCourse VALUES ('1604', '1602');
INSERT INTO prerequisiteCourse VALUES ('1604', '1601');
INSERT INTO prerequisiteCourse VALUES ('1302', '1301');
INSERT INTO prerequisiteCourse VALUES ('1502', '1501');
INSERT INTO prerequisiteCourse VALUES ('1503', '1501');
INSERT INTO prerequisiteCourse VALUES ('1202', '1201');

INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S101', '1001', 'MWF 10:00-11:00', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S102', '1002', 'MWF 08:00-09:00', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S103', '1101', 'MWF 13:00-14:00', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S104', '1201', 'MW 16:00-17:30', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S105', '1202', 'TR 09:00-10:30', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S106', '1301', 'MWF 09:00-10:00', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S107', '1302', 'TR 10:00-11:30', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S109', '1402', 'W 14:00-16:00', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S110', '1501', 'TR 12:00-13:30', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S112', '1503', 'MWF 15:00-16:00', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S114', '1602', 'TR 08:30-10:00', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('23S115', '1603', 'MWF 09:30-10:30', 'Fall 2023', to_date('08/15/2023', 'mm/dd/yyyy'), 40);

INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S201', '1001', 'TF 09:00-10:30', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S202', '1002', 'MW 10:30-12:00', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S203', '1101', 'TF 12:00-13:30', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S204', '1201', 'MWF 08:00-09:00', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S205', '1202', 'MW 14:00-15:30', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S206', '1301', 'TF 10:00-11:30', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S209', '1402', 'W 14:00-16:00', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S210', '1501', 'TR 08:00-09:30', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S211', '1502', 'MWF 11:00-12:00', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S213', '1601', 'MW 12:00-13:30', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S116', '1604', 'TF 14:00-15:30', 'Spring 2024', to_date('01/15/2024', 'mm/dd/yyyy'), 35);

INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S302', '1002', 'MTr 09:00-10:30', 'Summer 2024', to_date('06/01/2024', 'mm/dd/yyyy'), 20);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S310', '1501', 'TF 11:00-12:30', 'Summer 2024', to_date('06/01/2024', 'mm/dd/yyyy'), 20);


INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S101', '1001', 'MWF 10:00-11:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S102', '1002', 'MWF 08:00-09:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S103', '1101', 'MWF 13:00-14:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S104', '1201', 'MW 16:00-17:30', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S105', '1202', 'TR 09:00-10:30', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S106', '1301', 'MWF 09:00-10:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S107', '1302', 'TR 10:00-11:30', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S109', '1402', 'W 14:00-16:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S110', '1501', 'TR 12:00-13:30', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S112', '1503', 'MWF 15:00-16:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S114', '1602', 'TR 08:30-10:00', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('24S115', '1603', 'MWF 09:30-10:30', 'Fall 2024', to_date('08/15/2024', 'mm/dd/yyyy'), 40);

INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S201', '1001', 'TF 09:00-10:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S202', '1002', 'MW 10:30-12:00', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S203', '1101', 'TF 12:00-13:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S204', '1201', 'MWF 08:00-09:00', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S205', '1202', 'MW 14:00-15:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S206', '1301', 'TF 10:00-11:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S209', '1402', 'W 14:00-16:00', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S210', '1501', 'TR 08:00-09:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S211', '1502', 'MWF 11:00-12:00', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S213', '1601', 'MW 12:00-13:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S116', '1604', 'TF 14:00-15:30', 'Spring 2025', to_date('01/15/2025', 'mm/dd/yyyy'), 35);

INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S302', '1002', 'MTr 09:00-10:30', 'Summer 2025', to_date('06/01/2025', 'mm/dd/yyyy'), 20);
INSERT INTO section (sectionID, coursenumber, schedule, semester, enrollmentDeadline, capacity) VALUES ('25S310', '1501', 'TF 11:00-12:30', 'Summer 2025', to_date('06/01/2025', 'mm/dd/yyyy'), 20);


INSERT INTO enroll VALUES ('LI123456', '23S101', 'A');  
INSERT INTO enroll VALUES ('LI123456', '23S107', 'B'); 
INSERT INTO enroll VALUES ('LI123456', '23S112', 'A'); 
INSERT INTO enroll VALUES ('LI123456', '24S204', 'A'); 
INSERT INTO enroll VALUES ('LI123456', '24S210', 'C'); 
INSERT INTO enroll VALUES ('LI123456', '24S211', 'B'); 
INSERT INTO enroll VALUES ('LI123456', '24S102', ''); 
INSERT INTO enroll VALUES ('LI123456', '24S106', ''); 
INSERT INTO enroll VALUES ('LI123456', '24S115', ''); 

INSERT INTO enroll VALUES ('SM123457', '24S210', 'A');  
INSERT INTO enroll VALUES ('SM123457', '24S203', 'B');  
INSERT INTO enroll VALUES ('SM123457', '24S202', 'B');  
INSERT INTO enroll VALUES ('SM123457', '24S102', '');  
INSERT INTO enroll VALUES ('SM123457', '24S107', '');  
INSERT INTO enroll VALUES ('SM123457', '24S104', '');  

INSERT INTO enroll VALUES ('WA123458', '24S103', '');  
INSERT INTO enroll VALUES ('WA123458', '24S102', '');  
INSERT INTO enroll VALUES ('WA123458', '24S104', '');  

INSERT INTO enroll VALUES ('LA123459', '24S202', 'A');  
INSERT INTO enroll VALUES ('LA123459', '24S203', 'B');  
INSERT INTO enroll VALUES ('LA123459', '24S204', 'B');  
INSERT INTO enroll VALUES ('LA123459', '24S102', '');  
INSERT INTO enroll VALUES ('LA123459', '24S105', '');  
INSERT INTO enroll VALUES ('LA123459', '24S104', '');  

commit;
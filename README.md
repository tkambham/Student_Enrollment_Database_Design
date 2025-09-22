<h1>Student Information & Enrollment System Database Design</h1>
<p>This project is a comprehensive web-based Student Information System developed using PHP and an Oracle Database. It provides a dual-interface for students and administrators to manage academic and personal information efficiently. The system handles everything from student enrollment and grade management to detailed academic tracking, with built-in concurrency controls to ensure data integrity in a multi-user environment.</p>

<h3>Technology Stack</h3>
<ul>
  <li>
    Backend: PHP
  </li>
  <li>
    Database: Oracle Database
  </li>
</ul>

<h3>Database Schema Overview</h3>
<p>The database is designed to manage all aspects of student and academic data.</p>
<ul>
  <li>
    Student: Stores all personal and academic details for a student. Each student has a unique StudentID, name, age, type, and status. This entity also serves as the StudentUser for system login.
      <ul>
        <li>
          Graduate Students have a concentration.
        </li>
        <li>
          Undergraduate Students have a standing.
        </li>
      </ul>
  </li>
  <li>
    Course: Contains course information, including a unique CourseNumber, title, and credit hours.
  </li>
  <li>
    Section: Represents a specific offering of a course in a given semester, with details like SectionID, time, enrollment deadline, and capacity. Each section must belong to exactly one course.
  </li>
  <li>
    Enrollment: A junction table that links Students to Sections, storing the grade a student earns for each enrollment.
  </li>
  <li>
    Prerequisite: A self-referencing relationship on the Course table to manage prerequisite requirements. A course can have many prerequisites, and can be a prerequisite for many other courses.
  </li>
</ul>

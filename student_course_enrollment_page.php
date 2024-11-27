<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

$semester = isset($_GET['semester']) ? $_GET['semester'] : '';
$course_number_input = isset($_GET['course_number']) ? $_GET['course_number'] : '';

$sql = "SELECT section.sectionID, 
                course.coursenumber,
                course.courseTitle,
                course.creditHours,
                section.semester,
                section.schedule,
                section.enrollmentDeadline,
                section.capacity,
                section.seatsAvailable ".
        "FROM section " .
        "JOIN course ON section.coursenumber = course.coursenumber ";

if($semester != ''){
    $sql .= " WHERE section.semester = '$semester'";
    if($course_number_input != ''){
      $sql .= " AND course.coursenumber LIKE '%$course_number_input%'";
    }
}
else{
    if($course_number_input != ''){
      $sql .= " WHERE course.coursenumber LIKE '%$course_number_input%'";
    }
    $sql .= "ORDER BY section.enrollmentDeadline DESC";
}


$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("SQL Execution problem.");
}

$results_values = [];
while($value = oci_fetch_array ($cursor)){
    $results_values[] = $value;
}
oci_free_statement($cursor);

$sql2 = "SELECT studentID, username, usertype " .
       "FROM studentview " .
       "WHERE username = (SELECT username FROM usersession WHERE sessionid = '$sessionid')";

$result_array2 = execute_sql_in_oracle ($sql2);
$result2 = $result_array2["flag"];
$cursor2 = $result_array2["cursor"];

if ($result2 == false){
  display_oracle_error_message($cursor2);
  die("SQL Execution problem.");
}

if($values = oci_fetch_array ($cursor2)){
  oci_free_statement($cursor2);

  // saving the values in the variables
    $studentID = $values[0];
    $username = $values[1];
    $usertype = $values[2];
}


$sql3 = "SELECT enroll.sectionID, course.coursenumber, course.courseTitle, course.creditHours " .
       "FROM studentview " .
       "JOIN enroll ON studentview.studentID = enroll.studentID " .
       "JOIN section ON section.sectionID = enroll.sectionID " .
       "JOIN course ON section.coursenumber = course.coursenumber " .
       "WHERE studentview.username = (SELECT username FROM usersession WHERE sessionid = '$sessionid') AND section.semester = 'Spring 2025'";

$result_array3 = execute_sql_in_oracle ($sql3);
$result3 = $result_array3["flag"];
$cursor3 = $result_array3["cursor"];

if ($result3 == false){
  display_oracle_error_message($cursor3);
  die("SQL Execution problem.");
}

$results_values2 = [];
if($values = oci_fetch_array ($cursor3)){
  oci_free_statement($cursor3);
  $results_values2[] = $values;
}

// Here we can generate the content of the welcome page
echo("Hello, $username <br /><br />");

if($usertype == 'student' || $usertype == 'studentadmin'){

  echo "<div style='display: flex; justify-content: space-between; align-items: flex-start;'>";

    // Left Div (Form)
    echo "<div style='width: 45%; padding: 30px; margin-left: 20px;'>";
      echo "<form method='get'>
              <input type='hidden' name='sessionid' value='$sessionid'>
              <label for='semester'>Select a semester:</label>
              <select id='semester' name='semester'>
                  <option value=''>All</option>
                  <option value='Fall 2024'>Fall 2024</option>
                  <option value='Spring 2025'>Spring 2025</option>
                  <option value='Summer 2025'>Summer 2025</option>
              </select>
              <br />
              <label for='course_number'>Enter the course number:</label>
              <input type='text' id='course_number' name='course_number'>
              <br>
              <input type='submit' value='Submit'>
              </form>  
              ";
    echo "</div>";

    // Right Div (Course List)
    if (count($results_values2) > 0) {
      echo "<div style='width: 45%; padding: 30px; margin-right: 20px;'>";
        echo "<h2 style='font-family: Arial, sans-serif; color: #333;'>Summary</h2>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr>";
        echo "<th style = 'padding: 10px'>Section ID</th>";
        echo "<th style = 'padding: 10px'>Course Number</th>";
        echo "<th style = 'padding: 10px'>Course Title</th>";
        echo "<th style = 'padding: 10px'>Credit Hours</th>";
        echo "</tr>";
        foreach ($results_values2 as $values) {
            echo "<tr>";
            echo "<td>{$values[0]}</td>";
            echo "<td>{$values[1]}</td>";
            echo "<td>{$values[2]}</td>";
            echo "<td>{$values[3]}</td>";
            echo "<td><button><a href='student_course_enrollment_action.php?sessionid=$sessionid&sectionID={$values[0]}'>Enroll</a></button></td>";
            echo "</tr>";
        }
        echo "</table>";
      echo "</div>";
    }

  echo "</div>";


    echo "<div style='width: 90%; padding: 30px; margin-right: 20px;'>";
        echo "<h2 style='font-family: Arial, sans-serif; color: #333;'>All Courses</h2>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr>";
        echo "<th style = 'padding: 10px'>Section ID</th>";
        echo "<th style = 'padding: 10px'>Course Number</th>";
        echo "<th style = 'padding: 10px'>Course Title</th>";
        echo "<th style = 'padding: 10px'>Credit Hours</th>";
        echo "<th style = 'padding: 10px'>Semester</th>";
        echo "<th style = 'padding: 10px'>Schedule</th>";
        echo "<th style = 'padding: 10px'>Enroll Deadline</th>";
        echo "<th style = 'padding: 10px'>Capacity</th>";
        echo "<th style = 'padding: 10px'>Seats Available</th>";
        echo "<th style = 'padding: 10px'>Enroll</th>";
        echo "</tr>";
        foreach ($results_values as $values) {
            echo "<tr>";
            echo "<td>{$values[0]}</td>";
            echo "<td>{$values[1]}</td>";
            echo "<td>{$values[2]}</td>";
            echo "<td>{$values[3]}</td>";
            echo "<td>{$values[4]}</td>";
            echo "<td>{$values[5]}</td>";
            echo "<td>{$values[6]}</td>";
            echo "<td>{$values[7]}</td>";
            echo "<td>{$values[8]}</td>";
            echo "<td><form action=\"course_enrollment_action.php?sessionid=$sessionid\"><button>Enroll</button></form></td>";
            echo "</tr>";
        }
        echo "</table>";
    echo "</div>";

    echo '<form method="post" action="student.php?sessionid=' . $sessionid . '" style="text-align: center;">
            <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>';
    echo "<br />";
    }
    else{
      echo "You are not authorized to view this page.";
    }
?>
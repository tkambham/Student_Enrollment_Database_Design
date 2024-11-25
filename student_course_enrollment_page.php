<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$sql = "SELECT section.sectionID, 
                course.coursenumber,
                course.courseTitle,
                course.creditHours,
                section.semester,
                section.schedule,
                section.enrollmentDeadline,
                section.capacity ".
        "FROM section " .
        "JOIN course ON section.coursenumber = course.coursenumber ". 
        "ORDER BY section.enrollmentDeadline DESC";


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

// Here we can generate the content of the welcome page
echo("Hello, $values[1] <br /><br />");

if($usertype == 'student' || $usertype == 'studentadmin'){

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
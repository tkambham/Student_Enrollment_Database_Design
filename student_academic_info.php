<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$sql = 
    "SELECT studentview.studentID, studentview.studenttype, studentview.username, studentview.usertype, ". 
        "COUNT(
            CASE
            WHEN enroll.grade IS NOT NULL THEN course.coursenumber
            ELSE NULL
            END
        ) AS course_count, ". 
        "SUM(
            CASE
            WHEN enroll.grade IS NOT NULL THEN course.creditHours
            ELSE 0
            END
        ) AS total_credits, ". 
        "ROUND(SUM(
            CASE 
                WHEN enroll.grade = 'A' THEN 4 * course.creditHours
                WHEN enroll.grade = 'B' THEN 3 * course.creditHours
                WHEN enroll.grade = 'C' THEN 2 * course.creditHours
                WHEN enroll.grade = 'D' THEN 1 * course.creditHours
                WHEN enroll.grade = 'F' THEN 0 * course.creditHours
                ELSE 0
            END
        ) / 
        NULLIF(SUM(
            CASE 
                WHEN enroll.grade IN ('A', 'B', 'C', 'D', 'F') THEN course.creditHours
                ELSE 0
            END
        ), 0),2) AS GPA ".
    "FROM studentview ".
    "LEFT JOIN enroll ON studentview.studentID = enroll.studentID ".
    "LEFT JOIN section ON enroll.sectionID = section.sectionID ".
    "LEFT JOIN course ON section.coursenumber = course.coursenumber ".
    "WHERE studentview.username = (SELECT username FROM usersession WHERE sessionid = '$sessionid')".
    "GROUP BY studentview.studentID, studentview.studenttype, studentview.username, studentview.usertype"
;

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("SQL Execution problem.");
}

if($values = oci_fetch_array ($cursor)){
  oci_free_statement($cursor);

  // saving the values in the variables
    $studentID = $values[0];
    $studenttype = $values[1];
    $username = $values[2];
    $usertype = $values[3];
    $noofcourses = $values[4];
    $totalcredit = $values[5];
    $GPA = $values[6];
}

// Here we can generate the content of the welcome page
echo("Hello, $username <br /><br />");

if($usertype == 'student' || $usertype == 'studentadmin') {
    echo "<div style='text-align: left; padding: 30px; margin-left: 20px;'>";
    echo "<h2 style='font-family: Arial, sans-serif; color: #333;'>Academic Information</h2>";
    
    echo "<div style='font-family: Arial, sans-serif; color: #555; margin-bottom: 10px;'>";
    echo "<div style='margin-bottom: 10px;'><strong>Student ID        :</strong> $studentID</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Courses Completed :</strong> $noofcourses</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Credit Earned     :</strong> $totalcredit</div>";
    if($GPA == NULL) {
        $GPA = 0.00;
    }
    echo "<div style='margin-bottom: 10px;'><strong>CGPA              :</strong> $GPA</div>";
    echo "</div>";
    
    echo '<form method="post" action="student.php?sessionid=' . $sessionid . '" style="text-align: center;">
            <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>';
    echo "<br />"; 
} else {
    echo "You are not authorized to view this page.";
}
?>
<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

ini_set( "display_errors", 0);  

$username = $_POST["username"];
$studentID = $_POST["studentid"];
$sectionID = $_POST["sectionid"];
$coursenumber = $_POST["coursenumber"];
$enrollmentDeadline = $_POST["enrolldeadline"];
$seatsAvailable = $_POST["seatsavailable"];
$semester = $_POST["semester"];

$timestamp = strtotime($enrollmentDeadline);

$enrollmentDeadline = date("Y-m-d", $timestamp);
$today = date("Y-m-d");

// Check if the student is already enrolled in the course.
if($enrollmentDeadline < $today){
  echo "The enrollment deadline date($enrollmentDeadline) for this course has passed. <br />";
  echo "You cannot enroll in this course. <br />";
  echo '<form method="post" action="student_course_enrollment_page.php?sessionid=' . $sessionid . '" style="text-align: center;">
          <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
        </form>';
  die();
}
else{
    $sql = "SELECT grade FROM enroll ".
           "JOIN section ON enroll.sectionID = section.sectionID ".
           "WHERE enroll.studentID = '$studentID' AND section.coursenumber = '$coursenumber'";
    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    $values = oci_fetch_array ($cursor);
    $grade = $values[0];

    if($values != oci_fetch_array ($cursor) || $grade == 'D' || $grade == 'F'){
      echo "You are already enrolled in this course. <br />";
      echo '<form method="post" action="student_course_enrollment_page.php?sessionid=' . $sessionid . '" style="text-align: center;">
              <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            </form>';
      die();
    }
    else{
        if($seatsAvailable == 0){
          echo "The course is full. <br />";
          echo "You cannot enroll in this course. <br />";
          echo '<form method="post" action="student_course_enrollment_page.php?sessionid=' . $sessionid . '" style="text-align: center;">
                  <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                </form>';
          die();
        }
        else{
          $sqlP = "SELECT prerequisitecoursenumber FROM prerequisiteCourse WHERE coursenumber = '$coursenumber'";
          $result_arrayP = execute_sql_in_oracle($sqlP);
          $resultP = $result_arrayP["flag"];
          $cursorP = $result_arrayP["cursor"];

          $prerequisiteCourses = [];
          while ($valuesP = oci_fetch_array($cursorP)) {
              $prerequisiteCourses[] = $valuesP['prerequisitecoursenumber'];
          }

          $sqlC = "SELECT section.coursenumber FROM enroll ".
              "JOIN section ON enroll.sectionID = section.sectionID ".
              "WHERE enroll.studentID = '$studentID' AND enroll.grade != 'F' AND enroll.grade != ''";
          $result_arrayC = execute_sql_in_oracle($sqlC);
          $resultC = $result_arrayC["flag"];
          $cursorC = $result_arrayC["cursor"];


          $completedCourses = [];
          while ($valuesC = oci_fetch_array($cursorC)) {
              $completedCourses[] = $valuesC['coursenumber'];
          }

          $k = 0;
          foreach ($prerequisiteCourses as $prerequisiteCourse) {
              if (in_array($prerequisiteCourse, $completedCourses)) {
                  $k++; 
              }
          }

          if($k != count($prerequisiteCourses)){
            echo "You have not completed the prerequisite course for this course. <br />";
            echo "</ul>";
            echo "You cannot enroll in this course. <br />";
            echo '<form method="post" action="student_course_enrollment_page.php?sessionid=' . $sessionid . '" style="text-align: center;">
                    <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                  </form>';
            die();
          }
        }
    }
}

// Form the sql string and execute it.
$sql = "INSERT INTO enroll (studentID, sectionID) VALUES ('$studentID', '$sectionID')";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

echo("Hi, $username <br />");

if ($result == false){
  // Error handling interface.
  echo "<B>Deletion Failed.</B> <BR />";

  display_oracle_error_message($cursor);

  die("<i> 

  <form method=\"post\" action=\"student_course_enrollment.php?sessionid=$sessionid\">
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}
else{echo "<B>Enrollment Successful.</B> <BR />";
    echo "You have successfully enrolled in the course: $coursenumber - $coursetitle for the $semester semester. <BR />";

    echo '<form method="post" action="student_course_enrollment_page.php?sessionid=' . $sessionid . '" style="text-align: center;">
            <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>';
    echo "<br />";
}
?>
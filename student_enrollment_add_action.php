<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$connection = oci_connect("gq075", "vmwspp", "gqiannew3:1521/orc.uco.local");
if ($connection == false) {
    $e = oci_error();
    die($e['message']);
}

ini_set( "display_errors", 0);  

$username = $_POST["username"];
$studentID = $_POST["studentid"];
$selectedCourses = $_POST["selected_courses"]; 
$today = date("Y-m-d");

echo $username;
echo "<br />";
echo $studentID;


foreach ($selectedCourses as $sectionID) {
    
  try{
    // Fetch details of the course being selected
    $sql_fetch = "SELECT section.sectionID, 
                          course.coursenumber, 
                          course.courseTitle, 
                          course.creditHours, 
                          section.enrollmentDeadline, 
                          section.seatsAvailable
            FROM section 
            JOIN course ON section.coursenumber = course.coursenumber
            WHERE section.sectionID = '$sectionID'";

    $result_array = execute_sql_in_oracle($sql_fetch);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    if ($result == false) {
        display_oracle_error_message($cursor);
        die("SQL Execution problem.");
    }

    if($values = oci_fetch_array ($cursor)){
      oci_free_statement($cursor);
    
      // saving the values in the variables
      $coursenumber = $values[1];
      $courseTitle = $values[2];
      $creditHours = $values[3];
      $enrollmentDeadline = $values[4];
      $seatsAvailable = $values[5];
    }

    // Convert enrollment deadline to a date object for comparison
    $timestamp = strtotime($enrollmentDeadline);

    $enrollmentDeadline = date("Y-m-d", $timestamp);
    // Check if the student is already enrolled in the course.
    if($enrollmentDeadline < $today){
      echo "<strong>The enrollment deadline date($enrollmentDeadline) for this course $coursenumber has passed.</strong> <br />";
      echo "<em>You cannot enroll in this course.</em> <br />";
      echo "<br />";
      continue;
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
          echo "<strong>You are already enrolled or completed in this course $coursenumber.</strong> <br />";
          echo "<em>You cannot enroll in this course.</em> <br />";
          echo "<br />";
          continue;
        }
        else{
            if($seatsAvailable == 0){
              echo "<strong>No seats available for this course $coursenumber.</strong> <br />";
              echo "<em>You cannot enroll in this course.</em> <br />";      
              echo "<br />";    
              continue;
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
                  "WHERE enroll.studentID = '$studentID' AND enroll.grade != 'F' AND enroll.grade != 'NULL'";
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
                echo "<strong>You have not completed the prerequisite course for this course $coursenumber.</strong> <br />";
                echo "<strong>Please check the prerequisite table on the enrollment page for the course. </strong> <br />";
                echo "<em>You cannot enroll in this course.</em> <br />";
                echo "<br />";
                continue;
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
        echo "You have successfully enrolled in the course: $coursenumber - $courseTitle for the semester. <BR />";
        echo "<br />";
    }

    // Free the resources associated with the cursor.
    oci_commit($connection);
    oci_free_statement($cursor);

    echo "<br />";

  }
  catch(Exception $e){
    oci_rollback($connection);
    echo "<b>Error: </b>" . $e->getMessage();
    echo "<strong>Enrollment Failed.</strong> <br />";
    echo "<em>There was an error enrolling in the course. Please try again.</em> <br />";
    echo '<form method="post" action="student_course_enrollment_page.php?sessionid=' . $sessionid . '" style="text-align: center;">
              <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            </form>';
    echo "<br />";
  }
}
echo '<form method="post" action="student_course_enrollment_page.php?sessionid=' . $sessionid . '" style="text-align: center;">
              <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            </form>';
echo "<br />";
?>
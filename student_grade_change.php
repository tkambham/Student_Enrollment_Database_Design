<?
    include "verifysession.php";

    $sessionid =$_GET["sessionid"];
    verify_session($sessionid);

    $username = $_GET["username"];

    $sql = "SELECT studentuser.studentID, usertable.firstname, usertable.lastname, studentuser.age, studentuser.address, studentuser.studenttype, studentuser.status, usertable.username, usertable.usertype " .
           "FROM usertable " .
            "JOIN studentuser ON usertable.username = studentuser.username " .
           "WHERE usertable.username = '$username'";
    
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
        $name = $values[1] . " " . $values[2];
        $age = $values[3];
        $address = $values[4];
        $studenttype = $values[5];
        $status = $values[6];
        $username = $values[7];
        $usertype = $values[8];
    }

    $sql2 = "SELECT enroll.sectionID, section.coursenumber, course.courseTitle, enroll.grade, section.semester " .
            "FROM enroll " .
            "JOIN section ON enroll.sectionID = section.sectionID " .
            "JOIN course ON section.coursenumber = course.coursenumber " .
            "WHERE studentID = '$studentID' ".
            "ORDER BY section.semester DESC";

    $result_array2 = execute_sql_in_oracle ($sql2);
    $result2 = $result_array2["flag"];
    $cursor2 = $result_array2["cursor"];


    if ($result2 == false){
        display_oracle_error_message($cursor2);
        die("SQL Execution problem.");
    }

    echo "<div style='display: flex; justify-content: space-between; padding: 30px;'>";
        echo "<div style='flex: 1; padding-right: 20px;'>";
            echo "<h2 style='font-family: Arial, sans-serif; color: #333;'>Student Information</h2>";
            echo "<div style='font-family: Arial, sans-serif; color: #555; margin-bottom: 10px;'>";
                echo "<div style='margin-bottom: 10px;'><strong>Student ID       :</strong> $studentID</div>";
                echo "<div style='margin-bottom: 10px;'><strong>Name             :</strong> $name</div>";
                echo "<div style='margin-bottom: 10px;'><strong>Student Type     :</strong> $studenttype</div>";
                echo "<div style='margin-bottom: 10px;'><strong>Probation Status :</strong> $status</div>";
                echo "<div style='margin-bottom: 10px;'><strong>Username         :</strong> $username</div>";
            echo "</div>"; 

            echo "<h2 style='font-family: Arial, sans-serif; color: #333; margin-top: 30px;'>Grading</h2>";
            echo "<div style='font-family: Arial, sans-serif; color: #555; margin-bottom: 10px;'>";
                echo "<div style='margin-bottom: 10px;'><strong>A       :</strong> 4.0</div>";
                echo "<div style='margin-bottom: 10px;'><strong>B       :</strong> 3.0</div>";
                echo "<div style='margin-bottom: 10px;'><strong>C       :</strong> 2.0</div>";
                echo "<div style='margin-bottom: 10px;'><strong>D       :</strong> 1.0</div>";
                echo "<div style='margin-bottom: 10px;'><strong>F       :</strong> 0.0</div>";
            echo "</div>";
        echo "</div>"; 
        
        echo "<div style='flex: 1; padding-left: 20px;'>";
        echo "<h2 style='font-family: Arial, sans-serif; color: #333;'>Grades</h2>";

        echo "<table style='font-family: Arial, sans-serif; color: #555; width: 100%; margin-bottom: 10px;'>";
        echo "<tr>";
        echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Section ID</th>";
        echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Semester</th>";
        echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Course ID</th>";
        echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Course Title</th>";
        echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Grade</th>";
        echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Update</th>";
        echo "</tr>";

        while($values = oci_fetch_array($cursor2)){
            $sectionID = $values[0];
            $courseID = $values[1];
            $title = $values[2];
            $grade = $values[3];
            $semester = $values[4];

            echo "<form method=\"post\" action=\"student_grade_change_action.php?sessionid=$sessionid\">";
            echo "<input type=\"hidden\" name=\"username\" value=\"{$username}\">";
            echo "<input type=\"hidden\" name=\"studentID\" value=\"{$studentID}\">";
            echo "<input type=\"hidden\" name=\"sectionID\" value=\"{$sectionID}\">";
            echo "<input type=\"hidden\" name=\"semester\" value=\"{$semester}\">";
            echo "<input type=\"hidden\" name=\"courseID\" value=\"{$courseID}\">";
            echo "<input type=\"hidden\" name=\"grade\" value=\"{$grade}\">";
            echo "<tr>";
            echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>$sectionID</td>";
            echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>$semester</td>";
            echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>$courseID</td>";
            echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>$title</td>";
            echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'><input type=\"text\" name=\"grade\" value=\"{$grade}\" size=\"3\" maxlength=\"1\"></td>";
            echo '<td style="padding: 10px; border-bottom: 1px solid #ddd;"><input type="submit" value="Update" style="background-color: #FADA5E; color: black; padding: 10px 10px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;"></td>';
            echo "</tr>";
            echo "</form>";
        }
        echo "</table>";
        echo "</div>"; 

    echo "</div>"; 
    echo('<form method="post" action="admin.php?sessionid=' . $sessionid . '" style="text-align: center; margin-top: 20px;">
        <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
        </form>');
    echo("<br />");


?>
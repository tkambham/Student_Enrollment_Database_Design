<?
    include "verifysession.php";

    $sessionid =$_GET["sessionid"];
    verify_session($sessionid);

    $username = $_POST["username"];
    $grade = $_POST["grade"];
    $sectionID = $_POST["sectionID"];
    $studentID = $_POST["studentID"];
    $courseID = $_POST["courseID"];
    $semester = $_POST["semester"];

    $sql0 = "SELECT grade FROM enroll WHERE studentID = '$studentID' AND sectionID = '$sectionID'";

    $result_array0 = execute_sql_in_oracle ($sql0);
    $result0 = $result_array0["flag"];
    $cursor0 = $result_array0["cursor"];

    if ($result0 == false){
        display_oracle_error_message($cursor0);
        die("SQL Execution problem.");
    }

    if($values = oci_fetch_array ($cursor0)){
        oci_free_statement($cursor0);
        $old_grade = $values[0];
    }

    if($old_grade == $grade){
        echo "<h2 style='font-family: Arial, sans-serif; color: #333;'>Please enter a different grade for course number $courseID.</h2>";
        echo "<br>";
        echo "<a href='student_grade_change.php?sessionid=$sessionid&username=$username' style='text-align: center; margin-top: 20px; background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; text-decoration: none;'>Go Back</a>";

        die();
    }

    $sql = "UPDATE enroll SET grade = '$grade' WHERE studentID = '$studentID' AND sectionID = '$sectionID'";

    $result_array = execute_sql_in_oracle ($sql);

    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    if ($result == false){
        display_oracle_error_message($cursor);
        echo "<B>Update Failed.</B> <BR />";
        display_oracle_error_message($cursor);
        die("SQL Execution problem.");
    }

    echo "<h2 style='font-family: Arial, sans-serif; color: #333;'>Grade Updated Successfully</h2>";

    echo "<div style='font-family: Arial, sans-serif; color: #555; margin-bottom: 10px;'>";
    echo "<div style='margin-bottom: 10px;'><strong>Student ID       :</strong> $studentID</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Student Name     :</strong> $username</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Course ID        :</strong> $courseID</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Section ID       :</strong> $sectionID</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Semester         :</strong> $semester</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Grade            :</strong> $grade</div>";
    echo "</div>";
    echo "<br>";
    echo "<a href='student_grade_change.php?sessionid=$sessionid&username=$username' style='text-align: center; margin-top: 20px; background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; text-decoration: none;'>Go Back</a>";

    oci_free_statement($cursor);

    
?>
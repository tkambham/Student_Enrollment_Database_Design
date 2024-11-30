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

    echo("Hello, $username, $usertype <br /><br />");
    echo "<div style='text-align: left; padding: 30px; margin-left: 20px;'>";
    echo "<h2 style='font-family: Arial, sans-serif; color: #333;'>Personal Information</h2>";

    echo "<div style='font-family: Arial, sans-serif; color: #555; margin-bottom: 10px;'>";
    echo "<div style='margin-bottom: 10px;'><strong>Student ID       :</strong> $studentID</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Name             :</strong> $name</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Age              :</strong> $age</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Address          :</strong> $address</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Student Type     :</strong> $studenttype</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Probation Status :</strong> $status</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Username         :</strong> $username</div>";

    echo "</div>"; 

    echo "<h2 style='font-family: Arial, sans-serif; color: #333;'>Grades</h2>";

    echo "<table style='font-family: Arial, sans-serif; color: #555; margin-bottom: 10px;'>";
    echo "<tr>";
    echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Section ID</th>";
    echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Semester</th>";
    echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Course ID</th>";
    echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Course Title</th>";
    echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Grade</th>";
    echo "<th style='padding: 10px; border-bottom: 1px solid #ddd;'>Update</th>";
    echo "</tr>";

    while($values = oci_fetch_array ($cursor2)){
        $sectionID = $values[0];
        $courseID = $values[1];
        $title = $values[2];
        $grade = $values[3];
        $semester = $values[4];

        echo "<tr>";
        echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>$sectionID</td>";
        echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>$semester</td>";
        echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>$courseID</td>";
        echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>$title</td>";
        echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>$grade</td>";
        echo "</tr>";
    }

?>
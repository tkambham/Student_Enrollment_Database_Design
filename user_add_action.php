<?
    include "verifysession.php";

    $sessionid =$_GET["sessionid"];
    verify_session($sessionid);

    // Suppress PHP auto warnings.
    ini_set( "display_errors", 0);  

    $username = $_POST["uname"];
    if($username == ""){
        $username = "NULL";
        die("Username is required.");
    }

    $firstname = $_POST["fname"];
    $lastname = $_POST["lname"];
    $age = $_POST["age"];
    $address = $_POST["address"];
    $usertype = $_POST["utype"];
    $studenttype = $_POST["stype"];
    $password = $_POST["password"];
    $studentID = '';
    $status = "N";

    if($usertype == "" || $lastname == "" || $firstname == "" || $password == ""){
        $usertype = "NULL";
        $lastname = "NULL";
        $firstname = "NULL";
        $password = "NULL";
        die("Please fill out all required fields.");
    }

    if($usertype == "admin"){
        $startdate = $_POST["sdate"];
        if($startdate == ""){
            $startdate = "NULL";
            die("Start Date is required.");
        }
    }
    else if($usertype == "student"){
        $admissiondate = $_POST["adate"];
        if($admissiondate == ""){
            $admissiondate = "NULL";
            die("Admission Date is required.");
        }
        if($studenttype == ""){
            $studenttype = "NULL";
            die("Student Type is required.");
        }
        if($age == ""){
            $age = "NULL";
            die("Age is required.");
        }
        if($address == ""){
            $address = "NULL";
            die("Address is required.");
        }
    }
    else if($usertype == "studentadmin"){
        $startdate = $_POST["sdate"];
        if($startdate == ""){
            $startdate = "NULL";
            die("Start Date is required.");
        }
        $admissiondate = $_POST["adate"];
        if($admissiondate == ""){
            $admissiondate = "NULL";
            die("Admission Date is required.");
        }
        if($studenttype == ""){
            $studenttype = "NULL";
            die("Student Type is required.");
        }
        if($age == ""){
            $age = "NULL";
            die("Age is required.");
        }
        if($address == ""){
            $address = "NULL";
            die("Address is required.");
        }
    }

    $sqlP = "
            BEGIN 
                create_student_id(:lastname, :studentID);
            END;";

    $stmtP = execute_sql_in_oracle($sqlP);

    oci_bind_by_name($stmtP, ' :lastname', $lname, 20);
    oci_bind_by_name($stmtP, ' :studentID', $studentID, 8);

    $lname = $lastname;
    $studentID = 0;

    echo $studentID;

    $resultP = oci_execute($stmtP);

    if($resultP == false){
        display_oracle_error_message($stmtP);
        die("SQL Execution problem.");
    }

    if($usertype == "admin"){
        $sql = "INSERT INTO usertable (usertype, password, username, firstname, lastname) VALUES ('$usertype', '$password', '$username', '$firstname', '$lastname')";
        $sql2 = "INSERT INTO adminuser (username, startdate) VALUES ('$username', to_date('$startdate', 'mm/dd/yyyy'))";
    }
    else if($usertype == "student"){
        $sql = "INSERT INTO usertable (usertype, password, username, firstname, lastname) VALUES ('$usertype', '$password', '$username', '$firstname', '$lastname')";
        $sql2 = "INSERT INTO studentuser (studentID, age, address, studenttype, status, username, admissiondate) VALUES ( '$studentID', '$age', '$address', '$studenttype', '$status','$username', to_date('$admissiondate', 'mm/dd/yyyy'))";
    }
    else if($usertype == "studentadmin"){
        $sql = "INSERT INTO usertable (usertype, password, username, firstname, lastname) VALUES ('$usertype', '$password', '$username', '$firstname', '$lastname')";
        $sql2 = "INSERT INTO studentuser (studentID, age, address, studenttype, status, username, admissiondate) VALUES ( '$studentID', '$age', '$address', '$studenttype', '$status','$username', to_date('$admissiondate', 'mm/dd/yyyy'))";
        $sql3 = "INSERT INTO adminuser (username, startdate) VALUES ('$username', to_date('$startdate', 'mm/dd/yyyy'))";
    }

    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    $result_array2 = execute_sql_in_oracle ($sql2);
    $result2 = $result_array2["flag"];
    $cursor2 = $result_array2["cursor"];

    $result_array3 = execute_sql_in_oracle ($sql3);
    $result3 = $result_array3["flag"];
    $cursor3 = $result_array3["cursor"];

    if($result == false || $result2 == false || $result3 == false){
        // Error handling interface.
        echo "<B>insertion Failed.</B> <BR />";
        display_oracle_error_message($cursor);
        display_oracle_error_message($cursor2);
        display_oracle_error_message($cursor3);
        die("<i> 
    
            <form method=\"post\" action=\"user_add?sessionid=$sessionid\">
        
            <input type=\"hidden\" value = \"$username\" name=\"uname\">
            <input type=\"hidden\" value = \"$firstname\" name=\"fname\">
            <input type=\"hidden\" value = \"$lastname\" name=\"lname\">
            <input type=\"hidden\" value = \"$admissiondate\" name=\"adate\">
            <input type=\"hidden\" value = \"$startdate\" name=\"sdate\">
            <input type=\"hidden\" value = \"$password\" name=\"password\">
            
            Read the error message, and then try again:
            <input type=\"submit\" value=\"Go Back\">
            </form>
        
            </i>
        ");
    }

    oci_free_statement($cursor);
    oci_free_statement($cursor2);
    oci_free_statement($cursor3);
      
    Header("Location:admin.php?sessionid=$sessionid");


?>
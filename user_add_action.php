<?
    include "verifysession.php";

    $connection = oci_connect ("gq075", "vmwspp", "gqiannew3:1521/orc.uco.local");
    if ($connection == false){
    $e = oci_error(); 
    die($e['message']);
    }

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

    try{
        $sqlP = "
        BEGIN 
            create_student_id(:lastname, :studentID);
        END;
        ";

        $cursor = oci_parse($connection, $sqlP);
        if ($cursor == false) {
            $e = oci_error($connection);  
            die($e['message']);
        }

        oci_bind_by_name($cursor, ':lastname', $lastname, 20); 
        oci_bind_by_name($cursor, ':studentID', $studentID, 8); 

        $result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);
        if ($result == false) {
            $e = oci_error($cursor);  
            die($e['message']);
        }

        echo "Student ID: $studentID";

        oci_commit($connection);
        oci_free_statement($cursor);


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
            if(empty($studentID)){
                $studentID = "NULL";
                die("Couldn't generate Student ID.");
            }
            if ($studenttype == "Undergraduate") {
                $standing = $_POST["standing"];
                if ($standing == "") {
                    $standing = "NULL";
                    die("Standing is required.");
                }
            } elseif ($studenttype == "Graduate") {
                $concentration = $_POST["concentration"];
                if ($concentration == "") {
                    $concentration = "NULL";
                    die("Concentration is required.");
                }
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
            if(empty($studentID)){
                $studentID = "NULL";
                die("Couldn't generate Student ID.");
            }
            if ($studenttype == "Undergraduate") {
                $standing = $_POST["standing"];
                if ($standing == "") {
                    $standing = "NULL";
                    die("Standing is required.");
                }
            } elseif ($studenttype == "Graduate") {
                $concentration = $_POST["concentration"];
                if ($concentration == "") {
                    $concentration = "NULL";
                    die("Concentration is required.");
                }
            }
        }
        echo "Student ID: $studentID";
        echo "Concentration: $concentration";
        echo "Standing: $standing";

        if($usertype == "admin"){
            $sql = "INSERT INTO usertable (usertype, password, username, firstname, lastname) VALUES ('$usertype', '$password', '$username', '$firstname', '$lastname')";
            $sql2 = "INSERT INTO adminuser (username, startdate) VALUES ('$username', to_date('$startdate', 'mm/dd/yyyy'))";
        }
        else if($usertype == "student"){
            $sql = "INSERT INTO usertable (usertype, password, username, firstname, lastname) VALUES ('$usertype', '$password', '$username', '$firstname', '$lastname')";
            $sql2 = "INSERT INTO studentuser (studentID, age, address, studenttype, status, username, admissiondate) VALUES ( '$studentID', '$age', '$address', '$studenttype', '$status','$username', to_date('$admissiondate', 'mm/dd/yyyy'))";
            if($studenttype == "Undergraduate"){
                $sql3 = "INSERT INTO underGraduateStudent (studentID, standing) VALUES ('$studentID', '$standing')";
            }
            else if($studenttype == "Graduate"){
                $sql3 = "INSERT INTO graduateStudent (studentID, concentration) VALUES ('$studentID', '$concentration')";
            }
        }
        else if($usertype == "studentadmin"){
            $sql = "INSERT INTO usertable (usertype, password, username, firstname, lastname) VALUES ('$usertype', '$password', '$username', '$firstname', '$lastname')";
            $sql2 = "INSERT INTO studentuser (studentID, age, address, studenttype, status, username, admissiondate) VALUES ( '$studentID', '$age', '$address', '$studenttype', '$status','$username', to_date('$admissiondate', 'mm/dd/yyyy'))";
            $sql3 = "INSERT INTO adminuser (username, startdate) VALUES ('$username', to_date('$startdate', 'mm/dd/yyyy'))";
            if($studenttype == "Undergraduate"){
                $sql4 = "INSERT INTO underGraduateStudent (studentID, standing) VALUES ('$studentID', '$standing')";
            }
            else if($studenttype == "Graduate"){
                $sql4 = "INSERT INTO graduateStudent (studentID, concentration) VALUES ('$studentID', '$concentration')";
            }
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

        if($usertype == "studentadmin"){
            $result_array4 = execute_sql_in_oracle ($sql4);
            $result4 = $result_array4["flag"];
            $cursor4 = $result_array4["cursor"];
        }

        if($result == false || $result2 == false || $result3 == false || $result4 == false){
            // Error handling interface.
            echo "<B>insertion Failed.</B> <BR />";
            display_oracle_error_message($cursor);
            display_oracle_error_message($cursor2);
            display_oracle_error_message($cursor3);
            display_oracle_error_message($cursor4);
            die("<i> 
        
                <form method=\"post\" action=\"user_add?sessionid=$sessionid\">
            
                <input type=\"hidden\" value = \"$username\" name=\"uname\">
                <input type=\"hidden\" value = \"$firstname\" name=\"fname\">
                <input type=\"hidden\" value = \"$lastname\" name=\"lname\">
                <input type=\"hidden\" value = \"$admissiondate\" name=\"adate\">
                <input type=\"hidden\" value = \"$startdate\" name=\"sdate\">
                <input type=\"hidden\" value = \"$password\" name=\"password\">
                <input type=\"hidden\" value = \"$age\" name=\"age\">
                <input type=\"hidden\" value = \"$address\" name=\"address\">
                <input type=\"hidden\" value = \"$studenttype\" name=\"studenttype\">
                <input type=\"hidden\" value = \"$concentration\" name=\"concentration\">
                <input type=\"hidden\" value = \"$standing\" name=\"standing\">
                <input type=\"hidden\" value = \"$usertype\" name=\"usertype\">
                
                Read the error message, and then try again:
                <input type=\"submit\" value=\"Go Back\">
                </form>
            
                </i>
            ");
        }

        oci_commit($connection);
        oci_free_statement($cursor);
        oci_free_statement($cursor2);
        oci_free_statement($cursor3);
        oci_free_statement($cursor4);
        echo "<b>User added successfully.</b>";
        echo '<form method="post" action="admin.php?sessionid=' . $sessionid . '" style="text-align: center;">
            <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>';
        echo "<br />";
    }
    catch (Exception $e){
        oci_rollback($connection);
        echo "<b>Error: </b>" . $e->getMessage();
        echo '<form method="post" action="admin.php?sessionid=' . $sessionid . '" style="text-align: center;">
            <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>';
        echo "<br />";
        oci_free_statement($cursor);
    }
    finally {
        if (isset($cursor)) oci_free_statement($cursor);
        if (isset($cursor2)) oci_free_statement($cursor2);
        if (isset($cursor3)) oci_free_statement($cursor3);
        if (isset($cursor4)) oci_free_statement($cursor4);
        oci_close($connection);
        Header("Location:admin.php?sessionid=$sessionid");
    }


?>
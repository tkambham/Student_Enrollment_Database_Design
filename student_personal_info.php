<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$sql = "SELECT studentID, firstname, lastname, age, address, studenttype, status, username, usertype " .
       "FROM studentview " .
       "WHERE username = (SELECT username FROM usersession WHERE sessionid = '$sessionid')";

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

// Here we can generate the content of the welcome page
echo("Hello, $username <br /><br />");

if($usertype == 'student' || $usertype == 'studentadmin'){

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

    echo '<form method="post" action="student.php?sessionid=' . $sessionid . '" style="text-align: center;">
            <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>';
    echo "<br />";
    }
    else{
      echo "You are not authorized to view this page.";
    }
?>
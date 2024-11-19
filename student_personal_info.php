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
echo "<br />";
echo("Hello, $username <br /><br />");

if($usertype == 'student' || $usertype == 'studentadmin'){

    echo "<br />";
    echo "<div style = 'margin: 0 auto; text-align: center'><h2>Personal Information</h2></div>";
    echo "<br />";

    echo '<div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 30%; margin: 0 auto;">';

    echo "<div style='margin-bottom: 10px;'><strong>Student ID:</strong> $studentID</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Name:</strong> $name</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Age:</strong> $age</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Address:</strong> $address</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Student Type:</strong> $studenttype</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Probation Status:</strong> $status</div>";
    echo "<div style='margin-bottom: 10px;'><strong>Username:</strong> $username</div>";

    echo "</div>"; 

    echo "<br />";
    echo '<form method="post" action="student.php?sessionid=' . $sessionid . '" style="text-align: center;">
            <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>';
    echo "<br />";
    }
    else{
      echo "You are not authorized to view this page.";
    }
?>
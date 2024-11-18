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
  echo("Student ID       : $studentID <br />");
  echo("Name             : $name <br />");
  echo("Age              : $age <br />");
  echo("Address          : $address <br />");
  echo("Student Type     : $studenttype <br />");
  echo("Probation Status : $status <br />");
  echo("Username         : $username <br />");
  echo("<br />");
  echo("<form method=\"post\" action=\"student.php?sessionid=$sessionid\">
        <input type=\"submit\" value=\"Go Back\">
        </form>");
  echo("<br />");
}
?>
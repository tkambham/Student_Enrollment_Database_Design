<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$sql_studentview = "CREATE OR REPLACE VIEW studentview AS " .
       "SELECT usertable.username, usertable.firstname, usertable.lastname, usertable.usertype, usersession.sessionid, studentuser.studentID, studentuser.age, studentuser.address, studentuser.studenttype, studentuser.status, studentuser.admissiondate ".
       "FROM usertable ".
       "JOIN usersession ON usertable.username = usersession.username ".
       "JOIN studentuser ON usertable.username = studentuser.username";

$result_array = execute_sql_in_oracle($sql_studentview);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false) {
    display_oracle_error_message($cursor);
    die("SQL Execution problem while creating view.");
}
oci_free_statement($cursor);

$sql = "SELECT usertype, username ".
       "FROM studentview ".
       "WHERE sessionid='$sessionid'";

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
  $usertype = $values[0];
  $username = $values[1];
}

// Here we can generate the content of the welcome page
 
echo("Hello, $username <br /><br />");

if($usertype == 'student' || $usertype == 'studentadmin'){
  echo("<br />");
  echo("<br />");
  echo("<form method=\"post\" action=\"student_personal_info.php?sessionid=$sessionid\">
        <input type=\"submit\" value=\"Personal Information\">
        </form>");

  echo("<form method=\"post\" action=\"student_academic_info.php?sessionid=$sessionid\">
        <input type=\"submit\" value=\"Academic Information\">
        </form>");

  echo("<form method=\"post\" action=\"student_course_enrollment_page.php?sessionid=$sessionid\">
        <input type=\"submit\" value=\"Course Enrollment page\">
        </form>");
  echo("<br />");

  echo("<form method=\"post\" action=\"welcomepage.php?sessionid=$sessionid\">
        <input type=\"submit\" value=\"Go Back\">
        </form>");
  echo("<br />");

  echo("Click <A HREF = \"user_passwordchange.php?sessionid=$sessionid&username=$username\">here</A> to Change your Password.");
  echo("<br />");
  echo("<br />");
  echo("Click <A HREF = \"logoutresponsepage.php?sessionid=$sessionid\">here</A> to Logout.");
}
?>
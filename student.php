<?
include "verifysession.php";

// check if the session is valid
$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// get the username and usertype from the session
$sql = "SELECT usertype, username ".
       "FROM studentview ".
       "WHERE sessionid='$sessionid'";

// execute SQL statement
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

// check the result
if ($result == false){
  display_oracle_error_message($cursor);
  die("SQL Execution problem.");
}

// fetch the result
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
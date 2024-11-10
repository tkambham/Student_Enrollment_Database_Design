<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$sql = "SELECT usertable.usertype, usertable.username, usertable.firstname, usertable.lastname, studentuser.admissiondate " .
       "FROM usertable " .
       "JOIN usersession ON usertable.username = usersession.username " .
       "JOIN studentuser ON usertable.username = studentuser.username " .
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
  $firstname = $values[2];
  $lastname = $values[3];
  $admissiondate = $values[4];
}

// Here we can generate the content of the welcome page
 
echo("Hello, $username <br /><br />");

if($usertype == 'student' || $usertype == 'studentadmin'){
  echo("First Name     : $firstname <br />");
  echo("Last  Name     : $lastname <br />");
  echo("User  Name     : $username <br />");
  echo("User  Type     : $usertype <br />");
  echo("Admission Date : $admissiondate <br />");
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
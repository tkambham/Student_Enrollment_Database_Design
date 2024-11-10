<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$q_username = $_GET["username"];

$sql = "SELECT usertable.usertype, usertable.username, usertable.firstname, usertable.lastname, studentuser.admissiondate, adminuser.startdate ".
        "FROM usertable ".
        "LEFT JOIN studentuser ON usertable.username = studentuser.username ".
        "LEFT JOIN adminuser ON usertable.username = adminuser.username ".
       "WHERE usertable.username = '$q_username'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

if (!($values = oci_fetch_array ($cursor))) {
    // Record already deleted by a separate session.  Go back.
    Header("Location:admin.php?sessionid=$sessionid");
}

oci_free_statement($cursor);

$utype = $values[0];
$uname = $values[1];
$fname = $values[2];
$lname = $values[3];
$adate = $values[4];
$sdate = $values[5];
// Displaying the record to be deleted.
if($utype == 'admin'){
    echo("
  <form method=\"post\" action=\"user_delete_action.php?sessionid=$sessionid\">
  UserName (Read-only): <input type=\"text\" readonly value = \"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\"> <br /> 
  Firstname: <input type=\"text\" disabled value = \"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\">  <br />
  Lastname: <input type=\"text\" disabled value = \"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\">  <br />
  UserType: <input type=\"text\" disabled value = \"$utype\" size=\"12\" maxlength=\"12\" name=\"utype\">  <br />
  Start Date: <input type=\"text\" disabled value = \"$sdate\" size=\"10\" maxlength=\"10\" name=\"sdate\">  <br />
  ");
  echo("
  </select>  <input type=\"submit\" value=\"Delete\">
  </form>

  <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
");
}
else if($utype == 'student'){
    echo("
  <form method=\"post\" action=\"user_delete_action.php?sessionid=$sessionid\">
  UserName (Read-only): <input type=\"text\" readonly value = \"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\"> <br /> 
  Firstname: <input type=\"text\" disabled value = \"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\">  <br />
  Lastname: <input type=\"text\" disabled value = \"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\">  <br />
  UserType: <input type=\"text\" disabled value = \"$utype\" size=\"12\" maxlength=\"12\" name=\"utype\">  <br />
  Admission Date: <input type=\"text\" disabled value = \"$adate\" size=\"10\" maxlength=\"10\" name=\"adate\">  <br />
  ");
  echo("
  </select>  <input type=\"submit\" value=\"Delete\">
  </form>

  <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
");
}
else if($utype == 'studentadmin'){
    echo("
  <form method=\"post\" action=\"user_delete_action.php?sessionid=$sessionid\">
  UserName (Read-only): <input type=\"text\" readonly value = \"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\"> <br /> 
  Firstname: <input type=\"text\" disabled value = \"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\">  <br />
  Lastname: <input type=\"text\" disabled value = \"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\">  <br />
  UserType: <input type=\"text\" disabled value = \"$utype\" size=\"12\" maxlength=\"12\" name=\"utype\">  <br />
  Start Date: <input type=\"text\" disabled value = \"$sdate\" size=\"10\" maxlength=\"10\" name=\"sdate\">  <br />
  Admission Date: <input type=\"text\" disabled value = \"$adate\" size=\"10\" maxlength=\"10\" name=\"adate\">  <br />
  ");
  echo("
  </select>  <input type=\"submit\" value=\"Delete\">
  </form>

  <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
");
}   
?>
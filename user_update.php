<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


// Verify where we are from, admin.php or  user_update_action.php.
if (!isset($_POST["update_fail"])) { // from user.php
    // Fetch the record to be updated.
    $q_username = $_GET["username"];
  
    // the sql string
    $sql = "SELECT usertable.usertype, usertable.username, usertable.firstname, usertable.lastname, studentuser.admissiondate, adminuser.startdate ".
        "FROM usertable ".
        "LEFT JOIN studentuser ON usertable.username = studentuser.username ".
        "LEFT JOIN adminuser ON usertable.username = adminuser.username ".
       "WHERE usertable.username = '$q_username'";
    //echo($sql);
  
    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];
  
    if ($result == false){
      display_oracle_error_message($cursor);
      die("Query Failed.");
    }
  
    $values = oci_fetch_array ($cursor);
    oci_free_statement($cursor);
  
    $utype = $values[0];
    $uname = $values[1];
    $fname = $values[2];
    $lname = $values[3];
    $adate = $values[4];
    $sdate = $values[5];
  }
  else { // from emp_update_action.php
    // Obtain values of the record to be updated directly.
    $utype = $_POST["usertype"];
    $uname = $_POST["username"];
    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $adate = $_POST["admissiondate"];
    $sdate = $_POST["startdate"];
  }

// Display the record to be updated.
if($utype == 'admin'){
  echo("
    <form method=\"post\" action=\"user_update_action.php?sessionid=$sessionid\">
    UserName (Read-only): <input type=\"text\" readonly value = \"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\"> <br /> 
    Firstname (Required): <input type=\"text\" value = \"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\">  <br />
    Lastname (Required): <input type=\"text\" value = \"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\">  <br />
    UserType (Required): <input type=\"text\" value = \"$utype\" size=\"12\" maxlength=\"12\" name=\"utype\">  <br />
    Start Date (Required): <input type=\"text\" value = \"$sdate\" size=\"10\" maxlength=\"10\" name=\"sdate\">  <br />
  ");
  echo("
    </select>  <input type=\"submit\" value=\"Update\">
    <input type=\"reset\" value=\"Reset to Original Value\">
    </form>

    <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
    <input type=\"submit\" value=\"Go Back\">
    </form>
  ");
}
else if($utype == 'student'){
  echo("
    <form method=\"post\" action=\"user_update_action.php?sessionid=$sessionid\">
    UserName (Read-only): <input type=\"text\" readonly value = \"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\"> <br /> 
    Firstname (Required): <input type=\"text\" value = \"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\">  <br />
    Lastname (Required): <input type=\"text\" value = \"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\">  <br />
    UserType (Required): <input type=\"text\" value = \"$utype\" size=\"12\" maxlength=\"12\" name=\"utype\">  <br />
    Admission Date (Required): <input type=\"text\" value = \"$adate\" size=\"10\" maxlength=\"10\" name=\"adate\">  <br />
    ");
  echo("
    </select>  <input type=\"submit\" value=\"Update\">
    <input type=\"reset\" value=\"Reset to Original Value\">
    </form>

    <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
    <input type=\"submit\" value=\"Go Back\">
    </form>
  ");
}
else if($utype == 'studentadmin'){
  echo("
    <form method=\"post\" action=\"user_update_action.php?sessionid=$sessionid\">
    UserName (Read-only): <input type=\"text\" readonly value = \"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\"> <br /> 
    Firstname (Required): <input type=\"text\" value = \"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\">  <br />
    Lastname (Required): <input type=\"text\" value = \"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\">  <br />
    UserType (Required): <input type=\"text\" value = \"$utype\" size=\"12\" maxlength=\"12\" name=\"utype\">  <br />
    Start Date (Required): <input type=\"text\" value = \"$sdate\" size=\"10\" maxlength=\"10\" name=\"sdate\">  <br />
    Admission Date (Required): <input type=\"text\" value = \"$adate\" size=\"10\" maxlength=\"10\" name=\"adate\">  <br />
  ");
  echo("
    </select>  <input type=\"submit\" value=\"Update\">
    <input type=\"reset\" value=\"Reset to Original Value\">
    </form>

    <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
    <input type=\"submit\" value=\"Go Back\">
    </form>
  ");
}   

?>
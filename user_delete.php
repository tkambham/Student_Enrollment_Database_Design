<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$q_username = $_GET["username"];

$sql = "SELECT usertable.usertype, 
            usertable.username, 
            usertable.firstname, 
            usertable.lastname, 
            studentuser.admissiondate, 
            studentuser.studentID,
            studentuser.age,
            studentuser.address,
            studentuser.studenttype,
            studentuser.status,
            adminuser.startdate,
            graduateStudent.concentration,
            underGraduateStudent.standing ".
        "FROM usertable ".
        "LEFT JOIN studentuser ON usertable.username = studentuser.username ".
        "LEFT JOIN adminuser ON usertable.username = adminuser.username ".
        "LEFT JOIN graduateStudent ON studentuser.studentID = graduateStudent.studentID ".
        "LEFT JOIN underGraduateStudent ON studentuser.studentID = underGraduateStudent.studentID ".
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
    $studentID = $values[5];
    $age = $values[6];
    $address = $values[7];
    $type = $values[8];
    $status = $values[9];
    $sdate = $values[10];
    $concentration = $values[11];
    $standing = $values[12];
// Displaying the record to be deleted.
if($utype == 'admin'){
  echo("
  <form method=\"post\" action=\"user_delete_action.php?sessionid=$sessionid\" style=\"max-width: 500px; margin: 0 auto; padding: 20px;\">
    <div style=\"margin-bottom: 10px;\">
      <label for=\"uname\" style=\"font-weight: bold;\">UserName (Read-only):</label>
      <input type=\"text\" readonly value=\"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"utype\" style=\"font-weight: bold;\">UserName (Read-only):</label>
      <input type=\"text\" readonly value=\"$utype\" size=\"20\" maxlength=\"20\" name=\"utype\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"fname\" style=\"font-weight: bold;\">Firstname (Read-only):</label>
      <input type=\"text\" readonly value=\"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"lname\" style=\"font-weight: bold;\">Lastname (Read-only):</label>
      <input type=\"text\" readonly value=\"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"sdate\" style=\"font-weight: bold;\">Start Date (Read-only):</label>
      <input type=\"text\" readonly value=\"$sdate\" size=\"10\" maxlength=\"10\" name=\"sdate\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
      <div style=\"text-align: center;\">
          <input type=\"submit\" value=\"Delete\" style=\"background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;\">
      </div>
    </form>
    ");
    echo('<form method="post" action="admin.php?sessionid=' . $sessionid . '" style="text-align: center; margin-top: 20px;">
              <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>');
    echo("<br />");
}
else if($utype == 'student'){
  echo("
  <form method=\"post\" action=\"user_delete_action.php?sessionid=$sessionid\" style=\"max-width: 500px; margin: 0 auto; padding: 20px;\">
    <div style=\"margin-bottom: 10px;\">
      <label for=\"uname\" style=\"font-weight: bold;\">UserName (Read-only):</label>
      <input type=\"text\" readonly value=\"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"utype\" style=\"font-weight: bold;\">UserName (Read-only):</label>
      <input type=\"text\" readonly value=\"$utype\" size=\"20\" maxlength=\"20\" name=\"utype\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"studentID\" style=\"font-weight: bold;\">StudentID (Read-only):</label>
      <input type=\"text\" readonly value=\"$studentID\" size=\"20\" maxlength=\"20\" name=\"studentID\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"type\" style=\"font-weight: bold;\">Student Type (Read-only):</label>
      <input type=\"type\" readonly value=\"$type\" size=\"20\" maxlength=\"20\" name=\"type\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"fname\" style=\"font-weight: bold;\">Firstname (Read-only):</label>
      <input type=\"text\" readonly value=\"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"lname\" style=\"font-weight: bold;\">Lastname (Read-only):</label>
      <input type=\"text\" readonly value=\"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"age\" style=\"font-weight: bold;\">Age (Read-only):</label>
      <input type=\"text\" readonly value=\"$age\" size=\"3\" maxlength=\"3\" name=\"age\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"address\" style=\"font-weight: bold;\">Address (Read-only):</label>
      <input type=\"text\" readonly value=\"$address\" size=\"30\" maxlength=\"30\" name=\"address\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    <div style=\"margin-bottom: 10px;\">
      <label for=\"adate\" style=\"font-weight: bold;\">Admission Date (Read-only):</label>
      <input type=\"text\" readonly value=\"$adate\" size=\"10\" maxlength=\"10\" name=\"adate\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    ");
    if ($type == 'Graduate') {
    echo("
    <div style=\"margin-bottom: 10px;\">
      <label for=\"concentration\" style=\"font-weight: bold;\">Concentration:</label>
      <input type=\"text\" readonly value=\"$concentration\" size=\"10\" maxlength=\"10\" name=\"concentration\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
    </div>
    ");
    } 
    elseif ($type == 'Undergraduate') {
    echo("
      <div style=\"margin-bottom: 10px;\">
        <label for=\"standing\" style=\"font-weight: bold;\">Standing:</label>
        <input type=\"text\" readonly value=\"$standing\" size=\"10\" maxlength=\"10\" name=\"standing\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      ");
      } 
    echo("
      <div style=\"text-align: center;\">
          <input type=\"submit\" value=\"Delete\" style=\"background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;\">
      </div>
    </form>
    ");
    echo('<form method="post" action="admin.php?sessionid=' . $sessionid . '" style="text-align: center; margin-top: 20px;">
              <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>');
    echo("<br />");
}
else if($utype == 'studentadmin'){
    echo("
    <form method=\"post\" action=\"user_delete_action.php?sessionid=$sessionid\" style=\"max-width: 500px; margin: 0 auto; padding: 20px;\">
      <div style=\"margin-bottom: 10px;\">
        <label for=\"uname\" style=\"font-weight: bold;\">UserName (Read-only):</label>
        <input type=\"text\" readonly value=\"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      <div style=\"margin-bottom: 10px;\">
        <label for=\"utype\" style=\"font-weight: bold;\">UserName (Read-only):</label>
        <input type=\"text\" readonly value=\"$utype\" size=\"20\" maxlength=\"20\" name=\"utype\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      <div style=\"margin-bottom: 10px;\">
        <label for=\"studentID\" style=\"font-weight: bold;\">StudentID (Read-only):</label>
        <input type=\"text\" readonly value=\"$studentID\" size=\"20\" maxlength=\"20\" name=\"studentID\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      <div style=\"margin-bottom: 10px;\">
        <label for=\"type\" style=\"font-weight: bold;\">Student Type (Read-only):</label>
        <input type=\"type\" readonly value=\"$type\" size=\"20\" maxlength=\"20\" name=\"type\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      <div style=\"margin-bottom: 10px;\">
        <label for=\"fname\" style=\"font-weight: bold;\">Firstname (Read-only):</label>
        <input type=\"text\" readonly value=\"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      <div style=\"margin-bottom: 10px;\">
        <label for=\"lname\" style=\"font-weight: bold;\">Lastname (Read-only):</label>
        <input type=\"text\" readonly value=\"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      <div style=\"margin-bottom: 10px;\">
        <label for=\"age\" style=\"font-weight: bold;\">Age (Read-only):</label>
        <input type=\"text\" readonly value=\"$age\" size=\"3\" maxlength=\"3\" name=\"age\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      <div style=\"margin-bottom: 10px;\">
        <label for=\"address\" style=\"font-weight: bold;\">Address (Read-only):</label>
        <input type=\"text\" readonly value=\"$address\" size=\"30\" maxlength=\"30\" name=\"address\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      <div style=\"margin-bottom: 10px;\">
        <label for=\"sdate\" style=\"font-weight: bold;\">Start Date (Read-only):</label>
        <input type=\"text\" readonly value=\"$sdate\" size=\"10\" maxlength=\"10\" name=\"sdate\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      <div style=\"margin-bottom: 10px;\">
        <label for=\"adate\" style=\"font-weight: bold;\">Admission Date (Read-only):</label>
        <input type=\"text\" readonly value=\"$adate\" size=\"10\" maxlength=\"10\" name=\"adate\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      ");
      if ($type == 'Graduate') {
      echo("
      <div style=\"margin-bottom: 10px;\">
        <label for=\"concentration\" style=\"font-weight: bold;\">Concentration:</label>
        <input type=\"text\" readonly value=\"$concentration\" size=\"10\" maxlength=\"10\" name=\"concentration\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
      </div>
      ");
      } 
      elseif ($type == 'Undergraduate') {
      echo("
        <div style=\"margin-bottom: 10px;\">
          <label for=\"standing\" style=\"font-weight: bold;\">Standing:</label>
          <input type=\"text\" readonly value=\"$standing\" size=\"10\" maxlength=\"10\" name=\"standing\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
        </div>
      ");
      } 
    echo("
      <div style=\"text-align: center;\">
          <input type=\"submit\" value=\"Delete\" style=\"background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;\">
      </div>
    </form>
    ");
    echo('<form method="post" action="admin.php?sessionid=' . $sessionid . '" style="text-align: center; margin-top: 20px;">
              <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>');
    echo("<br />");
}   
?>
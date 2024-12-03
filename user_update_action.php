<?
include "verifysession.php";

$sessionid = $_GET["sessionid"];
verify_session($sessionid);

// Suppress PHP auto warning.
ini_set("display_errors", 0);  

$usertype = $_POST["utype"];
$username = $_POST["uname"];
$firstname = $_POST["fname"];
$lastname = $_POST["lname"];
$admissiondate = $_POST["adate"];
$studentID = $_POST["studentID"];
$age = $_POST["age"];
$address = $_POST["address"];
$type = $_POST["type"];
$status = $_POST["status"];
$startdate = $_POST["sdate"];
$concentration = $_POST["concentration"];
$standing = $_POST["standing"];

// Prepare SQL queries based on user type
if ($usertype == "student") {
    $sql = "UPDATE usertable SET firstname = '$firstname', lastname = '$lastname' WHERE username = '$username'";
    $sql2 = "UPDATE studentuser 
             SET admissiondate = to_date('$admissiondate', 'mm/dd/yyyy'),
             age = '$age',
             address = '$address'
             WHERE username = '$username'";  
    
    if ($type == "Undergraduate") {
        $sql3 = "UPDATE underGraduateStudent SET standing = '$standing' WHERE studentID = '$studentID'";
    } elseif ($type == "Graduate") {
        $sql3 = "UPDATE graduateStudent SET concentration = '$concentration' WHERE studentID = '$studentID'";
    }
} 
elseif ($usertype == "admin") {
    $sql = "UPDATE usertable SET firstname = '$firstname', lastname = '$lastname' WHERE username = '$username'";
    $sql3 = "UPDATE adminuser SET startdate = to_date('$startdate', 'mm/dd/yyyy') WHERE username = '$username'";
} 
elseif ($usertype == "studentadmin") {
    $sql = "UPDATE usertable SET firstname = '$firstname', lastname = '$lastname' WHERE username = '$username'";
    $sql2 = "UPDATE studentuser 
             SET admissiondate = to_date('$admissiondate', 'mm/dd/yyyy'),
             studentID = '$studentID',
             age = '$age',
             address = '$address'
             WHERE username = '$username'";  
    $sql3 = "UPDATE adminuser SET startdate = to_date('$startdate', 'mm/dd/yyyy') WHERE username = '$username'";
}

// Log SQL statements for debugging
error_log($sql);
error_log($sql2);
error_log($sql3);

// Execute SQL queries
$result_array = execute_sql_in_oracle($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if (isset($sql2)) {
    $result_array2 = execute_sql_in_oracle($sql2);
    $result2 = $result_array2["flag"];
    $cursor2 = $result_array2["cursor"];
} else {
    $result2 = true;
}

if (isset($sql3)) {
    $result_array3 = execute_sql_in_oracle($sql3);
    $result3 = $result_array3["flag"];
    $cursor3 = $result_array3["cursor"];
} else {
    $result3 = true;
}

// Check if any query failed
if ($result == false || $result2 == false || $result3 == false) {
    // Error handling interface
    echo "<B>Update Failed.</B> <BR />";
    if ($cursor) {
        display_oracle_error_message($cursor);
    }
    if ($cursor2) {
        display_oracle_error_message($cursor2);
    }
    if ($cursor3) {
        display_oracle_error_message($cursor3);
    }

    // Provide form for retry
    echo("<i> 
        <form method=\"post\" action=\"user_update.php?sessionid=$sessionid\">
            <input type=\"hidden\" value=\"1\" name=\"update_fail\">
            <input type=\"hidden\" value=\"$username\" name=\"username\">
            <input type=\"hidden\" value=\"$firstname\" name=\"firstname\">
            <input type=\"hidden\" value=\"$lastname\" name=\"lastname\">
            <input type=\"hidden\" value=\"$admissiondate\" name=\"admissiondate\">
            <input type=\"hidden\" value=\"$startdate\" name=\"startdate\">
            <input type=\"hidden\" value=\"$studentID\" name=\"studentID\">
            <input type=\"hidden\" value=\"$age\" name=\"age\">
            <input type=\"hidden\" value=\"$address\" name=\"address\">
            <input type=\"hidden\" value=\"$type\" name=\"type\">
            <input type=\"hidden\" value=\"$standing\" name=\"standing\">
            <input type=\"hidden\" value=\"$concentration\" name=\"concentration\">
            <input type=\"hidden\" value=\"$usertype\" name=\"usertype\">
            <input type=\"hidden\" value=\"$status\" name=\"status\">
            <input type=\"submit\" value=\"Go Back\" style=\"background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;\">
        </form>
    </i>");
    die();
}
else {
    // Success message
    echo "<B>Update Successful.</B> <BR />";
    echo("<i> 
        <form method=\"post\" action=\"user_update.php?sessionid=$sessionid\">
            <input type=\"hidden\" value=\"1\" name=\"update_fail\">
            <input type=\"hidden\" value=\"$username\" name=\"username\">
            <input type=\"hidden\" value=\"$firstname\" name=\"firstname\">
            <input type=\"hidden\" value=\"$lastname\" name=\"lastname\">
            <input type=\"hidden\" value=\"$admissiondate\" name=\"admissiondate\">
            <input type=\"hidden\" value=\"$startdate\" name=\"startdate\">
            <input type=\"hidden\" value=\"$studentID\" name=\"studentID\">
            <input type=\"hidden\" value=\"$age\" name=\"age\">
            <input type=\"hidden\" value=\"$address\" name=\"address\">
            <input type=\"hidden\" value=\"$type\" name=\"type\">
            <input type=\"hidden\" value=\"$standing\" name=\"standing\">
            <input type=\"hidden\" value=\"$concentration\" name=\"concentration\">
            <input type=\"hidden\" value=\"$usertype\" name=\"usertype\">
            <input type=\"hidden\" value=\"$status\" name=\"status\">
            <input type=\"submit\" value=\"Go Back\" style=\"background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;\">
        </form>
    </i>");
    die();
}

// Redirect to admin page on success
Header("Location:user_update.php?sessionid=$sessionid");
?>

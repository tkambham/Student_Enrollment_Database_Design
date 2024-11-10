<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// Suppress PHP auto warning.
ini_set( "display_errors", 0);  

$usertype = $_POST["utype"];
$username = $_POST["uname"];
$firstname = $_POST["fname"];
$lastname = $_POST["lname"];
$admissiondate = $_POST["adate"];
$startdate = $_POST["sdate"];

$sql = "UPDATE usertable SET firstname = '$firstname', lastname = '$lastname', usertype = '$usertype' WHERE username = '$username'";
$sql2 = "UPDATE studentuser SET admissiondate = to_date('$admissiondate', 'mm/dd/yyyy') WHERE username = '$username'";
$sql3 = "UPDATE adminuser SET startdate = to_date('$startdate', 'mm/dd/yyyy') WHERE username = '$username'";

// Log SQL statements for debugging
error_log($sql);
error_log($sql2);
error_log($sql3);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

$result_array2 = execute_sql_in_oracle ($sql2);
$result2 = $result_array2["flag"];
$cursor2 = $result_array2["cursor"];

$result_array3 = execute_sql_in_oracle ($sql3);
$result3 = $result_array3["flag"];
$cursor3 = $result_array3["cursor"];

if($result == false || $result2 == false || $result3 == false){
  // Error handling interface.
    echo "<B>Update Failed.</B> <BR />";
    display_oracle_error_message($cursor);
    display_oracle_error_message($cursor2);
    display_oracle_error_message($cursor3);
    die("<i> 

        <form method=\"post\" action=\"user_update?sessionid=$sessionid\">
    
        <input type=\"hidden\" value = \"1\" name=\"update_fail\">
        <input type=\"hidden\" value = \"$username\" name=\"uname\">
        <input type=\"hidden\" value = \"$firstname\" name=\"fname\">
        <input type=\"hidden\" value = \"$lastname\" name=\"lname\">
        <input type=\"hidden\" value = \"$admissiondate\" name=\"adate\">
        <input type=\"hidden\" value = \"$startdate\" name=\"sdate\">
        
        Read the error message, and then try again:
        <input type=\"submit\" value=\"Go Back\">
        </form>
    
        </i>
    ");
    }

    Header("Location:admin.php?sessionid=$sessionid");
?>

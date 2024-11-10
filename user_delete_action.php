<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

ini_set( "display_errors", 0);  

$username = $_POST["uname"];

// Form the sql string and execute it.
$sql = "DELETE FROM usertable WHERE username = '$username'";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
  echo "<B>Deletion Failed.</B> <BR />";

  display_oracle_error_message($cursor);

  die("<i> 

  <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record deleted.  Go back.
Header("Location:admin.php?sessionid=$sessionid");
?>
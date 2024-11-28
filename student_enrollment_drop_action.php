<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

ini_set( "display_errors", 0);  

$username = $_POST["username"];
$studentId = $_POST["studentid"];
$sectionId = $_POST["sectionid"];
$coursetitle = $_POST["coursetitle"];

// Form the sql string and execute it.
$sql = "DELETE FROM enroll WHERE studentID = '$username' AND sectionID = '$sectionId'";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

echo "Hello, $username";

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
else{
  echo "<B>You have successfully dropped from $coursename</B> <BR />";
  echo '<form method="post" action="student_course_enrollment_page.php?sessionid=' . $sessionid . '" style="text-align: center;">
            <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
          </form>';
    echo "<br />";
}

?>
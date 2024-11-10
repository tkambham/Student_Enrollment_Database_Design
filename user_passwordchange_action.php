<?
    include "verifysession.php";

    $sessionid =$_GET["sessionid"];
    verify_session($sessionid);

    // Suppress PHP auto warning.
    ini_set( "display_errors", 0);  

    $username = $_POST["username"];

    $oldpassword = $_POST["oldpassword"];
    $newpassword = $_POST["newpassword"];
    $confirmpassword = $_POST["confirmpassword"];

    $sql = "SELECT password, usertype ".
            "FROM usertable ".
            "WHERE username = '$username'";

    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    if ($result == false){
      display_oracle_error_message($cursor);
      die("Query Failed.");
    }
    $values = oci_fetch_array ($cursor);

    $password = $values[0];
    $usertype = $values[1];
    if ($oldpassword != $password){
      die("Old password is incorrect.");
    }
    if($newpassword == "" || $confirmpassword == "" || $oldpassword == ""){
        die("Password field cannot be empty.");
    }
    if ($newpassword != $confirmpassword){
      die("New password and confirm password do not match.");
    }
    

    $sql = "UPDATE usertable SET password = '$newpassword' WHERE username = '$username'";

    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    if($result == false){
      // Error handling interface.
        echo "<B>Password Change Failed.</B> <BR />";
        display_oracle_error_message($cursor);
        die("<i> 
            <form method=\"post\" action=\"user_passwordchange?sessionid=$sessionid\">
            <input type=\"hidden\" value = \"1\" name=\"update_fail\">
            <input type=\"hidden\" value = \"$username\" name=\"username\">
            Read the error message, and then try again:
            <input type=\"submit\" value=\"Go Back\">
            </form>
            </i>
        ");
    }
    if($usertype == 'student'){
        Header("Location:student.php?sessionid=$sessionid");
    }
    else{
        Header("Location:admin.php?sessionid=$sessionid");
    }
?>
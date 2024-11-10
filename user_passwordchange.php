<?
    include "verifysession.php";

    $sessionid =$_GET["sessionid"];
    verify_session($sessionid);

    $q_username = $_GET["username"];

    $sql = "SELECT password ".
            "FROM usertable ".
            "WHERE username = '$q_username'";

    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    if ($result == false){
      display_oracle_error_message($cursor);
      die("Query Failed.");
    }

    $values = oci_fetch_array ($cursor);
    oci_free_statement($cursor);

    $password = $values[0];

    echo("
      <form method=\"post\" action=\"user_passwordchange_action.php?sessionid=$sessionid\">
      <input type=\"hidden\" value=\"$q_username\" name=\"username\">
      <input type=\"hidden\" value=\"$password\" name=\"password\">
      <table>
      <tr>
        <td>Old Password:</td>
        <td><input type=\"password\" name=\"oldpassword\" size=\"20\"></td>
      </tr>
      <tr>
        <td>New Password:</td>
        <td><input type=\"password\" name=\"newpassword\" size=\"20\"></td>
      </tr>
      <tr>
        <td>Confirm New Password:</td>
        <td><input type=\"password\" name=\"confirmpassword\" size=\"20\"></td>
      </tr>
      </table>
      <input type=\"submit\" value=\"Change Password\">
      <input type=\"reset\" value=\"Reset to Original Value\">
      </form>

      <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
      <input type=\"submit\" value=\"Go Back\">
      </form>
    ")


?>
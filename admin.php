<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$sql = "SELECT usertable.usertype, usertable.username, usertable.firstname, usertable.lastname, adminuser.startdate " .
       "FROM usertable " .
       "JOIN usersession ON usertable.username = usersession.username " .
       "JOIN adminuser ON usertable.username = adminuser.username " .
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
  $startdate = $values[4];
}

// Here we can generate the content of the welcome page
 
echo("Hello, $username <br /><br />");

if($usertype == 'admin' || $usertype == 'studentadmin'){

    echo("
        <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
        UserName: <input type=\"text\" size=\"20\" maxlength=\"12\" name=\"q_username\"> 
        UserType: <input type=\"text\" size=\"12\" maxlength=\"12\" name=\"q_usertype\"> 
        <input type=\"submit\" value=\"Search\">
        </form>

        <form method=\"post\" action=\"welcomepage.php?sessionid=$sessionid\">
        <input type=\"submit\" value=\"Go Back\">
        </form>

        <form method=\"post\" action=\"user_add.php?sessionid=$sessionid\">
        <input type=\"submit\" value=\"Add A New User\">
        </form>
    ");

    // Interpret the query requirements
    $q_username = $_POST["q_username"];
    $q_usertype = $_POST["q_usertype"];

    $whereClause = " 1=1 ";

    if (isset($q_username) and trim($q_username)!= "") { 
    $whereClause .= " and usertable.username like '%$q_username%'"; 
    }

    if (isset($q_usertype) and $q_usertype!= "") { 
    $whereClause .= " and usertable.usertype like '%$q_usertype%'"; 
    }

    echo("User  Name     : $username <br />");
    echo("User  Type     : $usertype <br />");
    echo("Start Date : $startdate <br />");
    echo("<br />");
    echo("<br />");

    $sql = "SELECT usertable.usertype, usertable.username, usertable.firstname, usertable.lastname, studentuser.admissiondate, adminuser.startdate ".
    "FROM usertable ".
    "LEFT JOIN studentuser ON usertable.username = studentuser.username ".
    "LEFT JOIN adminuser ON usertable.username = adminuser.username ".
    "WHERE $whereClause";

    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    if ($result == false){
    display_oracle_error_message($cursor);
    die("Client Query Failed.");
    }

    // Display the query results
    echo "<table border=1>";
    echo "<tr> <th>Firstname</th> <th>Lastname</th> <th>User Name</th> <th>User Type</th> <th>Start Date</th> <th>Admission Date</th> <th>Update</th> <th>Delete</th></tr>";

    // Fetch the result from the cursor one by one
    while ($values = oci_fetch_array ($cursor)){
        $utype = $values[0];
        $uname = $values[1];
        $fname = $values[2];
        $lname = $values[3];
        $adate = $values[4];
        $sdate = $values[5];
        echo("<tr>" . 
            "<td>$fname</td> <td>$lname</td> <td>$uname</td> <td>$utype</td> <td>$sdate</td> <td>$adate</td>".
            " <td> <A HREF=\"user_update.php?sessionid=$sessionid&username=$uname\">Update</A> </td> ".
            " <td> <A HREF=\"user_delete.php?sessionid=$sessionid&username=$uname\">Delete</A> </td> ".
            "</tr>");
        }
    oci_free_statement($cursor);

    echo "</table>";
    }

    echo("<br />");
    echo("Click <A HREF = \"user_passwordchange.php?sessionid=$sessionid&username=$username\">here</A> to Change your Password.");
    echo("<br />");
    echo("<br />");
    echo("Click <A HREF = \"logoutresponsepage.php?sessionid=$sessionid\">here</A> to Logout.");
?>
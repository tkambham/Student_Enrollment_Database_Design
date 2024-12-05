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
        UserType: <select name=\"q_usertype\" size=\"1\">
            <option value=\"\">All</option>
            <option value=\"student\">Student</option>
            <option value=\"admin\">Admin</option>
            <option value=\"studentadmin\">Studentadmin</option>
        </select>
        <!-- <input type=\"text\" size=\"12\" maxlength=\"12\" name=\"q_usertype\"> -->
        Name: <input type=\"text\" size=\"20\" maxlength=\"20\" name=\"q_name\">
        Student ID: <input type=\"text\" size=\"8\" maxlength=\"8\" name=\"q_id\">
        Course Number: <input type=\"text\" size=\"4\" maxlength=\"4\" name=\"q_cnum\">
        Student Type: <select name=\"q_stype\" size=\"1\">
            <option value=\"\">All</option>
            <option value=\"Undergraduate\">Undergraduate</option>
            <option value=\"Graduate\">Graduate</option>
        </select>
        <!-- <input type=\"text\" size=\"13\" maxlength=\"13\" name=\"q_stype\"> -->
        Probation Status: <select name=\"q_pstatus\" size=\"1\">
            <option value=\"\">All</option>
            <option value=\"N\">Active</option>
            <option value=\"P\">Probation</option>
        </select>
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
    $q_name = $_POST["q_name"];
    $q_id = $_POST["q_id"];
    $q_cnum = $_POST["q_cnum"];
    $q_stype = $_POST["q_stype"];
    $q_pstatus = $_POST["q_pstatus"];

    $whereClause = " 1=1 ";

    if (isset($q_username) and trim($q_username)!= "") { 
        $whereClause .= " and usertable.username like '%$q_username%'"; 
    }

    if (isset($q_usertype) and $q_usertype!= "") { 
        $whereClause .= " and usertable.usertype like '%$q_usertype%'"; 
    }

    if (isset($q_name) and trim($q_name)!= "") {
        $whereClause .= " and (usertable.firstname like '%$q_name%' or usertable.lastname like '%$q_name%')"; 
    }

    if (isset($q_id) and trim($q_id)!= "") {
        $whereClause .= " and studentuser.studentID like '%$q_id%'"; 
    }

    if (isset($q_cnum) and trim($q_cnum)!= "") {
        $whereClause .= " and section.coursenumber like '%$q_cnum%'"; 
    }

    if (isset($q_stype) and $q_stype!= "") {
        $whereClause .= " and studentuser.studenttype like '%$q_stype%'"; 
    }

    if (isset($q_pstatus) and $q_pstatus!= "") {
        $whereClause .= " and studentuser.status like '%$q_pstatus%'"; 
    }


    echo("User  Name     : $username <br />");
    echo("User  Type     : $usertype <br />");
    echo("Start Date : $startdate <br />");
    echo("<br />");
    echo("<br />");

    $sql = "SELECT DISTINCT usertable.usertype, usertable.username, usertable.firstname, usertable.lastname, studentuser.admissiondate, studentuser.studentID, adminuser.startdate ".
    "FROM usertable ".
    "LEFT JOIN studentuser ON usertable.username = studentuser.username ".
    "LEFT JOIN adminuser ON usertable.username = adminuser.username ".
    "LEFT JOIN enroll ON studentuser.studentID = enroll.studentID " .
    "LEFT JOIN section ON enroll.sectionID = section.sectionID " .
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
    echo "<tr> <th>Firstname</th> <th>Lastname</th> <th>User Name</th> <th>User Type</th> <th>StudentID</th> <th>Start Date</th> <th>Admission Date</th> <th>Update</th> <th>Delete</th> <th>Update Grade</th></tr>";

    // Fetch the result from the cursor one by one
    while ($values = oci_fetch_array ($cursor)){
        $utype = $values[0];
        $uname = $values[1];
        $fname = $values[2];
        $lname = $values[3];
        $adate = $values[4];
        $sid = $values[5];
        $sdate = $values[5];
        echo("<tr>" . 
            "<td>$fname</td> <td>$lname</td> <td>$uname</td> <td>$utype</td> <td>$sid</td> <td>$sdate</td> <td>$adate</td>".
            " <td> <A HREF=\"user_update.php?sessionid=$sessionid&username=$uname\">Update</A> </td> ".
            " <td> <A HREF=\"user_delete.php?sessionid=$sessionid&username=$uname\">Delete</A> </td> ");
            if ($utype == 'student' || $utype == 'studentadmin') {
                echo(" <td> <A HREF=\"student_grade_change.php?sessionid=$sessionid&username=$uname\">Change</A> </td> ");
            } else {
                echo(" <td> </td> ");
            }
            echo("</tr>");
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
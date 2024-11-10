<?
include "verifysession.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$sql = "select usertable.usertype, usertable.username " .
       "from usertable " .
       "JOIN usersession ON usertable.username = usersession.username " .
       "where sessionid='$sessionid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("SQL Execution problem.");
}

if($values = oci_fetch_array ($cursor)){
  oci_free_statement($cursor);

  // found the client
  $usertype = $values[0];
  $username = $values[1];
}

// Here we can generate the content of the welcome page
 
echo("Hello, $username <br />");

if($usertype == 'admin'){
  echo("Welcome Admin: <br />");
  echo("<UL>
    <LI><A HREF=\"admin.php?sessionid=$sessionid\">Admin</A></LI>
    </UL>");

  echo("<br />");
  echo("<br />");
  echo("Click <A HREF = \"logoutresponsepage.php?sessionid=$sessionid\">here</A> to Logout.");
}
else if($usertype == 'student'){
  echo("Welcome Student: <br />");
  echo("<UL>
    <LI><A HREF=\"student.php?sessionid=$sessionid\">Student</A></LI>
    </UL>");

  echo("<br />");
  echo("<br />");
  echo("Click <A HREF = \"logoutresponsepage.php?sessionid=$sessionid\">here</A> to Logout.");
}

else if($usertype == 'studentadmin'){
  echo("Welcome StudentAdmin: <br />");
  echo("<UL>
    <LI><A HREF=\"admin.php?sessionid=$sessionid\">Admin</A></LI>
    </UL>");
  echo("<UL>
    <LI><A HREF=\"student.php?sessionid=$sessionid\">Student</A></LI>
    </UL>");

  echo("<br />");
  echo("<br />");
  echo("Click <A HREF = \"logoutresponsepage.php?sessionid=$sessionid\">here</A> to Logout.");
}
?>
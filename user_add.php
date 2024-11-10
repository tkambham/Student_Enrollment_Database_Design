<?
    include "verifysession.php";

    $sessionid =$_GET["sessionid"];
    verify_session($sessionid);

    $utype = $_POST["usertype"];
    $uname = $_POST["username"];
    $password = $_POST["password"];
    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $adate = $_POST["admissiondate"];
    $sdate = $_POST["startdate"];

    echo("
        <form method=\"post\" action=\"user_add_action.php?sessionid=$sessionid\">
        UserName (Required): <input type=\"text\" value = \"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\"> <br /> 
        Firstname (Required): <input type=\"text\" value = \"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\">  <br />
        Lastname (Required): <input type=\"text\" value = \"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\">  <br />
        UserType (Required): <input type=\"text\" value = \"$utype\" size=\"12\" maxlength=\"12\" name=\"utype\">  <br />
        Start Date (If Admin (mm/dd/yyyy)): <input type=\"text\" value = \"$sdate\" size=\"10\" maxlength=\"10\" name=\"sdate\">  <br />
        Admission Date (If Student (mm/dd/yyyy)): <input type=\"text\" value = \"$adate\" size=\"10\" maxlength=\"10\" name=\"adate\">  <br />
        Password (Required): <input type=\"password\" value = \"$password\" size=\"12\" maxlength=\"12\" name=\"password\"> <br /> 
    ");

    echo("
    </select>
    <input type=\"submit\" value=\"Add\">
    <input type=\"reset\" value=\"Reset to Original Value\">
    </form>

    <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
    <input type=\"submit\" value=\"Go Back\">
    </form>");

?>
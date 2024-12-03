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
    $age = $_POST["age"];
    $address = $_POST["address"];
    $stype = $_POST["studenttype"];
    $concentration = $_POST["concentration"];
    $standing = $_POST["standing"];

    echo("
        <form method=\"post\" action=\"user_add_action.php?sessionid=$sessionid\" style=\"max-width: 500px; margin: 0 auto; padding: 20px;\">
            <div style=\"margin-bottom: 10px;\">
                <label for=\"fname\" style=\"font-weight: bold;\">Firstname (Required):</label>
                <input type=\"text\" value=\"$fname\" size=\"20\" maxlength=\"20\" name=\"fname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
            </div>
            <div style=\"margin-bottom: 10px;\">
                <label for=\"lname\" style=\"font-weight: bold;\">Lastname (Required):</label>
                <input type=\"text\" value=\"$lname\" size=\"20\" maxlength=\"20\" name=\"lname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
            </div>
            <div style=\"margin-bottom: 10px;\">
                <label for=\"age\" style=\"font-weight: bold;\">Age (Required):</label>
                <input type=\"text\" value=\"$age\" size=\"3\" maxlength=\"3\" name=\"age\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
            </div>
            <div style=\"margin-bottom: 10px;\">
                <label for=\"address\" style=\"font-weight: bold;\">Address (Required):</label>
                <input type=\"text\" value=\"$address\" size=\"30\" maxlength=\"30\" name=\"address\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
            </div>
            <div style=\"margin-bottom: 10px;\">
                <label for=\"utype\" style=\"font-weight: bold;\">UserType (Required):</label>
                <select name=\"utype\" id=\"utype\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\" onchange=\"toggleDateFields()\">
                    <option value='student'>student</option>
                    <option value='admin'>admin</option>
                    <option value='studentadmin'>studentadmin</option>
                </select>
            </div>
            <div id=\"sdateDiv\" style=\"margin-bottom: 10px; display: none;\">
                <label for=\"sdate\" style=\"font-weight: bold;\">Start Date (If Admin - mm/dd/yyyy):</label>
                <input type=\"text\" value=\"$sdate\" size=\"10\" maxlength=\"10\" name=\"sdate\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
            </div>
            <div id=\"adateDiv\" style=\"margin-bottom: 10px; display: none;\">
                <label for=\"adate\" style=\"font-weight: bold;\">Admission Date (If Student - mm/dd/yyyy):</label>
                <input type=\"text\" value=\"$adate\" size=\"10\" maxlength=\"10\" name=\"adate\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
            </div>
            <div id=\"stypeDiv\" style=\"margin-bottom: 10px; display: none;\">
                <label for=\"stype\" style=\"font-weight: bold;\">StudentType (If student or studentadmin):</label>
                <select name=\"stype\" id=\"stype\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\" onchange=\"toggleStandingAndConcentration()\">
                    <option value='Undergraduate'>Undergraduate</option>
                    <option value='Graduate'>Graduate</option>
                </select>
            </div>
            <div id=\"concentrationDiv\" style=\"margin-bottom: 10px; display: none;\">
                <label for=\"concentration\" style=\"font-weight: bold;\">Concentration (Required):</label>
                <input type=\"text\" value=\"$concentration\" size=\"20\" maxlength=\"20\" name=\"concentration\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
            </div>
            <div id=\"standingDiv\" style=\"margin-bottom: 10px; display: none;\">
                <label for=\"standing\" style=\"font-weight: bold;\">Standing (Required):</label>
                <input type=\"text\" value=\"$standing\" size=\"20\" maxlength=\"20\" name=\"standing\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
            </div>
            <div style=\"margin-bottom: 10px;\">
                <label for=\"uname\" style=\"font-weight: bold;\">UserName (Required):</label>
                <input type=\"text\" value=\"$uname\" size=\"20\" maxlength=\"20\" name=\"uname\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
            </div>
            <div style=\"margin-bottom: 10px;\">
                <label for=\"password\" style=\"font-weight: bold;\">Password (Required):</label>
                <input type=\"password\" value=\"$password\" size=\"12\" maxlength=\"12\" name=\"password\" style=\"width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;\">
            </div>
            <div style=\"text-align: center;\">
                <input type=\"submit\" value=\"Add\" style=\"background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;\">
                <input type=\"reset\" value=\"Reset to Original Value\" style=\"background-color: #f44336; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;\">
            </div>
        </form>

        <script type=\"text/javascript\">
            function toggleDateFields() {
                var userType = document.getElementById('utype').value;
                var stypeDiv = document.getElementById('stypeDiv');
                var sdateDiv = document.getElementById('sdateDiv');
                var adateDiv = document.getElementById('adateDiv');
                
                // Reset visibility
                stypeDiv.style.display = 'none';
                sdateDiv.style.display = 'none';
                adateDiv.style.display = 'none';
                
                if (userType === 'studentadmin' || userType === 'student') {
                    stypeDiv.style.display = 'block'; // Show StudentType for student or studentadmin
                    adateDiv.style.display = 'block'; // Show Admission Date for student
                }
                if (userType === 'admin' || userType === 'studentadmin') {
                    sdateDiv.style.display = 'block'; // Show Start Date for admin
                }
            }

            function toggleStandingAndConcentration() {
                var studentType = document.getElementById('stype').value;
                var standingDiv = document.getElementById('standingDiv');
                var concentrationDiv = document.getElementById('concentrationDiv');
                
                if (studentType === 'Undergraduate') {
                    standingDiv.style.display = 'block';
                    concentrationDiv.style.display = 'none';
                } else if (studentType === 'Graduate') {
                    standingDiv.style.display = 'none';
                    concentrationDiv.style.display = 'block';
                }
            }

            window.onload = function() {
                toggleDateFields();
                toggleStandingAndConcentration();
            }
        </script>
    ");

    echo('<form method="post" action="admin.php?sessionid=' . $sessionid . '" style="text-align: center; margin-top: 20px;">
            <input type="submit" value="Go Back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
        </form>');
    echo("<br />");


?>
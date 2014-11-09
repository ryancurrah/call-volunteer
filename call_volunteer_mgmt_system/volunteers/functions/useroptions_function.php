<?
if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

// This function checks the entered data 
// and if valid makes the changes to the database
function changeUserInfo($currentpass, $newpass, $confirmpass, $email, $phone){
$username = $_SESSION['username'];
$userid = $_SESSION['user_id'];

require_once "../db_connector.php";
require_once "functions/formvalidator.php";

open_mysql_connection();

// Find an email address that matches the user entered one,
// store it in the 'email_taken' variable to use when validating.
$email_result = mysql_query('SELECT email FROM users');
while ($row = mysql_fetch_array($email_result)){
	if($email == $row['email']){
		$email_taken = $row['email'];
		break;
	}
}

// Password
if($currentpass != ""){
	// Convert passwords to MD5
	$currentpass = md5($currentpass);
	$newpass = md5($newpass);
	$confirmpass = md5($confirmpass);
	// Call validate function and set param
	$validator = new FormValidator();
	$validator->addValidation("newpass","minlen=5","Minimum password length is 5 characters.");
	$validator->addValidation("newpass","eqelmnt=confirmpass","New password and confirm password does not match.");
	//echo "<center><p>$currentpass, $email, $phone, $username. Password detected!</p></center>";
	
	// Grab current password from database
	$currentpass_query = mysql_query("SELECT password FROM users WHERE username = '$username'");
	$currentpass_result = mysql_fetch_array($currentpass_query);
	// Compare current password with user entered password
	if($currentpass != $currentpass_result['password']){
		echo "<center><p id='error'>Current password incorrect.</p></center>";
	}
	elseif(!$validator->ValidateForm()){
        $error_hash = $validator->GetErrors();
        foreach($error_hash as $inpname => $inp_err){
			echo "<center><p id='error'>$inp_err</p></center>\n";
        }
	}
	else{
		mysql_query("UPDATE users SET password='$newpass' WHERE username='$username' AND user_id ='$userid'");
		echo "<center><p id='success'>Password has been updated successfully.</p></center>";
	}
}

// Email
if($email != ""){
	$validator = new FormValidator();
	$validator->addValidation("email","email","Please input a valid email address.");
	if(!$validator->ValidateForm()){
        $error_hash = $validator->GetErrors();
        foreach($error_hash as $inpname => $inp_err){
			echo "<center><p id='error'>$inp_err</p></center>\n";
        }
	}
	elseif($email == $email_taken){
		echo "<center><p id='error'>Email address is already in use.</p></center>\n";
	}
	
	else{
	mysql_query("UPDATE users SET email='$email' WHERE username='$username' AND user_id ='$userid'");
	echo "<center><p id='success'>Email has been updated successfully.</p></center>";
	}
}

// Phone
if($phone != ""){
	$validator = new FormValidator();
	$validator->addValidation("phone","num","Spaces and non-numeric characters are not aloud for phone numbers.");
	$validator->addValidation("phone","minlen=10","Must enter a 10 digit phone number including the area code.");
	$validator->addValidation("phone","maxlen=10","Must enter a 10 digit phone number including the area code.");
	if(!$validator->ValidateForm()){
        $error_hash = $validator->GetErrors();
        foreach($error_hash as $inpname => $inp_err){
			echo "<center><p id='error'>$inp_err</p></center>\n";
        }
	}
	else{
	mysql_query('UPDATE users SET phone_number="'.$phone.'" WHERE username="'.$username.'" AND user_id ="'.$userid.'"');
	echo "<center><p id='success'>Phone number has been updated successfully.</p></center>";
	}
}
// All fields empty
if($phone == "" && $email == "" && $currentpass == ""){
	echo "<center><p id='error'>You have not entered anything. No changes made.</p></center>";
}
close_mysql_connection();
}
?>
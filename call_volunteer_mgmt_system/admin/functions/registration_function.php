<?php
if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

require_once "../db_connector.php";
require_once "functions/formvalidator.php";

 // Function Name:	repeatUser
 //       Purpose:  makes sure username are not repeated
 //       Accepts:  username
 //       Returns:  true
function repeatUser($username){
open_mysql_connection();
	// Get username data from MySQL 
	$query="SELECT username FROM users";
	$result = mysql_query($query);
	close_mysql_connection();
while($row = mysql_fetch_assoc($result))
{
   if($row['username'] == $username){
	return true;
	break;
   }
}
}

 // Function Name:	repeatEmail
 //       Purpose:  makes sure email address are not repeated
 //       Accepts:  email address
 //       Returns:  true
function repeatEmail($email){
open_mysql_connection();
	// Get email data from MySQL 
	$query="SELECT email FROM users";
	$result = mysql_query($query);
	close_mysql_connection();
while($row = mysql_fetch_assoc($result))
{
   if($row['email'] == $email){
	return true;
	break;
   }
}
}

 // Function Name:	userRegValidator
 //       Purpose:  validates the user registration form for duplicate entrys or missing/incorrect info
 //       Accepts:  registration form variables
 //       Returns:  errors on screen
function userRegValidator($first_name, $last_name, $volunteer_number, $email, $phone_number, $username, $password, $administrator){
 $validator = new FormValidator();
    $validator->addValidation("first_name","req","Please fill in first name.");
	$validator->addValidation("last_name","req","Please fill in last name.");
	$validator->addValidation("volunteer_number","req","Please fill in volunteer number.");
	$validator->addValidation("volunteer_number","num","Spaces and non-numeric characters are not aloud for volunteer numbers.");
    $validator->addValidation("email","req","Please fill in Email.");
	$validator->addValidation("email","email","Please input a valid email address.");
	$validator->addValidation("phone_number","reg","Please fill in a phone number.");
	$validator->addValidation("phone_number","num","Spaces and non-numeric characters are not aloud for phone numbers.");
	$validator->addValidation("phone_number","minlen=10","Must enter a 10 digit phone number including the area code.");
	$validator->addValidation("phone_number","maxlen=10","Must enter a 10 digit phone number including the area code.");
	$validator->addValidation("username","reg","Please fill in a username.");
	$validator->addValidation("username","minlen=4","Minimum username length is 4 characters.");
	$validator->addValidation("password","reg","Please fill in a password.");
	$validator->addValidation("password","eqelmnt=password_confirmation","Passwords do not match.");
	$validator->addValidation("password","minlen=5","Minimum password length is 5 characters.");
	
	if(repeatUser($username) == true){
	echo "<center><p>Username already exists!</p></center>";
	//echo "<center><a href='registration.php'>Back</a></center>";
	goto a;
	}
	elseif(repeatEmail($email) == true){
	echo "<center><p>Email already exists!</p></center>";
	//echo "<center><a href='registration.php'>Back</a></center>";
	goto a;
	}	
    if($validator->ValidateForm()){
		open_mysql_connection();
		$insert_query = 'INSERT INTO	users (
					first_name,
					last_name,
					volunteer_number,
					email,
					phone_number,
					username,
					password,
					administrator
					) 
					VALUES
					(
					"' . $first_name . '",
					"' . $last_name . '",	
					"' . $volunteer_number . '",
					"' . $email . '",
					"' . $phone_number . '",
					"' . $username . '",
					"' . md5($password) . '",
					"' . $administrator . '"
					)';
		mysql_query($insert_query); 
		close_mysql_connection();
        $registration_success = "registration.php?reg=true";	
		header("location: " . $registration_success . ""); 
    }
    
	else {
        $error_hash = $validator->GetErrors();
        foreach($error_hash as $inpname => $inp_err)
        {
          echo "<center><p>$inp_err</p></center>\n";
        }
		//echo "<center><a href='registration.php'>Back</a></center>";
    }
	a:
}
?>
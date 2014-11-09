<?
if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

require_once "../db_connector.php";

 // Function Name:	start_newReferral
 //       Purpose:  changes the users database to show a referral is open for the current user
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  referral_profile_id
function start_newReferral($user_id, $username){
	open_mysql_connection();
	// open new referral
	mysql_query('INSERT INTO referral_profile (date_added) VALUES (TIMESTAMP(current_timestamp()))');
	// retrieve referral id
	$newReferral_id = mysql_insert_id(); 
	// store newReferral id and set newReferral status to active
	mysql_query ('UPDATE users SET newReferral_id = "'.$newReferral_id.'", newReferral_status = "1" WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	close_mysql_connection();
}

 // Function Name:	get_newReferral_status
 //       Purpose:  To determine if the user has already started a referral
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  will either return a 1 for referral started or 0 for no referral started
function get_newReferral_status($user_id, $username){
	open_mysql_connection();
	$query = mysql_query('SELECT newReferral_id, newReferral_status FROM users WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	$result = mysql_fetch_array($query);
	// Check to see if a new referral is actually being created
	if($result['newReferral_status'] == 1 && $result['newReferral_id'] <= 0){
		echo "Error problem with the database start referral function not working!";
		exit();
	}
		$status = $result['newReferral_status'];
		return $status;	  
	close_mysql_connection();
}

  // Function Name:	get_Referral_Data
 //       Purpose:  Gets all the data existing data in a referral and stores it in a variable
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  returns the referral data
function get_Referral_Data($user_id, $username, $referral_profile_id){
	open_mysql_connection();
	if(isset($referral_profile_id)){
		$id = $referral_profile_id;
	}
	else{
	// Get new referral ID number so we can grab data from the call referral and display it
	$query = mysql_query('SELECT newReferral_id FROM users WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	$result = mysql_fetch_array($query);
	$id = $result['newReferral_id'];
	}
	
	// Pass referral ID to an array
	$referral_data = array ( "newReferral_id" => $id);
	
	// Now that we have the referral ID we can grab the data 
	$query = mysql_query('SELECT * FROM referral_profile WHERE referral_profile_id = "'.$id.'"');
	
	$result = mysql_fetch_array($query);
	
	// store the data into the array to be returned
	$referral_data["date_added"] = $result['date_added'];
	$referral_data["date_updated"] = $result['date_updated'];
	$referral_data["organization"] = $result['organization'];
	$referral_data["program"] = $result['program'];
	$referral_data["phone_number"] = $result['phone_number'];
	$referral_data["hours"] = $result['hours'];
	$referral_data["physical_address"] = $result['physical_address'];
	$referral_data["city"] = $result['city'];
	$referral_data["website_address"] = $result['website_address'];
	$referral_data["service_description"] = $result['service_description'];
	close_mysql_connection();
	return ($referral_data);	  
}

 // Function Name:	end_Call
 //       Purpose:  Set end time and call length
 //       Accepts:  referral_profile_id
 //       Returns:  returns the referral data
function end_Call($referral_profile_id, $_POST){
	open_mysql_connection();
	
	// Set end time and call length
	$query = mysql_query('SELECT call_length FROM referral_profile WHERE referral_profile_id = "'.$referral_profile_id.'"');
	$result = mysql_fetch_array($query);
	// only update end time if it hasnt been set before
	if(empty($result['call_length'])){
		mysql_query('UPDATE referral_profile SET end_time = TIMESTAMP(current_timestamp()), call_length = TIMEDIFF(CURRENT_TIMESTAMP(), start_time) WHERE referral_profile_id = "'.$referral_profile_id.'"');
	}
	
	close_mysql_connection();
}
 
 // Function Name:	print_Select_Form
 //       Purpose:  gets the form data from the database and displays it
 //       Accepts:  database name
 //       Returns:  form data
function print_Select_Form($referral_profile_id, $option_table, $option_attribute, $referral_attribute){
	open_mysql_connection();
	$optionQuery = mysql_query("SELECT * FROM `".$option_table."`");
	$referralQuery = mysql_query("SELECT `".$referral_attribute."` FROM `referral_profile` WHERE referral_profile_id = '".$referral_profile_id."'");
	
	$result = mysql_fetch_array($referralQuery);
	
	while($row = mysql_fetch_array($optionQuery)){
		if($row[$option_attribute] == $result[$referral_attribute]){
			echo "<option id='selected' selected='selected' value='".$row[$option_attribute]."'>".$row[$option_attribute]."</option>";
		}
		else{
			echo "<option value='".$row[$option_attribute]."'>".$row[$option_attribute]."</option>";
		}
		
	}
	close_mysql_connection();
}

 // Function Name:	submit_referral
 //       Purpose:  Submits all referral data
 //       Accepts:  referral_profile_id
 //       Returns:  returns the referral data
function submit_referral($referral_profile_id, $_POST){
	open_mysql_connection();
	
	$_POST['phone_number'] = str_replace('-','',$_POST['phone_number']);
	$_POST['phone_number'] = str_replace(' ','',$_POST['phone_number']);
	$_POST['phone_number'] = str_replace('(','',$_POST['phone_number']);
	$_POST['phone_number'] = str_replace(')','',$_POST['phone_number']);
	
	// organization_name
	$post_attribute = 'organization';
	$referral_attribute = 'organization';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE referral_profile SET '.$referral_attribute.' = "'.$_POST[$post_attribute].'"  WHERE referral_profile_id = "'.$referral_profile_id.'"');
	}
	
	// program
	$post_attribute = 'program';
	$referral_attribute = 'program';
		mysql_query('UPDATE referral_profile SET '.$referral_attribute.' = "'.$_POST[$post_attribute].'"  WHERE referral_profile_id = "'.$referral_profile_id.'"');

	// phone_number
	$post_attribute = 'phone_number';
	$referral_attribute = 'phone_number';
		mysql_query('UPDATE referral_profile SET '.$referral_attribute.' = "'.$_POST[$post_attribute].'"  WHERE referral_profile_id = "'.$referral_profile_id.'"');
	
	// hours
	$post_attribute = 'hours';
	$referral_attribute = 'hours';
		mysql_query('UPDATE referral_profile SET '.$referral_attribute.' = "'.$_POST[$post_attribute].'"  WHERE referral_profile_id = "'.$referral_profile_id.'"');
		
	// physical_address
	$post_attribute = 'physical_address';
	$referral_attribute = 'physical_address';
		mysql_query('UPDATE referral_profile SET '.$referral_attribute.' = "'.$_POST[$post_attribute].'"  WHERE referral_profile_id = "'.$referral_profile_id.'"');

	// website_address
	$post_attribute = 'website_address';
	$referral_attribute = 'website_address';
		mysql_query('UPDATE referral_profile SET '.$referral_attribute.' = "'.$_POST[$post_attribute].'"  WHERE referral_profile_id = "'.$referral_profile_id.'"');

	// city
	$post_attribute = 'city';
	$referral_attribute = 'city';
		mysql_query('UPDATE referral_profile SET '.$referral_attribute.' = "'.$_POST[$post_attribute].'"  WHERE referral_profile_id = "'.$referral_profile_id.'"');
	
	// category_id
	$post_attribute = 'category';
	$referral_attribute = 'category_id';
	if($_POST[$post_attribute] == 'NULL'){
		mysql_query('UPDATE referral_profile SET '.$referral_attribute.' = NULL  WHERE referral_profile_id = "'.$referral_profile_id.'"');
	}
	else{
		mysql_query('UPDATE referral_profile SET '.$referral_attribute.' = "'.$_POST[$post_attribute].'"  WHERE referral_profile_id = "'.$referral_profile_id.'"');
	}	

	// service_description
	$post_attribute = 'service_description';
	$referral_attribute = 'service_description';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE referral_profile SET '.$referral_attribute.' = "'.$_POST[$post_attribute].'"  WHERE referral_profile_id = "'.$referral_profile_id.'"');
	}
	close_mysql_connection();
}
 
 
 // Function Name:	close_referral
 //       Purpose:  sets open referral status to 0
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  
function close_referral($user_id, $username, $referral_profile_id, $POST){
	open_mysql_connection();
	$query = mysql_query('SELECT call_length FROM referral_profile WHERE referral_profile_id = "'.$referral_profile_id.'"');
	$result = mysql_fetch_array($query);
	
	// set error array
	$error = array ("error" => 0);
	
	// Duplicate organization name search
	$dup_query = mysql_query('SELECT referral_profile_id, organization FROM referral_profile');
	while($dup_result = mysql_fetch_array($dup_query)){
		if($POST['organization'] == $dup_result['organization'] && $referral_profile_id != $dup_result['referral_profile_id']){
			$error["error"] = 1;
			$error["dup_organization"] = "<center><p id='error'>You already have a referral profile under the organization name '".$POST['organization']."'!</p></center>";
		}
	}
	
	if(empty($POST['organization'])){
		$error["error"] = 1;
		$error["organization"] = "<center><p id='error'>You must set a Organization Name!</p></center>";
	}
	if(empty($POST['phone_number'])){
		$error["error"] = 1;
		$error["phone_number"] = "<center><p id='error'>You must set a Phone Number!</p></center>";
	}
	if($POST['category'] == "NULL"){
		$error["error"] = 1;
		$error["category"] = "<center><p id='error'>You must set a Category!</p></center>";
	}
	if(empty($POST['service_description'])){
		$error["error"] = 1;
		$error["service_description"] = "<center><p id='error'>You must enter a Service Description!</p></center>";
	}
		
	if($error["error"] == 1){
		return $error;
	}
	elseif($error["error"] == 0){
		mysql_query('UPDATE users SET newReferral_status = 0 WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
		header('Location: referral_menu.php');
	}
	close_mysql_connection();
}

 // Function Name:	close_referral_edit
 //       Purpose:  sets open referral status to 0
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  
function close_referral_edit($user_id, $username, $referral_profile_id, $POST){
	open_mysql_connection();
	$query = mysql_query('SELECT call_length FROM referral_profile WHERE referral_profile_id = "'.$referral_profile_id.'"');
	$result = mysql_fetch_array($query);
	
	// set error array
	$error = array ("error" => 0);
	
	// Duplicate organization name search
	$dup_query = mysql_query('SELECT referral_profile_id, organization FROM referral_profile');
	while($dup_result = mysql_fetch_array($dup_query)){
		if($POST['organization'] == $dup_result['organization'] && $referral_profile_id != $dup_result['referral_profile_id']){
			$error["error"] = 1;
			$error["dup_organization"] = "<center><p id='error'>You already have a referral profile under the organization name '".$POST['organization']."'!</p></center>";
		}
	}
	
	if(empty($POST['organization'])){
		$error["error"] = 1;
		$error["organization"] = "<center><p id='error'>You must set a Organization Name!</p></center>";
	}
	if(empty($POST['phone_number'])){
		$error["error"] = 1;
		$error["phone_number"] = "<center><p id='error'>You must set a Phone Number!</p></center>";
	}
	if($POST['category'] == "NULL"){
		$error["error"] = 1;
		$error["category"] = "<center><p id='error'>You must set a Category!</p></center>";
	}
	if(empty($POST['service_description'])){
		$error["error"] = 1;
		$error["service_description"] = "<center><p id='error'>You must enter a Service Description!</p></center>";
	}
		
	if($error["error"] == 1){
		return $error;
	}
	elseif($error["error"] == 0){
		unset($_SESSION['referral_edit_id']);
		$HTTP_REFERER = $_SESSION['referrer_edit_page'];
		unset($_SESSION['referrer_edit_page']);
		header('Location: '.$HTTP_REFERER.'');
	}
	close_mysql_connection();
}
?>
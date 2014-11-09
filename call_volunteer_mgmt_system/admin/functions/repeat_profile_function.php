<?
if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

require_once "../db_connector.php";

 // Function Name:	get_newRepeat_status
 //       Purpose:  To determine if the user has already started a report
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  will either return a 1 for report started or 0 for no report started
function get_newRepeat_status($user_id, $username){
	open_mysql_connection();
	$query = mysql_query('SELECT newRepeat_id, newRepeat_status FROM users WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	$result = mysql_fetch_array($query);
	// Check to see if a new report is actually being created
	if($result['newRepeat_status'] == 1 && $result['newRepeat_id'] <= 0){
		echo "Error problem with the database start report function not working!";
		exit();
	}
		$status = $result['newRepeat_status'];
		return $status;	  
	close_mysql_connection();
}

 // Function Name:	start_newRepeat
 //       Purpose:  changes the users database to show a Repeat is open for the current user
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  repeat_caller_id
function start_newRepeat($user_id, $username){
	open_mysql_connection();
	// open new Repeat
	mysql_query('INSERT INTO repeat_caller (`as_timestamp`) VALUES(CURRENT_TIMESTAMP)');
	// retrieve Repeat id
	$newRepeat_id = mysql_insert_id(); 
	// store newRepeat id and set newRepeat status to active
	mysql_query ('UPDATE users SET newRepeat_id = "'.$newRepeat_id.'", newRepeat_status = "1" WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	close_mysql_connection();
}

 // Function Name:	print_Select_Form
 //       Purpose:  gets the form data from the database and displays it
 //       Accepts:  database name
 //       Returns:  form data
function print_Select_Form($repeat_caller_id, $option_table, $option_attribute, $repeat_attribute){
	open_mysql_connection();
	$optionQuery = mysql_query("SELECT * FROM `$option_table`");
	$reportQuery = mysql_query("SELECT `$repeat_attribute` FROM `repeat_caller` WHERE repeat_caller_id = '$repeat_caller_id'");
	
	$result = mysql_fetch_array($reportQuery);
	
	while($row = mysql_fetch_array($optionQuery)){
		if($row[$option_attribute] == $result[$repeat_attribute]){
			echo "<option id='selected' selected='selected' value='".$row[$option_attribute]."'>".$row[$option_attribute]."</option>";
		}
		else{
			echo "<option value='".$row[$option_attribute]."'>".$row[$option_attribute]."</option>";
		}
		
	}
	close_mysql_connection();
}

 // Function Name:	print_Checkbox_Form
 //       Purpose:  gets the form data from the database and displays it
 //       Accepts:  database name
 //       Returns:  form data
function print_Checkbox_Form($repeat_caller_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute){
	open_mysql_connection();
	$optionQuery = mysql_query("SELECT * FROM `$option_table`");
	$junctionQuery = mysql_query ('SELECT * FROM `'.$junction_table.'` WHERE repeat_caller_id = "'.$repeat_caller_id.'"');	
	
	while($row = mysql_fetch_array($junctionQuery)){
		$junctionArray[] = $row[$junction_attribute];
	}
	
	while($optionRow = mysql_fetch_array($optionQuery)){
		$selected = 0;
		if(isset($junctionArray)){
			for($i=0;$i<count($junctionArray);$i++){
				if($optionRow[$option_attribute] == $junctionArray[$i]){
					$selected = 1;
					echo "<input type='checkbox' name='".$input_name."[]' value='".$optionRow[$option_attribute]."' checked><label id='checkbox_selected' for='".$option_attribute."'>".$optionRow[$option_attribute]."</label><br />";
					break;
				}
			}
		}
		if($selected == 0){
			echo "<input type='checkbox' name='".$input_name."[]' value='".$optionRow[$option_attribute]."'><label id='checkbox' for='".$option_attribute."'>".$optionRow[$option_attribute]."</label><br />";
		}
	}
	close_mysql_connection();
}

 // Function Name:	get_repeat_data
 //       Purpose:  Gets all the data existing data in a report and stores it in a variable
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  returns the report data
function get_repeat_data($user_id, $username, $repeat_caller_id){
	open_mysql_connection();
	if($repeat_caller_id > 0){
		$id = $repeat_caller_id;
	}
	else{
		// Get new report ID number so we can grab data from the call report and display it
		$query = mysql_query('SELECT newRepeat_id FROM users WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
		$result = mysql_fetch_array($query);
		$id = $result['newRepeat_id'];
	}
	// Pass call report ID to an array
	$repeat_data = array ( "newRepeat_id" => $id);
	
	// Now that we have the report ID we can grab the data 
	$query = mysql_query('SELECT * FROM repeat_caller WHERE repeat_caller_id = "'.$id.'"');
	$result = mysql_fetch_array($query);
	
	// store the data into the array to be returned
	$repeat_data["caller_status"] = $result['caller_status_id'];
	$repeat_data["caller_name"] = $result['caller_name'];
	$repeat_data["caller_phone"] = $result['phone_number'];	
	$repeat_data["caller_address"] = $result['street_address'];	
	$repeat_data["caller_postal"] = $result['postal_code'];	
	$repeat_data["caller_city"] = $result['city'];
	$repeat_data["gender"] = $result['gender_id'];
	$repeat_data["age_guess"] = $result['age_guess_id'];
	$repeat_data["living_status"] = $result['living_status_id'];
	$repeat_data["marital_status"] = $result['marital_status_id'];
	$repeat_data["economic_status"] = $result['economic_status_id'];
	$repeat_data["age_confirmed"] = $result['age_confirmed'];
	$repeat_data["financial_status"] = $result['financial_status_id'];
	$repeat_data["repeat_caller_description"] = $result['repeat_caller_description'];
	close_mysql_connection();
	return ($repeat_data);	  
}

 // Function Name:	submit_Checkbox_Form
 //       Purpose:  to e used in the submit_Form function to save/remove checkbox data
 //       Accepts:  post_attribute, junction_table, junction_attribute, id
 //       Returns:  
function submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $id){
	unset($junctionTable);
	// remove non selected entries from database 
	$query = mysql_query ('SELECT `'.$junction_attribute.'` FROM `'.$junction_table.'` WHERE repeat_caller_id = "'.$id.'"');		
	while ( $row = mysql_fetch_array($query)){
		$junctionTable[] = $row[$junction_attribute];
	}
	
	$dataChanged = 0;
	if(isset($junctionTable)){	
		for($z=0;$z<count($junctionTable);$z++){
			$selected = 0;
				for($i=0;$i<count($_POST[$post_attribute]);$i++){
					if($junctionTable[$z] == $_POST[$post_attribute][$i]){
						$selected = 1;
						break;
					}
				}
			if($selected == 0){
				$dataChanged += 1;
				mysql_query('DELETE FROM `'.$junction_table.'` WHERE repeat_caller_id = "'.$id.'" AND '.$junction_attribute.' = "'.$junctionTable[$z].'"');		
			}
		}
	}
	
	if($dataChanged > 0){
		unset($junctionTable);
		$query = mysql_query ('SELECT `'.$junction_attribute.'` FROM `'.$junction_table.'` WHERE repeat_caller_id = "'.$id.'"');
		while ( $row = mysql_fetch_array($query)){
			$junctionTable[] = $row[$junction_attribute];
		}
	}
		
	//print_r($junctionTable);
	if(empty($junctionTable)){
		for($i=0;$i<count($_POST[$post_attribute]);$i++){
			mysql_query('INSERT INTO `'.$junction_table.'` (repeat_caller_id, '.$junction_attribute.') VALUES ("'.$id.'", "'.$_POST[$post_attribute][$i].'")');
		}
	}
	else{
		// Put data into the database unless already there
		for($i=0;$i<count($_POST[$post_attribute]);$i++){
			$selected = 0;
				for($q=0;$q<count($junctionTable);$q++){
					if($_POST[$post_attribute][$i] == $junctionTable[$q]){
						$selected = 1;
						break;
					}
				}
			if($selected == 0){
				mysql_query('INSERT INTO `'.$junction_table.'` (repeat_caller_id, '.$junction_attribute.') VALUES ("'.$id.'", "'.$_POST[$post_attribute][$i].'")');		
				//	echo "<br />Data Inserted<br />";
			}
		}
	}
}

 // Function Name:	submit_Profile
 //       Purpose:  Submits all Profile data
 //       Accepts:  repeat_caller_id
 //       Returns:  returns the Profile data
function submit_Profile($repeat_caller_id, $_POST){
	open_mysql_connection();
	
	// caller_status_id
	$post_attribute = 'caller_status';
	$repeat_attribute = 'caller_status_id';
	if($_POST[$post_attribute] == 'NULL'){
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = NULL  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
	}
	else{
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
	}

	// caller_name
	$post_attribute = 'caller_name';
	$repeat_attribute = 'caller_name';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
	}
	
	// caller_phone
	$post_attribute = 'caller_phone';
	$repeat_attribute = 'phone_number';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
	}
		
	// caller_address
	$post_attribute = 'caller_address';
	$repeat_attribute = 'street_address';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
	}
		
	// caller_postal
	$post_attribute = 'caller_postal';
	$repeat_attribute = 'postal_code';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
	}
	
	// caller_city
	$post_attribute = 'caller_city';
	$repeat_attribute = 'city';
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
		
	// gender
	$post_attribute = 'gender';
	$repeat_attribute = 'gender_id';
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
		
	// age_guess
	$post_attribute = 'age_guess';
	$repeat_attribute = 'age_guess_id';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
	}
	
	// living_status
	$post_attribute = 'living_status';
	$repeat_attribute = 'living_status_id';
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
		
	// marital_status
	$post_attribute = 'marital_status';
	$repeat_attribute = 'marital_status_id';
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
		
	// economic_status
	$post_attribute = 'economic_status';
	$repeat_attribute = 'economic_status_id';
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
	
	// age_confirmed
	$post_attribute = 'age_confirmed';
	$repeat_attribute = 'age_confirmed';
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');	
	
	// financial_status
	$post_attribute = 'financial_status';
	$repeat_attribute = 'financial_status_id';
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');

////in_treatment//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'in_treatment';
	$junction_table = 'repeat_caller-in_treatment_junction';
	$junction_attribute = 'in_treatment_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $repeat_caller_id);
	}

	// sexuality
	$post_attribute = 'sexuality';
	$repeat_attribute = 'sexuality_id';
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');

////physical_abuse//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'physical_abuse';
	$junction_table = 'repeat_caller-physical_abuse_junction';
	$junction_attribute = 'physical_abuse_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $repeat_caller_id);
	}
######################################################################

////verbal_abuse//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'verbal_abuse';
	$junction_table = 'repeat_caller-verbal_abuse_junction';
	$junction_attribute = 'verbal_abuse_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $repeat_caller_id);
	}	
######################################################################

////mental_health//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'mental_health';
	$junction_table = 'repeat_caller-mental_health_junction';
	$junction_attribute = 'mental_health_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $repeat_caller_id);
	}	
######################################################################

////substance_abuse//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'substance_abuse';
	$junction_table = 'repeat_caller-substance_abuse_junction';
	$junction_attribute = 'substance_abuse_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $repeat_caller_id);
	}	
######################################################################
	
////physical_health//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'physical_health';
	$junction_table = 'repeat_caller-physical_health_junction';
	$junction_attribute = 'physical_health_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $repeat_caller_id);
	}	
######################################################################

////general_issuese//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'general_issues';
	$junction_table = 'repeat_caller-general_issues_junction';
	$junction_attribute = 'general_issues_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $repeat_caller_id);
	}	
######################################################################	

////interpersonal_issues//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'interpersonal_issues';
	$junction_table = 'repeat_caller-interpersonal_issues_junction';
	$junction_attribute = 'interpersonal_issues_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $repeat_caller_id);
	}	
######################################################################

////personal_issues//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'personal_issues';
	$junction_table = 'repeat_caller-personal_issues_junction';
	$junction_attribute = 'personal_issues_id';
	if(isset($_POST[$post_attribute])){	
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $repeat_caller_id);
	}	
######################################################################
	
	// call_description
	$post_attribute = 'repeat_caller_description';
	$repeat_attribute = 'repeat_caller_description';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE repeat_caller SET '.$repeat_attribute.' = "'.$_POST[$post_attribute].'"  WHERE repeat_caller_id = "'.$repeat_caller_id.'"');
	}

	close_mysql_connection();
}
 
 // Function Name:	close_Report
 //       Purpose:  sets open report status to 0
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  
function close_Repeat($user_id, $username){
	open_mysql_connection();

		mysql_query('UPDATE users SET newRepeat_status = 0 WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
		header('Location: repeat_menu.php');

	close_mysql_connection();
}

 // Function Name:	close_repeat_edit
 //       Purpose:  sets open referral status to 0
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  
function close_repeat_edit($user_id, $username, $referral_profile_id, $POST){
	open_mysql_connection();

		unset($_SESSION['repeat_edit_id']);
		$HTTP_REFERER = $_SESSION['repeat_edit_page'];
		unset($_SESSION['repeat_edit_page']);
		header('Location: '.$HTTP_REFERER.'');

	close_mysql_connection();
}
?>
<?
if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

require_once "../db_connector.php";

 // Function Name:	get_newReport_status
 //       Purpose:  To determine if the user has already started a report
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  will either return a 1 for report started or 0 for no report started
function get_newReport_status($user_id, $username){
	open_mysql_connection();
	$query = mysql_query('SELECT newReport_id, newReport_status FROM users WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	$result = mysql_fetch_array($query);
	// Check to see if a new report is actually being created
	if($result['newReport_status'] == 1 && $result['newReport_id'] <= 0){
		echo "Error problem with the database start report function not working!";
		exit();
	}
		$status = $result['newReport_status'];
		return $status;	  
	close_mysql_connection();
}

 // Function Name:	start_newReport
 //       Purpose:  changes the users database to show a report is open for the current user
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  call_report_id
function start_newReport($vol_num, $user_id, $username, $vol_name){
	open_mysql_connection();
	// open new report
	mysql_query('INSERT INTO call_report (start_time, volunteer_number, volunteer_name, username) VALUES (TIMESTAMP(current_timestamp()), "'.$vol_num.'", "'.$vol_name.'", "'.$username.'")');
	// retrieve report id
	$newReport_id = mysql_insert_id(); 
	// store newReport id and set newReport status to active
	mysql_query ('UPDATE users SET newReport_id = "'.$newReport_id.'", newReport_status = "1" WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	close_mysql_connection();
}

 // Function Name:	print_Select_Form
 //       Purpose:  gets the form data from the database and displays it
 //       Accepts:  database name
 //       Returns:  form data
function print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute){
	open_mysql_connection();
	$optionQuery = mysql_query("SELECT * FROM `$option_table`");
	$reportQuery = mysql_query("SELECT `$report_attribute` FROM `call_report` WHERE call_report_id = '$call_report_id'");
	
	$result = mysql_fetch_array($reportQuery);
	
	while($row = mysql_fetch_array($optionQuery)){
		if($row[$option_attribute] == $result[$report_attribute]){
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
function print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute){
	open_mysql_connection();
	$optionQuery = mysql_query("SELECT * FROM `$option_table`");
	$junctionQuery = mysql_query ('SELECT * FROM `'.$junction_table.'` WHERE call_report_id = "'.$call_report_id.'"');	
	
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

 // Function Name:	reverse_Print_Checkbox_Form
 //       Purpose:  gets the form data from the database and displays it
 //       Accepts:  database name
 //       Returns:  form data
function reverse_Print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute, $nolabel){
	open_mysql_connection();
	$optionQuery = mysql_query("SELECT * FROM `$option_table`");
	$junctionQuery = mysql_query ('SELECT * FROM `'.$junction_table.'` WHERE call_report_id = "'.$call_report_id.'"');	
	
	while($junctionRow = mysql_fetch_array($junctionQuery)){
		$junctionArray[] = $junctionRow[$junction_attribute];
	}
	
	// check to see if value in use for form
	// If no label is equal to 1 do not display labels
	if($nolabel == 1){
		while($optionRow = mysql_fetch_array($optionQuery)){
			$selected = 0;
			if(isset($junctionArray)){
				for($i=0;$i<count($junctionArray);$i++){
					if($optionRow[$option_attribute] == $junctionArray[$i]){
						$selected = 1;
						echo "<label id='checkbox_nolabel_selected' type='checkbox'></label><input type='checkbox' name='".$input_name."[]' value='".$optionRow[$option_attribute]."' checked><br />";
						break;
					}
				}
			}
			if($selected == 0){
				echo "<label id='checkbox_nolabel' type='checkbox'></label><input type='checkbox' name='".$input_name."[]' value='".$optionRow[$option_attribute]."'><br />";
			}

		}
	}
	else{
		while($optionRow = mysql_fetch_array($optionQuery)){
			$selected = 0;
			if(isset($junctionArray)){
				for($i=0;$i<count($junctionArray);$i++){
					if($optionRow[$option_attribute] == $junctionArray[$i]){
						$selected = 1;
						echo "<label id='checkbox_outcome_selected' for='".$option_attribute."'>".$optionRow[$option_attribute]."</label><input type='checkbox' name='".$input_name."[]' value='".$optionRow[$option_attribute]."' checked><br />";
						break;
					}
				}
			}
			if($selected == 0){
				echo "<label id='checkbox_outcome' for='".$option_attribute."'>".$optionRow[$option_attribute]."</label><input type='checkbox' name='".$input_name."[]' value='".$optionRow[$option_attribute]."'><br />";
			}

		}
	}
	close_mysql_connection();
}

 // Function Name:	get_Report_Data
 //       Purpose:  Gets all the data existing data in a report and stores it in a variable
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  returns the report data
function get_Report_Data($user_id, $username, $call_report_id){
	open_mysql_connection();
	if($call_report_id > 0){
		$id = $call_report_id;
	}
	else{
		// Get new report ID number so we can grab data from the call report and display it
		$query = mysql_query('SELECT newReport_id FROM users WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
		$result = mysql_fetch_array($query);
		$id = $result['newReport_id'];
	}
	// Pass call report ID to an array
	$report_data = array ( "newReport_id" => $id);
	
	// Now that we have the report ID we can grab the data 
	$query = mysql_query('SELECT * FROM call_report WHERE call_report_id = "'.$id.'"');
	$result = mysql_fetch_array($query);
	
	// store the data into the array to be returned
	$report_data["start_time"] = $result['start_time'];
	if($result['end_time'] == '0000-00-00 00:00:00'){
		$result['end_time'] = 'Call in progress';
	}
	$report_data["end_time"] = $result['end_time'];
	$report_data["call_length"] = $result['call_length'];
	$report_data["line"] = $result['line_id'];
	$report_data["caller_status"] = $result['caller_status_id'];
	$report_data["volunteer_number"] = $result['volunteer_number'];
	$report_data["volunteer_name"] = $result['volunteer_name'];
	$report_data["username"] = $result['username'];
	$report_data["hang_up"] = $result['hang_up'];
	$report_data["caller_name"] = $result['caller_name'];
	$report_data["caller_phone"] = $result['phone_number'];	
	$report_data["caller_address"] = $result['street_address'];	
	$report_data["caller_postal"] = $result['postal_code'];	
	$report_data["caller_city"] = $result['city'];
	$report_data["gender"] = $result['gender_id'];
	$report_data["age_guess"] = $result['age_guess_id'];
	$report_data["living_status"] = $result['living_status_id'];
	$report_data["marital_status"] = $result['marital_status_id'];
	$report_data["economic_status"] = $result['economic_status_id'];
	$report_data["age_confirmed"] = $result['age_confirmed'];
	$report_data["financial_status"] = $result['financial_status_id'];
	$report_data["call_description"] = $result['call_description'];
	close_mysql_connection();
	return ($report_data);	  
}

 // Function Name:	end_Call
 //       Purpose:  Set end time and call length
 //       Accepts:  call_report_id
 //       Returns:  returns the report data
function end_Call($call_report_id, $_POST){
	open_mysql_connection();
	
	// Set end time and call length
	$query = mysql_query('SELECT call_length FROM call_report WHERE call_report_id = "'.$call_report_id.'"');
	$result = mysql_fetch_array($query);
	// only update end time if it hasnt been set before
	if(empty($result['call_length'])){
		mysql_query('UPDATE call_report SET end_time = TIMESTAMP(current_timestamp()), call_length = TIMEDIFF(CURRENT_TIMESTAMP(), start_time) WHERE call_report_id = "'.$call_report_id.'"');
	}
	
	close_mysql_connection();
}

 // Function Name:	submit_Checkbox_Form
 //       Purpose:  to e used in the submit_Form function to save/remove checkbox data
 //       Accepts:  post_attribute, junction_table, junction_attribute, id
 //       Returns:  
function submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $id){
	unset($junctionTable);
	// remove non selected entries from database 
	$query = mysql_query ('SELECT `'.$junction_attribute.'` FROM `'.$junction_table.'` WHERE call_report_id = "'.$id.'"');		
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
				mysql_query('DELETE FROM `'.$junction_table.'` WHERE call_report_id = "'.$id.'" AND '.$junction_attribute.' = "'.$junctionTable[$z].'"');		
			}
		}
	}
	
	if($dataChanged > 0){
		unset($junctionTable);
		$query = mysql_query ('SELECT `'.$junction_attribute.'` FROM `'.$junction_table.'` WHERE call_report_id = "'.$id.'"');
		while ( $row = mysql_fetch_array($query)){
			$junctionTable[] = $row[$junction_attribute];
		}
	}
		
	//print_r($junctionTable);
	if(empty($junctionTable)){
		for($i=0;$i<count($_POST[$post_attribute]);$i++){
			mysql_query('INSERT INTO `'.$junction_table.'` (call_report_id, '.$junction_attribute.') VALUES ("'.$id.'", "'.$_POST[$post_attribute][$i].'")');
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
				mysql_query('INSERT INTO `'.$junction_table.'` (call_report_id, '.$junction_attribute.') VALUES ("'.$id.'", "'.$_POST[$post_attribute][$i].'")');		
				//	echo "<br />Data Inserted<br />";
			}
		}
	}
}

 // Function Name:	submit_Report
 //       Purpose:  Submits all report data
 //       Accepts:  call_report_id
 //       Returns:  returns the report data
function submit_Report($call_report_id, $_POST){
	open_mysql_connection();
	
	// line_id
	$post_attribute = 'line';
	$report_attribute = 'line_id';
	if($_POST[$post_attribute] == 'NULL'){
		mysql_query('UPDATE call_report SET '.$report_attribute.' = NULL  WHERE call_report_id = "'.$call_report_id.'"');
	}
	else{
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
	}
	
	// caller_status_id
	$post_attribute = 'caller_status';
	$report_attribute = 'caller_status_id';

		if($_POST[$post_attribute] == 'NULL'){
			mysql_query('UPDATE call_report SET '.$report_attribute.' = NULL  WHERE call_report_id = "'.$call_report_id.'"');
		}
		else{
			mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
		}

	
	// hang_up
	$post_attribute = 'hang_up';
	$report_attribute = 'hang_up';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
	}
	else{
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "0"  WHERE call_report_id = "'.$call_report_id.'"');
	}

	// caller_name
	$post_attribute = 'caller_name';
	$report_attribute = 'caller_name';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
	}
	
	// caller_phone
	$post_attribute = 'caller_phone';
	$report_attribute = 'phone_number';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
	}
		
	// caller_address
	$post_attribute = 'caller_address';
	$report_attribute = 'street_address';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
	}
		
	// caller_postal
	$post_attribute = 'caller_postal';
	$report_attribute = 'postal_code';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
	}
	
	// caller_city
	$post_attribute = 'caller_city';
	$report_attribute = 'city';
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
		
	// gender
	$post_attribute = 'gender';
	$report_attribute = 'gender_id';
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
		
	// age_guess
	$post_attribute = 'age_guess';
	$report_attribute = 'age_guess_id';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
	}
	
	// living_status
	$post_attribute = 'living_status';
	$report_attribute = 'living_status_id';
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
		
	// marital_status
	$post_attribute = 'marital_status';
	$report_attribute = 'marital_status_id';
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
		
	// economic_status
	$post_attribute = 'economic_status';
	$report_attribute = 'economic_status_id';
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
	
	// age_confirmed
	$post_attribute = 'age_confirmed';
	$report_attribute = 'age_confirmed';
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');	
	
	// financial_status
	$post_attribute = 'financial_status';
	$report_attribute = 'financial_status_id';
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');

////referral_from//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'referral_from';
	$junction_table = 'call_report-referral_from_junction';
	$junction_attribute = 'referral_from_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}
######################################################################

////in_treatment//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'in_treatment';
	$junction_table = 'call_report-in_treatment_junction';
	$junction_attribute = 'in_treatment_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}
######################################################################
	
	// suicide
	$post_attribute = 'suicide';
	$report_attribute = 'suicide_id';
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');

	// sexuality
	$post_attribute = 'sexuality';
	$report_attribute = 'sexuality_id';
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');

////physical_abuse//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'physical_abuse';
	$junction_table = 'call_report-physical_abuse_junction';
	$junction_attribute = 'physical_abuse_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}
######################################################################

////verbal_abuse//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'verbal_abuse';
	$junction_table = 'call_report-verbal_abuse_junction';
	$junction_attribute = 'verbal_abuse_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################

////mental_health//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'mental_health';
	$junction_table = 'call_report-mental_health_junction';
	$junction_attribute = 'mental_health_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################

////substance_abuse//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'substance_abuse';
	$junction_table = 'call_report-substance_abuse_junction';
	$junction_attribute = 'substance_abuse_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################
	
////physical_health//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'physical_health';
	$junction_table = 'call_report-physical_health_junction';
	$junction_attribute = 'physical_health_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################

////general_issuese//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'general_issues';
	$junction_table = 'call_report-general_issues_junction';
	$junction_attribute = 'general_issues_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################	

////interpersonal_issues//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'interpersonal_issues';
	$junction_table = 'call_report-interpersonal_issues_junction';
	$junction_attribute = 'interpersonal_issues_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################

////personal_issues//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'personal_issues';
	$junction_table = 'call_report-personal_issues_junction';
	$junction_attribute = 'personal_issues_id';
	if(isset($_POST[$post_attribute])){	
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################

////response_to_call//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'response_to_call';
	$junction_table = 'call_report-response_to_call_junction';
	$junction_attribute = 'response_to_call_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################

////call_content//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'call_content';
	$junction_table = 'call_report-call_content_junction';
	$junction_attribute = 'call_content_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}
######################################################################

////caller_reaction//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'caller_reaction';
	$junction_table = 'call_report-caller_reaction_junction';
	$junction_attribute = 'caller_reaction_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################

////initial_caller_outcome//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'initial_caller_outcome';
	$junction_table = 'call_report-caller_outcome_initial_junction';
	$junction_attribute = 'initial_caller_outcome_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################

////final_caller_outcome//////////////////////////////////////////////////////////////////////// 

	$post_attribute = 'final_caller_outcome';
	$junction_table = 'call_report-caller_outcome_final_junction';
	$junction_attribute = 'final_caller_outcome_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);	
	}	
######################################################################

////referred to//////////////////////////////////////////////////////////////////////// 
	$post_attribute = 'referral_profile';
	$junction_table = 'referral_profile-junction';
	$junction_attribute = 'referral_profile_id';
	if(isset($_POST[$post_attribute])){
		submit_Checkbox_Form($post_attribute, $junction_table, $junction_attribute, $call_report_id);
	}	
######################################################################
	
	// call_description
	$post_attribute = 'call_description';
	$report_attribute = 'call_description';
	if(isset($_POST[$post_attribute])){
		mysql_query('UPDATE call_report SET '.$report_attribute.' = "'.$_POST[$post_attribute].'"  WHERE call_report_id = "'.$call_report_id.'"');
	}

	close_mysql_connection();
}
 
  
 
 // Function Name:	close_Report
 //       Purpose:  sets open report status to 0
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  
function close_Report($user_id, $username, $call_report_id, $POST){
	open_mysql_connection();
	$query = mysql_query('SELECT call_length FROM call_report WHERE call_report_id = "'.$call_report_id.'"');
	$result = mysql_fetch_array($query);
	
	// set error array
	$error = array ("error" => 0);
	
	// Check if hang up was selcted
	if(isset($POST['hang_up'])){
		if($POST['hang_up'] == 1){
			// Check if call has ended
			if(empty($result['call_length'])){
				$error["error"] = 1;
				$error["end_call"] = "<center><p id='error'>Call has not ended. Please end the call and save before closing.</p></center>";
			}
			if($POST['line'] == "NULL"){
				$error["error"] = 1;
				$error["line"] = "<center><p id='error'>You must select a line!</p></center>";
			}
		}
	}
	else{
		// Check if call has ended
		if(empty($result['call_length'])){
			$error["error"] = 1;
			$error["end_call"] = "<center><p id='error'>Call has not ended. Please end the call and save before closing.</p></center>";
		}	
		if($POST['line'] == "NULL"){
			$error["error"] = 1;
			$error["line"] = "<center><p id='error'>You must select a line!</p></center>";
		}
		if($POST['caller_status'] == "NULL"){
			$error["error"] = 1;
			$error["caller_status"] = "<center><p id='error'>You must set a caller status!</p></center>";
		}
		if($POST['gender'] == "NULL"){
			$error["error"] = 1;
			$error["gender"] = "<center><p id='error'>You must set a gender!</p></center>";
		}
		if($POST['age_guess'] == "NULL" && $POST['age_confirmed'] == ''){
			$error["error"] = 1;
			$error["age_guess"] = "<center><p id='error'>You must guess an age or input a confirmed age!</p></center>";
		}
		if(empty($POST['referral_from'])){
			$error["error"] = 1;
			$error["referral_from"] = "<center><p id='error'>You must choose atleast one referral type!</p></center>";
		}
		if(empty($POST['response_to_call'])){
			$error["error"] = 1;
			$error["response_to_call"] = "<center><p id='error'>You must select a response to call!</p></center>";
		}
		if(empty($POST['call_content'])){
			$error["error"] = 1;
			$error["overall_call_content"] = "<center><p id='error'>You must select a overall call content!</p></center>";
		}
		if(empty($POST['caller_reaction'])){
			$error["error"] = 1;
			$error["caller_reaction"] = "<center><p id='error'>You must select a caller reaction!</p></center>";
		}
		if(empty($POST['initial_caller_outcome'])){
			$error["error"] = 1;
			$error["initial_caller_outcome"] = "<center><p id='error'>You must select a initial caller outcome!</p></center>";
		}
		if(empty($POST['final_caller_outcome'])){
			$error["error"] = 1;
			$error["final_caller_outcome"] = "<center><p id='error'>You must select a final caller outcome!</p></center>";
		}
	}
	
	if($error["error"] == 1){
		return $error;
	}
	elseif($error["error"] == 0){
		mysql_query('UPDATE users SET newReport_status = 0 WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
		header('Location: report_menu.php');
	}
	close_mysql_connection();
}
?>
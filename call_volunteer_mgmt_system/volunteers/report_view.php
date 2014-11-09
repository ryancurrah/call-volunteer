<?php
include "../session_function.php";
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

require_once "functions/report_form_function.php";

$call_report_id = $_GET['id'];
// Get information from database for display
$report_Data = get_Report_Data($_SESSION['user_id'], $_SESSION['username'], $call_report_id);

if(isset($_POST['close'])) {
	header('Location: report_menu.php');
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Call Report</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="../css/NiftyLayout.css" media="screen">
		<script type="text/javascript" src="../jscript/niftycube.js"></script>
		<script type="text/javascript" src="../jscript/niftyLayout.js"></script>
		<script type="text/javascript" src="../jscript/jquery.js"></script>  
		<script type="text/javascript" src="../jscript/popup.js"></script> 
	</head>
<!--- BODY --->
<body>
<div id="header">
<!-- Navigation Menu -->
<a id="logout" href="../logout.php">Logout</a>
<h1>inFORM Call Management System</h1>
	<div id="menu">
		<ul id="nav">
			<li id="home"><a href="../index.php">Home</a></li>
			<li id="vol" class="activelink"><a href="../volunteers.php">Volunteers</a></li>
			<li id="stats"><a href="../statistics.php">Statistics</a></li>
			<li id="help"><a href="../help.php">Help</a></li>
			<li id="admin"><a href="../administrators.php">Administrators</a></li>
		</ul>
	</div>
</div>
<!-- Welcome message -->
<div id="container">
	<ul id="intro">
		<li id="welcome"><p id="userwelcome">Welcome <b><? echo $_SESSION['fullname'] . "</b>! Volunteer Number <b>" . $_SESSION['volnum']; ?></b>.</p></li>
	</ul>
<!-- PAGE TITLE -->
<h2>View Report</h2>
	<a id="title" href="../volunteers.php">Volunteers</a> <a id="title" href="#"> > </a> <a id="title" href="report_menu.php">Call Report Menu</a> <a id="title" href="#"> > </a> <a id='title' href='report_view.php?id=<?echo $call_report_id;?>'>View Report</a>
<br /><br /><br />
	
<!--- NEW CALL REPORT FORM --->
<form name="start_report_form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<br />
<input type="submit" name="close" value="Close">

	<h2>Report Information</h2>
	<hr />
	<!--- FORM DATA --->
	<div id="floatleft">
		<label for="report_num">Report Number:&nbsp;</label>
		<input type="text" name="report_num" value="<?echo $report_Data["newReport_id"]?>" readonly="readonly">
			<br />
		<label for="start_time">Start Time:&nbsp;</label>
		<input type="text" name="start_time" value="<? echo $report_Data['start_time']; ?>" readonly="readonly">
			<br />
		<label for="end_time">End Time:&nbsp;</label>
		<input type="text" name="end_time" value="<? echo $report_Data['end_time']; ?>" readonly="readonly">
		<input type="submit" name="end_call" value="End Call">
			<br />
		<label for="call_length">Call Length:&nbsp;</label>
		<input type="text" name="call_length" value="<? echo $report_Data['call_length']; ?>" readonly="readonly">
			<br />
		<label for="line">Which Line?<b>*</b>:&nbsp;</label>
		<select name="line">
			<option value="NULL"></option>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$option_table = 'call_report-line';
			$option_attribute = 'line';
			$report_attribute = 'line_id';
				print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute);
			?>
		</select>
			<br />
		<label for="caller_status">Caller Status<b>*</b>:&nbsp;</label>
		<select name="caller_status">
			<option value="NULL"></option>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$option_table = 'call_report-caller_status';
			$option_attribute = 'caller_status';
			$report_attribute = 'caller_status_id';
				print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute);
			?>
		</select>
	</div>
	
	<div id="floatleft">
		<label for="repeat_caller">Repeat Caller Profile:&nbsp;</label>
		<select name="repeat_caller">
			<option value=""></option>
			<option value="frank">Frank</option>
			<option value="mary">Mary</option>
			<option value="mary2">Mary2</option>
		</select>
		<input type="submit" name="load_profile" value="Load Profile">
			<br />
		<label for="volunteer_number">Volunteer Number:&nbsp;</label>
			<?
			$attribute = 'volunteer_number';
			echo "<input id='text_vol_num' type='text' name='".$attribute."' value='".$report_Data[$attribute]."' readonly='readonly'>";	
			?>
			<br />
		<label for="volunteer_name">Volunteer Name:&nbsp;</label>
			<?
			$attribute = 'volunteer_name';
			echo "<input id='text_vol_num' type='text' name='".$attribute."' value='".$report_Data[$attribute]."' readonly='readonly'>";	
			?>
			<br />
		<label for="username">Username:&nbsp;</label>
			<?
			$attribute = 'username';
			echo "<input id='text_vol_num' type='text' name='".$attribute."' value='".$report_Data[$attribute]."' readonly='readonly'>";	
			?>
			<br />
		<label for="hang_up">Hang Up Call:&nbsp;</label>
			<?
			$attribute = 'hang_up';
			if(empty($report_Data[$attribute])){
				echo '<input type="checkbox" name="'.$attribute.'" value="1">';				
			}
			else{
				echo '<input type="checkbox" name="'.$attribute.'" value="1" checked="checked">';
			}
			?>
			<br />


	</div>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		
	<h2>Contact Information</h2>
	<hr />
	<!--- FORM DATA --->
	<div id="floatleft">
		<label for="caller_name">Name:&nbsp;</label>
			<?
			$attribute = 'caller_name';
			echo '<input type="text" name="'.$attribute.'" value="'.$report_Data[$attribute].'">';
			?>		
			<br />
		<label for="caller_phone">Phone Number:&nbsp;</label>
			<?
			$attribute = 'caller_phone';
			echo '<input type="text" name="'.$attribute.'" value="'.$report_Data[$attribute].'">';
			?>
			<br />
		<label for="caller_address">Address:&nbsp;</label>
			<?
			$attribute = 'caller_address';
			echo '<input type="text" name="'.$attribute.'" value="'.$report_Data[$attribute].'">';
			?>
			<br />
	</div>
	
	<div id="floatleft">
		<label for="caller_postal">Postal Code:&nbsp;</label>
			<?
			$attribute = 'caller_postal';
			echo '<input type="text" name="'.$attribute.'" value="'.$report_Data[$attribute].'">';
			?>
			<br />
		<label for="caller_city">City:&nbsp;</label>
		<select name="caller_city">
			<?
			$attribute = 'caller_city';
			if(isset($report_Data[$attribute])){
				echo "<option STYLE='color:green'; value='".$report_Data[$attribute]."'>".$report_Data[$attribute]."</option>";	
			} 
			?>
				<option value=""></option>
			<optgroup label="London and Area">
				<option value="London">London</option>
				<option value="Lambeth">Lambeth</option>
				<option value="Komoka">Komoka</option>
				<option value="Lobo">Lobo</option>
				<option value="Lucan">Lucan</option>
				<option value="Ilderton">Ilderton</option>
				<option value="Delaware">Delaware</option>
				<option value="Thamesford">Thamesford</option>
				<option value="Talbotville">Talbotville</option>
			</optgroup>
			<optgroup label="South Western Ontario">
				<option value="St.Thomas">St.Thomas</option>
				<option value="Windsor">Windsor</option>
				<option value="Sarnia">Sarnia</option>
				<option value="Woodstock">Woodstock</option>
				<option value="Ingersoll">Ingersoll</option>
				<option value="Kitchener">Kitchener</option>
				<option value="Waterloo">Waterloo</option>
				<option value="Tilsonburg">Tilsonburg</option>
				<option value="Brantford">Brantford</option>
			</optgroup>
			<optgroup label="Other Location">
				<option value="Other">Other</option>
			</optgroup>
		</select>
	</div>
		<br /><br /><br /><br /><br /><br />
		
	<h2>Demographic Information</h2>
	<hr />
	<!--- FORM DATA --->
	<div id="floatleft">
		<label for="gender">Gender<b>*</b>:&nbsp;</label>
		<select name="gender">
			<option value="NULL"></option>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$option_table = 'call_report-gender';
			$option_attribute = 'gender';
			$report_attribute = 'gender_id';
				print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute);
			?>
		<select>
			<br />
			
		<label for="age_guess">Age Guess<b>*</b>:&nbsp;</label>
		<select name="age_guess">
			<option value="NULL"></option>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$option_table = 'call_report-age_guess';
			$option_attribute = 'age_guess';
			$report_attribute = 'age_guess_id';
				print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute);
			?>
		</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Or..
			<br />
			
		<label for="living_status">Living Arrangement:&nbsp;</label>
		<select name="living_status">
			<option value="NULL"></option>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$option_table = 'call_report-living_status';
			$option_attribute = 'living_status';
			$report_attribute = 'living_status_id';
				print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute);
			?>
		</select>
			<br />
			
		<label for="marital_status">Marital Status:&nbsp;</label>
		<select name="marital_status">
			<option value="NULL"></option>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$option_table = 'call_report-marital_status';
			$option_attribute = 'marital_status';
			$report_attribute = 'marital_status_id';
				print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute);
			?>
		</select>												   
	</div>
	
	<div id="floatleft">
		<label for="economic_status">Economic Status:&nbsp;</label>
		<select name="economic_status">
			<option value="NULL"></option>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$option_table = 'call_report-economic_status';
			$option_attribute = 'economic_status';
			$report_attribute = 'economic_status_id';
				print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute);
			?>
		</select>
			<br />
			
		<label for="age_confirmed">Age Confirmed:&nbsp;</label>
		<?
		$attribute = 'age_confirmed';
		echo '<input maxlength="3" size="1" type="text" name="'.$attribute.'" value="'.$report_Data[$attribute].'">';
		?>
			<br />		
			
		<label for="financial_status">Financial Status:&nbsp;</label>
		<select name="financial_status">
			<option value="NULL"></option>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$option_table = 'call_report-financial_status';
			$option_attribute = 'financial_status';
			$report_attribute = 'financial_status_id';
				print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute);
			?>
		</select>		
	</div>
		<br /><br /><br /><br /><br /><br /><br />
		
	<h2>Referral Information</h2>
	<hr />
	<!--- FORM DATA --->
	<div id="floatleft">
		<label id="checkbox_title" for="referral_from"><b>Referred From*</b>:&nbsp;</label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'referral_from';
			$option_table = 'call_report-referral_from';
			$option_attribute = 'referral_from';
			$junction_table = 'call_report-referral_from_junction'; 
			$junction_attribute = 'referral_from_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>	
	</div>
	
<div id="floatleft">
<label id="checkbox_title" for="organization"><b>Referred To:</b>&nbsp;</label>
<br />
	<div id="scroll">
				<?
				$call_report_id = $report_Data["newReport_id"]; 
				$input_name = 'organization';
				$option_table = 'referral_profile';
				$option_attribute = 'organization';
				$junction_table = 'referral_profile-junction'; 
				$junction_attribute = 'referral_profile_id';
					print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
				?>
	</div>
</div>
	<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		
	<h2>Call Content</h2>
	<hr />
	<div id="floatleft">
		<label id="checkbox_title" for="in_treatment"><b>In Treatment:</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'in_treatment';
			$option_table = 'call_report-in_treatment';
			$option_attribute = 'in_treatment';
			$junction_table = 'call_report-in_treatment_junction'; 
			$junction_attribute = 'in_treatment_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br /><br />	
			
		<label id="suicide" for="suicide">Suicide:</label>
		<select name="suicide">
			<option value="NULL"></option>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$option_table = 'call_report-suicide';
			$option_attribute = 'suicide';
			$report_attribute = 'suicide_id';
				print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute);
			?>
		</select>	
			<br /><br />
		
		<label id="sexuality" for="sexuality">Sexuality:</label>
		<select name="sexuality">
			<option value="NULL"></option>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$option_table = 'call_report-sexuality';
			$option_attribute = 'sexuality';
			$report_attribute = 'sexuality_id';
				print_Select_Form($call_report_id, $option_table, $option_attribute, $report_attribute);
			?>
		</select>	
			<br /><br />
			
		<label id="checkbox_title" for="physical_abuse"><b>Physical Abuse:</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'physical_abuse';
			$option_table = 'call_report-physical_abuse';
			$option_attribute = 'physical_abuse';
			$junction_table = 'call_report-physical_abuse_junction'; 
			$junction_attribute = 'physical_abuse_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
		<br /><br />
			
		<label id="checkbox_title" for="verbal_abuse"><b>Verbal Abuse:</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'verbal_abuse';
			$option_table = 'call_report-verbal_abuse';
			$option_attribute = 'verbal_abuse';
			$junction_table = 'call_report-verbal_abuse_junction'; 
			$junction_attribute = 'verbal_abuse_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>			
	</div>
	
	<div id="floatleft">
		<label id="checkbox_title" for="mental_health"><b>Mental Health:</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'mental_health';
			$option_table = 'call_report-mental_health';
			$option_attribute = 'mental_health';
			$junction_table = 'call_report-mental_health_junction'; 
			$junction_attribute = 'mental_health_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
		<br /><br />
			
		<label id="checkbox_title" for="substance_abuse"><b>Substance Abuse and Addictions:</b></label>
			<br /><br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'substance_abuse';
			$option_table = 'call_report-substance_abuse';
			$option_attribute = 'substance_abuse';
			$junction_table = 'call_report-substance_abuse_junction'; 
			$junction_attribute = 'substance_abuse_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br />
	</div>
	
	<div id="floatleft">
		<label id="checkbox_title" for="physical_health"><b>Physical Health:</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'physical_health';
			$option_table = 'call_report-physical_health';
			$option_attribute = 'physical_health';
			$junction_table = 'call_report-physical_health_junction'; 
			$junction_attribute = 'physical_health_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br /><br />
			
		<label id="checkbox_title" for="general_issues"><b>General issues:</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"];
			$input_name = 'general_issues';			
			$option_table = 'call_report-general_issues';
			$option_attribute = 'general_issues';
			$junction_table = 'call_report-general_issues_junction'; 
			$junction_attribute = 'general_issues_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br />
	</div>
	
	<div id="floatleft">
		<label id="checkbox_title" for="interpersonal_issues"><b>Interpersonal issues:</b></label><br />
			<?
			$call_report_id = $report_Data["newReport_id"];
			$input_name = 'interpersonal_issues';
			$option_table = 'call_report-interpersonal_issues';
			$option_attribute = 'interpersonal_issues';
			$junction_table = 'call_report-interpersonal_issues_junction'; 
			$junction_attribute = 'interpersonal_issues_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br /><br />
			
		<label id="checkbox_title" for="personal_issues"><b>Personal issues:</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'personal_issues';
			$option_table = 'call_report-personal_issues';
			$option_attribute = 'personal_issues';
			$junction_table = 'call_report-personal_issues_junction'; 
			$junction_attribute = 'personal_issues_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
	</div>
	<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
	
	<h2>Call Information</h2>
	<hr />
	<div id="floatleft">
		<label id="checkbox_title" for="response_to_call"><b>Response to Call*:</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'response_to_call';
			$option_table = 'call_report-response_to_call';
			$option_attribute = 'response_to_call';
			$junction_table = 'call_report-response_to_call_junction'; 
			$junction_attribute = 'response_to_call_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br /><br />
	</div>
	
	<div id="floatleft">
		<label id="checkbox_title" for="call_content"><b>Overall Call Content*:</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'call_content';
			$option_table = 'call_report-call_content';
			$option_attribute = 'call_content';
			$junction_table = 'call_report-call_content_junction'; 
			$junction_attribute = 'call_content_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br /><br />
	</div>

	<div id="floatleft">
		<label id="checkbox_title" for="caller_reaction"><b>Caller Reaction*:</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'caller_reaction';
			$option_table = 'call_report-caller_reaction';
			$option_attribute = 'caller_reaction';
			$junction_table = 'call_report-caller_reaction_junction'; 
			$junction_attribute = 'caller_reaction_id';
				print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br />
	</div>

<div id="floatleft">
	<b>Caller Outcome*:</b>
</div>	
<br /><br />
	<div id="floatleft">
		<label id="checkbox_initial" id="checkbox_nolabel_title" for="initial_caller_outcome"><b>Initial</b></label>			
			<br/>
			<?
			$call_report_id = $report_Data["newReport_id"];
			$input_name = 'initial_caller_outcome';
			$option_table = 'call_report-caller_outcome';
			$option_attribute = 'caller_outcome';
			$junction_table = 'call_report-caller_outcome_initial_junction'; 
			$junction_attribute = 'initial_caller_outcome_id';
			$nolabel = 1;
				reverse_Print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute, $nolabel);
			?>
	</div>
	<div id="floatleft">
		<label id="checkbox_title" id="checkbox_nolabel_title" for="final_caller_outcome"><b>Final</b></label>
			<br />
			<?
			$call_report_id = $report_Data["newReport_id"]; 
			$input_name = 'final_caller_outcome';
			$option_table = 'call_report-caller_outcome';
			$option_attribute = 'caller_outcome';
			$junction_table = 'call_report-caller_outcome_final_junction'; 
			$junction_attribute = 'final_caller_outcome_id';
			$nolabel = 0;
				reverse_Print_Checkbox_Form($call_report_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute, $nolabel);
			?>
	</div>

	<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<label id="checkbox_title" for="call_description"><b>Call Description:</b></label>
<textarea id="text_call_description" name="call_description" rows="10" cols="121"><?echo $report_Data["call_description"];?></textarea>
	<br /><br />
<input type="submit" name="close" value="Close">
	<br /><br /><br /><br />
</form>
</div>
</body>
</html>
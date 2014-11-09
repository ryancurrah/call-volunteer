<?php
include "../session_function.php";
require_once '../global_vars.php';
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}
require_once '../global_vars.php';
require_once "functions/repeat_profile_function.php";

// Save id to session variable
if(isset($_GET['id'])){
	$_SESSION['repeat_edit_id'] = $_GET['id'];
}

$repeat_profile_id = $_SESSION['repeat_edit_id'];

$repeat_data = get_repeat_data($_SESSION['user_id'], $_SESSION['username'], $repeat_profile_id);

if(isset($_POST['submit'])){
	submit_Profile($_POST['repeat_number'], $_POST);
	$repeat_data = get_repeat_data($_SESSION['user_id'], $_SESSION['username'], $_POST['repeat_number']);
	//header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_SESSION['repeat_edit_id'].'');
}

if(empty($_SESSION['repeat_edit_page'])){
	$_SESSION['repeat_edit_page'] = $_SERVER['HTTP_REFERER'];
}

if(isset($_POST['close'])){
	submit_Profile($_POST['repeat_number'], $_POST);
	$repeat_data = get_repeat_data($_SESSION['user_id'], $_SESSION['username'], $repeat_profile_id);
	$error = close_repeat_edit($_SESSION['user_id'], $_SESSION['username'], $repeat_profile_id, $_POST);
}

if(isset($_POST['close_nosave'])){
	unset($_SESSION['repeat_edit_id']);
	$HTTP_REFERER = $_SESSION['repeat_edit_page'];
	unset($_SESSION['repeat_edit_page']);
	header('Location: '.$HTTP_REFERER.'');
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Edit repeat Profile</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="../css/NiftyLayout.css" media="screen">
		<script type="text/javascript" src="../jscript/niftycube.js"></script>
		<script type="text/javascript" src="../jscript/niftyLayout.js"></script>
		<script type="text/javascript" src="../jscript/jquery.js"></script>  
		<script type="text/javascript" src="../jscript/popup.js"></script>  
		<script type="text/javascript">
	$(document).ready(function(){			
		autosave();
	});
	
			function autosave(){
				var t = setTimeout("autosave()", 20000);
						
				var service_description = $("#text_service_description").val();
				var organization = $("#text_organization").val();
				var program = $("#text_program").val();
				var phone_number = $("#text_phone_number").val();
				var physical_address = $("#text_physical_address").val();
				var website_address = $("#text_website_address").val();
			
				if (service_description.length > 0 || organization.length > 0 || program.length > 0 || phone_number.length > 0 || physical_address.length > 0 || website_address.length > 0)
				{
					$.ajax(
					{
						type: "POST",
						url: "functions/repeat_autosave.php",
						data: "newrepeat_id=" + <?php echo $repeat_profile_id; ?> + "&service_description=" + service_description + "&organization=" + organization + "&program=" + program  + "&phone_number=" + phone_number + "&physical_address=" + physical_address+ "&website_address=" + website_address, 
						cache: false,
						success: function(message)
						{	
							$("#timestamp").empty().append(message);
						}
					});
				}
			}		 
	</script>
	</head>
<!--- BODY --->
<body>
<div id="header">
<!-- Navigation Menu -->
<a id="logout" href="../logout.php">Logout</a>
<h1><?echo $softwareTitle;?></h1>
	<div id="menu">
		<ul id="nav">
			<li id="home"><a href="../index.php">Home</a></li>
			<li id="vol"><a href="../volunteers.php">Volunteers</a></li>
			<li id="stats"><a href="../statistics.php">Statistics</a></li>
			<li id="help"><a href="../help.php">Help</a></li>
			<li id="admin" class="activelink"><a href="../administrators.php">Administrators</a></li>
		</ul>
	</div>
</div>
<!-- Welcome message -->
<div id="container">
	<ul id="intro">
		<li id="welcome"><p id="userwelcome">Welcome <b><? echo $_SESSION['fullname'] . "</b>! Volunteer Number <b>" . $_SESSION['volnum']; ?></b>.</p></li>
	</ul>
<!-- PAGE TITLE -->
<h2>Edit Repeat Caller Profile</h2>
	<a id="title" href="../administrators.php">Volunteers</a> <a id="title" href="#"> > </a> <a id="title" href="repeat_menu.php">Repeat Caller Profiles Menu</a> <a id="title" href="#"> > </a> <a id='title' href='repeat_edit.php?id=<? echo $repeat_profile_id;?>'>Edit Repeat Caller Profile</a>
<br /><br /><br />
<?// Check if user is Admin
if ($_SESSION['admin'] != true){
	echo "<center><p>Insufficient Privileges. You are trying to access a resticted page.</p><center>";
	echo "<center><a href='javascript:history.back(-1);'>Back</a><center><br /><br /><br /><br /><br /><br />"; 
	exit;
}
?>
<?
// display errors on save
if(isset($error)){
	#echo 'Status: '.$newrepeat_status;
	#echo '<br />ID: ' . $newrepeat_id;
	$keys = array_keys($error);
	for($i=1;$i<count($keys);$i++){
		echo $error[$keys[$i]];		
	}
}
?>

<!--- Note to the user --->
<small>
<h3>Note:</h3>
	<ul>
		<li>Starred (*) Items must be filled out.</li>
	</ul>
</small>
	
<!--- NEW CALL repeat FORM --->
<form name="start_repeat_form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<br />
<input type="submit" name="submit" value="Save">
<input type="submit" name="close" value="Save & Close">
<input type="submit" name="close_nosave" value="Close">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="Reset Form">


	<h2>Repeat Caller Profile</h2>
	<hr />
	<!--- FORM DATA --->
	<div id="floatleft">
		<label for="report_num">Repeat Profile Number:&nbsp;</label>
		<input type="text" name="repeat_number" value="<?echo $repeat_data["newRepeat_id"]?>" readonly="readonly">
			<br />
	</div>
	
	<div id="floatleft">
		<label for="caller_status">Caller Status:&nbsp;</label>
		<select name="caller_status">
			<option value="NULL"></option>
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"];
			$option_table = 'call_report-caller_status';
			$option_attribute = 'caller_status';
			$repeat_attribute = 'caller_status_id';
				print_Select_Form($repeat_caller_id, $option_table, $option_attribute, $repeat_attribute);
			?>
		</select>
	</div>
		<br /><br />
		
	<h2>Contact Information</h2>
	<hr />
	<!--- FORM DATA --->
	<div id="floatleft">
		<label for="caller_name">Name:&nbsp;</label>
			<?
			$attribute = 'caller_name';
			echo '<input id="text_caller_name" type="text" name="'.$attribute.'" value="'.$repeat_data[$attribute].'">';
			?>		
			<br />
		<label for="caller_phone">Phone Number:&nbsp;</label>
			<?
			$attribute = 'caller_phone';
			echo '<input id="text_caller_phone" type="text" name="'.$attribute.'" value="'.$repeat_data[$attribute].'">';
			?>
			<br />
		<label for="caller_address">Address:&nbsp;</label>
			<?
			$attribute = 'caller_address';
			echo '<input id="text_caller_address" type="text" name="'.$attribute.'" value="'.$repeat_data[$attribute].'">';
			?>
			<br />
	</div>
	
	<div id="floatleft">
		<label for="caller_postal">Postal Code:&nbsp;</label>
			<?
			$attribute = 'caller_postal';
			echo '<input id="text_caller_postal" type="text" name="'.$attribute.'" value="'.$repeat_data[$attribute].'">';
			?>
			<br />
		<label for="caller_city">City:&nbsp;</label>
		<select name="caller_city">
			<?
			$attribute = 'caller_city';
			if(isset($repeat_data[$attribute])){
				echo "<option STYLE='color:green'; value='".$repeat_data[$attribute]."'>".$repeat_data[$attribute]."</option>";	
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
		<br /><br /><br /><br /><br />
		
	<h2>Demographic Information</h2>
	<hr />
	<!--- FORM DATA --->
	<div id="floatleft">
		<label for="gender">Gender:&nbsp;</label>
		<select name="gender">
			<option value="NULL"></option>
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"];
			$option_table = 'call_report-gender';
			$option_attribute = 'gender';
			$repeat_attribute = 'gender_id';
				print_Select_Form($repeat_caller_id, $option_table, $option_attribute, $repeat_attribute);
			?>
		<select>
			<br />
			
		<label for="age_guess">Age Guess:&nbsp;</label>
		<select name="age_guess">
			<option value="NULL"></option>
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"];
			$option_table = 'call_report-age_guess';
			$option_attribute = 'age_guess';
			$repeat_attribute = 'age_guess_id';
				print_Select_Form($repeat_caller_id, $option_table, $option_attribute, $repeat_attribute);
			?>
		</select>
		
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Or..
			<br />
			
		<label for="living_status">Living Arrangement:&nbsp;</label>
		<select name="living_status">
			<option value="NULL"></option>
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"];
			$option_table = 'call_report-living_status';
			$option_attribute = 'living_status';
			$repeat_attribute = 'living_status_id';
				print_Select_Form($repeat_caller_id, $option_table, $option_attribute, $repeat_attribute);
			?>
		</select>
			<br />
			
		<label for="marital_status">Marital Status:&nbsp;</label>
		<select name="marital_status">
			<option value="NULL"></option>
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"];
			$option_table = 'call_report-marital_status';
			$option_attribute = 'marital_status';
			$repeat_attribute = 'marital_status_id';
				print_Select_Form($repeat_caller_id, $option_table, $option_attribute, $repeat_attribute);
			?>
		</select>												   
	</div>
	
	<div id="floatleft">
		<label for="economic_status">Economic Status:&nbsp;</label>
		<select name="economic_status">
			<option value="NULL"></option>
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"];
			$option_table = 'call_report-economic_status';
			$option_attribute = 'economic_status';
			$repeat_attribute = 'economic_status_id';
				print_Select_Form($repeat_caller_id, $option_table, $option_attribute, $repeat_attribute);
			?>
		</select>
			<br />
			
		<label for="age_confirmed">Age Confirmed:&nbsp;</label>
		<?
		$attribute = 'age_confirmed';
		echo '<input  id="text_age_confirmed" maxlength="3" size="1" type="text" name="'.$attribute.'" value="'.$repeat_data[$attribute].'">';
		?>
			<br />		
			
		<label for="financial_status">Financial Status:&nbsp;</label>
		<select name="financial_status">
			<option value="NULL"></option>
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"];
			$option_table = 'call_report-financial_status';
			$option_attribute = 'financial_status';
			$repeat_attribute = 'financial_status_id';
				print_Select_Form($repeat_caller_id, $option_table, $option_attribute, $repeat_attribute);
			?>
		</select>		
	</div>
		<br /><br /><br /><br /><br /><br />
		
	<h2>Call Content</h2>
	<hr />
	<div id="floatleft">
		<label id="checkbox_title" for="in_treatment"><b>In Treatment:</b></label>
			<br />
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"]; 
			$input_name = 'in_treatment';
			$option_table = 'call_report-in_treatment';
			$option_attribute = 'in_treatment';
			$junction_table = 'repeat_caller-in_treatment_junction'; 
			$junction_attribute = 'in_treatment_id';
				print_Checkbox_Form($repeat_caller_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br />
		
		<label id="sexuality" for="sexuality">Sexuality:</label>
		<select name="sexuality">
			<option value="NULL"></option>
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"];
			$option_table = 'call_report-sexuality';
			$option_attribute = 'sexuality';
			$repeat_attribute = 'sexuality_id';
				print_Select_Form($repeat_caller_id, $option_table, $option_attribute, $repeat_attribute);
			?>
		</select>	
			<br /><br />
			
		<label id="checkbox_title" for="physical_abuse"><b>Physical Abuse:</b></label>
			<br />
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"]; 
			$input_name = 'physical_abuse';
			$option_table = 'call_report-physical_abuse';
			$option_attribute = 'physical_abuse';
			$junction_table = 'repeat_caller-physical_abuse_junction'; 
			$junction_attribute = 'physical_abuse_id';
				print_Checkbox_Form($repeat_caller_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
		<br /><br />
			
		<label id="checkbox_title" for="verbal_abuse"><b>Verbal Abuse:</b></label>
			<br />
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"]; 
			$input_name = 'verbal_abuse';
			$option_table = 'call_report-verbal_abuse';
			$option_attribute = 'verbal_abuse';
			$junction_table = 'repeat_caller-verbal_abuse_junction'; 
			$junction_attribute = 'verbal_abuse_id';
				print_Checkbox_Form($repeat_caller_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>			
	</div>
	
	<div id="floatleft">
		<label id="checkbox_title" for="mental_health"><b>Mental Health:</b></label>
			<br />
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"]; 
			$input_name = 'mental_health';
			$option_table = 'call_report-mental_health';
			$option_attribute = 'mental_health';
			$junction_table = 'repeat_caller-mental_health_junction'; 
			$junction_attribute = 'mental_health_id';
				print_Checkbox_Form($repeat_caller_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
		<br /><br />
			
		<label id="checkbox_title" for="substance_abuse"><b>Substance Abuse and Addictions:</b></label>
			<br /><br />
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"]; 
			$input_name = 'substance_abuse';
			$option_table = 'call_report-substance_abuse';
			$option_attribute = 'substance_abuse';
			$junction_table = 'repeat_caller-substance_abuse_junction'; 
			$junction_attribute = 'substance_abuse_id';
				print_Checkbox_Form($repeat_caller_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br />
	</div>
	
	<div id="floatleft">
		<label id="checkbox_title" for="physical_health"><b>Physical Health:</b></label>
			<br />
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"]; 
			$input_name = 'physical_health';
			$option_table = 'call_report-physical_health';
			$option_attribute = 'physical_health';
			$junction_table = 'repeat_caller-physical_health_junction'; 
			$junction_attribute = 'physical_health_id';
				print_Checkbox_Form($repeat_caller_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br /><br />
			
		<label id="checkbox_title" for="general_issues"><b>General issues:</b></label>
			<br />
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"];
			$input_name = 'general_issues';			
			$option_table = 'call_report-general_issues';
			$option_attribute = 'general_issues';
			$junction_table = 'repeat_caller-general_issues_junction'; 
			$junction_attribute = 'general_issues_id';
				print_Checkbox_Form($repeat_caller_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br />
	</div>
	
	<div id="floatleft">
		<label id="checkbox_title" for="interpersonal_issues"><b>Interpersonal issues:</b></label><br />
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"];
			$input_name = 'interpersonal_issues';
			$option_table = 'call_report-interpersonal_issues';
			$option_attribute = 'interpersonal_issues';
			$junction_table = 'repeat_caller-interpersonal_issues_junction'; 
			$junction_attribute = 'interpersonal_issues_id';
				print_Checkbox_Form($repeat_caller_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
			<br /><br />
			
		<label id="checkbox_title" for="personal_issues"><b>Personal issues:</b></label>
			<br />
			<?
			$repeat_caller_id = $repeat_data["newRepeat_id"]; 
			$input_name = 'personal_issues';
			$option_table = 'call_report-personal_issues';
			$option_attribute = 'personal_issues';
			$junction_table = 'repeat_caller-personal_issues_junction'; 
			$junction_attribute = 'personal_issues_id';
				print_Checkbox_Form($repeat_caller_id, $input_name, $option_table, $option_attribute, $junction_table, $junction_attribute);
			?>
	</div>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<label id="checkbox_title" for="repeat_profile_description"><b>Repeat Profile Description:*</b></SPAN></label>
<textarea id="text_repeat_caller_description" STYLE="background-color: lightyellow" name="repeat_caller_description" rows="10" cols="123"><?echo $repeat_data["repeat_caller_description"];?></textarea>
	<br /><br />
<input type="hidden" name="newRepeat_id" value="<?php echo $repeat_data["newRepeat_id"] ?>" />
<input type="submit" name="submit" value="Save">
<input type="submit" name="close" value="Save & Close">
<input type="submit" name="close_nosave" value="Close">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="Reset Form">
<br /><br /><br /><br />
</form>
<div id="timestamp"></div>

</div>
</body>
</html>
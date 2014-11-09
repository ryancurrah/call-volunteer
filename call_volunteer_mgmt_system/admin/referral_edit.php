<?php
include "../session_function.php";
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}
require_once '../global_vars.php';
require_once "functions/referral_profile_function.php";

// Save timeclock_id to variable
if(isset($_GET['id'])){
	$_SESSION['referral_edit_id'] = $_GET['id'];
}

$referral_profile_id = $_SESSION['referral_edit_id'];

$referral_Data = get_Referral_Data($_SESSION['user_id'], $_SESSION['username'], $referral_profile_id);

if(isset($_POST['submit'])){
	if($_POST['hour'] != ''){
		$_POST['hours'] = $_POST['hour'] .':'. $_POST['minute'] .' '. $_POST['am_pm'] .' - '. $_POST['hour2'] .':'. $_POST['minute2'] .' '. $_POST['am_pm2'] .' '. $_POST['day'] .' to '. $_POST['day2'];
	}
	submit_referral($_POST['referral_number'], $_POST);
	$referral_Data = get_Referral_Data($_SESSION['user_id'], $_SESSION['username'], $_POST['referral_number']);
	//header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_SESSION['referral_edit_id'].'');
}

if(empty($_SESSION['referrer_edit_page'])){
	$_SESSION['referrer_edit_page'] = $_SERVER['HTTP_REFERER'];
}

if(isset($_POST['close'])){
	if($_POST['hour'] != ''){
		$_POST['hours'] = $_POST['hour'] .':'. $_POST['minute'] .' '. $_POST['am_pm'] .' - '. $_POST['hour2'] .':'. $_POST['minute2'] .' '. $_POST['am_pm2'] .' '. $_POST['day'] .' to '. $_POST['day2'];
	}
	submit_referral($_POST['referral_number'], $_POST);
	$referral_Data = get_Referral_Data($_SESSION['user_id'], $_SESSION['username'], $referral_profile_id);
	$error = close_referral_edit($_SESSION['user_id'], $_SESSION['username'], $referral_profile_id, $_POST);
}

if(isset($_POST['close_nosave'])){
	unset($_SESSION['referral_edit_id']);
	$HTTP_REFERER = $_SESSION['referrer_edit_page'];
	unset($_SESSION['referrer_edit_page']);
	header('Location: '.$HTTP_REFERER.'');
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Edit Referral Profile</title>
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
						url: "functions/referral_autosave.php",
						data: "newReferral_id=" + <?php echo $referral_profile_id; ?> + "&service_description=" + service_description + "&organization=" + organization + "&program=" + program  + "&phone_number=" + phone_number + "&physical_address=" + physical_address+ "&website_address=" + website_address, 
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
<h2>Edit Referral Profile</h2>
	<a id="title" href="../administrators.php">Volunteers</a> <a id="title" href="#"> > </a> <a id="title" href="referral_menu.php">Referral Profiles Menu</a> <a id="title" href="#"> > </a> <a id='title' href='referral_edit.php?id=<? echo $referral_profile_id;?>'>Edit Referral Profile</a>
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
	#echo 'Status: '.$newreferral_status;
	#echo '<br />ID: ' . $newReferral_id;
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
	
<!--- NEW CALL referral FORM --->
<form name="start_referral_form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<br />
<input type="submit" name="submit" value="Save">
<input type="submit" name="close" value="Save & Close">
<input type="submit" name="close_nosave" value="Close">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="Reset Form">

	<h2>Referral Profile</h2>
	<hr />
	<!--- FORM DATA --->
	<div id="floatleft">
		<label for="referral_number">Referral Number:&nbsp;</label>
		<input type="text" name="referral_number" value="<? echo $referral_profile_id ?>" size="3" readonly="readonly"/>
			<br />
		<label for="organization">Organization Name*:&nbsp;</label>
		<input id="text_organization" type="text" name="organization" value="<? echo $referral_Data['organization'] ?>" size="30"/>
			<br />
		<label for="referral_program">Program Name:&nbsp;</label>
		<input id="text_program" type="text" name="program" value="<? echo $referral_Data['program'] ?>" size="30"/>
			<br />		
		<label for="phone_number">Phone Number*:&nbsp;</label>
		<input id="text_phone_number" type="text" name="phone_number" value="<? echo $referral_Data['phone_number'] ?>" size="10"/><small>ex: xxx-xxx-xxxx</small>
			<br />		
		<label for="hours">Hours:&nbsp;</label>
		<?if($referral_Data['hours'] != ''){
			echo "<input type='text' name='hours' value='".$referral_Data['hours']."' size='35'/>";
		}
		else{
		?>
		<select name="hour">
			<option value=''></option>
			<?
			for($i=1;$i<13;$i++){
				echo "<option value='$i'>$i</option>";
			}
			?>	
		</select> :
		<select name="minute">
			<option value=''></option>
			<?
			$i = 00;
			while($i < 46){
				if($i == 00){
					echo "<option value='00'>00</option>";
				}
				else{
					echo "<option value='$i'>$i</option>";
				}
				$i+= 15;
			}
			?>	
		</select>
		<select name="am_pm">
			<option value=''></option>
			<option value='AM'>AM</option>
			<option value='PM'>PM</option>
		</select>
		-to- <br/>
		<label for="hours">&nbsp;</label>
		<select name="hour2">
			<option value=''></option>
			<?
			for($i=1;$i<13;$i++){
				echo "<option value='$i'>$i</option>";
			}
			?>	
		</select> :
		<select name="minute2">
			<option value=''></option>
			<?
			$i = 00;
			while($i < 46){
				if($i == 00){
					echo "<option value='00'>00</option>";
				}
				else{
					echo "<option value='$i'>$i</option>";
				}
				$i+= 15;
			}
			?>	
		</select>
		<select name="am_pm2">
			<option value=''></option>
			<option value='AM'>AM</option>
			<option value='PM'>PM</option>
		</select>
		<br/>
		<label for="hours">&nbsp;</label>
		<select name="day">
			<option value=''></option>
			<option value='Monday'>Monday</option>
			<option value='Tuesday'>Tuesday</option>
			<option value='Wednesday'>Wednesday</option>
			<option value='Thursday'>Thursday</option>
			<option value='Friday'>Friday</option>
			<option value='Saturday'>Saturday</option>
			<option value='Sunday'>Sunday</option>
		</select> -to- 
		<select name="day2">
			<option value=''></option>
			<option value='Monday'>Monday</option>
			<option value='Tuesday'>Tuesday</option>
			<option value='Wednesday'>Wednesday</option>
			<option value='Thursday'>Thursday</option>
			<option value='Friday'>Friday</option>
			<option value='Saturday'>Saturday</option>
			<option value='Sunday'>Sunday</option>
		</select>
		<?}?>
	</div>
	<br/>
	<div id="floatleft">
		<label for="physical_address">Physical Address:&nbsp;</label>
		<input id="text_physical_address" type="text" name="physical_address" value="<? echo $referral_Data['physical_address'] ?>" size="30"/>
			<br />
		<label for="city">City:&nbsp;</label>
		<select name="city">
			<?
			$attribute = 'city';
			if(isset($referral_Data[$attribute])){
				echo "<option STYLE='color:green'; value='".$referral_Data[$attribute]."'>".$referral_Data[$attribute]."</option>";	
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
			<br/>
		<label for="website_address">Website Address:&nbsp;</label>
		<input id="text_website_address" type="text" name="website_address" value="<? echo $referral_Data['website_address'] ?>" />
			<br />
		<label for="category">Category*:&nbsp;</label>
		<select name="category">
			<option value="NULL"></option>
			<?
			$option_table = 'referral_profile-category';
			$option_attribute = 'category';
			$referral_attribute = 'category_id';
				print_Select_Form($referral_profile_id, $option_table, $option_attribute, $referral_attribute);
			?>
		</select>
	</div>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<label id="checkbox_title" for="service_description">Service Description*:</label><BR />
<textarea id="text_service_description" name="service_description" rows="10" cols="121"><?echo $referral_Data["service_description"];?></textarea>
<br /><br />
<input type="hidden" name="newReferral_id" value="<?php echo $referral_Data["newReferral_id"] ?>" />
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
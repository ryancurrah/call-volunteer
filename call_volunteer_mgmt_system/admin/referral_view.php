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
	$_SESSION['referral_view_id'] = $_GET['id'];
}

$referral_profile_id = $_SESSION['referral_view_id'];

$referral_Data = get_Referral_Data($_SESSION['user_id'], $_SESSION['username'], $referral_profile_id);

if(empty($_SESSION['referral_view_referrer'])){
	$_SESSION['referral_view_referrer'] = $_SERVER['HTTP_REFERER'];
}

if(isset($_POST['close'])) {
	unset($_SESSION['referral_view_id']);
	$HTTP_REFERER = $_SESSION['referral_view_referrer'];
	unset($_SESSION['referral_view_referrer']);
	header('Location: '.$HTTP_REFERER.'');
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>View Referral Profile</title>
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
<h2>View Referral Profile</h2>
	<a id="title" href="../administrators.php">Volunteers</a> <a id="title" href="#"> > </a> <a id="title" href="referral_menu.php">Referral Profiles Menu</a> <a id="title" href="#"> > </a> <a id='title' href='referral_view.php'>View Referral Profile</a>
<br /><br /><br />
<?// Check if user is Admin
if ($_SESSION['admin'] != true){
	echo "<center><p>Insufficient Privileges. You are trying to access a resticted page.</p><center>";
	echo "<center><a href='javascript:history.back(-1);'>Back</a><center><br /><br /><br /><br /><br /><br />"; 
	exit;
}
?>

<!--- Note to the user --->
	
<!--- NEW CALL referral FORM --->
<form name="start_referral_form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<br />
<input type="submit" name="close" value="Close">
	<h2>Referral Profile</h2>
	<hr />
	<!--- FORM DATA --->
	<div id="floatleft">
		<label for="referral_number">Referral Number:&nbsp;</label>
		<input type="text" name="referral_referral_number" value="<? echo $referral_Data['newReferral_id'] ?>" size="3" readonly="readonly"/>
			<br />
		<label for="organization">Organization Name:&nbsp;</label>
		<input id="text_organization" type="text" name="organization" value="<? echo $referral_Data['organization'] ?>" size="30"readonly="readonly"/>
			<br />
		<label for="referral_program">Program Name:&nbsp;</label>
		<input id="text_program" type="text" name="program" value="<? echo $referral_Data['program'] ?>" size="30"readonly="readonly"/>
			<br />		
		<label for="phone_number">Phone Number:&nbsp;</label>
		<input id="text_phone_number" type="text" name="phone_number" value="<? echo $referral_Data['phone_number'] ?>" size="10"readonly="readonly"/><small>ex: xxx-xxx-xxxx</small>
			<br />		
		<label for="hours">Hours:&nbsp;</label>
		<input type="text" name="hours" value="<? echo $referral_Data['hours'] ?>" size="30"readonly="readonly"/>
			<br />		
	</div>
	<div id="floatleft">
		<label for="physical_address">Physical Address:&nbsp;</label>
		<input id="text_physical_address" type="text" name="physical_address" value="<? echo $referral_Data['physical_address'] ?>" size="30"readonly="readonly"/>
			<br />
		<label for="city">City:&nbsp;</label>
		<select name="city" disabled="disabled">
			<?
			$attribute = 'city';
			if(isset($referral_Data[$attribute])){
				echo "<option STYLE='color:green'; value='".$referral_Data[$attribute]."'>".$referral_Data[$attribute]."</option>";	
			} 
			?>
		</select>
			<br/>
		<label for="website_address">Website Address:&nbsp;</label>
		<input id="text_website_address" type="text" name="website_address" value="<? echo $referral_Data['website_address'] ?>" readonly="readonly"/>
			<br />
		<label for="category">Category:&nbsp;</label>
		<select name="category" disabled="disabled">
			<option value="NULL"></option>
			<?
			$referral_profile_id = $referral_Data['newReferral_id'];
			$option_table = 'referral_profile-category';
			$option_attribute = 'category';
			$referral_attribute = 'category_id';
				print_Select_Form($referral_profile_id, $option_table, $option_attribute, $referral_attribute);
			?>
		</select>
	</div>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<label id="checkbox_title" for="service_description">Service Description:</label><BR />
<textarea id="text_service_description" name="service_description" rows="10" cols="121" readonly="readonly"><?echo $referral_Data["service_description"];?></textarea>
<br /><br />
<input type="submit" name="close" value="Close">
<br /><br /><br /><br />
</form>

</div>
</body>
</html>
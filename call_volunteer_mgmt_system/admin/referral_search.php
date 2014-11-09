<?php
include "../session_function.php";
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}
require_once '../global_vars.php';
require_once "functions/referral_profile_function.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Referral Profile Search</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="../css/NiftyLayout.css" media="screen">
		<script type="text/javascript" src="../jscript/niftycube.js"></script>
		<script type="text/javascript" src="../jscript/niftyLayout.js"></script>
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
<h2>Referral Profile Search</h2>
	<a id="title" href="../administrators.php">Administrators</a> <a id="title" href="#"> > </a> <a id="title" href="referral_menu.php">Referral Menu</a> <a id="title" href="#"> > </a> </a> <a id="title" href="referral_search.php">Referral Profile Search</a>
<br /><br /><br />
<?
// Check if user is Admin
if ($_SESSION['admin'] != true){
	echo "<center><p>Insufficient Privileges. You are trying to access a resticted page.</p><center>";
	echo "<center><a href='javascript:history.back(-1);'>Back</a><center><br /><br /><br /><br /><br /><br />"; 
	exit;
}

if(isset($_POST['submit'])){
	require_once "functions/referral_search_function.php";
	$checkSearch = checkSearch($_POST['referral_profile_id'], $_POST['organization'], $_POST['program'], $_POST['category'], $_POST['city']);

	// 1 means there is an error in the form
	if($checkSearch != 1 ){
		userSearch($_POST['referral_profile_id'], $_POST['organization'], $_POST['program'], $_POST['category'], $_POST['city']);
	}
}
?>

<!--- USER SEARCH FORM --->
<div class="form">
	<form autocomplete="off" name="user_search_form" method="post" action="<?php echo $_SERVER['PHP_SELF']."?=".$_POST['organization']; ?>">
	<h3>Search by:</h3><p><small>Note: You can search by all criteria, some or just one.</small></p>
		<label for="referral_profile_id">Referral Number:&nbsp;</label>
		<input type="text" name="referral_profile_id">
			<br /><br />
		<label for="organization">Organization Name:&nbsp;</label>
		<select name="organization">
			<option value="NULL"></option>
			<?
			$referral_profile = 0;
			$option_table = 'referral_profile';
			$option_attribute = 'organization';
			$referral_attribute = 'organization';
				print_Select_Form($referral_profile, $option_table, $option_attribute, $referral_attribute);
			?>
		</select>
			<br /><br />			
		<label for="program">Program Name:&nbsp;</label>
		<input type="text" name="program">
			<br /><br />			
		<label for="category">Category:&nbsp;</label>
		<select name="category">
			<option value="NULL"></option>
			<?
			$referral_profile = 0;
			$option_table = 'referral_profile-category';
			$option_attribute = 'category';
			$referral_attribute = 'category_id';
				print_Select_Form($referral_profile, $option_table, $option_attribute, $referral_attribute);
			?>
		</select>
			<br /><br />	
		<label for="city">City:&nbsp;</label>
		<select name="city">
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
			<br /><br />
		<center>
			<label for="Search"></label>
			<input type="submit" name="submit" value="Search">
				<br /><br />
				<a href='http://home.currah.ca/admin/referral_menu.php'>Back</a>
		</center>			
	</form>
</div>

<br /><br /><br /><br /><br />
</div>
</body>
</html>
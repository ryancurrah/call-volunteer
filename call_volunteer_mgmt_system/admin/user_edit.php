<?php
include "../session_function.php";
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}
require_once '../global_vars.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>User Edit</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="../css/NiftyLayout.css" media="screen">
		<link rel="stylesheet" type="text/css" href="calendar/calendar.css" media="screen">
		<script type="text/javascript" src="../jscript/niftycube.js"></script>
		<script type="text/javascript" src="../jscript/niftyLayout.js"></script>
		<script language="javascript" src="../calendar/calendar.js"></script>
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
<h2>User Edit</h2>
	<a id="title" href="../administrators.php">Administrators</a> <a id="title" href="#"> > </a> <a id="title" href="user_database.php">User Database</a> <a id="title" href="#"> > </a> </a> <a id="title" href="user_edit.php">User Edit</a>
<br /><br />
<?
// Check if user is Admin
if ($_SESSION['admin'] != true){
	echo "<center><p>Insufficient Privileges. You are trying to access a resticted page.</p><center>";
	echo "<center><a href='javascript:history.back(-1);'>Back</a><center><br /><br /><br /><br /><br /><br />"; 
	exit;
}

// Validate and make changes
if(isset($_POST['submit'])){
	require_once "functions/user_edit_function.php";
	changeUserInfo($_POST['newpass'], $_POST['confirmpass'], $_POST['email'], $_POST['phone'], $_POST['administrator']);
}

require_once "../db_connector.php";
if(isset($_GET['id'])){
	$_SESSION['userdata_id'] = $_GET['id'];
}
$user_id  = $_SESSION['userdata_id'];

// Get current user information
open_mysql_connection();
$user_query = mysql_query("SELECT * FROM users WHERE user_id = '$user_id'");
$user_result = mysql_fetch_array($user_query);
close_mysql_connection();
?>

<!--- USER OPTIONS FORM --->
<div class="form">
<p>Now editing user (<? echo $user_result['username']; ?>)</p>
	<form name="useroptions_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<h3>Change Password</h3>
			<label for="newpass">New Password:&nbsp;</label>
			<input type="password" name="newpass">
				<br />
			<label for="confirmpass">Confirm New Password:&nbsp;</label>
			<input type="password" name="confirmpass">
				<br /><br />
		<h3>Change Email Address</h3>
			<label for="currentemail">Current Email Address:&nbsp;</label><? echo "<p>".$user_result['email']."</p>"; ?>
			
			<label for="email">New Email Address:&nbsp;</label>
			<input type="text" name="email">
				<br /><br />
		<h3>Change Phone Number</h3>
			<label for="currentphone">Current Phone Number:&nbsp;</label><? echo "<p>".$user_result['phone_number']."</p>"; ?>
			
			<label for="phone">New Phone Number:&nbsp;</label>
			<input type="text" name="phone">
				<br /><br />
			<label for="administrator">Administrator:&nbsp;</label>
			<?
			if($user_result['administrator'] == 1){
				echo "<select name='administrator'>
						<option value='0'>No</option>
						<option selected='selected' value='1'>Yes</option>
					</select>";
			}
			else{
				echo "<select name='administrator'>
						<option selected='selected' value='0'>No</option>
						<option value='1'>Yes</option>
					</select>";
			}
			?>
			
			<br /><br />
			<center>
				<label for="Change"></label>
				<input type="submit" name="submit" value="Change">
					<br /><br />
					<a href='http://home.currah.ca/admin/user_database.php'>Back</a>
			</center>
	</form>
</div>

<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
<?php
include "../session_function.php";
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>User Options</title>
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
<h1>Chaos2 Call Management System</h1>
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
<h2>User Options</h2>
	<a id="title" href="../volunteers.php">Volunteers</a><a id="title" href="#"> > </a> <a id="title" href="useroptions.php">User Options</a>
<br /><br />
<?
// Validate and make changes
if(isset($_POST['submit'])){
	require_once "functions/useroptions_function.php";
	changeUserInfo($_POST['currentpass'], $_POST['newpass'], $_POST['confirmpass'], $_POST['email'], $_POST['phone']);
}

require_once "../db_connector.php";
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
// Get current user information
open_mysql_connection();
$user_query = mysql_query("SELECT * FROM users WHERE username = '$username' AND user_id = '$user_id'");
$user_result = mysql_fetch_array($user_query);
close_mysql_connection();
?>

<!--- USER OPTIONS FORM --->
<div class="form">
	<form name="useroptions_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<h3>Change Password</h3>
			<label for="currentpass">Current Password:&nbsp;</label>
			<input type="password" name="currentpass">
				<br />
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
			<center>
				<label for="Change"></label>
				<input type="submit" name="submit" value="Change">
				<br /><br />
				<a href='http://home.currah.ca/volunteers.php'>Back</a>
			</center>
	</form>
</div>

<br /><br /><br /><br /><br />
</div>
</body>
</html>
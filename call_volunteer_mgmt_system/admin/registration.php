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
	<title>Register User</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="../css/NiftyLayout.css" media="screen">
		<script type="text/javascript" src="../jscript/niftycube.js"></script>
		<script type="text/javascript" src="../jscript/niftyLayout.js"></script>
		<script type="text/javascript" src="../jscript/form_validate.js"></script>
	</head>
<!--- BODY --->
<body>
<div id="header">
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

<div id="container">
	<ul id="intro">
		<li id="welcome"><p id="userwelcome">Welcome <b><? echo $_SESSION['fullname'] . "</b>! Volunteer Number <b>" . $_SESSION['volnum']; ?></b>.</p></li>
	</ul>
<h2>Register User</h2>
	<a id="title" href="../administrators.php">Administrators</a> <a id="title" href="#"> > </a> <a id="title" href="user_database.php">User Database</a> <a id="title" href="#"> > </a> <a id="title" href="registration.php">Register User</a>
<br /><br />
<?
// Check if user is Admin
if ($_SESSION['admin'] != true){
	echo "<center><p>Insufficient Privileges. You are trying to access a resticted page.</p><center>";
	echo "<center><a href='javascript:history.back(-1);'>Back</a><center><br /><br /><br /><br /><br /><br />"; 
	exit;
}

if(isset($_POST['submit'])){
	require_once "functions/registration_function.php";
	// Validate the registration this function will stop registration if failed
	userRegValidator($_POST['first_name'], $_POST['last_name'], $_POST['volunteer_number'], $_POST['email'], $_POST['phone_number'], $_POST['username'], $_POST['password'], $_POST['administrator']);
}
?>

<!--- REGISTRATION FORM --->
<div class="form">
	<form autocomplete="off" name="registration_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return Validate();">
		<label for="first_name">First Name:&nbsp;</label>
		<input type="text" name="first_name">
			<br />
		<label for="last_name">Last Name:&nbsp;</label>
		<input type="text" name="last_name">
			<br />
		<label for="volunteer_number">Volunteer Number:&nbsp;</label>
		<input type="text" name="volunteer_number">
			<br />
		<label for="email">Email Address:&nbsp;</label>
		<input type="text" name="email">
			<br />
		<label for="phone_number">Phone Number:&nbsp;</label>
		<input type="text" name="phone_number"/>
			<br />
		<label for="username">Username:&nbsp;</label>
		<input type="text" name="username"/>
			<br />
		<label for="password">Password:&nbsp;</label>
		<input type="password" name="password">
			<br />
		<label for="password_confirmation">Confirm Password:&nbsp;</label>
		<input type="password" name="password_confirmation">
			<br />
		<label for="administrator">Administrator:&nbsp;</label>
		<input type="checkbox" name="administrator" value="1" />
			<br /><br />
		<center>
			<label for="Register"></label>
			<input type="submit" name="submit" value="Register">
				<br /><br />
			<a href='http://home.currah.ca/admin/user_database.php'>Back</a>
		</center>
	</form>
</div>

<?
// If a registration was a success the user will be notified
if (isset($_GET['reg'])) {
	$reg = $_GET['reg'];
		if($reg == true) {
			?><script language="JavaScript">alert('Registration Successful!');</script><?
		}
}
?>
<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
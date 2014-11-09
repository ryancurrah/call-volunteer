<?php
include "session_function.php";
require_once 'global_vars.php';
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}
require_once 'global_vars.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Administrators</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="css/NiftyLayout.css" media="screen">
		<script type="text/javascript" src="jscript/niftycube.js"></script>
		<script type="text/javascript" src="jscript/niftyLayout.js"></script>
	</head>
<!--- BODY --->
<body>
<div id="header">
<a id="logout" href="../logout.php">Logout</a>
<h1><?echo $softwareTitle;?></h1>
	<div id="menu">
		<ul id="nav">
			<li id="home"><a href="index.php">Home</a></li>
			<li id="vol"><a href="volunteers.php">Volunteers</a></li>
			<li id="stats"><a href="statistics.php">Statistics</a></li>
			<li id="help"><a href="help.php">Help</a></li>
			<li id="admin" class="activelink"><a href="administrators.php">Administrators</a></li>
		</ul>
	</div>
</div>

<div id="container">
	<ul id="intro">
		<li id="welcome"><p id="userwelcome">Welcome <b><? echo $_SESSION['fullname'] . "</b>! Volunteer Number <b>" . $_SESSION['volnum']; ?></b>.</p></li>
	</ul>
<h2>Administrators</h2>
	<p id="title"> > Welcome to the Admin pannel!</p>
<br />
<?// Check if user is Admin
if ($_SESSION['admin'] != true){
	echo "<center><p>Insufficient Privileges. You are trying to access a resticted page.</p><center>";
	echo "<center><a href='javascript:history.back(-1);'>Back</a><center><br /><br /><br /><br /><br /><br />"; 
	exit;
}
?>
<table class="menu">
	<tr>
		<td><h3>inFORM</h3><a id="link_icons" href="admin/report_menu.php"><img id="icon" src="images/Config.png"></a></td>
		<td><h3>Repeat Caller Profiles</h3><a id="link_icons" href="admin/repeat_menu.php"><img id="icon" src="images/repeat.png"></a></td>
		<td><h3>Referral Profiles</h3><a id="link_icons" href="admin/referral_menu.php"><img id="icon" src="images/referral.jpg"></a></td>
		<td><h3>Timeclock Admin</h3><a id="link_icons" href="admin/timeclock_admin.php"><img id='icon' src="images/Clock.png"></a></td>
		<td><h3>User Database</h3><a id="link_icons" href="admin/user_database.php"><img id='icon' src="images/registeruser.png"></a></td>
	</tr>
</table>

<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
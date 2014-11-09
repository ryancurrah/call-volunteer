<?php
include "session_function.php";
require_once 'global_vars.php';
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: login_form.php");	
    exit;
}
require_once 'global_vars.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Volunteers</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
<!--	<meta http-equiv="X-UA-Compatible" content="IE=7">  -->
		<link rel="stylesheet" type="text/css" href="css/NiftyLayout.css" media="screen">
		<script type="text/javascript" src="jscript/niftycube.js"></script>
		<script type="text/javascript" src="jscript/niftyLayout.js"></script>
	</head>
<!--- BODY --->
<body>
<div id="header">
<!-- Navigation Menu -->
<a id="logout" href="logout.php">Logout</a>
<h1><?echo $softwareTitle;?></h1>
	<div id="menu">
		<ul id="nav">
			<li id="home"><a href="index.php">Home</a></li>
			<li id="vol" class="activelink"><a href="volunteers.php">Volunteers</a></li>
			<li id="stats"><a href="statistics.php">Statistics</a></li>
			<li id="help"><a href="help.php">Help</a></li>
			<li id="admin"><a href="administrators.php">Administrators</a></li>
		</ul>
	</div>
</div>
<!-- Welcome message -->
<div id="container">
	<ul id="intro">
		<li id="welcome"><p id="userwelcome">Welcome <b><? echo $_SESSION['fullname'] . "</b>! Volunteer Number <b>" . $_SESSION['volnum']; ?></b>.</p></li>
	</ul>
<!-- PAGE TITLE -->
<h2>Volunteers</h2>
<p id='title'> > Please remember to clock in and out!</p>
<br />
<table class="menu">
	<tr>
		<td><h3>inFORM</h3><a id="link_icons" href="volunteers/report_menu.php"><img id='icon' src="images/Config.png"></a></td>
		<td><h3>Repeat Profiles</h3><a id="link_icons" href="admin/repeat_menu.php"><img id="icon" src="images/repeat.png"></a></td>
		<td><h3>Referral Profiles</h3><a id="link_icons" href="admin/referral_menu.php"><img id="icon" src="images/referral.jpg"></a></td>
		<td><h3>Timeclock</h3><a id="link_icons"  href="volunteers/timeclock.php"><img id='icon' src="images/Clock.png"></a></td>
		<td><h3>User Options</h3><a id="link_icons" href="volunteers/useroptions.php"><img id='icon' src="images/Edit.png"></a></td>
	</tr>
</table>

<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
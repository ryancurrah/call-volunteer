<?php
include "session_function.php";
startSession();


require_once 'global_vars.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Logout</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="css/NiftyLayout.css" media="screen">
		<script type="text/javascript" src="jscript/niftycube.js"></script>
		<script type="text/javascript" src="jscript/niftyLayout.js"></script>
		<script type="text/javascript" src="jscript/jquery.js"></script>  
		<script type="text/javascript" src="jscript/popup.js"></script>  
	</head>
<!--- BODY --->
<body>
<div id="header">
<!-- Navigation Menu -->
<a id="logout" href="login_form.php">Login</a>
<h1><?echo $softwareTitle;?></h1>
	<div id="menu">
		<ul id="nav">
			<li id="home"><a href="index.php">Home</a></li>
			<li id="vol"><a href="volunteers.php">Volunteers</a></li>
			<li id="stats"><a href="statistics.php">Statistics</a></li>
			<li id="help"><a href="help.php">Help</a></li>
			<li id="admin"><a href="administrators.php">Administrators</a></li>
		</ul>
	</div>
</div>

<div id="container">

<h2>Logout</h2>
<br /><br />

<?
require_once "volunteers/functions/timeclock_function.php";
$timeclock_status = get_timeclock_status($_SESSION['user_id'], $_SESSION['username']);

if($_SESSION['authorized'] != true){
	echo "<center><p>You are not logged in. No logout has occured.</p></center>";
}
if($timeclock_status == 1){
?>
<center><a href="logout.php">Reload Page...</a></center>
    <div id="popupContact">  
        <a id="popupContactClose">x</a>  
        <h1>You have not clocked out.</h1>
		<p id="contactArea">
            It seems that you have not clocked out if you are at the end of your shift please clock out now.
            <br/><br/>  
            <a href="timeclock.php">Go to timeclock...</a>
            <br/><br/>  
            <b>Or</b>
            <br/><br/>
			If for some reason you need to logout without clocking out please click the link below.
			<br/><br/>
			<a href="logout_forced.php">Logout without clocking out...</a>
        </p>  
    </div>  
    <div id="backgroundPopup"></div>  
<?
}
else{
	// Destroy session variables and session
	session_unset();
	$session_d = session_destroy();
	setcookie(session_name(), '', time()-3600, '/');
	if($session_d != TRUE)
	{
		echo "<center><p>Logout has failed!<br />Please notify the administrator by email at <a href='mailto:admin@".$_SERVER['HTTP_HOST']."'>admin@".$_SERVER['HTTP_HOST']."</p></center>";
	}
	else{
		echo "<center><p>You have been logged out successfully!</p></center>";
	}
}
?>
<br/>
<br/>
<br/>
<br/>
</div>
</body>
</html>
	
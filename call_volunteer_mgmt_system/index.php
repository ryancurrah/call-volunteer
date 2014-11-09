<?php
include "session_function.php";
require_once 'global_vars.php';
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: login_form.php");	
    exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Home</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="css/NiftyLayout.css" media="screen">
		<script type="text/javascript" src="jscript/niftycube.js"></script>
		<script type="text/javascript" src="jscript/niftyLayout.js"></script>
	</head>
<!--- BODY --->
<body>
<div id="header">
<a id="logout" href="logout.php">Logout</a>
<h1><?echo $softwareTitle;?></h1>
	<div id="menu">
		<ul id="nav">
			<li id="home" class="activelink"><a href="index.php">Home</a></li>
			<li id="vol"><a href="volunteers.php">Volunteers</a></li>
			<li id="stats"><a href="statistics.php">Statistics</a></li>
			<li id="help"><a href="help.php">Help</a></li>
			<li id="admin"><a href="administrators.php">Administrators</a></li>
		</ul>
	</div>
</div>

<div id="container">
	<ul id="intro">
		<li id="welcome"><p id="userwelcome">Welcome <b><? echo $_SESSION['fullname'] . "</b>! Volunteer Number <b>" . $_SESSION['volnum']; ?></b>.</p></li>
	</ul>
<h2>Home</h2>
<p id='title'> > Latest News and Updates!</p>
<br />
<table>
<tr>
<td>
<?php
$session_length = ini_get('session.gc_maxlifetime');
echo "<p>Welcome user ". $_SESSION['username'] ." you have been authenticated.</p>";
echo "<p>Tracking with session ID: " . session_id() . "</p>";
echo "<p>Max session length: " . $session_length . " seconds or " . $session_length/60 . " minutes or " . $session_length/60/60 . " hours</p>"; 
?>
<br /><br /><br />
<b>Session data: </b>
<?
print_r ($_SESSION); 
?> 
<br /><br /><br />
<b>Cookie: </b>
<?
$CookieInfo = $_COOKIE;
print_r($CookieInfo);
?>
<br /><br /><br />
<b>Browser agent:</b>
<?
require_once 'browser_detection.php';
$ua=getBrowser();
$browser_dectect = $ua['name'] . " " . $ua['version'];
print_r($browser_dectect);
?>
</td>
</tr>
</table>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
</div>
</body>
</html>
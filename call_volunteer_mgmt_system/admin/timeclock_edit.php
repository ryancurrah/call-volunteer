<?php
include "../session_function.php";
require_once '../global_vars.php';
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
	<title>Timeclock Admin</title>
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
<h2>Timeclock Admin</h2>
	<a id="title" href="../administrators.php">Administrators</a> <a id="title" href="#"> > </a> <a id="title" href="timeclock_admin.php">Timeclock Admin</a>
<br /><br />
<?
// Check if user is Admin
if ($_SESSION['admin'] != true){
	echo "<center><p>Insufficient Privileges. You are trying to access a resticted page.</p><center>";
	echo "<center><a href='javascript:history.back(-1);'>Back</a><center><br /><br /><br /><br /><br /><br />"; 
	exit;
}
require_once '../db_connector.php';
if (isset($_GET['id'])){
// Save timeclock_id to variable
$_SESSION['edit_timeclock_id'] = $_GET['id'];
}

open_mysql_connection();

if(isset($_POST['change'])){
	$login = $_POST['login'];
	$logout = $_POST['logout'];
	//echo $login .', '.$logout.', '.$_SESSION['timeclock_id'];
	mysql_query('UPDATE timeclock SET login_time = "'.$login.'", logout_time = "'.$logout.'", time_diff = TIMEDIFF("'.$logout.'", "'.$login.'") WHERE timeclock_id = "'.$_SESSION['edit_timeclock_id'].'"');
	?>
	<div class="form">
		<center>
			<p id='success'>Record Changed Successfully</p>
			<br /><a href='timeclock_admin.php'>Back</a>
		</center>
	</div>
	<br /><br /><br /><br /><br /><br />
	<?
	close_mysql_connection();
	exit();
}

$timeclock_query = mysql_query("SELECT * FROM timeclock WHERE timeclock_id = '".$_SESSION['edit_timeclock_id']."'");
$timeclock_result = mysql_fetch_array($timeclock_query);
close_mysql_connection();

?>
<h1>Editing <? echo $timeclock_result['fullname'] ."'s"; ?> timeclock entry</h1>
<div class="form">
	<form name="timeclock_edit_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<center><h3>Date Format:<br />yyyy-mm-dd hh:mm:ss</h3></center>
		<h3>Change Clock In Time</h3>
			<label for="login">Clock In Time:&nbsp;</label><input type="text" name="login" value="<? echo $timeclock_result['login_time']; ?>"><br />
		<h3>Change Clock Out Time</h3>
			<label for="logout">Clock Out Time:&nbsp;</label><input type="text" name="logout" value="<? echo $timeclock_result['logout_time']; ?>"><br /><br />
			<center><label for="Change"></label><input type="submit" name="change" value="Change"><br /><br /><a href='timeclock_admin.php'>Back</a></center>
	</form>
</div>

<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
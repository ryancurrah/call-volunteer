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

?>
<form name="timeframe" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php
//get class into the page
require_once('calendar/tc_calendar.php');
	$today = date("Y-m-d");
	$date1_default = $today;
	$date2_default = $today;
	?>
<p class="largetxt"><b>Choose a date range:</b></p>
<div style="float: left;">
<div style="float: left; padding-right: 4px; line-height: 18px;">from:</div>
    <div style="float: left;">
		<?
		$myCalendar = new tc_calendar("date1", true, false);
		$myCalendar->setIcon("calendar/images/iconCalendar.gif");
		$myCalendar->setDate(date('d', strtotime($date1_default))
			, date('m', strtotime($date1_default))
			, date('Y', strtotime($date1_default)));
		$myCalendar->setPath("calendar/");
		$myCalendar->setYearInterval(2011, 2030);
		$myCalendar->setDatePair('date1', 'date2', $date2_default);
		//output the calendar
		$myCalendar->writeScript();	  
		?>
	</div>
</div>             
<div style="float: left;">
<div style="float: left; padding-left: 3px; padding-right: 4px; line-height: 18px;">to:</div>
    <div style="float: left;">
		<?
		$myCalendar = new tc_calendar("date2", true, false);
		$myCalendar->setIcon("calendar/images/iconCalendar.gif");
		$myCalendar->setDate(date('d', strtotime($date2_default))
			, date('m', strtotime($date2_default))
			, date('Y', strtotime($date2_default)));
		$myCalendar->setPath("calendar/");
		$myCalendar->setYearInterval(2011, 2030);
		$myCalendar->setAlignment('left', 'bottom');
		$myCalendar->setDatePair('date1', 'date2', $date1_default);
		//output the calendar
		$myCalendar->writeScript();	  
		?>
	</div>
</div>
&nbsp;<input type="submit" name="select" value="Select">
</form>
<br />
<!-- Display the table based on the date selected -->
<?
if (isset($_REQUEST['date1'])){
	$_SESSION['dateFrom'] = $_REQUEST['date1'];
	$_SESSION['dateTo'] = $_REQUEST['date2'];
}

if (isset($_SESSION['dateFrom'])){
	require_once "functions/timeclock_admin_function.php";
	$dateFrom = $_SESSION['dateFrom'];
	$dateTo = $_SESSION['dateTo'];
	//echo "$dateFrom to $dateTo";
	display_timeclock($dateFrom, $dateTo);
	$count = count_rows($dateFrom, $dateTo);
}
?>

<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
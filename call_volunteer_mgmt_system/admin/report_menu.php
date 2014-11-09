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
	<title>Call Report Menu</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="../css/NiftyLayout.css" media="screen">
		<script type="text/javascript" src="../jscript/niftycube.js"></script>
		<script type="text/javascript" src="../jscript/niftyLayout.js"></script>
		<script type="text/javascript" src="../jscript/jquery.js"></script>  
		<script type="text/javascript" src="../jscript/popup.js"></script>  
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
<h2>Call Report Menu</h2>
	<a id="title" href="../administrators.php">Administrators</a> <a id="title" href="#"> > </a> <a id="title" href="report_menu.php">Call Report Menu</a>
<br /><br />
<?// Check if user is Admin
if ($_SESSION['admin'] != true){
	echo "<center><p>Insufficient Privileges. You are trying to access a resticted page.</p><center>";
	echo "<center><a href='javascript:history.back(-1);'>Back</a><center><br /><br /><br /><br /><br /><br />"; 
	exit;
}
?>
<center>
<?
// Report menu links 
	require_once('../db_connector.php');
?>
<a id="title" href='#'>Comment On Calls</a> | <a id="title" href='#'>Crisis Calls</a> | <a id="title" href='report_search.php'>Report Search</a>
		<br /><br />
	<p>Showing last 20 reports...</p>
</center>
<?
require_once "../db_connector.php";
open_mysql_connection();
$call_report_query = mysql_query ('SELECT * FROM call_report ORDER BY call_report_id DESC LIMIT 0, 20');
close_mysql_connection();
?>
<!--
<small>
<h3>Note:</h3>
	<ul>
		<li></li>
	</ul>
</small>
-->

<table class='result-table'>
	<tr>
		<th id='table_id'>Report #</th>
		<th id='table_time'>Start Time</th>
		<th id='table_time'>End Time</th>
		<th>Call<br />Length<br><small>hh:mm:ss</small></th>
		<th id="table_vol_num">Volunteer #</th>		
		<th>Volunteer Name</th>
		<th id='table_line'>Line</th>
		<th>Caller Name</th>
		<th>View</th>
		<th>Edit</th>
	</tr>
<? while($row = mysql_fetch_array($call_report_query)){ 
  echo "<tr>";
	echo "<td>". $row['call_report_id'] ."</td>";
	echo "<td>". $row['start_time'] ."</td>";
	echo "<td>". $row['end_time'] ."</td>";
	echo "<td>". $row['call_length'] ."</td>";
	echo "<td>". $row['volunteer_number'] ."</td>";
	echo "<td>". $row['volunteer_name'] ."</td>";
	echo "<td>". $row['line_id'] ."</td>";	
	echo "<td>". $row['caller_name'] ."</td>";
	echo "<td><a href='view_report.php?id=". $row['call_report_id'] ."'><img src='../images/document.png' width='27' height='30' border='0' alt='View' /></a></td>";
	echo "<td><a href='#'><img src='../images/Edit.png' width='20' height='30' border='0' alt='Edit' /></a></td>";
  echo "</tr>";
}?>
</table>
<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
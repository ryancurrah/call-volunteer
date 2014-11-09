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
	<title>Repeat Caller Profiles Menu</title>
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
<h2>Repeat Caller Profiles Menu</h2>
	<a id="title" href="../administrators.php">Administrators</a> <a id="title" href="#"> > </a> <a id="title" href="repeat_menu.php">Repeat Caller Profiles Menu</a>
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
	require_once('../db_connector.php');
	open_mysql_connection();
	$query = mysql_query('SELECT newRepeat_status FROM users WHERE user_id = "'.$_SESSION['user_id'].'" AND username = "'.$_SESSION['username'].'"');
	$result = mysql_fetch_array($query);
	if($result['newRepeat_status'] == 1){echo "<a id='title' href='repeat_profile.php'>Continue Repeat Caller Profile</a>";}
	else{echo "<a id='title' href='repeat_profile.php'>Create Repeat Caller Profile</a>";}
	close_mysql_connection();
?>
 | <a id="title" href='#'>Repeat Caller Profile Search</a>
		<br /><br />
	<p>Showing last 20 repeat callers...</p>
</center>
<!--
<small>
<h3>Note:</h3>
	<ul>
		<li></li>
	</ul>
</small>
-->
<?
require_once "../db_connector.php";
open_mysql_connection();
$query = mysql_query ('SELECT * FROM repeat_caller ORDER BY repeat_caller_id DESC LIMIT 0, 20');
close_mysql_connection();
?>
<table class='result-table'>
	<tr>
		<th>Profile<br />#</th>
		<th>Name</th>
		<th>Age</th>
		<th>Status</th>
		<th>City</th>
		<th>Gender</th>
		<th>View</th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
<? while($row = mysql_fetch_array($query)){ 
  echo "<tr>";
	echo "<td>". $row['repeat_caller_id'] ."</td>";
	echo "<td>". $row['caller_name'] ."</td>";
	if(empty($row['age_confirmed'])){echo "<td>". $row['age_guess_id'] ."</td>";}else{echo "<td>". $row['age_confirmed'] ."</td>";}
	echo "<td>". $row['caller_status_id'] ."</td>";
	echo "<td>". $row['city'] ."</td>";
	echo "<td>". $row['gender_id'] ."</td>";
	echo "<td><a href='repeat_view.php?id=". $row['repeat_caller_id'] ."'><img src='../images/document.png' width='27' height='30' border='0' alt='View' /></a></td>";
	echo "<td><a href='repeat_edit.php?id=". $row['repeat_caller_id'] ."'><img src='../images/Edit.png' width='20' height='30' border='0' alt='Edit' /></a></td>";
    echo "<td><a href='repeat_delete.php?id=".$row['repeat_caller_id']."'><img src='../images/Delete.png' width='20' height='30' border='0' alt='Delete' /></a></td>"; 
  echo "</tr>";
}?>
</table>
<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
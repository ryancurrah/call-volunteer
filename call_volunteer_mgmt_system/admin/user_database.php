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
	<title>User Database</title>
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
<h2>User Database</h2>
	<a id="title" href="../administrators.php">Administrators</a> <a id="title" href="#"> > </a> <a id="title" href="user_database.php">User Database</a>
<br /><br />
<?
// Check if user is Admin
if ($_SESSION['admin'] != true){
	echo "<center><p>Insufficient Privileges. You are trying to access a resticted page.</p><center>";
	echo "<center><a href='javascript:history.back(-1);'>Back</a><center><br /><br /><br /><br /><br /><br />"; 
	exit;
}
?>

<center>
	<a id="title" href='registration.php'>Register User</a> | <a id="title" href='user_search.php'>User Search</a>
		<br /><br />
	<p>Showing the last 10 registered users...</p>
</center>

<?
require_once "../db_connector.php";
open_mysql_connection();
$user_query = mysql_query ('SELECT * FROM users ORDER BY user_id DESC LIMIT 0, 10');
close_mysql_connection();
?>

<table class='result-table'>
	<tr>
		<th>User Name</th>
		<th>Registration Date</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Volunteer<br />Number</th>
		<th>Email Address</th>
		<th>Phone Number</th>
		<th>Clocked In</th>
		<th>Admin</th>
		<th>Edit<br />User</th>
		</tr>
<? while($row = mysql_fetch_array($user_query)){ 
  echo "<tr>";
	echo "<td>". $row['username'] ."</td>";
	echo "<td>". $row['registration_date'] ."</td>";
	echo "<td>". $row['first_name'] ."</td>";
	echo "<td>". $row['last_name'] ."</td>";
	echo "<td>". $row['volunteer_number'] ."</td>";
	echo "<td>". $row['email'] ."</td>";
	echo "<td>". $row['phone_number'] ."</td>";
	if($row['timeclock_status'] == 1){echo "<td> Yes </td>";} else {echo "<td> No </td>";}
	if($row['administrator'] == 1){echo "<td> Yes </td>";} else {echo "<td> No </td>";}
	echo "<td><a href='user_edit.php?id=". $row['user_id'] ."'><img src='../images/Edit.png' width='20' height='30' border='0' alt='Edit' /></a></td>";
  echo "</tr>";
}?>
</table>

<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
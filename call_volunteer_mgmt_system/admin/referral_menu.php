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
	<title>Referral Profiles Menu</title>
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
<h2>Referral Profiles Menu</h2>
	<a id="title" href="../administrators.php">Administrators</a> <a id="title" href="#"> > </a> <a id="title" href="referral_menu.php">Referral Profiles Menu</a>
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
	$query = mysql_query('SELECT newReferral_status FROM users WHERE user_id = "'.$_SESSION['user_id'].'" AND username = "'.$_SESSION['username'].'"');
	$result = mysql_fetch_array($query);
	if($result['newReferral_status'] == 1){echo "<a id='title' href='referral_profile.php'>Continue Referral Profile</a>";}
	else{echo "<a id='title' href='referral_profile.php'>Create Referral Profile</a>";}
	close_mysql_connection();
?>
 | <a id="title" href='referral_search.php'>Referral Profile Search</a>
		<br /><br />
	<p>Showing last 20 referral profiles...</p>
</center>
<?
require_once "../db_connector.php";
open_mysql_connection();
$query = mysql_query ('SELECT * FROM referral_profile ORDER BY referral_profile_id DESC LIMIT 0, 20');
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
		<th>Referral<br />Number</th>
		<th>Organization</th>	
		<th>Category</th>
		<th>Phone Number</th>
		<th>Hours</th>
		<th>Website</th>
		<th>City</th>		
		<th>View</th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
<? while($row = mysql_fetch_array($query)){ 
  echo "<tr>";
	echo "<td>". $row['referral_profile_id'] ."</td>";
	echo "<td>". $row['organization'] ."</td>";
	echo "<td>". $row['category_id'] ."</td>";
	echo "<td>". $row['phone_number'] ."</td>";
	echo "<td>". $row['hours'] ."</td>";
	echo "<td><a href='http://". $row['website_address'] ."' target='_blank'>". $row['website_address'] ."</a></td>";
	echo "<td>". $row['city'] ."</td>";	
	echo "<td><a href='referral_view.php?id=". $row['referral_profile_id'] ."'><img src='../images/document.png' width='27' height='30' border='0' alt='View' /></a></td>";
  	echo "<td><a href='referral_edit.php?id=". $row['referral_profile_id'] ."'><img src='../images/Edit.png' width='20' height='30' border='0' alt='Edit' /></a></td>";
 	echo "<td><a href='referral_delete.php?id=".$row['referral_profile_id']."'><img src='../images/Delete.png' width='20' height='30' border='0' alt='Delete' /></a></td>"; 
  echo "</tr>";
}?>
</table>
<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
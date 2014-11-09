<?php
include "../session_function.php";
require_once '../global_vars.php';
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Referral Profile(Delete Record)</title>
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
<h2>Referral Profile(Delete Record)</h2>
	<a id="title" href="../administrators.php">Administrators</a> <a id="title" href="#"> > </a> <a id="title" href="referral_menu.php">Referral Profiles Menu</a>
<br /><br />
<?
// Check if user is Admin
if ($_SESSION['admin'] != true){
	echo "<center><p>Insufficient Privileges. You are trying to access a resticted page.</p><center>";
	echo "<center><a href='javascript:history.back(-1);'>Back</a><center><br /><br /><br /><br /><br /><br />"; 
	exit;
}
require_once '../db_connector.php';

	// Save referral_profile_id to variable
	if(isset($_GET['id'])){
		$_SESSION['referrer_del_id'] = $_GET['id'];
	}
	
// Security check to see if referrer page is as per defined and if not than a error will apear
if($_SERVER['HTTP_REFERER'] == "http://$siteURL/admin/referral_menu.php" || isset($_SESSION['referrer_del_id']) || $_SERVER['HTTP_REFERER'] == "http://$siteURL/admin/referral_search.php?id=".$_SESSION['referrer_del_id'].""){
	
	// Delete record if DELETE is submitted
	if(isset($_POST['delete'])){
		open_mysql_connection();
		$query = mysql_query("DELETE FROM referral_profile WHERE referral_profile_id = '".$_SESSION['referrer_del_id']."'");
		close_mysql_connection();
		?>
		<div class="form">
			<center>
				<h3>Record Deleted Successfully</h3>
					<br />
				<a href='referral_menu.php'>Back</a>
			</center>
		</div>
		<br /><br /><br /><br /><br /><br />
		<?
		unset($_SESSION['referrer_del_id']);
		exit();
	}

	// DOES NOT delete record and returns user to previous page
	if(isset($_POST['cancel'])){
		unset($_SESSION['referrer_del_id']);
		header('Location: referral_menu.php');
	}

	require_once "../db_connector.php";
	open_mysql_connection();
	$query = mysql_query ("SELECT referral_profile_id, organization FROM referral_profile WHERE referral_profile_id = '".$_SESSION['referrer_del_id']."'");
	$result = mysql_fetch_array($query);
	close_mysql_connection();
	?>
	<h1>Delete '<? echo $result['organization']?>' with referral profile entry number <? echo $result['referral_profile_id']; ?>.</h1>
		<div class="form">
			<center><h3>Are you sure you want to delete this record?</h3></center>
			<form name="delete_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<center>
					<input type="submit" name="delete" value="Yes">
						&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" name="cancel" value="No">
						<br /><br />
				</center>
			</form>
		</div>
<?
}
else{
// Else there is probrlem with the referring page and offends explict rules set
echo "<p>You have tried to access this page in a impicit way. This page requires you to access it in an explicit way. ".
   	"If you beleive you reached this in error please contact the support at ".
	"<a href='mailto:$adminEmail?subject=Delete record error&body=I have reached the delete record page in error. Todays date is ".date('l jS \of F Y h:i:s A').". ------Username: ".$_SESSION['username']." ------Browser: ".$_SERVER['HTTP_USER_AGENT']."'>$adminEmail</a>".
	"</p>";
}
?>

<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
<?php
include "../session_function.php";
startSession();

if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Timeclock</title>
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
<h1>Chaos2 Call Management System</h1>
	<div id="menu">
		<ul id="nav">
			<li id="home"><a href="../index.php">Home</a></li>
			<li id="vol" class="activelink"><a href="../volunteers.php">Volunteers</a></li>
			<li id="stats"><a href="../statistics.php">Statistics</a></li>
			<li id="help"><a href="../help.php">Help</a></li>
			<li id="admin"><a href="../administrators.php">Administrators</a></li>
		</ul>
	</div>
</div>
<!-- Welcome message -->
<div id="container">
	<ul id="intro">
		<li id="welcome"><p id="userwelcome">Welcome <b><? echo $_SESSION['fullname'] . "</b>! Volunteer Number <b>" . $_SESSION['volnum']; ?></b>.</p></li>
	</ul>
<!-- PAGE TITLE -->
<h2>Timeclock</h2>
	<a id="title" href="../volunteers.php">Volunteers</a> <a id="title" href="#"> > </a> <a id="title" href="timeclock.php">Timeclock</a>
<br /><br />
<?
include ("functions/timeclock_function.php");
include ("functions/date_time.php");

// store end of shift note
if(isset($_POST['note'])){
	submit_note($_SESSION['user_id'] ,$_SESSION['username'], $_POST['note']);
}
// clock in 
if(isset($_POST['timein'])){
   clock_in($_SESSION['user_id'], $_SESSION['username'],$_SESSION['fullname'], $_SESSION['volnum']);
}
// clock out 
if(isset($_POST['timeout'])){
	clock_out($_SESSION['user_id'] ,$_SESSION['username']);
	?>
	<div id="popupContact">  
    <a id="popupContactClose">x</a>  
       <h1>Shift Note</h1>
		<p id="contactArea">
			<form name="note" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<textarea cols="48" rows="18" name="note">End of shift note...</textarea>
					<br />
				<center>
					<input type="submit" name="submit" value="Submit Note"/>
				</center>
			</form>
		</p>
    </div>  
    <div id="backgroundPopup"></div>  
	<?
}
// will either return a 1 for clocked in or 0 for clocked out
$timeclock_status = get_timeclock_status($_SESSION['user_id'], $_SESSION['username']);
//echo "".$timeclock_status."";        //only use to see if function is working correctly
?>

<!--- Timeclock In / Out Form --->
<div class="form">
	<form name="timeclock_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table class="timeclock">
		<tr>	
			<? 
			if($timeclock_status == 1){
				echo "<center><b>Current Date and Time is: </b><br/>" . print_date_time() . "</center>";
				?>
				<td>
					<h3>Timeclock In</h3>
					<label for="timein"></label>
					<input type="submit" name="timein" value="Clock In" disabled>
				</td>
				<td>
					<h3>Timeclock Out</h3>
					<label for="timeout"></label>
					<input type="submit" name="timeout" value="Clock Out">
				</td>
				<?
			}
			if($timeclock_status == 0){
				echo "<center><b>Current Date and Time is: </b><br/>" . print_date_time() . "</center>";
				?>
				<td>
					<h3>Timeclock In</h3>
					<label for="timein"></label>
					<input type="submit" name="timein" value="Clock In">
				</td>
				<td>
					<h3>Timeclock Out</h3>
					<label for="timeout"></label>
					<input type="submit" name="timeout" value="Clock Out" disabled>
				</td>
				<?
			}
			?>
		</tr>
		</table>
			<br /><br />
		<center><a href='http://home.currah.ca/volunteers.php'>Back</a></center>			
	</form>
</div>

<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>
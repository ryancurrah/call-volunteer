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
	<title>Report Search</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="../css/NiftyLayout.css" media="screen">
		<link rel="stylesheet" type="text/css" href="calendar/calendar.css" media="screen">
		<script type="text/javascript" src="../jscript/niftycube.js"></script>
		<script type="text/javascript" src="../jscript/niftyLayout.js"></script>
		<script language="javascript" src="calendar/calendar.js"></script>
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
<h2>Report Search</h2>
	<a id="title" href="../volunteers.php">Volunteers</a> <a id="title" href="#"> > </a> <a id="title" href="report_menu.php">Call Report Menu</a> <a id="title" href="#"> > </a> <a id="title" href="report_search.php">Report Search</a>
<br /><br />

<?
require_once "functions/report_search_function.php";
if(isset($_POST['submit'])){
/*
echo "<br />".$_POST['date1'];
echo "<br />".$_POST['date2'];
echo "<br />".$_POST['calendar'];
echo "<br />".$_POST['call_report_id'];
echo "<br />".$_POST['username'];
echo "<br />".$_POST['volunteer_number'];
echo "<br />".$_POST['line_id'];
*/

$checkSearch = checkSearch($_POST['date1'], $_POST['date2'], $_POST['calendar'], $_POST['call_report_id'], $_POST['username'], $_POST['volunteer_number'], $_POST['line_id']);

// 1 means there is an error in the form
if($checkSearch != 1 ){
	reportSearch($_POST['date1'], $_POST['date2'], $_POST['calendar'], $_POST['call_report_id'], $_POST['username'], $_POST['volunteer_number'], $_POST['line_id']);
}

}
?>

<!--- REPORT SEARCH FORM --->
<div class="form">
	<form name="report_search_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<h3>Search by:</h3>
	<p><small>Note: You can search by all criteria, some or just one.</small></p>
		<?
		//get class into the page
		require_once('calendar/tc_calendar.php');
		$today = date("Y-m-d");
		$date1_default = $today;
		$date2_default = $today;
		?>
	
	<h3>Choose a date range:</h3>
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
			<br /><br />
		<label for="calendar">Search by Date:&nbsp;</label>
		<input type="checkbox" name="calendar" value="1">
			<br /><br /><br />
		<label for="call_report_id">Report Number:&nbsp;</label>
		<input type="text" name="call_report_id">
			<br /><br />	
		<label for="username">Username:&nbsp;</label>
		<input type="text" name="username">
			<br /><br />			
		<label for="volunteer_number">Volunteer Number:&nbsp;</label>
		<input type="text" name="volunteer_number">
			<br /><br />			
		<label for="line_id">Call line:&nbsp;</label>
		<select name="line_id">
			<option value=""></option>
			<?
			$option_table = 'call_report-line';
			$option_attribute = 'line';
			print_Select_Form($option_table, $option_attribute);
			?>
		</select> 
		
		<center>
				<br /><br />
			<input type="submit" name="submit" value="Search">
				<br /><br />
			<a href='http://home.currah.ca/volunteers/report_menu.php'>Back</a>
		</center>			
	</form>
</div>

<br /><br /><br /><br /><br /><br />
</div>
</body>
</html>

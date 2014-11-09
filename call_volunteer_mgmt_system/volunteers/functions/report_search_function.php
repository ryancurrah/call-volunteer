<?
if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

	require_once "../db_connector.php";
	
 // Function Name:	checkSearch
 //       Purpose:  checks to make sure search data entered correctly 
 //       Accepts:  first name, last name, vol number, email, username
 //       Returns:  an error status of 1 or nothing
function checkSearch($date1, $date2, $calendar, $call_report_id, $username, $volunteer_number, $line_id){

	if(empty($calendar) && empty($call_report_id) && empty($username) && empty($volunteer_number) && empty($line_id)){
		echo "<center><h3>You have have not entered anything into the search!</h3></center>";
		$error = 1;
	}
	return $error;
}	
	
	
 // Function Name:	reportSearch
 //       Purpose:  looks in the database for the user information 
 //       Accepts:  first name, last name, vol number, email, username
 //       Returns:  a table with the results
function reportSearch($date1, $date2, $calendar, $call_report_id, $username, $volunteer_number, $line_id){

	open_mysql_connection();
	if($calendar == 1){
		$report_result = mysql_query ("SELECT * FROM call_report WHERE start_time BETWEEN '$date1 00:00:00' AND '$date2 23:59:59' OR username = '$username' OR line_id = '$line_id' ORDER BY call_report_id DESC");
	}		
	else{
		$report_result = mysql_query ("SELECT * FROM call_report WHERE call_report_id = '$call_report_id' OR username = '$username' or volunteer_number = '$volunteer_number' or line_id = '$line_id' ORDER BY call_report_id DESC");
	}
	close_mysql_connection();
// Result amount
$num_rows = mysql_num_rows($report_result);
if($num_rows == 1){
	echo "<h1>Now showing ".$num_rows." result...</h1>";
}
elseif($num_rows > 1){
	echo "<h1>Now showing ".$num_rows." results...</h1>";
}
	?>
<table class='result-table'>
	<?
	if(mysql_num_rows($report_result) <= 0){
		?><center><h3>Your search has returned no results!</h3></center><?
	}
	?>
	<tr>
		<th>Report Number</th>
		<th>Start Time</th>
		<th>End Time</th>
		<th>Call Length</th>
		<th>Volunteer Name</th>
		<th>Volunteer Number</th>
		<th>Line</th>
		<th>Caller Name</th>

		<th>View</th>
		<th>Edit</th>
	</tr>
<? while($row = mysql_fetch_array($report_result)){ 
  echo "<tr>";
	echo "<td>". $row['call_report_id'] ."</td>";
	echo "<td>". $row['start_time'] ."</td>";
	echo "<td>". $row['end_time'] ."</td>";
	echo "<td>". $row['call_length'] ."</td>";
	echo "<td>". $row['vol_name'] ."</td>";
	echo "<td>". $row['volunteer_number'] ."</td>";
	echo "<td>". $row['line_id'] ."</td>";	
	echo "<td>". $row['caller_name'] ."</td>";

	echo "<td><a href='#'><img src='../images/document.png' width='27' height='30' border='0' alt='View' /></a></td>";
	if($row['username'] == $_SESSION['username']){
		echo "<td><a href='#'><img src='../images/Edit.png' width='20' height='30' border='0' alt='Edit' /></a></td>";
	}
	else{
		echo "<td></td>";
	}
  echo "</tr>";
}?>
</table>
<br />
<center><a href='http://home.currah.ca/volunteers/report_search.php'>Back</a></center>
<br /><br /><br /><br /><br /><br />
<?
exit();
}

 // Function Name:	print_Select_Form
 //       Purpose:  gets the form data from the database and displays it
 //       Accepts:  database name
 //       Returns:  form data
function print_Select_Form($option_table, $option_attribute){
	open_mysql_connection();
	$optionQuery = mysql_query("SELECT * FROM `$option_table`");
		
	while($row = mysql_fetch_array($optionQuery)){	
		echo "<option value='".$row[$option_attribute]."'>".$row[$option_attribute]."</option>";
	}
	close_mysql_connection();
}
?>
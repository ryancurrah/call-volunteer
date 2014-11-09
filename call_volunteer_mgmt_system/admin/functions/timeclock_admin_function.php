<?
if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

require_once "../db_connector.php";

 // Function Name:	display_timeclock
 //       Purpose:  To display the requested days of login times and edit/delete entries
 //       Accepts:  $dateFrom , $dateTo
 //       Returns:  nothing
function display_timeclock($dateFrom , $dateTo){

open_mysql_connection();
$timeclock_query = mysql_query("SELECT * FROM timeclock WHERE login_time BETWEEN '$dateFrom 00:00:00' AND '$dateTo 23:59:59' ORDER BY login_time ASC");
echo "<h3>Showing date From: <u><b>$dateFrom</b></u> To: <u><b>$dateTo</b></u>.</h3>";

echo "<table class='result-table'>";
  echo "<tr>";
	echo "<th>Entry ID</th>";
	echo "<th>Volunteer Name</th>";
	echo "<th>Volunteer #</th>";
	echo "<th>Time In<br /><i><small>(yyyy-mm-dd hh:mm:ss)</small></i></th>";
	echo "<th>Time Out<br /><i><small>(yyyy-mm-dd hh:mm:ss)</small></i></th>";
	echo "<th>Total Time<br /></th>";
	echo "<th>End of Shift Note</th>";
	echo "<th>Edit</th>";
	echo "<th>Delete</th>";
  echo "</tr>";
while($row = mysql_fetch_array($timeclock_query)){
  echo "<tr>";
	echo "<td>". $row['timeclock_id'] ."</td>";
	echo "<td class='volunteer-name'>" . $row['fullname'] . "</td>";
	echo "<td>" . $row['volnum'] . "</td>";
	echo "<td class='loginout'>" . $row['login_time'] . "</td>";
		if($row['logout_time'] ==  '0000-00-00 00:00:00'){
			$row['logout_time'] = '<b>User still clocked in.</b>';
		} 
	echo "<td class='loginout'>" . $row['logout_time'] . "</td>";
	echo "<td>" . $row['time_diff'] . "<br /><small>hh:mm:ss</small></td>";
	echo "<td>" . $row['note'] . "</td>";
	echo "<td><a href='timeclock_edit.php?id=".$row['timeclock_id']."'><img src='../images/Edit.png' width='20' height='30' border='0' alt='Edit' /></a></td>";   
	echo "<td><a href='timeclock_delete.php?id=".$row['timeclock_id']."'><img src='../images/Delete.png' width='20' height='30' border='0' alt='Delete' /></a></td>";
  echo "</tr>";
}
echo "</table>";
close_mysql_connection();
echo "</form>";
}

 // Function Name:	count_rows
 //       Purpose:  count the number of rows a request has generated
 //       Accepts:  $dateFrom , $dateTo
 //       Returns:  the row count
function count_rows($dateFrom , $dateTo){
open_mysql_connection();
$timeclock_query = mysql_query("SELECT * FROM timeclock WHERE login_time BETWEEN '$dateFrom 00:00:00' AND '$dateTo 23:59:59' ORDER BY login_time ASC");
$count=mysql_num_rows($timeclock_query);

return $count;
}
?>
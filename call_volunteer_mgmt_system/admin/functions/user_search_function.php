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
function checkSearch($first_name, $last_name, $vol_num, $email, $username){

	if(empty($first_name) && empty($last_name) && empty($vol_num) && empty($email) && empty($username)){
		echo "<center><h3>You have have not entered anything into the search!</h3></center>";
		$error = 1;
	}
	if(!(empty($first_name)) || !(empty($last_name))){
		if(isset($first_name) && empty($last_name) || empty($first_name) && isset($last_name)){
			echo "<center><h3>You need to enter in a First Name and Last Name!</h3></center>";
			$error = 1;
		}
	}	
	return $error;
}	
	
	
 // Function Name:	userSearch
 //       Purpose:  looks in the database for the user information 
 //       Accepts:  first name, last name, vol number, email, username
 //       Returns:  a table with the results
function userSearch($first_name, $last_name, $vol_num, $email, $username){

	open_mysql_connection();
	$query = mysql_query ("SELECT * FROM users WHERE first_name = '$first_name' AND last_name = '$last_name' OR volunteer_number = '$vol_num' OR email = '$email' OR username = '$username'");
	close_mysql_connection();
// Result amount
$num_rows = mysql_num_rows($query);
if($num_rows == 1){
	echo "<h1>Now showing ".$num_rows." result...</h1>";
}
elseif($num_rows > 1){
	echo "<h1>Now showing ".$num_rows." results...</h1>";
}
?>

<table class='result-table'>
	<?
	if(mysql_num_rows($query) <= 0){
		?><h3>Your search has returned no results!</h3><?
	}
	?>
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
<? while($row = mysql_fetch_array($query)){ 
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
}
?>
</table>
<br />
<center><a href='http://home.currah.ca/admin/user_search.php'>Back</a></center>
<br /><br /><br /><br /><br /><br />
<?
exit();
}
?>
<?
if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

	require_once "../db_connector.php";
	
 // Function Name:	checkSearch
 //       Purpose:  checks to make sure search data entered correctly 
 //       Accepts:  referral_profile_id, organization, program, category_id, city
 //       Returns:  an error status of 1 or nothing
function checkSearch($referral_profile_id, $organization, $program, $category_id, $city){
	$error = 0;
	if(empty($referral_profile_id) && empty($organization) && empty($program) && empty($category_id) && empty($city)){
		echo "<center><h3>You have have not entered anything into the search!</h3></center>";
		$error = 1;
	}
	return $error;
}	
	
 // Function Name:	userSearch
 //       Purpose:  looks in the database for the user information 
 //       Accepts:  first name, last name, vol number, category_id, city
 //       Returns:  a table with the results
function userSearch($referral_profile_id, $organization, $program, $category_id, $city){

	if($referral_profile_id == ''){
		$referral_profile_id = 'NULL';
	}
	if($organization == ''){
		$organization = 'NULL';
	}		
	if($program == ''){
		$program = 'NULL';
	}
	if($category_id== ''){
		$category_id = 'NULL';
	}
	if($city== ''){
		$city = 'NULL';
	}
	
	open_mysql_connection();
	$query = mysql_query ("SELECT * FROM referral_profile WHERE referral_profile_id = '$referral_profile_id' OR organization = '$organization' OR program = '$program' OR category_id = '$category_id' OR city = '$city' ORDER BY referral_profile_id DESC");
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

	<?
	if(mysql_num_rows($query) <= 0){
		?><h3>Your search has returned no results!</h3><?
	}
	?>
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

<br />
<center><a href='javascript:history.back(-1);'>Back</a><center>
<br /><br /><br /><br /><br /><br />
<?
exit();
}
?>
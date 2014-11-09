<?
if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

// $_SERVER['PHP_SELF'] returns the path of the current script relative to the document root. 
// The explode function breaks this up into an array and the end function merely points to the last entry. 
$thisFilename = end(explode('/', $_SERVER['PHP_SELF']));
if($thisFilename == 'logout.php') { 
require_once "db_connector.php";
} 
else { 
require_once "../db_connector.php";
}  

 // Function Name:	clock_in
 //       Purpose:  adds the clock in time and changes timeclock status to 1 meaning they timed in
 //       Accepts:  user_id , username, fullname from session variable
 //       Returns:  nothing
function clock_in($user_id ,$username, $fullname, $volnum){
	open_mysql_connection();
	$query = 'INSERT INTO timeclock(
						 user_id,
						 username,
						 fullname,
						 volnum
						 )
						 
						 VALUES(
						 "'.$user_id.'",
						 "'.$username.'",
						 "'.$fullname.'",
						 "'.$volnum.'"
						 )
						';
	mysql_query($query);
	// Save the latest primary key number to the user account
	$timeclock_id_query = mysql_insert_id();
	mysql_query ('UPDATE users SET timeclock_id = "'.$timeclock_id_query.'", timeclock_status = "1" WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	close_mysql_connection();
 }
 
 // Function Name:	clock_out
 //       Purpose:  adds the clock out time and changes timeclock status to 0 meaning they timed out
 //       Accepts:  user_id , username from session variable
 //       Returns:  nothing
function clock_out($user_id ,$username){
	open_mysql_connection();
	// get timeclock_id from user database
	$query = mysql_query('SELECT timeclock_id FROM users WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	$result = mysql_fetch_array($query);
	$timeclock_id = $result['timeclock_id'];
	// get time difference
	// update the timeclock with the clock out and time diff
	$query = mysql_query('UPDATE timeclock SET logout_time = CURRENT_TIMESTAMP(), time_diff = TIMEDIFF(CURRENT_TIMESTAMP(), login_time) WHERE timeclock_id = "'.$timeclock_id.'"');
	// set the timeclock_status to 0
	$query = 'UPDATE users SET timeclock_status = "0" WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"';
	mysql_query($query);
	close_mysql_connection();
 }

 // Function Name:	get_timeclock_status
 //       Purpose:  gets the current timeclock status
 //       Accepts:  user_id and username, from the session variable
 //       Returns:  will either return a 1 for clocked in or 0 for clocked out
function get_timeclock_status($user_id, $username){
	open_mysql_connection();
	$query = mysql_query('SELECT timeclock_status FROM users WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	$result = mysql_fetch_array($query);
	$status = $result['timeclock_status'];
	close_mysql_connection();
	return $status;	  
}

 // Function Name:	submit_note
 //       Purpose:  submits note to 
 //       Accepts:  user_id, username from session variable and note from POST DATA
 //       Returns:  nothing
function submit_note($user_id ,$username, $note){
	open_mysql_connection();
	// get timeclock_id from user database
	$query = mysql_query('SELECT timeclock_id FROM users WHERE user_id = "'.$user_id.'" AND username = "'.$username.'"');
	$result = mysql_fetch_array($query);
	$timeclock_id = $result['timeclock_id'];
	// update notes into databse
	$query = 'UPDATE timeclock SET note = "'.$note.'" WHERE timeclock_id = "'.$timeclock_id.'"';
	mysql_query($query);
	close_mysql_connection();
}
 ?>
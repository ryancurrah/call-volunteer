<?php

include "session_function.php";
startSession();


include "db_connector.php";
open_mysql_connection();
$username = $_POST['username'];
$password = md5($_POST['password']);

// Select any result from mysql
$user_query = mysql_query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
// Select admin flag
$admin_query = mysql_query("SELECT administrator FROM `users` WHERE username = '$username' AND password = '$password' AND administrator = '1'"); 

// Store result from $user_query for SESSION variables
$user_result = mysql_fetch_array($user_query);

// count the number of results from mysql
$num_rows = mysql_num_rows($user_query);
$num_rows_admin = mysql_num_rows($admin_query); //check user has admin priv

if ($num_rows != 0){

/*	if(isset($_COOKIE[session_name()])){
		setcookie(session_name(), '', time()-3600, '/');
		session_destroy();
	}*/
	
	// Set session cookie/token for Internet Explorer 7 users
/*	require_once 'browser_detection.php';
	$ua=getBrowser();
	$browser_dectect = $ua['name']; // . " " . $ua['version'];
	if($browser_dectect == 'Internet Explorer'){
		setcookie(session_name(), session_id(), time()+60*60*10, '/');
		echo $bonner;
	} */

    session_register('authorized');
	$_SESSION['authorized'] = true;
		if($num_rows_admin != 0){
			$_SESSION['admin'] = true;
		}
		else{
			$_SESSION['admin'] = false;
		}
	$_SESSION['username'] = $username;
	$_SESSION['user_id'] = $user_result['user_id'];
	$_SESSION['fullname'] = $user_result['first_name'] . " " . $user_result['last_name'];
	$_SESSION['volnum'] = $user_result['volunteer_number'];
	close_mysql_connection();
	header("Location: index.php");
    exit;
}
else{
	$login_fail = "login_form.php?logf=true";	
    header("location: " . $login_fail . "");
	exit;	
}
?>
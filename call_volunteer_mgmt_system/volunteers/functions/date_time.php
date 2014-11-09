<?
if ($_SESSION['authorized'] != true){
    header("Location: ../login_form.php");	
    exit;
}

require_once "../db_connector.php";

 // Function Name:	print_date_time
 //       Purpose:  gets the date and time from mysql
 //       Accepts:  nothing
 //       Returns:  date time
function print_date_time(){
	open_mysql_connection();
	$result = mysql_query('SELECT TIMESTAMP(current_timestamp())');
	$timestamp = mysql_result($result, 0);
	close_mysql_connection();
return $timestamp;
}

?>
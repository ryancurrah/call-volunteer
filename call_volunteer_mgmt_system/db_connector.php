<?php
$databaseName = 'cms_db';
 // Function Name:	open_mysql_connection
 //       Purpose:  to create a connection with the mysql server 
 //       Accepts:  nothing
 //       Returns:  error if no connection to database is made
function open_mysql_connection()
{
	$connect=mysql_connect("localhost","root","password");
	if(!($connect))
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("cms_db", $connect) or die("Unable to select database");
	
}
 // Function Name:	close_mysql_connection
 //       Purpose:  to close a mysql connection 
 //       Accepts:  nothing
 //       Returns:  nothing
function close_mysql_connection()
{
$close_connect = mysql_connect("localhost","root","password");
mysql_close($close_connect);
}
?>
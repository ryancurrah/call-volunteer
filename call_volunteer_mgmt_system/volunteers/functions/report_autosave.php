<?php

//include DB configuration file
include('../../db_connector.php');

//Connect to database
open_mysql_connection();

$call_description = mysql_real_escape_string($_POST['call_description']);
$caller_name = mysql_real_escape_string($_POST['caller_name']);
$caller_phone = mysql_real_escape_string($_POST['caller_phone']);
$caller_address = mysql_real_escape_string($_POST['caller_address']);
$caller_postal = mysql_real_escape_string($_POST['caller_postal']);
$age_confirmed = mysql_real_escape_string($_POST['age_confirmed']);

$id = (int)$_POST['newReport_id'];

//save contents to database
mysql_query("UPDATE `call_report` SET caller_name = '$caller_name', phone_number = '$caller_phone', street_address = '$caller_address', postal_code = '$caller_postal', age_confirmed = '$age_confirmed', call_description = '$call_description' WHERE call_report_id = '$id'");

//get timestamp
$result = mysql_query("SELECT as_timestamp FROM `call_report` WHERE call_report_id = $id");
$timestamp = mysql_result($result, 0);

//output timestamp
echo 'Last Auto Save: ', $timestamp;
?>
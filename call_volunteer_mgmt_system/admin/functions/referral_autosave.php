<?php

//include DB configuration file
include('../../db_connector.php');

//Connect to database
open_mysql_connection();

$service_description = mysql_real_escape_string($_POST['service_description']);
$organization = mysql_real_escape_string($_POST['organization']);
$program = mysql_real_escape_string($_POST['program']);
$phone_number = mysql_real_escape_string($_POST['phone_number']);
$physical_address = mysql_real_escape_string($_POST['physical_address']);
$website_address = mysql_real_escape_string($_POST['website_address']);

$id = (int)$_POST['newReferral_id'];

//save contents to database
mysql_query("UPDATE `referral_profile` SET `organization` = '$organization', `program` = '$program', `phone_number` =  '$phone_number', `physical_address` = '$physical_address', `website_address` = '$website_address', `service_description` = '$service_description' WHERE referral_profile_id = '$id'");

//get timestamp
$result = mysql_query("SELECT date_updated FROM `referral_profile` WHERE referral_profile_id = $id");
$timestamp = mysql_result($result, 0);

//output timestamp
echo 'Last Auto Save: ', $timestamp;



 
?>
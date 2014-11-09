<?
session_start();

// Set cookie to invalid time, unset all session variables and destroy the session
setcookie(session_name(), '', time()-3600, '/');
session_unset();
session_destroy();
header("Location: login_form.php");
?>
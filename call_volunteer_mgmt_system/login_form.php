<?php


require_once 'global_vars.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<title>Login</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="HAPedit 3.1">
		<link rel="stylesheet" type="text/css" href="css/NiftyLayout.css" media="screen">
		<script type="text/javascript" src="jscript/niftycube.js"></script>
		<script type="text/javascript" src="jscript/niftyLayout.js"></script>
	</head>
<!--- BODY --->
<body>
<div id="header">
<a id="logout" href="login_form.php">Login</a>
<h1><?echo $softwareTitle;?></h1>
	<div id="menu">
		<ul id="nav">
			<li id="home"><a href="index.php">Home</a></li>
			<li id="vol"><a href="volunteers.php">Volunteers</a></li>
			<li id="stats"><a href="statistics.php">Statistics</a></li>
			<li id="help"><a href="help.php">Help</a></li>
			<li id="admin"><a href="administrators.php">Administrators</a></li>
		</ul>
	</div>
</div>

<div id="container">

<h2>Please Login to Chaos2</h2>
<br /><br />
<?
// Login failed
if (isset($_GET['logf'])) {
		$logf = $_GET['logf'];
			if($logf == true) {
				?><center><p>Your username or password was incorrect. Please try again.</p></center><?
			}
}
?>
<!--- LOGIN FORM --->
<div class="form">
	<form method="POST" action="/login.php">
		<label for="username">Username:&nbsp;</label><input type="text" name="username"><br />
		<label for="password">Password:&nbsp;</label><input type="password" name="password"><br />
		<center><label for="submit"></label><input type="submit" value="Login"></center>
	</form>
</div>
<br/>
<br/>
<br/>
<br/>
</div>
</body>
</html>
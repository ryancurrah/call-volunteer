<?

function	startSession(){
	include 'global_vars.php';
	
	ini_set("session.gc_maxlifetime", "14400");
//	$session_name = 'inFORM';
	session_name($session_name);
	session_start();
	setcookie(session_name(), session_id(), time()+14400, '/');

}

?>
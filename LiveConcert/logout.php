<?php 
include "includes/login_head.php"; 
include "includes/path.php";
require_once $path."/LiveConcert/includes/config.php";
$username = $_SESSION['username'];
$score = $_SESSION['score'];
if($logout = $mysqli->query("call logoutrecord('$username')")){
	// $logout->close();
	
}
if($score <10){
	if($calcuscore = $mysqli->query("call calculate_login_score('$username')") or die($mysqli->error)){
	// $calcuscore->close();
	
	}
}

$mysqli->close();
//function to calculatet he score
unset($_SESSION['username']);
unset($_SESSION['loggedin']);
unset($_SESSION['error']);
unset($_SESSION['score']);
unset($_SESSION['city']);

session_destroy();
header("location: index.php");

?>
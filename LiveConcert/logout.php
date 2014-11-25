<?php 
include "includes/head.php"; 
$username = $_SESSION['username'];

if($logout = $mysqli->query("call logoutrecord('$username')")){
	// $logout->close();
	
}
if($calcuscore = $mysqli->query("call calculate_login_score('$username')") or die($mysqli->error)){
	// $calcuscore->close();
	
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
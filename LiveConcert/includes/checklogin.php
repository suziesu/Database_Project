<?php 
if(!isset($_SESSION['username']) || !$_SESSION['loggedin']){
	$_SESSION['error'] = "Please log in!";
	header('Location: /LiveConcert/login.php');
}

?>
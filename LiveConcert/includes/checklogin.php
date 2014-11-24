<?php 
if(isset($_SESSION['username']) && $_SESSION['loggedin']){
	echo "Welcome ". $_SESSION['email'];
}else{
	$_SESSION['error'] = "Please log in!";
	header('Location: login.php');

}

?>
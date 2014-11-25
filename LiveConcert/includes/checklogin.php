<?php 
if(isset($_SESSION['username']) && $_SESSION['loggedin']){
	echo "Welcome ". $_SESSION['username'];
}else{
	$_SESSION['error'] = "Please log in!";
	header('Location: index.php');

}

?>
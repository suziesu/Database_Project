<?php
$mysqli = new mysqli('localhost','root','','LiveConcert',0,'/tmp/mysql.sock');
if(mysqli_connect_errno()){
	printf("connect failed: %s\n",mysqli_connect_error());
	exit();
}
session_start();

if(isset($SESSION["REMOTE_ADDR"]) && $SESSION["REMOTE_ADDR"] != $SERVER["REMOTE_ADDR"]) {
  session_destroy();
  session_start();
}



if(isset($_SESSION['error']) && $_SESSION['error']){
	// echo "<div class='error'>".$_SESSION['error']."</div>";
	unset($_SESSION['error']);
}
?> 

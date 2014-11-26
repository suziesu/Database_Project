<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
include $path."/LiveConcert/menu/home_menu.php";?>
	<title>Edit Concert</title>
</head>
<body>
<?php 
	$cname = "";
	$username = $_SESSION['username'];
	$score = $_SESSION['score'];
	if(isset($_GET['cname'])){
		$cname = $_GET['cname'];
	}
	if($score >=10){
		//update concert into Concert
	}else{
		//update concert into ConcertProcess
	}
?>
</body>
</html>
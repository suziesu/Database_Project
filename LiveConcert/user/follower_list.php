<!DOCTYPE html>
<html>
<html>
<head>
<?php 
include "../includes/new_head.html"; 
include "../includes/regular_page_head.php";

$username = $_GET['username'];

?>
	<title>Follower member</title>
</head>
<body>
<section class='content'>
<div class="container white-background">
		<h3> <?php echo $username ?>'s Follower</h3>
		<div id='concert_list' class='row'>
		<div class='span4'></div>
			<div class='span4'>
<?php
if($follower = $mysqli->query("call follower_list('$username')") or die($mysqli->error)){
	if($follower->num_rows > 0){
		while($row = $follower->fetch_object()){
			$fusername = $row->fusername;
			echo "<h3><li><a href='/LiveConcert/user/user_page.php?username=$fusername'><img src='/LiveConcert/assets/images/$fusername.jpg'>$fusername</a></li></h3>";
		}
	}
	$follower->close();
	$mysqli->close();
}

echo "<h3><a href='/LiveConcert/user/user_page.php?username=$username'><button id='create_concert' type='button'>Go Back </button></h3>";

?>
</div>
<div class="span4"></div>
</div>
</div>
</section>
</body>
</html>
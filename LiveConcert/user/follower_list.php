<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
$username = $_SESSION['username'];
include "../menu/home_menu.php";
?>
	<title>Follower member</title>
</head>
<body>

<?php
echo "<a href='user_page.php?username=$username'><button type='button'>Go Back</button>";
if($follower = $mysqli->query("call follower_list('$username')") or die($mysqli->error)){
	if($follower->num_rows > 0){
		while($row = $follower->fetch_object()){
			$fusername = $row->fusername;
			echo "<li><a href='user_page.php?username=$fusername'><img src='assets/images/$fusername.jpg'>$fusername</a></li>";
		}
	}
	$follower->close();
	$mysqli->close();
}


?>
</body>
</html>
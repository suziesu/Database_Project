<!DOCTYPE html>
<html>
<html>
<head>
<?php 
include "../includes/new_head.html"; 
include "../includes/regular_page_head.php";
$username = $_GET['username'];

?>
	<title>Following member</title>
</head>
<body>
<section class='content'>
<div class="container white-background">
		<h3> <?php echo $username ?>'s Following</h3>
		<div id='concert_list' class='row'>
			

<?php
if($following = $mysqli->query("call following_list('$username')") or die($mysqli->error)){
	if($following->num_rows > 0){
		while($row = $following->fetch_object()){
			$fname = $row->username;
			echo "<div class='span3'>";
			echo "<h3><li><a href='/LiveConcert/user/user_page.php?username=$fname'><img src='/LiveConcert/assets/images/$fname.jpg'><h3>$fname</h3></a></li></h3>";
			echo "</div>";
		}
	}
	$following->close();
	$mysqli->close();
}

echo "<h3><a href='/LiveConcert/user/user_page.php?username=$username'><button id='create_concert' type='button'>Go Back</button></h3>";

?>

</body>
</html>
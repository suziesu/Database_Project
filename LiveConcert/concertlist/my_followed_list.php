<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
include $path."/LiveConcert/menu/home_menu.php";?>
	<title>My Followed List</title>
</head>
<body>
<?php 
$username = $_SESSION['username'];
	if(isset($_POST['listname']) && isset($_POST['submit'])){
		$listname = $_POST['listname'];
		if($unfollow = $mysqli->query("call unfollow_recommenlist('$listname')") or die($mysqli->error)){
			$unfollow->close();
			$mysqli->next_result();
		}
	}


	if($result = $mysqli->query("call followed_recommend_list('$username')") or die($mysqli->error)){
		if($result->num_rows > 0){
			while($row = $result->fetch_object()){
				$fl = $row->listname;
				$createby = $row->username;
				$lcreatetime = $row->lcreatetime;
				$ldescription = $row->ldescription;
				echo "<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$fl' >";
				if(file_exists("/LiveConcert/assets/images/$fl.jpg")){
					echo "<img src='/LiveConcert/assets/images/$fl.jpg'>";
				}
				echo "$fl</a>";
				echo "<span>$createby</span>";
				echo "<span>$lcreatetime</span>";
				echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'>";
				echo "<input type='hidden' name='listname' value='$fl'>";
				echo "<input type='button' name='submit' value='Unfollowed'></form>"
				echo "<div>$ldiscrip</div>";
			}
		}
		$result->close();
	}
	$mysqli->close();

?>
</body>
</html>
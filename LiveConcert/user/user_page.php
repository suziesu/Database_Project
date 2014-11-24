<!DOCTYPE html>
<html>
<head>
<?php include "includes/head.php"; ?>
	<title>User Page</title>
</head>
<body>
<?php 
$following_count = $follower_count = 0;
$userself = true;
$userscore = $_SESSION['score'];
	$username = $_SESSION['username'];
	if(isset($_GET['username']) && $_GET['username'] != $username){
		$username = $_GET['username'];
		$userself = false;
	}else if(isset($_POST['page_owner']) && $_POST['page_owner'] != $username){
		$username = $_POST['page_owner'];
		$userself = false;
		if($insertfollow = $mysqli->query("call insert_follow($username,$_SESSION['username'])")){
			$insertfollow->close();
		}
	}
	if($following = $mysqli->query("call following_list($username)")){
		if($following->num_rows > 0){
			$following_count = $following->num_rows;
		}
		$following->close();
	}
	if($follower = $mysqli->query("call follower_list($username)")){
		if($follower->num_rows > 0){
			$follower_count = $follower->num_rows;
		}
		$follower->close();
	}

?>
<!-- profile -->
<div aligh='left'><a href='edit_profile.php?username = <?php echo $username;?>'><img scr='assets/images/<?php echo $username; ?>.jpg'></a></div>
<div><?php echo $username."</div><div>"; 
	if($_SESSION['score'] == 20){
		echo "<span color='red'>Artist</span>";
		if($bandname=$mysqli->prepare("select baname from Artist where username = ?")){
			$baname->bind_param('s',$username);
			$bandname->execute();
			$bandname->bind_result($baname);
			if($bandname->fetch()){
				echo "<div>My Band&Concert Info<a href='/LiveConcert/artist_band/band_page.php?baname=$baname'>$baname</a></div>";
				$bandname->close();
			}else if($userself){
				echo "<a href='/LiveConcert/artist_band/post_band.php?username=$username'>POST Your Band or New Band Info<button type='button'>";
			}
		}
	}
	echo "</div><div>";
	if(!$userself){
		$visiter = $_SESSION['username'];
		if($ff = $mysqli->query("call check_followed($username,$visiter)")){
			if($ff->num_rows > 0){
				echo "<input color='grey' type='button' name='username' value='Followed'>";
			}else{
				echo "<form action='user_page.php' method='POST'><input type='hidden' name='page_owner' value='$username' ><input type='button' name='button' value='Follow'></form>";
			}
			$ff->close();
		}
		
	}

?></div>
<!-- score -->
<div><h3><a href='following_list.php'>Following(<?php echo $following_count; ?>)</a>
<a href='follower_list.php'>Follower(<?php echo $follower_count; ?>)</a></h3></div>
<div>Score:<?php 
for ($i = 0; $i < $_SESSION['score']; $i++) {
	echo "<img src='/LiveConcert/assets/TheMintRecord.gif' height='42' width='42'>";
}
?></div>
<!-- button of post band and post concert -->

<div><h3>Concert</h3>
	<div><h4>Plan To</h4>
	<?php 
		if($result = $mysqli->query("call plan_to_concert($username)")){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					echo "<a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
					if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
						echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
					}
					echo "$cname</a>";
				}
			}
			$result->close();
		}

	?>

	</div>
	<div><h4>Going</h4>
	<?php 
		if($result = $mysqli->query("call going_concert($username)")){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					echo "<a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
					if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
						echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
					}
					echo "$cname</a>";
				}
			}
			$result->close();
		}

	?>

	</div>
	<div><h4>Attended</h4>
	<?php 
		if($result = $mysqli->query("call attended_concert($username)")){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					echo "<a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
					if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
						echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
					}
					echo "$cname</a>";
					$review = true;
					if($userself){
						echo "<a href='/LiveConcert/concert/concert_page.php#review?cname=$cname&review=$review'><button>Review</button>";
					}
				}
			}
			$result->close();
		}

	?>

	</div>
</div>
<div>
	<h3>Band</h3>
	<?php 
		if($result = $mysqli->query("call followed_band($username)")){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$b = $row->baname;
					echo "<a href='/LiveConcert/artist_band/band_page.php?baname=$b' >";
					if(file_exists("/LiveConcert/assets/images/$b.jpg")){
						echo "<img src='/LiveConcert/assets/images/$b.jpg'>";
					}
					echo "$b</a>";
				}
			}
			$result->close();
		}

	?>
</div>
<div>
	<h3>My Recommend List</h3><a href='/LiveConcert/concertlist/my_concertlist.php'>See All</a>
	<?php 
		if($userself){
			echo "<span><a href='/LiveConcert/concertlist/create_new_list.php'>Create a New List</a></span>";
		}
		if($result = $mysqli->query("call my_recommend_list($username)")){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$ml = $row->listname;
					echo "<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$ml' >";
					if(file_exists("/LiveConcert/assets/images/$ml.jpg")){
						echo "<img src='/LiveConcert/assets/images/$ml.jpg'>";
					}
					echo "$ml</a>";
				}
			}
			$result->close();
		}

	?>
</div>
<div>
	<h3>Followed List</h3><a href='/LiveConcert/concertlist/my_followed_list.php'>See All</a>
	<?php 

		if($result = $mysqli->query("call followed_recommend_list($username)")){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$fl = $row->listname;
					echo "<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$fl' >";
					if(file_exists("/LiveConcert/assets/images/$fl.jpg")){
						echo "<img src='/LiveConcert/assets/images/$fl.jpg'>";
					}
					echo "$fl</a>";
				}
			}
			$result->close();
		}

	?>
</div>

</body>
</html>


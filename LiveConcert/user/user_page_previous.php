<!DOCTYPE html>
<html>
<head>
<?php include "../includes/login_head.php"; 
include "../includes/regular_page_head.php";
?>
	<title>User Page</title>
</head>
<body>
<?php 
$following_count = $follower_count = 0;
$userself = true;
$exist = true;
$userscore = $_SESSION['score'];
$username = $_SESSION['username'];
	if(isset($_GET['username'])){
		$pageowner = $_GET['username'];
		if($userExist = $mysqli->query("call find_user_byname('$pageowner')") or die($mysqli->error)){
			if($userExist->num_rows > 0){
				$userExist->close();
				$mysqli->next_result();
				if( $pageowner != $username){
					$userself = false;
					$username = $pageowner;
				}
				
			}else{
				echo "no such user in our system";
				$exist = false;
				header("refresh: 3,");
			}
		}
	}else if(isset($_POST['page_owner']) && $_POST['page_owner'] != $_SESSION['username']){
		$username = $_POST['page_owner'];
		$userself = false;
		$visiter = $_SESSION['username'];
		if($insertfollow = $mysqli->query("call insert_follow('$username','$visiter')") or die($mysqli->error)){
			// $insertfollow->close();
			$mysqli->next_result();
		}
	}
	//check if username exist in our system


	if($following = $mysqli->query("call following_list('$username')") or die($mysqli->error)){
		if($following->num_rows > 0){
			$following_count = $following->num_rows;
		}
		$following->close();
		$mysqli->next_result();
	}
	if($follower = $mysqli->query("call follower_list('$username')") or die($mysqli->error)){
		if($follower->num_rows > 0){
			$follower_count = $follower->num_rows;
		}
		$follower->close();
		$mysqli->next_result();
	}
		//get the user's follow info
	

?>
<!-- profile -->
<div aligh='left'><a href='edit_profile.php?username = <?php echo $username;?>'><img scr='assets/images/<?php echo $username; ?>.jpg'></a></div>
<div><?php echo $username."</div><div>"; 
	if($_SESSION['score'] == 20){
		echo "<span color='red'>Artist</span>";
		if($bandname=$mysqli->prepare("select baname from Artist where username = ?") or die($mysqli->error)){
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
		if($ff = $mysqli->query("call check_followed('$username','$visiter')") or die($mysqli->error)){
			if($ff->num_rows > 0){
				echo "<input color='grey' type='submit' name='username' value='Followed'>";
			}else{
				echo "<form action='user_page.php' method='POST'><input type='hidden' name='page_owner' value='$username' ><input type='submit' name='button' value='Follow'></form>";
			}
			$ff->close();
			$mysqli->next_result();
		}
		
	}

?></div>
<!-- score -->
<div><h3><a href='following_list.php'>Following(<?php echo $following_count; ?>)</a>
<a href='follower_list.php'>Follower(<?php echo $follower_count; ?>)</a></h3></div>
<div>Score:<?php 
if($_SESSION['score'] >= 1){
	for ($i = 0; $i <$_SESSION['score']; $i++) {
		echo "<img src='/LiveConcert/assets/images/Trecord3.gif' height='22',width='22'>";
	}
}else{
	// echo $_SESSION['score'];
	echo " Below 1, more activity more score";
}
	
?></div>
<!-- button of post band and post concert -->

<div><h3>Concert</h3>
<a href='/LiveConcert/concert/create_concert.php'><button>Create A Concert</button></a>
	<div><h4>Plan To</h4>
	<?php 
		if($result = $mysqli->query("call plan_to_concert('$username')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					echo "<ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
					if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
						echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
					}
					echo "$cname</a></ul>";
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>

	</div>
	<div><h4>Going</h4>
	<?php 
		if($result = $mysqli->query("call going_concert('$username')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					echo "<ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
					if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
						echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
					}
					echo "$cname</a></ul>";
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>

	</div>
	<div><h4>Attended</h4>
	<?php 
		if($result = $mysqli->query("call attended_concert('$username')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					echo "<ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
					if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
						echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
					}
					echo "$cname</a></ul>";
					$review = true;
					if($userself){
						echo "<a href='/LiveConcert/concert/concert_page.php#review?cname=$cname&review=$review'><button>Review</button></ul>";
					}
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>

	</div>
	<div><h4>My Created/Edited Concert</h4>
	
	<?php 
		if($result = $mysqli->query("call my_create_concert('$username')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					echo "<ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
					if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
						echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
					}
					echo "$cname</a></ul>";
					
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>
		<div>
			<?php 
			$score = $_SESSION['score'];
			if($score < 10){
				echo "---My In Process Concert------------------------------";
				if($result = $mysqli->query("call my_create_concert_in_process('$username')") or die($mysqli->error)){
					if($result->num_rows > 0){
						while($row = $result->fetch_object()){
							$cname = $row->cname;
							echo "<ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
							if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
								echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
							}
							echo "$cname</a></ul>";
							
						}
					}
					$result->close();
					$mysqli->next_result();
				}
			}

			?>
		</div>
	</div>



</div>
<div>
	<h3>Band</h3>
	<?php 
		if($result = $mysqli->query("call followed_band('$username')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$b = $row->baname;
					echo "<ul><a href='/LiveConcert/artist_band/band_page.php?baname=$b' >";
					if(file_exists("/LiveConcert/assets/images/$b.jpg")){
						echo "<img src='/LiveConcert/assets/images/$b.jpg'>";
					}
					echo "$b</a></ul>";
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>
</div>
<div>
	<h3>My List</h3><a href='/LiveConcert/concertlist/my_concertlist.php'>See All</a>
	<?php 
		if($userself){
			echo "<span><a href='/LiveConcert/concertlist/create_new_list.php'><button>Create a New List</button></a></span>";
		}
		if($result = $mysqli->query("call my_recommend_list('$username')") or die($mysqli->error)){
			if($result->num_rows > 0){
				echo "<ul>";
				while($row = $result->fetch_object()){
					$ml = $row->listname;
					echo "<ul><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$ml' >";
					if(file_exists("/LiveConcert/assets/images/$ml.jpg")){
						echo "<img src='/LiveConcert/assets/images/$ml.jpg'>";
					}
					echo "$ml</a></ul>";
				}
				echo "</ul>";
			}
			$result->close();
			$mysqli->next_result();
		}

	?>
</div>
<div>
	<h3>Followed List</h3><a href='/LiveConcert/concertlist/my_followed_list.php'>See All</a>
	<?php 

		if($result = $mysqli->query("call followed_recommend_list('$username')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$fl = $row->listname;
					echo "<ul><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$fl' >";
					if(file_exists("/LiveConcert/assets/images/$fl.jpg")){
						echo "<img src='/LiveConcert/assets/images/$fl.jpg'>";
					}
					echo "$fl</a></ul>";
				}
			}
			$result->close();
			$mysqli->next_result();
		}
		$mysqli->close();

	?>
</div>

</body>
</html>


<!DOCTYPE html>
<html>
<html>
<head>
<?php 
include "../includes/new_head.html"; 
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
$pageowner = "";
$username = $_SESSION['username'];
echo $username;
	if(isset($_GET['username'])){
		$pageowner = $_GET['username'];
		if($userExist = $mysqli->query("call find_user_byname('$pageowner')") or die($mysqli->error)){
			if($userExist->num_rows > 0){
				$userExist->close();
				$mysqli->next_result();
				if( $pageowner != $username){
					$userself = false;
					// $username = $pageowner;
				}
				
			}else{
				echo "no such user in our system";
				$exist = false;
				header("refresh: 3,");
			}
		}
	}else if(isset($_POST['page_owner']) && $_POST['page_owner'] != $_SESSION['username']){
		$pageowner = $_POST['page_owner'];
		$userself = false;
		$visiter = $_SESSION['username'];
		if($_POST['button']=='UnFollow'){
			if($removeFollow = $mysqli->query("call remove_follow('$pageowner','$visiter')") or die($mysqli->error)){
				$mysqli->next_result();
			}
		}
		if($_POST['button'] == 'Follow'){
			if($insertfollow = $mysqli->query("call insert_follow('$pageowner','$visiter')") or die($mysqli->error)){
			// $insertfollow->close();
				$mysqli->next_result();
			}
		}
		
	}
	//check if username exist in our system


	if($following = $mysqli->query("call following_list('$pageowner')") or die($mysqli->error)){
		if($following->num_rows > 0){
			$following_count = $following->num_rows;
		}
		$following->close();
		$mysqli->next_result();
	}
	if($follower = $mysqli->query("call follower_list('$pageowner')") or die($mysqli->error)){
		if($follower->num_rows > 0){
			$follower_count = $follower->num_rows;
		}
		$follower->close();
		$mysqli->next_result();
	}
		//get the user's follow info
	

?>


<section class='content'>
<div class="container white-background">
	<div id='user_info' class='row'>
		<div  class="span6">
<img src='/LiveConcert/assets/images/<?php echo $pageowner; ?>.jpg'>
	<?php echo "<h3>$pageowner</h3>"; ?>

			
		


<!-- profile -->
<?php 
//get $pageowner  score
$pageowner_score = "";
$artist=false;
if($page_score = $mysqli->query("call find_user_byname('$pageowner')") or die($mysqli->error)){
	if($row = $page_score->fetch_object()){
		$pageowner_score = $row->score;
		$pageowner_email = $row->email;
		$pageowner_city = $row->city;
		$pageowner_registime = $row->registime;
	}
	$page_score->close();
	$mysqli->next_result();
}

echo "<p>".$pageowner_score;
if($pageowner_score >= 1 && $pageowner_score <10){
	for ($i = 0; $i <$pageowner_score; $i++) {
		echo "<i class='icon-bolt icon-x'></li>";
		// "<img id='userscore' src='/LiveConcert/assets/images/Trecord4.png' height='4px',width='4px'>";
	}
}else if ($pageowner_score >=10){
	// echo $_SESSION['score'];
	echo "<i class='icon-heart-empty icon-2x'></i>" ;
}else{
	echo " Below 1, more activity more score";

}
echo "</p>";
if($userself){
	echo "<p><i class='icon-envelope'></i>&nbsp; $pageowner_email</p>";
	echo "<p><i class='icon-map-marker icon-x'></i>&nbsp;$pageowner_city</p>";
	echo "<p><i class='icon-calendar icon-x'></i>&nbsp;$pageowner_registime</p>";
}
// $usertaste_array = array();
if($usertaste = $mysqli->query("call get_user_taste('$pageowner')") or die($mysqli->error)){
	if($usertaste->num_rows > 0){
		echo "<p><i class='icon-coffee'></i>&nbsp;&nbsp;";
		while($row = $usertaste->fetch_object()){
			// $usertaste_array[] = $row->subtypename;
			echo "$row->subtypename&nbsp;&nbsp;";
		}
		echo "</p>";
	}
	$usertaste->close();
	$mysqli->next_result();
}	


	if($pageowner_score == 20){
		$artist = true;

		echo "<p id='blue'> ARTIST </p>";
		if($bandname=$mysqli->prepare("select baname from Artist where username = ?") or die($mysqli->error)){
			$bandname->bind_param('s',$pageowner);
			$bandname->execute();
			$bandname->bind_result($baname);
			if($bandname->fetch()){
				echo "<p>My Band Info<a href='/LiveConcert/artist_band/band_page.php?baname=$baname'>$baname</a></p>";
				$bandname->close();
			}else if($userself){
				echo "<p><a href='/LiveConcert/artist_band/post_band.php?username=$username'><button id='create_concert'>POST New Band Info</button></a></p>";
			}
		}
	}
	if(!$userself){
		$visiter = $_SESSION['username'];
		if($ff = $mysqli->query("call check_followed('$pageowner','$visiter')") or die($mysqli->error)){
			if($ff->num_rows > 0){
				echo "<h3>Followed</h3><p><form action='/LiveConcert/user/user_page.php' method='POST'><input type='hidden' name='page_owner' value='$pageowner' ><input id='submit' type='submit' name='button' value='UnFollow'></form></p>";
			}else{
				echo "<p><form action='/LiveConcert/user/user_page.php' method='POST'><input type='hidden' name='page_owner' value='$pageowner' ><input id='submit' type='submit' name='button' value='Follow'></form></p>";
			}
			$ff->close();
			$mysqli->next_result();
		}
		
	}

?>
<!-- score -->
<h4 id='margin-minus'><a href='following_list.php?username=<?php echo $pageowner;?>'>Following(<?php echo $following_count; ?>)</a>
<a href='follower_list.php?username=<?php echo $pageowner; ?>'>&nbsp; &nbsp; Follower(<?php echo $follower_count; ?>)</a></h4>

<!-- button of post band and post concert -->



</div>
		<div class="span4">
			<p>
			<?php if($userself){echo 
			"<ul><a href='edit_profile.php?username=$username'><i class='icon-github-alt icon-3x'></i><span>&nbsp; &nbsp;Edit Profile</span></a></ul></p>
			<ul><a href='/LiveConcert/concert/create_concert.php'><i class='icon-magic icon-3x'></i><span>&nbsp; &nbsp;Post Concert</span></a></ul>
			<ul><a href='/LiveConcert/concertlist/create_new_list.php'><i class='icon-file-alt icon-3x'></i><span>&nbsp; &nbsp;Create a Recommendation List</span></a></ul>
			<ul><i class='icon-search icon-3x'></i>
			<form method='POST' action='user_list.php'><input name='search_user' type='text' value='' placeholder='search a user'></form></ul>
			<p>";
			} ?>
		</div>
	</div>
	<div class="row" id='concert'>
	<h2>Concert
		<?php 
	if($userself){
	echo " &nbsp; &nbsp; <a href='/LiveConcert/concert/create_concert.php'><button id='create_concert'>Create Concert</button></a>";}?>
		</h2>
		<div class="span3" >
		
			<h3>Plan To</h3>

	<?php 
	if($userself){
	}
		if($result = $mysqli->query("call plan_to_concert('$pageowner')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					echo "<p><ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
					echo "<img src='/LiveConcert/assets/images/$cname.jpg'></p>";
					echo "<p>$cname</a></ul></p>";
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>

</div>
		<div class="span3">
			<h3>Going</h3>


	<?php 
		if($result = $mysqli->query("call going_concert('$pageowner')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					echo "<p><ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
					echo "<img src='/LiveConcert/assets/images/$cname.jpg'></p>";
					echo "<p>$cname</a></ul></p>";
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>


</div>
		<div class="span3">
			<h3>Attended</h3>


	<?php 
		if($result = $mysqli->query("call attended_concert('$pageowner')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					echo "<p><ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
					echo "<img src='/LiveConcert/assets/images/$cname.jpg'></p>";
					echo "<p>$cname</a></ul></p>";
					$review = true;
					if($userself){
						echo "<p><a href='/LiveConcert/concert/concert_page.php?cname=$cname&review=$review'><button id='submit'>Review</button></ul><p>";
					}
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>

		</div>
	</div>
	<div class="row" id='band'>
	
	<?php 
		if($userself && $userscore > 10){
			echo "<h2>My Band/PostBand<a href='/LiveConcert/artist_band/post_band.php'><button id='create_concert'>Post A Band</button></a>";
		}
		echo "</h2>";
		if($result = $mysqli->query("call my_band('$pageowner')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$b = $row->baname;
					echo "<div class='span3'>";
					echo "<h3><a href='/LiveConcert/artist_band/band_page.php?baname=$b'>$b</h3>";
					echo "<p><img src='/LiveConcert/assets/images/$b.jpg'></p>";
					echo "</a></div>";
				}
			}
			$result->close();
			$mysqli->next_result();
		}

?>

	</div>
	<div class="row" id='band'>
		

		<h2>Band Followed&nbsp; &nbsp;
	<?php 
	
		echo "</h2>";
		if($result = $mysqli->query("call followed_band('$pageowner')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$b = $row->baname;
					echo "<div class='span3'>";
					echo "<h3><a href='/LiveConcert/artist_band/band_page.php?baname=$b'>$b</h3>";
					echo "<p><img src='/LiveConcert/assets/images/$b.jpg'></p>";
					echo "</a></div>";
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>

	</div>
	<div class="row" id='band'>
		<h2>My List&nbsp; &nbsp;
	
	<?php 
		if($userself){
			echo "<a href='/LiveConcert/concertlist/create_new_list.php'><button id='create_concert'>New List</button></a>";
		}
		echo "</h2><p><a href='/LiveConcert/concertlist/my_concertlist.php'>>>>&nbsp; &nbsp;See All</a></p>";
		if($result = $mysqli->query("call my_recommend_list('$pageowner')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$ml = $row->listname;
					echo "<div class='span3'>";
					echo "<p><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$ml' >";
					echo "<img src='/LiveConcert/assets/images/$ml.jpg'></p>";
					echo "<p>$ml</a></p>";
					echo "</div>";
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>
	</div>
	<div class="row" id='band'>



	<h2>Followed List</h2>
	<p><a href='/LiveConcert/concertlist/my_followed_list.php'>>>>&nbsp; &nbsp;See All</a></p>
	<?php 

		if($result = $mysqli->query("call followed_recommend_list('$pageowner')") or die($mysqli->error)){
			if($result->num_rows > 0){
				while($row = $result->fetch_object()){
					$fl = $row->listname;
					echo "<div class='span3'><p><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$fl' >";
					echo "<img src='/LiveConcert/assets/images/$fl.jpg'></p>";
					echo "<p>$fl</a></ul></p></div>";
				}
			}
			$result->close();
			$mysqli->next_result();
		}

	?>
</div>
</div>

</section>





</body>
</html>


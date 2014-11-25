<!DOCTYPE html>
<html>
<head>
<?php include "includes/head.php";
	include "includes/checklogin.php";
	$username = $_SESSION['username']; 
	include "menu/home_menu.php";
?>
	<title>New Info</title>
</head>
<body>

<center><h1>Live Concert</h1></center>
<div aligh='right'><a href='user/edit_profile.php?username = <?php echo $username;?>'><img src='assets/images/<?php echo $username; ?>.jpg'></a></div>
<div>
	<h3>Your Bands New Concert</h3>
<!-- the upcoming concert played by the band of which the user is fan  -->
	<?php 
		if($result = $mysqli->query("call new_concert_band_user_follow('$username')") or die($mysqli->error)){
			if($result->num_rows >0){
				$prev = "";
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					$baname = $row->baname;
					if($cname == $prev){
						echo "<li><a href='artist_band/band_page.php?baname=$baname'>$baname</a></li>";
					}else{
						$cposttime = $row->cposttime;
						echo "<img src='assets/images/$cname.jpg'>";
						echo "<a href='concert/concert_page.php?cname=$cname'>$cname</a>: ".$cposttime."<li>Band:<a href='artist_band/band_page.php?baname=$baname'>$baname</a></li>";
					}
					$prev = $cname;
				}
				
			}
			$result->close();
			$mysqli->next_result();

		}
	?>
</div>
<div>

	<h3>Your Following New Recommend Concert</h3>
	<?php 
		if($result = $mysqli->query("call new_recommen_list_by_follow('$username')") or die($mysqli->error)){
			if($result->num_rows >0){
				while($row = $result->fetch_object()){
					$listname = $row->listname;
					$createby = $row->username;
					echo "<a href='concertlist/concertlist_page.php?listname=$listname'>$listname</a> by<a href='user/user_page.php?username=$createby' >$createby</a>".$row->lcreatetime;
				}
				
			}
			$result->close();
			$mysqli->next_result();

		}
	?>
</div>
<div>
	<h3>Your Following Update</h3>
	
	<?php 
		if($result = $mysqli->query("call follower_attend_concert('$username')") or die($mysqli->error)){
			if($result->num_rows >0){
				while($row = $result->fetch_object()){
					$follower = $row->username;
					$cname = $row->cname;
					$decision = $row->decision;
					echo "<a href='user/user_page.php?user=$follower'>$follower</a> decide $decision <a href='concert/concert_page.php?cname=$cname'>$cname</a>";
				}
				
			}
			$result->close();
			$mysqli->next_result();

		}
	?>
</div>
<div>
	<h3>New Artist</h3>
	
	<?php 
		if($result = $mysqli->query("call new_registe_artist('$username')") or die($mysqli->error)){
			if($result->num_rows >0){
				while($row = $result->fetch_object()){
					$artist = $row->username;
					echo "<img src='assets/images/$artist.jpg'>";
					echo "<a href='user/user_page.php?username=$artist'>$artist</a> has just registe our website";
				}
				
			}
			$result->close();
			$mysqli->next_result();

		}
	?>
</div>
<div>
	<h3>New Band</h3>
	
	<?php 

		if($result = $mysqli->query("call new_band('$username')") or die($mysqli->error)){
			if($result->num_rows >0){
				while($row = $result->fetch_object()){
					$band = $row->baname;
					echo "<img src='assets/images/$band.jpg'>";
					echo "<a href='artist_band/band_page.php?baname=$band'>$band</a>";

				}
				
			}
			$result->close();
			$mysqli->next_result();

		}
	?>

</div>

</body>
</html>



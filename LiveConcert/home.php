<!DOCTYPE html>
<html>
<head>
<?php include "includes/head.php";
	include "includes/checklogin.php";
?>
	<title>New Info</title>
</head>
<body>
<?php $username = $_SESSION['username']; ?> 
<center><h1>Live Concert</h1></center>
<h2>New updates</h2>
<div>
	<h3>Your Bands New Concert</h3>
	
	<?php 
		if($result = $mysqli->query("call new_concert_band_user_follow($username)")){
			if($result->num_rows >0){
				$prev = "";
				while($row = $result->fetch_object()){
					$cname = $row->cname;
					$baname = $row->baname;
					if($cname == $prev){
						echo "<li><a href='artist_band/band_page.php?baname=$baname'>$baname</a></li>";
					}else{
						echo "<img scr='assets/images/$cname.jpg'>"
						echo "<a href='concert/concert_page.php?cname=$cname'>$cname</a>: ".$row->cposttime."<li>Band:<a href='artist_band/band_page.php?baname=$baname'>$baname</a></li>";
					}
					$prev = $cname;
				}
				$result->close();
			}

		}
	?>
</div>
<div>
	<h3>Your Following New Recommend Concert</h3>
	<?php 
		if($result = $mysqli->query("call new_recommen_list_by_follow($username)")){
			if($result->num_rows >0){
				while($row = $result->fetch_object()){
					$listname = $row->listname;
					$createby = $row->username;
					echo "<a href='concertlist/concertlist_page.php?listname=$listname'>$listname</a> by<a href='user/user_page.php?username=$createby' >$createby</a>".$row->lcreatetime;
				}
				$result->close();
			}

		}
	?>
</div>
<div>
	<h3>Your Following Update</h3>
	
	<?php 
		if($result = $mysqli->query("call follower_attend_concert($username)")){
			if($result->num_rows >0){
				while($row = $result->fetch_object()){
					$follower = $row->username;
					$cname = $row->cname;
					$decision = $row->decision;
					echo "<a href='user/user_page.php?user=$follower'>$follower</a> decide $decision <a href='concert/concert_page.php?cname=$cname'>$cname</a>";
				}
				$result->close();
			}

		}
	?>
</div>
<div>
	<h3>New Artist</h3>
	
	<?php 
		if($result = $mysqli->query("call new_registe_artist($username)")){
			if($result->num_rows >0){
				while($row = $result->fetch_object()){
					$artist = $row->username;
					echo "<img scr='assets/images/$artist.jpg'>";
					echo "<a href='user/user_page.php?username=$artist'>$artist</a> has just registe our website";
				}
				$result->close();
			}

		}
	?>
</div>
<div>
	<h3>New Band</h3>
	new_band
	<?php 

		if($result = $mysqli->query("call ")){
			if($result->num_rows >0){
				while($row = $result->fetch_object()){
					$band = $row->baname;
					echo "<img scr='assets/images/$band.jpg'>";
					echo "<a href='artist_band/band_page.php?baname=$band'>$band</a>";

				}
				$result->close();
			}

		}
	?>

</div>
<?php 


?>
</body>
</html>



<!DOCTYPE html>
<html>
<head>
<?php 
include "includes/config.php";
	include "includes/new_head.html"; 
	include "includes/checklogin.php";
include "menu/home_menu.php";
	?>
	<title>You May Like</title>
</head>
<body>

<section class='content'>
<h3 class="letter">Concert <strong>Recommendation 

	<div class="container white-background ">
		<div  class='row' id='concert'> 
			<h2>Recommend By system Pearson similarity</h2>
		
		
					
<?php 

$username = $_SESSION['username'];
// pearson similarity
	if($get_c_score = $mysqli->query("call get_predict_concert_score('$username')") or die($mysqli->error)){
		if($get_c_score->num_rows > 0){
			while($row = $get_c_score->fetch_object()){
				$cname = $row->cname;
				$avg_score = $row->predict_score;

				echo "<div class='span3'>";
				echo "<a href='/LiveConcert/concert/concert_page.php?cname=$cname'>
					<img height='250px' width='250px' src='/LiveConcert/assets/images/$cname.jpg' ></a>";
					echo "<h3>$cname</h3><h3>$score</h3>";
				echo "</div>";
						
			}
		}
		$get_c_score->close();
		$mysqli->next_result();
		echo "</div>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
	}
//-- other querys could be used. 
 // the system could recommend to the user those concerts in the categories the user likes that 
// were recommended in many lists by other usersthose can divide to 2 query in application
	
	echo  "
		<div  class='row' id='concert'> ";
	echo "<h2>Recommend to the user those concerts in the categories the user likes that 
were recommended in many lists by other users</h2>";

 	if($get_recommend_most = $mysqli->query("call similar_taste_bandconcert_recommended_most('$username')") or die($mysqli->error)){
 		if($get_recommend_most->num_rows > 0){
 			while($row = $get_recommend_most->fetch_object()){
 				$cname = $row->cname;
 				echo "<div class='span3'>";
				echo "<a href='/LiveConcert/concert/concert_page.php?cname=$cname'>
					<img height='250px' width='250px' src='/LiveConcert/assets/images/$cname.jpg' ></a>";
					echo "<h3>$cname</h3>";
				echo "</div>";

 			}
 		}
 		$get_recommend_most->close();
 		$mysqli->next_result();
 		echo "</div>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
 	}
 	echo "<div  class='row' id='concert'> ";
 	echo "<h2>The system could suggest bands that were liked </h2>";
 	if($high_rate_by_simi_taste = $mysqli->query("call fan_of_band_concert('$username')") or die($mysqli->error)){
 		if($high_rate_by_simi_taste->num_rows > 0){
 			while($row= $high_rate_by_simi_taste->fetch_object()){
 				$cname = $row->cname;
 				echo "<div class='span3'>";
				echo "<a href='/LiveConcert/concert/concert_page.php?cname=$cname'>
					<img height='250px' width='250px' src='/LiveConcert/assets/images/$cname.jpg' ></a>";
					echo "<h3>$cname</h3>";
				echo "</div>";
 			}
 		}
 		$high_rate_by_simi_taste->close();
 		$mysqli->next_result();
 		echo "</div>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
 	}
 	echo "<div  class='row' id='concert'> ";
 	echo "<h2>The concerts were highly rated by other users that 
had similar tastes to this user in the past<h2>";
	if($high_rate = $mysqli->query("call similar_taste_user_high_rate_concert_band_future_concert('$username')") or die($mysqli->error)){
		if($high_rate->num_rows > 0){
			while($row = $high_rate->fetch_object()){
				echo "<div class='span3'>";
				echo "<a href='/LiveConcert/concert/concert_page.php?cname=$cname'>
					<img height='250px' width='250px' src='/LiveConcert/assets/images/$cname.jpg' ></a>";
					echo "<h3>$cname</h3>";
				echo "</div>";
			}
		}
		$high_rate->close();
		$mysqli->next_result();
		echo "</div>";
	}

?>
</div>
</div>
</section>
</body>
</html>
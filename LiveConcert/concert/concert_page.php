<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="assets/js/jquery/jquery.js"></script>
<?php include "../includes/head.php"; 
include $path."/LiveConcert/menu/home_menu.php";

?>
	<title>Concert Page</title>
</head>
<body>

<?php 
$review = false;
$username = $_SESSION['username'];
$cname = "";
$userscore = $_SESSION['score'];
$err = "";
$decision = "";
$ratingscore = 0;
$price = 0;
$isInProcess = false;
$isPastConcert = false;
$attended = false;
$capacity="";
$availability=0;
//if concert has not started then no review and star
//if past show "past"
function IsPastConcert($cname){
	global $err,$mysqli;
	if($cname){
		if($inpast = $mysqli->query("call is_past_concert('$cname')") or die($mysqli->error)){
			if($inpast->num_rows > 0){
				$inpast->close();
				$mysqli->next_result();
				return true;
			}else{
				$inpast->close();
				$mysqli->next_result();
				return false;
			}
			
		}else{
			$err = "mysqli fetch error";
			return false;
		}
	}else{
		$err = "no concert name";
		return false;
	}

}
function IsInConcertProcess($cname){
	global $err, $mysqli;
	if($cname){
		if($inprocess = $mysqli->query("call is_in_concert_process('$cname')") or die($mysqli->error)){
			if($inprocess->num_rows > 0){
				$inprocess->close();
				$mysqli->next_result();
				return true;
			}else{
				$inprocess->close();
				$mysqli->next_result();
				return false;
			}
			
		}else{
			return false;
		}
	}else{
		$err = 'no concert name';
		return false;
	}

}
function UserDecision($username,$cname){
	global $err,$mysqli;
	if($cname){
		if($decision = $mysqli->query("call user_decision('$username','$cname')") or die($mysqli->error)){
			if($row = $decision->fetch_object()){
				$userDecision = $row->decision;
				$decision->close();
				$mysqli->next_result();
				return $userDecision; 
			}else{
				$decision->close();
				$mysqli->next_result();
				return false;
			}
			
		}else{
			$err = "fetch error";
			return false;
		}

	}else{
		$err = "no concert name";
		return false;
	}
}
function RatedConcertScore($username, $cname){
	global $err,$mysqli;
	if($cname){
		if($ratescore = $mysqli->query("call rated_concert_score('$username', '$cname')") or die($mysqli->error)){
			if($row = $ratescore->fetch_object()){
				$score = $row->rating;
				$ratescore->close();
				$mysqli->next_result();
				return $score;
			}else{
				$ratescore->close();
				$mysqli->next_result();
				return 0;
			}
		}else{
			$err = "fetch error";
			return 0;
		}
	}else{
		$err = "no concert name";
		return -1;
	}
	
}
function IsAttended($username,$cname){
	global $err,$mysqli;
	if($attend = $mysqli->query("call is_attended('$username','$cname')") or die($mysqli->error)){
		if($attend->num_rows > 0){
			$attend->close();
			$mysqli->next_result();
			return true;
		}else{
			$attend->close();
			$mysqli->next_result();
			return false;
		}
	}else{
		$err = "fetch error";
		return false;
	}
}
if(isset($_GET['cname'])){
	$cname = $_GET['cname'];
	$decision = UserDecision($username,$cname);
	$ratingscore =RatedConcertScore($username,$cname);
	$isInProcess = IsInConcertProcess($cname);
	$isPastConcert = IsPastConcert($cname);
	$attended = IsAttended($username,$cname);
	if(isset($_GET['review'])){
		$review = true;
	}
	// if($userscore >=10)
}
if($_SERVER["REQUEST_METHOD"]=='POST'){
	if(isset($_GET['cname'])){
		$cname = $_GET['cname'];
		if(isset($_POST['review'])){
			$reviewSubmit = $_POST['review'];
			if($insertReview = $mysqli->query("call insert_review('$username','$cname','$reviewSubmit')")){
				// $insertReview->close();
				// $mysqli->next_result();
				$review = false;
			}
		}
		if(isset($_POST['rating-input-1'])){
			$stars = count($_POST['rating-input-1']);
			if($insertRating = $mysqli->query("call insert_rating('$username','$cname',$stars)")){
				// $insertRating->close();
				// $mysqli->next_result();
			}

		}
		//need more consideration
		if(isset($_POST['decision'])){
			$post_decision = $_POST['decision'];
			if($insertAttend = $mysqli->query("call insert_to_attendconcert('$username','$cname')") or die($mysqli->error)){
				echo "success";
			}
		}
		
	}else{
		echo "no cname";
	}
}


?>
<span color='red'><?php echo $err; ?></span>
<div id='whole_concert_page'>
<center><h2><?php echo $cname; ?></h2></center>
	<img src='/LiveConcert/assets/images/<?php echo $cname;?>.jpg'>

	
<?php 
	if($isInProcess){
		if($userscore >= 10){
		// check the page in process add the prove button
			echo "status: In Process";
			echo "<form><input type='hidden' name='cname' value='$cname'><input type='submit' name='submit' value='Approve'></form>";
			// echo "<>"
			//original ??????????

		}else{
			//concert turn gray
			echo "<span>In Process, Wait to be Approved</span>";
		}

	}
?>
<div id='decision_button'>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method='POST'>
		<select name ='decision'>
			<option>Decide to </option>
			<option value ='planto' <?php if($decision == 'planto') echo 'selected'; ?> >Plan To</option>
			<option value ='going' <?php if($decision == 'going') echo 'selected'; ?>  >Going</option>
			<option value ='notgoing' >Not Noing</option>
		</select>
	</form>

</div>
<div id='concert_button'>
	<ul>
	
	<a href='/LiveConcert/concertlist/my_concertlist.php?cname=<?php echo $cname; ?>'><button>Add To Recommend List</button></a>
	<span><a href='/LiveConcert/concert/edit_concert.php?cname=<?php echo $cname; ?>'><button>Edit Concert</button></a></span>
	</ul>
</div>
<!-- if it is a past concert add going plan to option	 -->
<!-- add concert to list -->

<div id="rating">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method='POST'>

	<span class="rating">
        <input type="radio" class="rating-input"
            id="rating-input-1-5" name="rating-input-1[]" "<?php if($ratingscore >= 1){echo 'checked';} ?>" >
        <label for="rating-input-1-5" class="rating-star"></label>
        <input type="radio" class="rating-input"
            id="rating-input-1-4" name="rating-input-1[]" "<?php if($ratingscore >= 2){echo 'checked';} ?>" >
        <label for="rating-input-1-4" class="rating-star"></label>
        <input type="radio" class="rating-input"
            id="rating-input-1-3" name="rating-input-1[]" "<?php if($ratingscore >= 3){echo 'checked';} ?>" >
        <label for="rating-input-1-3" class="rating-star"></label>
        <input type="radio" class="rating-input"
            id="rating-input-1-2" name="rating-input-1[]" "<?php if($ratingscore >= 4){echo 'checked';} ?>">
        <label for="rating-input-1-2" class="rating-star"></label>
        <input type="radio" class="rating-input"
            id="rating-input-1-1" name="rating-input-1[]" "<?php if($ratingscore >= 5){echo 'checked';} ?>">
        <label for="rating-input-1-1" class="rating-star"></label>
    </span>
    </form>
</div>
======================================================================
<!-- concert infomation -->
<div id='basic_info'>
<?php 
$locname="";
$locnameUP="";
if($basicInfo= $mysqli->query("call concert_basic_info('$cname')") or die($mysqli->error)){
	if($row = $basicInfo->fetch_object()){
		$time = $row->cdatetime;
		$price = $row->price;
		$description = $row->cdescription;
		$postby = $row->cpostby;
		$posttime = $row->cposttime;
		$ticketlink = $row->ticketlink;
		$locname = $row->locname;
		$availability = $row->availability;
		$basicInfo->close();
		$mysqli->next_result();
		echo "<div id=TL><h4>Time</h4>";
		echo "<span>$time</sapn></div><br>";
		echo "<div id=TL><h4>Description</h4>";
		echo "<p>$description</p><br>";
		echo "<span>$postby</span>&nbsp;<span>$posttime</span>";
		if($isInProcess && $userscore >= 10){
			if($updated = $mysqli->query("call process_concert_basic_info('$cname')") or die($mysqli->error)){
				if($row = $updated->fetch_object()){
					$timeUP = $row->cdatetime;
					$locnameUP = $row->locname;
					$priceUP = $row->price;
					$descriptionUP = $row->cdescription;
					$editby = $row->editby;
					$edittime = $row->posttime;
					$availabilityUP = $row->availability;
					if($timeUP != $time){
						echo "<div id=UpdateInfo><h3>Update:</h3>";
						echo "<div id=UpdateTime>Updated";
						echo "<span>$timeUP</sapn></div><br>";
					}
					if($description != $descriptionUP){
						echo "<div id=DescripUpdate>Updated Description";
						echo "<p>$descriptionUP</p><br></div>";
					}
					
					if($price !=$proceUP){
						echo "<div id='PriceUpdate'>Updated Price";
						echo "<p>$priceUP</p><br></div>";
					}
					if($editby || $edittime){
						echo "<div id='UpdateBy'>Edit By";
						echo "<span><a href='/LiveConcert/user/user_page?username=$editby'>$editby</span>&nbsp;<span>$edittime</span></div>";
						echo "</div>";
					}
				}
				$updated->close();
				$mysqli->next_result();
			}
			
		}
	}
	
}
	
// valid user will see concertinfo in ConcertProcess

?>
</div>
<!-- location -->
<div id='Location_info'>
<?php 
if($originloc = $mysqli->query("call concert_loc_info('$locname')") or die($mysqli->error)){
	if($row = $originloc->fetch_object()){
		$address = $row->address;
		$city = $row->city;
		$state = $row->state;
		$capacity = $row->capacity;
		$web = $row->web;
		$originloc->close();
		$mysqli->next_result();
		echo "<div id='Location'><h3>Location:</h3>";
		echo "<div id='Location'><h4>$locname</h4>";
		echo "<li>$address</li>";
		echo "<li>$city,$state</li>";
		echo "<li>Capacity: $capacity</li>";
		echo "<li>Web: $web</li></div>";
		if($isInProcess && $userscore >= 10){
			if($locname != $locnameUP){
				if($updateLoc = $mysqli->query("call concert_loc_info('$locnameUP')") or die($mysqli->error)){
					if($row = $updateLoc->fetch_object()){
						$addressUP = $row->address;
						$cityUP = $row->city;
						$stateUP = $row->state;
						$capacityUP = $row->capacity;
						$webUP = $row->web;
						
						echo "<div id='LocationUpdate'><h4>Location update</h4>";
						echo "<div id='Location'><h3>$locnameUP</h3>";
						echo "<li>$addressUP</li>";
						echo "<li>$cityUP,$stateUP</li>";
						echo "<li>Capacity: $capacityUP</li>";
						echo "<li>Web: $webUP</li></div>";
					}
					$updateLoc->close();
					$mysqli->next_result();
				}
			}
			
		}
	}
	// valid user will see concertinfo in ConcertProcess
}

?>
</div>
<!-- bandinfo -->
<div id='Band_info'>
<?php 
if($originband = $mysqli->query("call get_band_by_cname('$cname')") or die($mysqli->error)){
	echo "<div id='playband'><h3>Play Band:</h3>";
	if($originband->num_rows > 0){
		$row=$originband->fetch_object() or die($mysqli->error);
		while($row=$originband->fetch_object()){
			$originband = $row->baname;
			echo "<li><a href='/LiveConcert/artist_band/band_page?baname=$originband'>";
			echo "<img src='/LiveConcert/assets/images/$originband.jpg'>$originband</a></li>";
		}
		echo "</div>";
		$originband->close();
		$mysqli->next_result();

		if($isInProcess && $userscore >= 10){
	// valid user will see concertinfo in ConcertProcess
			if($bandupdate= $mysqli->query("call get_band_by_process_cname('$cname')") or die($mysqli->error)){
				echo "<div id='BandUpdate'><h4>Band Update:</h4>";
				if($bandupdate->num_rows > 0){
					while($row = $bandupdate->fetch_object()){
						$bandUP = $row->baname;

						echo "<li><a href='/LiveConcert/artist_band/band_page?baname=$bandUP'>";
						echo "<img src='/LiveConcert/assets/images/$bandUP.jpg'>$bandUP</a></li>";
					}
				}
				echo "</div>";
				$bandupdate->close();
				$mysqli->next_result();
			}
		}
	}
}
?>
</div>
<!-- ticket -->


<div id='ticket'>
	<h4><span>Ticket:</span><span><?php echo "$".$price;?></span></h4>

	
	<?php 

	// if($ticketall = $mysqli->query("call get_all_ticket_count_cn('$cname')") or die($mysqli->error)){
	// 	if($ticketall->num_rows > 0){
	// 		if($row= $ticketall->fetch_object()){
	// 			$ticketTotal = $row->count;
	// 			$availability = $capacity - $ticketTotal;
	// 		}

	// 	}else{
	// 		$availability = $capacity;
	// 	}
	// 	$ticketall->close();
	// 	$mysqli->next_result();
	// }
	?>
	<span><?php if($isInProcess && $userscore >= 10){echo $availabilityUP;}else{echo $availability; } ?>&nbsp;Left!</span>
	<div>TicketLink: <?php echo $ticketlink; ?></div>
	<a href='/LiveConcert/concert/buy_ticket.php?cname=<?php echo $cname;?>'><button>Buy Now</button></a>

</div>
<div id='review'>    
	<?php  
	//get review from other user
	if($getReview = $mysqli->query("call concert_review('$cname')")){
		if($getReview->num_rows > 0){
			while($row = $getReview->fetch_object()){
				echo "<ul>".$row->username.":".$row->reviewtime."<br>";
				echo "<li>".$row->review."</li><ul>";
			}
		}
		$getReview->close();
		$mysqli->next_result();
	}
	?>

	<div id='write_review'>
		<button id='write_review_button'>Write Review</button>
		<div id='review_box'>
			<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='POST'>";
			<textarea id='review' name='review' cols='40'rows='5' ></textarea>
			<input type='submit' value='Submit Review'>
			</form>
		</div>
	</div>
</div>

<div id='in_recommend_list'>
<h3>Inclued In RecommendList</h3>
<?php 
	if($recomList = $mysqli->query("call get_recommend_list_from_cname('$cname')") or die($mysqli->error)){
		echo "<table>";
		while($row = $recomList->fetch_object()){
			$listname = $row->listname;
			$createdby = $row->username;
			$description = $row->ldescription;
			echo "<ul><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname'>";
			echo "<img src='/LiveConcert/assets/images/$listname.jpg'><span>$listname</span></a>";
			echo "<a href='/LiveConcert/user/user_page?username=$createdby'>$createdby</a>";
			echo "<p>$description</p></ul>";
		}
		$recomList->close();
		$mysqli->next_result();
	}

?>
</div>
</div>	
<script type="text/javascript">
	$('#review').hide();
	
	$("#rating").hide();
	$('#review_box').hide();
	var cname = '<?php echo $cname; ?>';
		// var review = <?php echo $review; ?>;
	var userscore = parseInt(<?php echo $userscore;?>);

	// var isInProcess = <?php echo $isInProcess; ?>;
	// var isPastConcert = <?php echo $isPastConcert; ?>;
	// var attended = <?php echo $attended; ?>;
	$('#write_review_button').click(function(){
		$('#review_box').toggle();
	});
	if(<?php echo (int)$isInProcess; ?>){
		$('#concert_button').hide();
		$('#decision_button').hide();
		$('#ticket').hide();
		if(userscore < 10){
			$('#whole_concert_page').css("color","grey");
		}
	}else{
		
		if(<?php echo (int)$isPastConcert; ?>){
			$('#ticket').hide();
			$('#decision_button').hide();
			if(<?php echo (int)$attended; ?>){
				alert('123');
				$("#rating").show();
				$('#review').show();
				$('#write_review').show();
				$('#review_box').fadeIn();
				$('#decision_button').show();
			}
			if(<?php echo (int)$review; ?>){
				$('#review_box').fadeIn();
			}
		}
	}
</script>
	
	
</div>
</body>
</html>
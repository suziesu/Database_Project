<!DOCTYPE html>
<html>
<head>
<?php include "../includes/new_head.html"; 
include "../includes/regular_page_head.php";
?>
 <script type="text/javascript" src="../assets/js/jquery/jquery.js"></script>

	<title>Concert Page</title>

</head>
<body>

<?php 
$review = 0;
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
function isConcertExist($cname){
	global $mysqli,$err;
	if($cname){
		if($concertExist = $mysqli->query("call concert_basic_info('$cname')") or die($mysqli->error)){
			if($concertExist->num_rows > 0){
				$concertExist->close();
				$mysqli->next_result();
				return true;
			}else{
				$concertExist->close();
				$mysqli->next_result();
				return false;
			}
			
		}else{
			// echo "mysql error";
			return false;
		}
	}else{
		// echo "no cname";
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
// function approvedbyInConcertProcess($cname){
// 	global $err, $mysqli;
// 	if($cname){
// 		if($inprocess = $mysqli->query("call is_in_concert_process('$cname')") or die($mysqli->error)){
// 			if($inprocess->num_rows > 0){
// 				$approvedby = 
// 				$inprocess->close();
// 				$mysqli->next_result();
// 				return true;
// 			}else{
// 				$inprocess->close();
// 				$mysqli->next_result();
// 				return false;
// 			}
			
// 		}else{

// 			return false;
// 		}
// 	}else{
// 		$err = 'no concert name';
// 		return false;
// 	}

// }
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
				// echo "rate";

				// echo $row->rating;
				$rating_score = $row->rating;
				$ratescore->close();
				$mysqli->next_result();
				// echo "123".$rating_score;
				return $rating_score;
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
	if(isset($_GET['review'])){
		$review = true;
	}
	// echo $username;
	$decision = UserDecision($username,$cname);
	// echo "10:".$decision;
	$ratingscore =RatedConcertScore($username,$cname);
	// echo "11:".$ratingscore;
	$isInProcess = IsInConcertProcess($cname);
	// echo "12:".$isInProcess;
	$isPastConcert = IsPastConcert($cname);
	// echo "13:".$isPastConcert;
	$attended = IsAttended($username,$cname);
	
	// if($userscore >=10)
}
if($_SERVER["REQUEST_METHOD"]=='POST'){
	if(isset($_POST['cname']) || isset($_GET['cname'])){
		// echo "aaaaaa";
		if(isset($_POST['cname'])){$cname = $_POST['cname'];}else{$cname = $_GET['cname'];}
		// $cname = $_POST['cname'];

		// echo $cname;
		// $decision = UserDecision($username,$cname);
		// $ratingscore =RatedConcertScore($username,$cname);
		// $isInProcess = IsInConcertProcess($cname);
		// $isPastConcert = IsPastConcert($cname);
		// $attended = IsAttended($username,$cname);

		if(isset($_POST['review_submit']) && $_POST['submit'] == 'review'){
			// echo "b";
			$reviewSubmit = $_POST['review_submit'];
			unset($_POST['review_submit']);
			if($insertReview = $mysqli->query("call insert_review('$username','$cname','$reviewSubmit')") or die($mysqli->error)){
				// $insertReview->close();
				// $mysqli->next_result();
				// echo "review success";
				$review = false;
				$mysqli->next_result();
			}
			$plus_score = 0.1;
			if($update_user_score = $mysqli->query("call update_user_score_by_review('$username','$plus_score')") or die($mysqli->error)){
				// echo "success";
				$mysqli->next_result();
			}
							
		}
		if(isset($_POST['rating']) && isset($_POST['submit']) && $_POST['submit']=='Rate The Concert' && $_POST['rating'] > 0){
			// echo "c";

			$ratingscore = ($_POST['rating']);

			// unset($_POST['rating']);
			if($delete_prev = $mysqli->query("call delete_rating('$username','$cname')") or die($mysqli->error)){
				// echo 'success';
				// $mysqli->next_result();
			}
			if($insertRating = $mysqli->query("call insert_rating('$username','$cname',$ratingscore)") or die($mysqli->error)){
				// $insertRating->close();
				echo "rating success";
				// $mysqli->next_result();
				
			}
			$plus_score = 0.1;
			if($update_user = $mysqli->query("call update_user_score_by_review('$username','$plus_score')") or die($mysqli->error)){
				echo "userscore success";
				$mysqli->next_result();
			}
			//call calculate pearson similarity
			$operand_array = array();
			if($get_operand = $mysqli->query("call get_pearson_calculate_operand()") or die($mysqli->error)){
				if($get_operand->num_rows > 0){
					while($row = $get_operand->fetch_object()){
						$row_obj = array();
						$row_obj['baname1'] = $row->baname1;
						$row_obj['baname'] = $row->baname;
						$row_obj['sumR1'] = $row->sumR1;
						$row_obj['sumR2'] = $row->sumR2;
						$row_obj['sumR1square'] = $row->sumR1square;
						$row_obj['sumR2square'] = $row->sumR2square;
						$row_obj['sumR1multiR2'] = $row->sumR1multiR2;
						$row_obj['count'] = $row->count;
						$operand_array[] = $row_obj;

					}
				}
				$get_operand->close();
				$mysqli->next_result();
			}
			foreach ($operand_array as $key) {
				$b1 = $row_obj['baname1'] ;
				$b2 = $row_obj['baname'];
				$x = $row_obj['sumR1'];
				$y = $row_obj['sumR2'] ;
				$xx = $row_obj['sumR1square'];
				$yy = $row_obj['sumR2square'];
				$xy = $row_obj['sumR1multiR2'];
				$count = $row_obj['count']; 
				if($calcu_PS = $mysqli->query("call calcu_PearsonSimilarity('$b1','$b2','$x','$y','$xx','$yy','$xy','$count')") or die($mysqli->error)){
					// echo " pearson cal success";
					$mysqli->next_result();
				}
				# code...
			}

		}
		//need more consideration
		if(isset($_POST['decision']) && $_POST['submit'] == 'decide'){
			// echo "d";
			$decision = $_POST['decision'];
			unset($_POST['decision']);
			if($deleteD= $mysqli->query("call delete_decision('$username','$cname')") or die($mysqli->error)){
				// $mysqli->next_result();
			}
			if($insertAttend = $mysqli->query("call insert_to_attendconcert('$username','$cname','$decision')") or die($mysqli->error)){
				// echo "success";
				$mysqli->next_result();
				
			}
		}
		if(isset($_POST['submit']) && $_POST['submit'] == 'Approve'){
			// echo "e";
			$cname = $_POST['cname'];
			// echo $cname;
			if(IsInConcertProcess($cname)){
				// echo "k";
				if($statusInfo = $mysqli->query("call process_concert_basic_info('$cname')") or die ($mysqli->error)){
					if($row = $statusInfo->fetch_object()){
						$posttime_CP = $row->posttime;
						$editby_CP = $row->editby;
						$status_result = $row->cstatus;
						$cdatetime_CP = $row->cdatetime;
						$locname_CP = $row->locname;
						$price_CP = $row->price;
						$availability_CP = $row->availability;
						$cdescription_CP = $row->cdescription;
						$statusInfo->close();
						$mysqli->next_result();
						$band_in_PC_array = array();
						if($status_result == 'pending'){
							// echo "f";
							// change to "in process"
							$status_update = 'in process';
							if($update_process = $mysqli->query("call update_status('$cname','$status_update')") or die($mysqli->error)){
								// echo "UP statis success";
								$_SESSION['approvedbyuser'] = $username;
								$mysqli->next_result();
							}
						}else if($status_result == 'in process'){
							// echo "g";
							//get band in BandProcess
							$band_in_PC_array = array();
							if($bandInProcess = $mysqli->query("call get_band_info_in_process('$cname')") or die($mysqli->error)){
								if($bandInProcess->num_rows > 0){
									// echo "h";
									while($row = $bandInProcess->fetch_object()){
										$band_in_PC_array[] = $row->baname;
									}
									$bandInProcess->close();
									$mysqli->next_result();
								}else{
									// echo "i";
									$bandInProcess->close();
									$mysqli->next_result();
								}
							}
							// update first
							$concert_exist = isConcertExist($cname);
							// echo $concert_exist."?";
							if($concert_exist){
								// echo "j";
								if($updateToConcert = $mysqli->query("call update_into_concert('$cname','$posttime_CP','$editby_CP','$cdatetime_CP','$locname_CP','$price_CP','$availability_CP','$cdescription_CP')") or die($mysqli->error)){
									// echo "update Concert success";
									// $mysqli->next_result();
								}
								if(count($band_in_PC_array) > 0){
									// echo "k";
									if($delete_band = $mysqli->query("call delete_from_playband('$cname')") or die($mysqli->error)){
										// echo "success";
										// $mysqli->next_result();
									}
								}

							}else{
								// echo "l";
								$ticketlink = "";
								if($insertToConcert = $mysqli->query("call create_concert('$cname','$cdatetime_CP','$locname_CP','$price_CP','$availability_CP','$cdescription_CP','$editby_CP','$ticketlink')") or die($mysqli->error)){
									echo "insert success";
									$mysqli->next_result();
								}
							}
							
							//delete band first
							
							
							// update band info
							foreach ($band_in_PC_array as $key) {
								if($updateToBand = $mysqli->query("call update_into_playband('$cname','$key')") or die($mysqli->error)){
									echo "insert success";
									$mysqli->next_result();
								}
								# code...
							}
							//delete band in playbandprocess
							if($delete_from_PBP = $mysqli->query("call delete_from_playbandprocess('$cname')") or die($mysqli->error)){
								echo "delete PBP success";
								$mysqli->next_result();
							}
							
							// delete from concertprocess update to Cocnert
							if($deleteCP = $mysqli->query("call delete_from_CP('$cname')") or die($mysqli->error)){
								echo "success";
								$mysqli->next_result();
							}
						}else{
							echo "status is not right";
						}
					}
					
				}
			}else{
				echo "not in process concert";

			}

		}
		
	}else{
		echo "no cname";
	}
}

?>

</header>
<span color='red'><?php echo $err; ?></span>
<div id='whole_concert_page'>




<section id="content">
        <!--Container  -->    
        <div class="container white-background">
            <div class="row">
                <div class="span6">
                    <div class="slider1 flexslider">
                        <img class="concert" src="/LiveConcert/assets/images/<?php echo $cname; ?>.jpg">
                    </div>
                </div>
                <div class="span6 scrollable-2 gray-background-1">
                    <div class="row">
                        <div class="span6">
                            <?php 
                            	echo '<h1>'.$cname;
								if($isInProcess){

									if($userscore >= 10){
										echo "status: In Process";
										if(!isset($_SESSION['approvedbyuser']) || $_SESSION['approvedbyuser'] != $username){
											echo "<form action='".htmlspecialchars($_SERVER['PHP_SELF'])."?cname=$cname' method='POST'><input type='hidden' name='cname' value='$cname'/><input type='submit' name='submit' value='Approve'/></form>";

										}
									}else{echo "<span>In Process</span>";}
								}?></h1>
                        </div>
                    </div>
          <!-- basic concert info -->
						<?php 
						$locname="";$time="";$price="";$postby="";$posttime="";$description="";
						$locnameUP="";$timeUP="";$priceUP="";$editby="";$edittime="";$ticketlink="";
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
								echo "<p>".$description."</p>";

							}else{
								$basicInfo->close();
								$mysqli->next_result();
							}
							
							
						}
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
									if($description != $descriptionUP){
										echo "<p ><i id='update' class='icon-quote-left'></i>$descriptionUP<i id='update' class='icon-quote-right'></i></p>";
									}
								}
								$updated->close();
								$mysqli->next_result();
							}
							
						}
						echo "<h3><i class='icon-calendar icon-x'></i>      $time";
						 if($isInProcess && $userscore >= 10 && $timeUP!=""){
						 	echo "<i id='update' class='icon-quote-left'></span></i>$timeUP<i id='update' class='icon-quote-right'></i>";
						 }
						 echo "</h3>";
						echo "<h3><i class='icon-home icon-x'></i> Location: <a href='/LiveConcert/venues/venues.php?locname=$locname'>$locname</a>"; 
						if($isInProcess && $userscore >= 10 && $locnameUP!=""){
							echo "<i id='update' class='icon-quote-left'></i><a href='/LiveConcert/venues/venues.php?locname=$locnameUP'>$locnameUP</a><i id='update' class='icon-quote-right'></i>";
						} 
						echo "</h3>";                  
			// get band info
						if($originB= $mysqli->query("call get_band_by_cname('$cname')") or die($mysqli->error)){
							echo " <h2>Play Band</h2><blockquote>";
							if($originB->num_rows > 0){
								while($row=$originB->fetch_object()){
									$originband = $row->baname;
									echo "<h4><a href='/LiveConcert/artist_band/band_page.php?baname=$originband'>$originband</h4>";
								}
								
							}
							$originB->close();
							$mysqli->next_result();
							
							echo "</blockquote>";
						}
						if($isInProcess && $userscore >= 10){
						// valid user will see concertinfo in ConcertProcess
							if($bandupdate= $mysqli->query("call get_band_by_process_cname('$cname')") or die($mysqli->error)){
								echo "<div id='BandUpdate'><h3>Band Update:</h3>";
								if($bandupdate->num_rows > 0){
									while($row = $bandupdate->fetch_object()){
										$bandUP = $row->baname;
										echo "<h4><i id='update' class='icon-quote-left'></i><a href='/LiveConcert/artist_band/band_page?baname=$bandUP'>$bandUP<i id='update' class='icon-quote-right'></i></h4>";
									}
								}
								echo "</div>";
								$bandupdate->close();
								$mysqli->next_result();
							}
						}
						?>

                   
                	<p><i class='icon-edit'></i>  Postby:  <?php echo "<a href='/LiveConcert/user/user_page.php?username=$postby'>".$postby."</a>&nbsp;".$posttime;if($isInProcess && $userscore >= 10 && $editby !=""){echo "<span id='update'><i class='icon-quote-left'></i><a href='/LiveConcert/user/user_page.php?username=$editby'>$editby</a><i class='icon-quote-right'></i></sapn>";} ?></p>
                </div>
            </div>
			<div class='row gray-background-1'>
              <div class='row gray-background-1'>
                <!-- <div class='span2 center padding-75'>                 -->
                    
                                 
              
 <!-- rate concert            -->
<div id="rating" class='span2 center padding-75'>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?cname='.$cname;?>" method='POST'>
		
	<span class="rating">
	<fieldset class="rating">
    <input <?php if($ratingscore == 5){echo 'checked';} ?>  type="radio" id="star5" name="rating" value="5"  /><label for="star5" title="Rocks!">5 stars</label>
    <input <?php if($ratingscore ==4){echo 'checked';} ?> type="radio" id="star4" name="rating" value="4"  /><label for="star4" title="Pretty good">4 stars</label>
    <input <?php if($ratingscore == 3){echo 'checked';} ?> type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Meh">3 stars</label>
    <input <?php if($ratingscore == 2){echo 'checked';} ?> type="radio" id="star2" name="rating" value="2"  /><label for="star2" title="Kinda bad">2 stars</label>
    <input <?php if($ratingscore == 1){echo 'checked';} ?> type="radio" id="star1" name="rating" value="1"  /><label for="star1" title="Sucks big time">1 star</label>
	</fieldset>
    </span>
 	

    <span class='write-comment'><input id='rate_concert' type='submit'  name='submit' value='Rate The Concert'/></span></a> 

    </form>
</div>

<!-- concert button -->

				<div class='span2 center padding-75' id='concert_button'>
				<div id='concert_button'>
                    <a href='/LiveConcert/concertlist/my_concertlist.php?cname=<?php echo $cname; ?>'><i class='icon-heart-empty icon-3x'></i></a>
                    <span class='write-comment'><a href='/LiveConcert/concertlist/my_concertlist.php?cname=<?php echo $cname; ?>'>Add To My List</a></span></a>                                   
               </div>
               </div>
               <div class='span2 center padding-75'>
               <div id='concert_button'>
               <a href='/LiveConcert/concert/edit_concert.php?cname=<?php echo $cname; ?>'>
               <i class='icon-edit icon-3x'></i><span class='write-comment'>Edit Concert</span></a>
                    </div>                  
               </div>


			    <div class='span6'>
			        
                        <p class='padding-15'>
                       <!--  <a href='#' class='social-network-3 behance'></a>
                        <a href='#' class='social-network-3 deviantart'></a>
                        <a href='#' class='social-network-3 facebook'></a>
                        <a href='#' class='social-network-3 twitter'></a>
                        <a href='#' class='social-network-3 dribble'></a>
                        <a href='#' class='social-network-3 twitter'></a> -->
                                
                                <!-- if it is a past concert add going plan to option	 -->
<!-- add concert to list -->

							<div id='decision_button'>
								<div id='decision_button' class='span2 center padding-75'><p>Decision</p>
								<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?cname='.$cname;?>" method='POST'>
									<select name ='decision'>
									<!-- <span class='write-comment'>Decision</span> -->
										<option>Decide to </option>
										<option value ='planto' <?php if($decision == 'planto') echo 'selected'; ?> >Plan To</option>
										<option value ='going' <?php if($decision == 'going') echo 'selected'; ?>  >Going</option>
										<option value ='notgoing' >Not going</option>
									</select>
									<input type="text" name='cname' value='<?php echo $cname; ?>' style="display:none"/>
									<input type="submit" name='submit' value='decide' style="display:none"/>
								</form>
								<!-- </div> -->
							</div>
						</p>

                </div>

            </div class="row spacer40"></div>
            </div>

<!-- location -->
<!-- <div id='Location_info'> -->
			<div class='row'>
				<div class='span6'>
					<center><h3>Location</h3></center>
				

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
		echo "<center><div id='Location'><a href='/LiveConcert/venues/venues.php?locname=$locname'><h4>$locname</h4></a>";
		echo "<p>$address";
		echo "<p>$city,$state</p>";
		echo "<p>Capacity: $capacity</p>";
		echo "<p>Web: $web</p></center>";
		
		echo "</p>
				</div>";
	}else{
		$originloc->close();
		$mysqli->next_result();
	}
	// valid user will see concertinfo in ConcertProcess
}
if($isInProcess && $userscore >= 10){
	if($locname != $locnameUP){
		if($updateLoc = $mysqli->query("call concert_loc_info('$locnameUP')") or die($mysqli->error)){
			if($row = $updateLoc->fetch_object()){
				$addressUP = $row->address;
				$cityUP = $row->city;
				$stateUP = $row->state;
				$capacityUP = $row->capacity;
				$webUP = $row->web;
				
				echo "<center><div id='update'><h4>Location update</h4>";
				echo "<h3><i id='update' class='icon-quote-left'></i>$locnameUP<i id='update' class='icon-quote-right'></i></h3> ";
				echo "<ul>$addressUP</ul>";
				echo "<ul>$cityUP,$stateUP</ul>";
				echo "<ul>Capacity: $capacityUP</ul>";
				echo "<ul>Web: $webUP</ul></center></div>";
				$updateLoc->close();
				$mysqli->next_result();	
			}else{
				echo "no new location";
				$updateLoc->close();
				$mysqli->next_result();	
			}
			
		}
	}
	
}

?>


			<div id='ticket'>
	<!-- <h4><span>Ticket:</span><span></span></h4> -->
				<div class='span6'>
					<center><h3>Ticket</h3>
					<p><?php echo "$".$price;?></p>
					<p><?php if($isInProcess && $userscore >= 10){echo $availabilityUP;}else{echo $availability; } ?>&nbsp;Left!</p>
					<p>TicketLink: <br><?php echo $ticketlink; ?></p>
					<p><a href='/LiveConcert/concert/buy_ticket.php?cname=<?php echo $cname;?>&price=<?php echo $price; ?>&availability=<?php echo $availability; ?>'><input id='submit' type="submit" value='Buy Now'/></a></p></center>

				</div>
			</div>
			<div class="row">
			</div>

				
				
			

					<h4>Included In Other User's Recommend List</h4>
				<?php 
					if($recomList = $mysqli->query("call get_recommend_list_from_cname('$cname')") or die($mysqli->error)){
						echo "<table>";
						while($row = $recomList->fetch_object()){
							$listname = $row->listname;
							$createdby = $row->username;
							$description = $row->ldescription;
							echo "<div class='span3'>";
							echo "<h3><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname'>$listname</a></h3>";
							echo "<p><img src='/LiveConcert/assets/images/$listname.jpg'><p>";
							echo "<p><a href='/LiveConcert/user/user_page?username=$createdby'>create by: $createdby</a></p>";
							echo "</div>";
							// echo "<p>$description</p></ul>";
						}
						$recomList->close();
						$mysqli->next_result();
					}

				?>
			</div>
			</div>
            <div class='row spacer40'></div>
        </div>                   
        <!-- ./Container --> 

        <!-- Container -->
        <div class='container gray-background-1'>
            <div id='review' class='row'>
                <div class='span10'>
                    <h2>REVIEWS</h2>
                </div>
            </div>
            <div class='row'>
                <div class='span10'>
<?php  
	//get review from other user
	if($getReview = $mysqli->query("call concert_review('$cname')")){
		if($getReview->num_rows > 0){
			while($row = $getReview->fetch_object()){
				echo " <div class='comment'>";
				echo "<h6>".$row->username."</h6>";
				echo "<div class='comment-date'>".$row->reviewtime."</div>";
				echo "<p>".$row->review."</p>";
				echo "<hr class='comment-line'>
                    </div>";
			}
		}
		$getReview->close();
		$mysqli->next_result();
	}
	?>	
     
                </div>
            </div>
            <div class='row spacer30'></div>

        <div id="write_review">
            <div class='row'>
                <div class='span12 center'>
                    <div id='write_review_button'>
                    <i class='icon-comment-alt icon-4x'></i>
                    <span class='write-comment'>Write Review</div></span>
                </div>
            </div>
            <div class='row spacer60'></div>
            </div>  <!-- ./container -->
            <center>
			<div id='review_box'>
				<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?cname=".$cname;?>' method='POST'>
				<textarea id='review' name='review_submit' cols='40'rows='10' placeholder='review'></textarea><br>
				<input type='hidden' name='cname' value='<?php echo $cname; ?>'/>
				<input id='submit' type='submit' name='submit' value='review'/>
				</form>
			</div>
			</center>
		</div>
		</div>


	

<!-- <div id='in_recommend_list'> -->
<!-- 
	 // <script src='/LiveConcert/assets/new/js/jquery.js'></script> 
	 // <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	// <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    // <script src='/LiveConcert/assets/new/js/bootstrap.min.js'></script>  -->
    <!-- // <script src='/LiveConcert/assets/new/js/mobile.menu.js'></script>  -->
     <!-- // <script src='/LiveConcert/assets/new/js/jquery.flexslider-min.js'></script>  -->
    <!-- // <script src='/LiveConcert/assets/new/js/jquery.mousewheel.js'></script>  -->
    <!-- // <script src='/LiveConcert/assets/new/js/jquery.prettyPhoto.js'></script>  -->
    <!-- // <script src='/LiveConcert/assets/new/js/jquery.mCustomScrollbar.min.js'></script>  -->
    <!-- // <script src='/LiveConcert/assets/new/js/jquery.masonry.min.js'></script> -->
    <!-- // <script src='/LiveConcert/assets/new/js/functions.js'></script> -->
	
<script type="text/javascript">


	// alert("123");
	$('#review').hide();
	$('#write_review').hide();
	$('.comment').hide();
	$("#rating").hide();
	$('#review_box').hide();
	var cname = '<?php echo $cname; ?>';
		var score_string = '<?php echo $userscore;?>';
	var userscore = parseInt(score_string);
	
	$('#write_review_button').click(function(){
		$('#review_box').toggle();
	});
	var is_Process = <?php echo (int)$isInProcess; ?>;
	if(is_Process){

		$('#concert_button').hide();
		$('#rating').hide()
		$('#decision_button').hide();
		$('#ticket').hide();
		if(userscore < 10){
			$('#whole_concert_page').hide();
		}
	}else{
		// alert( <?php echo (int)$review; ?>);
		
		if(<?php echo (int)$isPastConcert; ?>){
			$('#ticket').hide();

			$('#decision_button').hide();
			if(<?php echo (int)$attended; ?> || <?php echo (int)$review; ?>){
				$("#rating").show();
				$('#review').show();
				$('.comment').show();
				$('#write_review').show();
				$('#review_box').fadeIn();
				// $('#decision_button').show();
			}
			
		}else{
			$('#review').hide();
			$('#write_review').hide();
		}
	}





</script>
	
</body>
</html>
<!DOCTYPE html>
<html>
<head>
<?php 
include "../includes/regular_page_head.php";
include "../includes/new_head.html";
	include "../functions/input_text_function.php";

include "../functions/login_inputcheck.php";?>
	<title>Edit Concert Info</title>
</head>
<body>
<?php 
	$cname = "";
	$username = $_SESSION['username'];
	$score = $_SESSION['score'];
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

$location="";$time="";$price="";$postby="";$posttime="";$description="";
$originband = array(); $ticketlink='';
if(isset($_GET['cname'])){
	$cname = $_GET['cname'];
	if(IsInConcertProcess($cname)){
		// echo "cannot edit";
		header("Location:/LiveConcert/concert/concert_page.php?cname=".$cname);
	}else{
//get info
		if($basicInfo= $mysqli->query("call concert_basic_info('$cname')") or die($mysqli->error)){
			if($row = $basicInfo->fetch_object()){
				$time = $row->cdatetime;
				$price = $row->price;
				$description = $row->cdescription;
				$postby = $row->cpostby;
				$posttime = $row->cposttime;
				$ticketlink = $row->ticketlink;
				$location = $row->locname;
				$availability = $row->availability;
				$basicInfo->close();
				$mysqli->next_result();
				// echo "<p>".$description."</p>";

			}else{
				$basicInfo->close();
				$mysqli->next_result();
			}
			
			
		}
		if($originB= $mysqli->query("call get_band_by_cname('$cname')") or die($mysqli->error)){
			if($originB->num_rows > 0){
				while($row=$originB->fetch_object()){
					$originband[] = $row->baname;
				}
				
			}
			$originB->close();
			$mysqli->next_result();
			
		}
	}
}
	
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['cname'])){

	if(isset($_POST['cname']) && isset($_POST['new_cname'])){
		$prev_cname = $_POST['cname'];
		$new_cname = $_POST['new_cname'];
		$capacicy = $_POST['availability'];
			//update cname band
		$datetime = $_POST['datetime'];
		$locname = $_POST['locname'];
		$price = $_POST['price'];
		$description = $_POST['description'];
		$bandarray = array();
		// echo "123";
		$bandarray = $_POST['bandmember'];
		$ticketlink =null_allowed_input( $_POST['ticketlink']);
		if($score >=10){
		//delete band update concert incert playband
			if($deleteBand = $mysqli->query("call delete_playband_by_cname('$prev_cname')") or die($mysqli->error)){
				// echo "sucuess";
				$mysqli->next_result();
			}
			if($update_concert = $mysqli->query("call update_concert_info('$prev_cname','$new_cname','$datetime','$locname','$price','$description','$username','$ticketlink')") or die($mysqli->error)){
				// echo "update concert";
				$mysqli->next_result();
			}
			foreach ($bandarray as $key) {
					# code...
				if($insertPB = $mysqli->query("call create_play_band('$new_cname','$key')") or die($mysqli->error)){
					// echo "PB success";
					$mysqli->next_result();
					header("Location:/LiveConcert/concert/concert_page.php?cname=".$cname);
				}
			}
		}else{
			if($insertConcert = $mysqli->query("call create_concert_process('$new_cname','$username','$datetime','$locname','$price','$capacicy','$description')") or die($mysqli->error)){
				// echo 'success';
				$mysqli->next_result();
				foreach ($bandarray as $key) {
					if($insertPBprocess = $mysqli->query("call create_play_band_process('$new_cname','$key')") or die($mysqli->error)){
						// echo "PBProcess success";
						$mysqli->next_result();
						header("Location:/LiveConcert/concert/concert_page.php?cname=".$cname);
					}
					# code...
				}
			}
		//update concert into ConcertProcess
		}

	//update concert into Concert
	}
}
	
?>

<section class='content'>
	<div class="container white-background">
	<h2>Edit a Concert</h2>
<!-- <div id='user_info' class='row'> -->
		<div id='post_concert' class='row'>
				<center>	<form id="" method="POST" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?cname=".$cname;?>'>
					<table>
					<tr><th></th></tr>
					<tr><td>Concert Name:</td><td><input type='text' name='new_cname' value='<?php echo $cname; ?>'><span class="error">* <?php echo $nameERR; ?></td></tr>
					<tr><td>Date:</td><td><input type="text" id="datetime24" data-format="DD-MM-YYYY HH:mm" data-template="DD / MM / YYYY     HH : mm" name="datetime" value="<?php echo $time; ?>"></td></tr>
					<tr><td>Location</td><td>
					<div id='Location'>
						<select  name='locname'>
						<option value='' >Choose A Venues</option>
						<?php 
						$playBand = array();

						if($allBand = $mysqli->prepare("select locname from Venues")){
							$allBand->execute();
							$allBand->bind_result($locname);
							
							while($allBand->fetch()){
								if($locname ==$location ){
									echo "<option value='$locname' selected>$locname</option>";
								}else{
									echo "<option value='$locname'>$locname</option>";
								}
								
							}
						}	
						?>
						</select>
						<br>
						<br>
					</div>
					</td></tr>
					<tr><td>Price:</td><td><input type='text' name='price' value='<?php echo $price; ?>'><span class="error">* <?php echo $numberERR; ?></td></tr>
					<tr><td>Short Description:</td><td><textarea name='description' rows="8" cols="50" value='<?php echo $description; ?>'><?php echo $description; ?></textarea></td></tr>
					<tr><td>Play Band
					<img id='addNewOne' src='/LiveConcert/assets/images/add_black_button.png' width='20' height='20'></td><td>

					<!-- <div id='band_div'> -->
					
						<div id='bandmember_div'>
						<?php 
						foreach ($originband as $key) {
							# code...echo "<span id='band_member'>";
							echo "<span id='band_member'>";
							echo "<p><input type='text' name='bandmember[]' value='$key'>&nbsp;&nbsp;&nbsp;</p></span>";
						}
						?>
						</select>
						<br>
						<br>
					</span>
					</div>



					</td></tr>
					<tr><td>TicketLink</td><td><input type='text' name='ticketlink' size='80' value='<?php echo $ticketlink; ?>'></td></tr>
					<input id='submit' type='hidden' name='cname' value='<?php echo $cname ?>'>
					
					<input id='submit' type='hidden' name='availability' value='<?php echo $availability ?>'>
					<tr><td></td><td><input style='float:right' id='submit' type='submit' name='submit' value='Edit'></td></tr>
					</table>
					</form></center>


		</div>
	</div>
</section>
	<script src="../assets/js/jquery/jquery.js"></script> 
	<script src="../assets/js/moment.js"></script> 
	<script src="../assets/js/combodate.js"></script> 
<script type="text/javascript">
	$("#addNewOne").each(function(){
		$(this).click(function(){
			$('#bandmember_div').append($("#band_member").clone());
			// $(this).hide();
		});
	});
</body>
</html>
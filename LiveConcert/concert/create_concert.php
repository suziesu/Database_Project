<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
	include "../functions/input_text_function.php";
include $path."/LiveConcert/menu/home_menu.php";
include "../functions/login_inputcheck.php";
?>
	<script src="../assets/js/jquery/jquery.js"></script> 
	<script src="../assets/js/moment.js"></script> 
	<script src="../assets/js/combodate.js"></script> 
	<title>Post a New Concert</title>
</head>
<body>
<?php 
$username = $_SESSION['username'];
$cname="";
$locname = "";
$score = $_SESSION['score'];
$description="";
$price=0;
$ticketlink="";
function find_concert_by_cname($cname){
	global $nameERR,$mysqli;
	
	if($getconcert = $mysqli->query("call concert_basic_info('$cname')") or die($mysqli->error)){
		if($getconcert->num_rows > 0){
			$getconcert->close();
			$mysqli->next_result();
			$nameERR = "Concert name exists, please change a new one";
			return true;
		}else{
			$getconcert->close();
			$mysqli->next_result();
			return false;
		}
	}
}
function find_concertprocess_by_cname($cname){
	global $nameERR,$mysqli;
	if($getconcert = $mysqli->query("call is_in_concert_process('$cname')") or die($mysqli->error)){
		if($getconcert->num_rows > 0){
			$getconcert->close();
			$mysqli->next_result();
			$nameERR = "Concert name exists, please change a new one";
			return true;
		}else{
			$getconcert->close();
			$mysqli->next_result();
			return false;
		}
	}
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['cname'])){

	$cname = name_entered($_POST['cname']);
	$price = number_check($_POST['price']);
	$datetime = date_time_check($_POST['datetime']);
	$locname = null_allowed_input($_POST['locname']);
	$description = null_allowed_input($_POST['description']);
	$bandarray = array();
		if(isset($_POST['band'])){
		$bandarray = $_POST['band'];

	}
	$ticketlink = null_allowed_input($_POST['ticketlink']);
	$capacicy=0;
	if($cname && $price >=0  && !find_concert_by_cname($cname) && !find_concertprocess_by_cname($cname)){
		if($checkcapacity = $mysqli->query("call get_location_info('$locname')") or die($mysqli->error)){
			if($row = $checkcapacity->fetch_object()){
				$capacicy = $row->capacity;
			}
			$checkcapacity->close();
			$mysqli->next_result();
		}
		if($score >=10){
		//insert concert into Concert
			if($insertConcert = $mysqli->query("call create_concert('$cname','$datetime','$locname','$price','$capacicy','$description','$username','$ticketlink')") or die($mysqli->error)){
				echo "success";
				$mysqli->next_result();
				foreach ($bandarray as $key) {
					# code...
					if($insertPB = $mysqli->query("call create_play_band('$cname','$key')") or die($mysqli->error)){
						echo "PB success";
						$mysqli->next_result();
					}
				}
			}
				
		}else{
			if($insertConcert = $mysqli->query("call create_concert_process('$cname','$username','$datetime','$locname','$price','$capacicy','$description')") or die($mysqli->error)){
				echo 'success';
				$mysqli->next_result();
				foreach ($bandarray as $key) {
					if($insertPBprocess = $mysqli->query("call create_play_band_process('$cname','$key')") or die($mysqli->error)){
						echo "PBProcess success";
						$mysqli->next_result();
					}
					# code...
				}
			}
				
			//insert concert into ConcertProcess
		}
	}
}

	

	
	//
	
?>
<form id="" method="POST" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
<table>
<tr><th></th></tr>
<tr><td>Concert Name:</td><td><input type='text' name='cname' value='<?php echo $cname; ?>'><span class="error">* <?php echo $nameERR; ?></td></tr>
<tr><td>Date:</td><td><input type="text" id="datetime24" data-format="DD-MM-YYYY HH:mm" data-template="DD / MM / YYYY     HH : mm" name="datetime" value="21-12-2012 20:30"></td></tr>
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
			echo "<option value='$locname' >$locname</option>";
		}
	}	
	?>
	</select>
	<br>
	<br>
</div>
</td></tr>
<tr><td>Price:</td><td><input type='text' name='price' value='<?php echo $price; ?>'><span class="error">* <?php echo $numberERR; ?></td></tr>
<tr><td>Short Description:</td><td><textarea name='description' rows="8" cols="50" value='<?php echo $description; ?>'></textarea></td></tr>
<tr><td>Play Band</td><td>
<div id='band_div'>
<span id='play_band'>
	<select id='span_option_0' name='band[]'>
	<option value='' >Choose A Band</option>
	<?php 
	$playBand = array();

	if($allBand = $mysqli->prepare("select baname from Band")){
		$allBand->execute();
		$allBand->bind_result($baname);
		
		while($allBand->fetch()){
			echo "<option value='$baname' >$baname</option>";
		}
	}	
	?>
	</select>
	<br>
	<br>
</span>
</div>
	<img id='addNewOne' src='/LiveConcert/assets/images/add_black_button.png' width='20' height='20'>



</td></tr>
<tr><td>TicketLink</td><td><input type='text' name='ticketlink' size='80' value='<?php echo $ticketlink; ?>'></td></tr>
<tr><td></td><td><input type='submit' name='submit' value='Create'></td></tr>
</table>
</form>
<script type="text/javascript">
	$('datetime').combodate({
	    minYear: 2010,
	    maxYear: 2018,
	    minuteStep: 10
	});  
	$(function(){
	    $('#datetime24').combodate();  
	});

	$("img[id='addNewOne']").each(function(){
		$(this).click(function(){
			$('#band_div').append($("#play_band").clone());
			// $(this).hide();
		});
		// var pre = "#play_band"+count;
	});
	// $('#addNewOne_'+count)

</script>

</body>
</html>
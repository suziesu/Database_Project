<!DOCTYPE html>
<html lang="en">
	<head>
		
	<?php 
	include "../includes/regular_page_head.php";
	include "../includes/concert_list_head.html";
	
?>
	<title>All Concert List</title>
<style type="text/css">
	#tfheader{
		background-color:#2e343e;
	}
	#tfnewsearch{
		float:center;
		padding:20px;
	}
	.tftextinput{
		margin: 0;
		padding: 5px 10px;
		font-family: Arial, Helvetica, sans-serif;
		font-size:14px;
		border:1px solid #0076a3; border-right:0px;
		border-top-left-radius: 5px 5px;
		border-bottom-left-radius: 5px 5px;
	}
	.tfbutton {
		margin: 0;
		padding: 5px 15px;
		font-family: Arial, Helvetica, sans-serif;
		font-size:14px;
		outline: none;
		cursor: pointer;
		text-align: center;
		text-decoration: none;
		color: #ffffff;
		border: solid 1px #0076a3; border-right:0px;
		background: #0095cd;
		background: -webkit-gradient(linear, left top, left bottom, from(#00adee), to(#0078a5));
		background: -moz-linear-gradient(top,  #00adee,  #0078a5);
		border-top-right-radius: 5px 5px;
		border-bottom-right-radius: 5px 5px;
	}
	.tfbutton:hover {
		text-decoration: none;
		background: #007ead;
		background: -webkit-gradient(linear, left top, left bottom, from(#0095cc), to(#00678e));
		background: -moz-linear-gradient(top,  #0095cc,  #00678e);
	}
	/* Fixes submit button height problem in Firefox */
	.tfbutton::-moz-focus-inner {
	  border: 0;
	}
	.tfclear{
		clear:both;
	}
</style>
</head>
<body id="page2">
		<div class="extra">
<body>
<!-- this will show all the future concert

also has genre type when people click is will show that type of concert

The system recommendation concert will be at explore page -->


<?php
$username = $_SESSION['username'];
$score = $_SESSION['score'];
$concert_array = array();

//get all typelist

// $cname="";
// $location="";
// $time="";
// $baname="";
//search future concert based on name
// <input type = "text" class="tftextinput" name="name" size="21" maxlength="80" value='' placeholder='input name' >
// 				<input type="submit" name='button' value="inputname" class="tfbutton">

// 			<input type = "text" class="tftextinput" name="location" size="21" maxlength="80" value='' placeholder='input location'>
// 				<input type="submit" name='button' value="Location" class="tfbutton">

// 			<input type = "text" class="tftextinput" name="time" size="21" maxlength="80" value=''placeholder='input time'>
// 				<input type="submit" name='button' value="Time" class="tfbutton">

// 			<input type = "text" class="tftextinput" name="bandname" size="21" maxlengt h="80" value=''placeholder='input bandname'>
// 				<input type="submit" name='button' value="Band" class="tfbutton">


if(isset($_POST['button'])){

	if($_POST['button'] == 'Band'){
		if(isset($_POST['bandname'])){
			$bandname = $_POST['bandname'];
			if($search_band = $mysqli->query("call search_concert_in_PC_by_band('$bandname')") or die($mysqli->error)){
				while($row = $search_band->fetch_object()){
					echo "123";
					$concert = array();
					$concert['cname'] = $row->cname;
					$concert['cdatetime'] = $row->cdatetime;
					$concert['locname'] = $row->locname;
					$concert['price'] = $row->price;
					$concert['cdescription'] = $row->cdescription;
					if($row->cstatus){
						$concert['cstatus'] = $row->cstatus;
					}else{
						$concert['cstatus'] = "";
					}
					$concert_array[] = $concert;
				}
				$search_band->close();
				$mysqli->next_result();
			}
			if($score >- 10){
				if($search_band_PC = $mysqli->query("call search_concert_only_in_PC_by_band('$bandname')") or die($mysqli->error)){
					while($row = $search_band_PC->fetch_object()){
						$concert = array();
						$concert['cname'] = $row->cname;
						$concert['cdatetime'] = $row->cdatetime;
						$concert['locname'] = $row->locname;
						$concert['price'] = $row->price;
						$concert['cdescription'] = $row->cdescription;
						$concert['cstatus'] = $row->cstatus;
						$concert_array[] = $concert;
					}
					$search_band_PC->close();
					$mysqli->next_result();
				}
			}
		}else{
			echo "no bandname input";
		}

	}
	if($_POST['button'] == 'Time'){
		if(isset($_POST['time'])){
			$time = $_POST['time'];
			if($search_time = $mysqli->query("call search_concert_in_PC_by_time('$time')") or die($mysqli->error)){
				while($row = $search_time->fetch_object()){
					$concert = array();
					$concert['cname'] = $row->cname;
					$concert['cdatetime'] = $row->cdatetime;
					$concert['locname'] = $row->locname;
					$concert['price'] = $row->price;
					$concert['cdescription'] = $row->cdescription;
					if($row->cstatus){
						$concert['cstatus'] = $row->cstatus;
					}else{
						$concert['cstatus'] = "";
					}
					$concert_array[] = $concert;

				}
				$search_time->close();
				$mysqli->next_result();
			}
			if($score >= 10){
				if($search_time_PC = $mysqli->query("call search_concert_only_in_PC_by_time('$time')") or die($mysqli->error)){
					while($row = $search_time_PC->fetch_object()){
						$concert = array();
						$concert['cname'] = $row->cname;
						$concert['cdatetime'] = $row->cdatetime;
						$concert['locname'] = $row->locname;
						$concert['price'] = $row->price;
						$concert['cdescription'] = $row->cdescription;
						$concert['cstatus'] = $row->cstatus;
						$concert_array[] = $concert;
					}
					$search_time_PC->close();
					$mysqli->next_result();
				}
			}
		}else{
			echo "no time input";
		}

	}
	if($_POST['button'] == 'Location'){
		if(isset($_POST['location'])){
			$locname = $_POST['location'];
			if($search_loc = $mysqli->query("call search_concert_in_PC_by_location('$locname')") or die($mysqli->error)){
				while($row = $search_loc->fetch_object()){
					$concert = array();
					$concert['cname'] = $row->cname;
					$concert['cdatetime'] = $row->cdatetime;
					$concert['locname'] = $row->locname;
					$concert['price'] = $row->price;
					$concert['cdescription'] = $row->cdescription;
					if($row->cstatus){
						$concert['cstatus'] = $row->cstatus;
					}else{
						$concert['cstatus'] = "";
					}
					$concert_array[] = $concert;
				}
				$search_loc->close();
				$mysqli->next_result();

			}
			if($score >=10){
				if($search_loc_PC = $mysqli->query("call search_concert_only_in_PC_by_location('$locname')") or die($mysqli->error)){
					while($row = $search_loc_PC->fetch_object()){
						$concert = array();
						$concert['cname'] = $row->cname;
						$concert['cdatetime'] = $row->cdatetime;
						$concert['locname'] = $row->locname;
						$concert['price'] = $row->price;
						$concert['cdescription'] = $row->cdescription;
						$concert['cstatus'] = $row->cstatus;
						$concert_array[] = $concert;
					}
					$search_loc_PC->close();
					$mysqli->next_result();
				}
			}
		}else{
			echo "no location input";
		}
	}
	if($_POST['button'] == 'inputname'){
		if(isset($_POST['name'])){
			$name = $_POST['name'];
			echo $name;
			if($search_name = $mysqli->query("call search_concert_in_PC_by_name('$name')") or die($mysqli->error)){
				echo $search_name->num_rows;
				while($row = $search_name->fetch_object()){
					echo "333";
					$concert = array();
					$concert['cname'] = $row->cname;
					echo $row->cname;
					$concert['cdatetime'] = $row->cdatetime;
					$concert['locname'] = $row->locname;
					$concert['price'] = $row->price;
					$concert['cdescription'] = $row->cdescription;
					if($row->cstatus){
						$concert['cstatus'] = $row->cstatus;
					}else{
						$concert['cstatus'] = "";
					}
					$concert_array[] = $concert;
				}
				$search_name->close();
				$mysqli->next_result();
			}else{
				echo "error";
			}

			if($score >=10){
				echo "123";
				if($search_name_PC = $mysqli->query("call search_concert_only_in_PC_by_name('$name')") or die($mysqli->error)){
					echo $search_name_PC->num_rows;
					while ($row= $search_name_PC->fetch_object()) {
						$concert = array();
						$concert['cname'] = $row->cname;
						$concert['cdatetime'] = $row->cdatetime;
						$concert['locname'] = $row->locname;
						$concert['price'] = $row->price;
						$concert['cdescription'] = $row->cdescription;
						$concert['cstatus'] = $row->cstatus;
						$concert_array[] = $concert;# code...
					}
					$search_name_PC->close();
					$mysqli->next_result();
				}

			}
			
		}else{
			echo "no name input";
		}
	}






	// $coname = name_entered($_POST['']);
 //    	if($selectConcert = $mysqli->query("call select_fconcert_name('$coname')")){
	// 	while($row = $selectConcert->fetch_object()){
	// 		$concert = array();
	// 		$concert['cname'] = $row->cname;
	// 		$concert['cdatetime'] = $row->cdatetime;
	// 		$concert['locname'] = $row->locname;
	// 		$concert['price'] = $row->price;
	// 		$concert['cdescription'] = $row->cdescription;
	// 		if($row->cstatus){
	// 			$concert['cstatus'] = $row->cstatus;
	// 		}else{
	// 			$concert['cstatus'] = "";
	// 		}
	// 		$concert_array[] = $concert;

	// 	}
	// 	$selectConcert->close();
	// 	$mysqli->next_result();

	// }

	// if($score >= 10){
	// 	if($ConcertProcess = $mysqli->query("call get_all_future_onlyin_CP()") or die($mysqli->error)){
	// 		while($row = $allConcertProcess->fetch_object()){
	// 			$concert = array();
	// 			$concert['cname'] = $row->cname;
	// 			$concert['cdatetime'] = $row->cdatetime;
	// 			$concert['locname'] = $row->locname;
	// 			$concert['price'] = $row->price;
	// 			$concert['cdescription'] = $row->cdescription;
	// 			$concert['cstatus'] = $row->cstatus;
	// 			$concert_array[] = $concert;
	// 		}
	// 		$allConcertProcess->close();
	// 		$mysqli->next_result();
	// 	}
	// }
}
//get subtype concert>
else if(isset($_GET['type']) && isset($_GET['subtype'])){
	$subtype = $_GET['subtype'];
	if($subtypeConcert = $mysqli->query("call get_subtype_future_concert('$subtype')") or die($mysqli->error)){
		while($row = $subtypeConcert->fetch_object()){
			$concert = array();
			$concert['cname'] = $row->cname;
			$concert['cdatetime'] = $row->cdatetime;
			$concert['locname'] = $row->locname;
			$concert['price'] = $row->price;
			$concert['cdescription'] = $row->cdescription;
			if($row->cstatus){
				$concert['cstatus'] = $row->cstatus;
			}else{
				$concert['cstatus'] = "";
			}
			$concert_array[] = $concert;
		}
		$subtypeConcert->close();
		$mysqli->next_result();

	}
	if($score >= 10){
		if($subtypeConcertProcess = $mysqli->query("call get_subtype_future_onlyin_CP('$subtype')") or die($mysqli->error)){
			while($row = $subtypeConcertProcess->fetch_object()){
				$concert = array();
				$concert['cname'] = $row->cname;
				$concert['cdatetime'] = $row->cdatetime;
				$concert['locname'] = $row->locname;
				$concert['price'] = $row->price;
				$concert['cdescription'] = $row->cdescription;
				$concert['cstatus'] = $row->cstatus;
				$concert_array[] = $concert;
			}
			$subtypeConcertProcess->close();
			$mysqli->next_result();
		}
		
	}
//get type concert
}else{

 if(isset($_GET['type'])){
	$type = $_GET['type'];
	if($typeConcert = $mysqli->query("call get_type_future_concert('$type')")){
		while($row = $typeConcert->fetch_object()){
			$concert = array();
			$concert['cname'] = $row->cname;
			$concert['cdatetime'] = $row->cdatetime;
			$concert['locname'] = $row->locname;
			$concert['price'] = $row->price;
			$concert['cdescription'] = $row->cdescription;
			if($row->cstatus){
				$concert['cstatus'] = $row->cstatus;
			}else{
				$concert['cstatus'] = "";
			}
			$concert_array[] = $concert;
		}
		$typeConcert->close();
		$mysqli->next_result();
	}
	if($score >= 10){
		if($typeConcertProcess = $mysqli->query("call get_type_future_onlyin_CP('$type')") or die($mysqli->error)){
			while($row = $typeConcertProcess->fetch_object()){
				$concert = array();
				$concert['cname'] = $row->cname;
				$concert['cdatetime'] = $row->cdatetime;
				$concert['locname'] = $row->locname;
				$concert['price'] = $row->price;
				$concert['cdescription'] = $row->cdescription;
				$concert['cstatus'] = $row->cstatus;
				$concert['cstatus'] = $row->cstatus;
				$concert_array[] = $concert;
			}
			$typeConcertProcess->close();
			$mysqli->next_result();
		}
	}
//get all concert
}else{
	if($allConcert = $mysqli->query("call get_all_future_concert()")){
		while($row = $allConcert->fetch_object()){
			$concert = array();
			$concert['cname'] = $row->cname;
			$concert['cdatetime'] = $row->cdatetime;
			$concert['locname'] = $row->locname;
			$concert['price'] = $row->price;
			$concert['cdescription'] = $row->cdescription;
			if($row->cstatus){
				$concert['cstatus'] = $row->cstatus;
			}else{
				$concert['cstatus'] = "";
			}
			$concert_array[] = $concert;

		}
		$allConcert->close();
		$mysqli->next_result();
//get
	}
	if($score >= 10){
		if($allConcertProcess = $mysqli->query("call get_all_future_onlyin_CP()") or die($mysqli->error)){
			while($row = $allConcertProcess->fetch_object()){
				$concert = array();
				$concert['cname'] = $row->cname;
				$concert['cdatetime'] = $row->cdatetime;
				$concert['locname'] = $row->locname;
				$concert['price'] = $row->price;
				$concert['cdescription'] = $row->cdescription;
				$concert['cstatus'] = $row->cstatus;
				$concert_array[] = $concert;
			}
			$allConcertProcess->close();
			$mysqli->next_result();
		}
	}
}
}
?>
<!-- search function -->
	<body id="page2">
		<div class="extra">
		<section id="tfheader">
			<form id="tfnewsearch" method="POST" action="concert_list.php">
				<tr><td>Search Future Concert:</td><td>
			<input type = "text" class="tftextinput" name="name" size="21" maxlength="80" value='' placeholder='input name' >
				<input type="submit" name='button' value="inputname" class="tfbutton">
			<input type = "text" class="tftextinput" name="location" size="21" maxlength="80" value='' placeholder='input location'>
				<input type="submit" name='button' value="Location" class="tfbutton">
			<input type = "text" class="tftextinput" name="time" size="21" maxlength="80" value=''placeholder='input time'>
				<input type="submit" name='button' value="Time" class="tfbutton">
			<input type = "text" class="tftextinput" name="bandname" size="21" maxlengt h="80" value=''placeholder='input bandname'>
				<input type="submit" name='button' value="Band" class="tfbutton">
		</form>
		</section>

<!--==============================content================================-->
			<section id="content">
			<div id="sidebar">
				<?php
					if($alltype = $mysqli->prepare("select typename from Type")){
						$alltype->execute();
						$alltype->bind_result($typename);
						$getAllType = array();
						while($alltype->fetch()){
							array_push($getAllType,$typename);
						}
						$alltype->close();
						foreach ($getAllType as $key) {
							echo "<ul><a href='/LiveConcert/concert/concert_list.php?type=$key'>$key</a></ul>";
							if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')")){
								// echo "<ul>";
								while($row = $allsubtype->fetch_object()){
									$subtypename = $row->subtypename;
									echo "<li><a href='/LiveConcert/concert/concert_list.php?type=$key&subtype=$subtypename'>$subtypename</li>";
								}
								$allsubtype->close();
								$mysqli->next_result();
							}
							// echo "</ul>";
						}
					}

				?>
			</div>
				<div class="main">

					<div class="content-padding-2">
						<div class="zerogrid">
							<div class="row">
								<div class="col-full">
									<div class="padding-grid-1">
										<h3 class="letter">Our <strong>Concert &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
										<a href='/LiveConcert/concert/past_concert_list.php'>Past Concert</a></span></strong></h3>
									</div>

									<?php 
									foreach ($concert_array as $key ) {
										$cname = $key['cname'];
										$cstatus=$key['cstatus'];
										echo "<div class='wrapper p3'>
												<article class='col-1-3'>
													<div class='padding-grid-2'>
														<div class='wrapper'>
															<figure class='style-img-2 fleft'><a href='/LiveConcert/concert/concert_page.php?cname=$cname'>
															<img height='250px' width='250px' src='/LiveConcert/assets/images/$cname.jpg' ></a></figure>
														</div>
													</div>
												</article>
												<article class='col-2-3'>
													<div class='padding-grid-2'>
														<a href='/LiveConcert/concert/concert_page.php?cname=$cname'>
														<h4 class='margin-none indent-top1'><strong>".$key['cdatetime']."&nbsp;&nbsp;&nbsp;</strong>$cname&nbsp;&nbsp;&nbsp;$cstatus</h4></a>
														<p class='prev-indent-bot'>".$key['cdescription']."</p>
														<div class='wrapper'>
															<ul class='list-1 fleft'>";
															echo "<ul><h3>Band</h3></ul>";
										if($originB= $mysqli->query("call get_band_by_cname('$cname')") or die($mysqli->error)){
											if($originB->num_rows > 0){
												while($row =$originB->fetch_object()){
													$originband = $row->baname;
													echo "<li><a href='/LiveConcert/artist_band/band_page.php?baname=$originband'>$originband</a></li>";
												}
												
											}
											$originB->close();
											$mysqli->next_result();
										}
										if($cstatus !="" && $score >= 10){
									// valid user will see concertinfo in ConcertProcess
											if($bandupdate= $mysqli->query("call get_band_by_process_cname('$cname')") or die($mysqli->error)){
												if($bandupdate->num_rows > 0){
													// $row=$bandupdate->fetch_object() or die($mysqli->error);
													while($row =$bandupdate->fetch_object()){
														$bandUP = $row->baname;
														echo "<li><a href='/LiveConcert/artist_band/band_page?baname=$bandUP'>$bandUP</a></li>";
													}
												}
												$bandupdate->close();
												$mysqli->next_result();
											}
										}
										echo "</ul></div>
											</div>
										</article>
									</div>";
								}
								$mysqli->close();

								?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="block"></div>
			</section>
		</div>

		<script type="text/javascript"> Cufon.now(); 

		</script>
	</body>
</html>



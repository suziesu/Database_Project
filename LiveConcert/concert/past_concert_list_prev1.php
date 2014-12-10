<!DOCTYPE html>
<html lang="en">
	<head>
		
	<?php 
	include "../includes/regular_page_head.php";
	include "../includes/concert_list_head.html";
?>
	<title>Past Concert List</title>
</head>
<body id="page2">
		<div class="extra">
<body>
<!-- this will show all the past concert

also has genre type when people click is will show that type of concert

The system recommendation concert will be at explore page -->

<?php
$username = $_SESSION['username'];
$score = $_SESSION['score'];
//get all typelist

$concert_array = array();

//get subtype concert>
if(isset($_GET['type']) && isset($_GET['subtype'])){
	$subtype = $_GET['subtype'];
	if($subtypeConcert = $mysqli->query("call get_subtype_past_concert('$subtype')") or die($mysqli->error)){
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
		if($subtypeConcertProcess = $mysqli->query("call get_subtype_past_onlyin_CP('$subtype')") or die($mysqli->error)){
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
}else if(isset($_GET['type'])){
	$type = $_GET['type'];
	if($typeConcert = $mysqli->query("call get_type_past_concert('$type')")){
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
		if($typeConcertProcess = $mysqli->query("call get_type_past_onlyin_CP('$type')") or die($mysqli->error)){
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
	if($allConcert = $mysqli->query("call get_all_past_concert()")){
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
		if($allConcertProcess = $mysqli->query("call get_all_past_onlyin_CP()") or die($mysqli->error)){
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
?>
<body id="page2">
		<div class="extra">
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
							echo "<ul><a href='/LiveConcert/concert/past_concert_list.php?type=$key'>$key</a></ul>";
							if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')")){
								// echo "<ul>";
								while($row = $allsubtype->fetch_object()){
									$subtypename = $row->subtypename;
									echo "<li><a href='/LiveConcert/concert/past_concert_list.php?type=$key&subtype=$subtypename'>$subtypename</li>";
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
										<h3 class="letter">Past <strong>Concert &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
										<a href='/LiveConcert/concert/concert_list.php'>Future Concert</a></span></strong></h3>
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
															<img height='200px' width='200px' src='/LiveConcert/assets/images/$cname.jpg' ></a></figure>
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
								?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="block"></div>
			</section>
		</div>

		<script type="text/javascript"> Cufon.now(); </script>
	</body>
</html>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
<?php include "../includes/concert_list_head.html"; 
	include "../includes/regular_page_head.php";?>
	<title>Music Genre</title>
</head>

<body id="page2">
		<div class="extra">
<!--==============================content================================-->
			<section id="content">
				<div class="mylist_main">

					<div class="content-padding-2">
						<div class="zerogrid">
							<div class="row">
								<div class="col-full">
								<div class="padding-grid-1">
										<h3 class="letter"><a href='/LiveConcert/concertlist/create_new_list.php'>Create A New One</a></strong></h3>
										 </strong></h3>
									</div>
								
									<div class="padding-grid-1">
										<h3 class="letter"><center>Music&nbsp;&nbsp;&nbsp;<strong> Genre</strong></center></h3>
									</div>
<?php 
if(isset($_GET['subtype'])){
		$subtype = $_GET['subtype'];
		
	if($sub = $mysqli->query("call get_subtype_describ('$subtype')") or die($mysqli->error)){
		echo "<h2>$subtype</h2>";
		if($row = $sub->fetch_object()){
			echo "<div>".$row->subtypedescrip."</div>";
		}else{
			$sub->close();
			$mysqli->next_result();
			if($tp = $mysqli->query("call get_type_describ('$subtype')") or die($mysqli->error)){
				if ($row = $tp->fetch_object()){
					echo "<div>".$row->typedecrip."</div>";
				}
				$tp->close();
			}
		// $mysqli->next_result();
		}
			
			// $mysqli->next_result();
	}

$mysqli->close();
}
?><center>
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
										
												</ul></div>
											</div>
										</article>
									</div>
								$mysqli->close();

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="block"></div>
			</section>
		</div>

		<script type="text/javascript"> Cufon.now(); </script>

<a href='/LiveConcert/genre/genre_list.php'><button id="goback">Go Back To Type List</button></a></center>
</body>
</html>
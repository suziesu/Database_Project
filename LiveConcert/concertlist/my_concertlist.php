<!DOCTYPE html>
<html>
<head>
	<?php include "../includes/concert_list_head.html";
	include "../includes/regular_page_head.php";?>
	<title>All Recommendation List</title>
</head>
<?php 
if(isset($_SESSION['error'])){
	echo $_SESSION['error'];
}
unset($_SESSION['error']);
$concertname = "";
$username = $_SESSION['username'];
if(isset($_GET['cname'])){
	$concertname = $_GET['cname'];
}

if($_SERVER["REQUEST_METHOD"]=='POST'){
	if(isset($_POST['listname']) && $_POST['submit'] == 'Add'){
		$ml = $_POST['listname'];
		$concertname = $_POST['cname'];
		if($addToList = $mysqli->query("call add_to_recommendlist('$ml','$concertname')") or die($mysqli->error)){
			// $addToList->close();
			// $mysqli->next_result();
			echo "add successed";
			header("Location: concertlist_page.php?listname=".$ml);
		}
	}
	if(isset($POST['delete']) && $_POST['submit'] == 'Delete'){
		$deleteListName = $_POST['delete'];
		if($delete = $mysqli->query("call delete_userrecommendlist('$username', '$deleteListName')") or die($mysqli->error)){
			// $delete->close();
			$mysqli->next_result();
		} 
	}
}
$my_list_array = array();
if($result = $mysqli->query("call my_recommend_list('$username')") or die($mysqli->error)){
	if($result->num_rows > 0){
		while($row = $result->fetch_object()){
			$result_mylist = array();
			$result_mylist['listname'] = $row->listname;
			$result_mylist['ldescrip'] = $row->ldescription;
			$my_list_array[] = $result_mylist; 
			
			
		}
	}
	$result->close();
	$mysqli->next_result();
}

?>
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
										<h3 class="letter">My&nbsp;&nbsp;&nbsp;<strong>Recommendation List&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></h3>
									</div>

									<?php 
									foreach ($my_list_array as $key ) {
										$ml = $key['listname'];
										echo "<div class='wrapper p3'>
												<article class='col-1-3'>
													<div class='padding-grid-2'>
														<div class='wrapper'>
															<figure class='style-img-2 fleft'><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$ml'>
															<img height='250px' width='250px' src='/LiveConcert/assets/images/$ml.jpg' ></a></figure>
														</div>
													</div>
												</article>
												<article class='col-2-3'>
													<div class='padding-grid-2'>
														<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$ml'>
														<h3 class='margin-none indent-top1'>$ml&nbsp;&nbsp;&nbsp;</h3></a>"; 
														if(isset($_GET['cname'])){
															echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'>";
															echo "<input type='hidden' name='listname' value='$ml'><input type='hidden', name='cname' value='$concertname'>";
															echo "<input type='submit' id='submit' name='submit' value='Add'></form>";
														}else{
															echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'>
															<input type='hidden' name='delete' value='$ml'><input id='submit' type='submit' name='submit' value='Delete'></form>";
														}
													echo "
														<p class='prev-indent-bot'>".$key['ldescrip']."</p>
														<div class='wrapper'>
															<ul class='list-1 fleft'>";
															echo "<ul><h4 class='margin-none indent-top1'><strong>Concert</strong></h4></ul>";
										
															if($getConcert = $mysqli->query("call get_recommend_list_concert('$ml')") or die($mysqli->error)){
																if($getConcert->num_rows > 0){
																	while($row = $getConcert->fetch_object()){
																		$concert = $row->cname;
																		echo "<li><a href='/LiveConcert/concert/concert_page.php?cname=$concert'>$concert</a></li>";
																		//remove concert button
																	}
																}
																$getConcert->close();
																$mysqli->next_result();

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

		<script type="text/javascript"> Cufon.now(); </script>
	</body>
</html>
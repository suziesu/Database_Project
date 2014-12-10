<!DOCTYPE html>
<html>
<head>
<?php 
	include "includes/config.php";
	include "includes/new_head.html"; 
	include "includes/checklogin.php";
include "menu/home_menu.php";
	$username = $_SESSION['username'];
	$score = $_SESSION['score'];
	
?>
	<title>New Update</title>
</head>
<body>

<center><h1>New Update</h1></center>
<!-- <div aligh='right'><a href='user/edit_profile.php?username = <?php ;?>'><img src='assets/images/<?php echo $username; ?>.jpg'></a></div> -->
    <!-- Content -->
    <section id='content'>
        <!-- Container -->
        <div class='container gray-background-2'>
            <div class='blog'>
                <div class='row'>
                    <!-- blog post -->
                    
                        <!-- type of blog post -->

                	<?php 
						if($result = $mysqli->query("call new_concert_band_user_follow('$username')") or die($mysqli->error)){
							if($result->num_rows >0){
								$prev = "";
								while($row = $result->fetch_object()){
									$cname = $row->cname;
									$baname = $row->baname;
									$cdatetime = $row->cdatetime;
									$cdescription = $row->cdescription;
									$locname = $row->locname;
									if($cname != $prev){
										$cposttime = $row->cposttime;
										echo "<div class='span3 post'>
												<div class='image-post'>
							                            <div class='square'>
							                                <div class='img-container'>
							                                    <a href='/LiveConcert/concert/concert_page.php?cname=$cname'>
							                                    <img alt='lorem' src='assets/images/$cname.jpg'>
							                                    <div class='square-icon'></div></a>
							                                </div>
							                            </div><a href='/LiveConcert/concert/concert_page.php?cname=$cname'>
							                            <h3>New Concert POST</h3></a>
							                        </div>
							                        <p><i class='icon-calendar'></i > $cdatetime</p>";
							                    echo " <p>$cdescription</p>
                        						<p class='padding-5-30'><a href='/LiveConcert/artist_band/band_page.php?baname=$baname'><i class='icon-heart-empty'></i > $baname</a></p>
						                        <p class='padding-5-30'><a href='/LiveConcert/venues/venues.php?locname=$locname' rel='external'><i class=
						                        'icon-map-marker'></i > $locname</a></p>
						                        <p class='padding-5-30'><i class='icon-eye-open'></i >Post at $cposttime</p>
						                        <div class='row spacer30'></div>
						                    </div> ";

											}
											$prev = $cname;
										}
										
									}
									$result->close();
									$mysqli->next_result();

								}
							?>
                        
                     <?php 
						if($result = $mysqli->query("call new_recommen_list_by_follow('$username')") or die($mysqli->error)){
							if($result->num_rows >0){
								while($row = $result->fetch_object()){
									$listname = $row->listname;
									$createby = $row->username;
									$ldescription = $row->ldescription;
									$lcreatetime = $row->lcreatetime;
									echo "<div class='span3 post'>
												<div class='image-post'>
							                            <div class='square'>
							                                <div class='img-container'>
							                                    <a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname'>
							                                    <img alt='lorem' src='assets/images/$listname.jpg'>
							                                    <div class='square-icon'></div></a>
							                                </div>
							                            </div><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname'>
							                            <h3>Recommend List POST</h3></a>
							                        </div>
							                        <p><i class='icon-calendar'></i > $lcreatetime</p>";
							                    echo " <p>$ldescription</p>
                        						<p class='padding-5-30'><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname'><i class='icon-file-alt'></i > $listname</a></p>
						                        <p class='padding-5-30'><a href='/LiveConcert/user/user_page.php?username=$createby' rel='external'><i class=
						                        'icon-edit'></i > $createby</a></p>
						                        <div class='row spacer30'></div>
						                    </div> ";
								}
								
							}
							$result->close();
							$mysqli->next_result();

						}
					?> 
                   <?php 
						if($result = $mysqli->query("call follower_attend_concert('$username')") or die($mysqli->error)){
							if($result->num_rows >0){
								while($row = $result->fetch_object()){
									$follower = $row->username;
									$cname = $row->cname;
									$decision = $row->decision;
									$actime = $row->actime;
									echo "<div class='span3 post text-post'>
				                            <a href='blog-detail.html'>
				                            <h3>Following Update</h3></a>

				                        <p><i class='icon-calendar'></i > $actime</p>

				                        <p>Your following <a href='/LiveConcert/user/user_page.php?user=$follower'>$follower</a>  decide $decision to <a href='/LiveConcert/concert/concert_page.php?cname=$cname'>$cname</a></p>

				                        <p class='padding-5-30'><a href='/LiveConcert/user/user_page.php?user=$follower'><i class='icon-edit'></i > by $follower</a></p>

				                        <p class='padding-5-30'><a href='concert/concert_page.php?cname=$cname'><i class=
				                        'icon-music'></i >&nbsp;$cname</a></p>

				                        <div class='row spacer30'></div>
				                    </div>";
								}
								
							}
							$result->close();
							$mysqli->next_result();

						}
					?>
						<?php 
							if($result = $mysqli->query("call new_registe_artist('$username')") or die($mysqli->error)){
								if($result->num_rows >0){
									while($row = $result->fetch_object()){
										$artist = $row->username;
										$verifytime = $row->verifytime;
										echo "<div class='span3 post'>
												<div class='image-post'>
							                            <div class='square'>
							                                <div class='img-container'>
							                                    <a href='/LiveConcert/user/user_page.php?username=$artist'>
							                                    <img src='/LiveConcert/assets/images/$artist.jpg'>
							                                    <div class='square-icon'></div></a>
							                                </div>
							                            </div><a href='/LiveConcert/user/user_page.php?username=$artist'>
							                            <h3>New Artist</h3></a>
							                        </div>
							                        <p><i class='icon-calendar'></i > $verifytime</p>";
							                    echo " <p>New Aritist <a href='/LiveConcert/user/user_page.php?username=$artist'>$artist</a> registed our website. Go and check !</p>

                        						<p class='padding-5-30'><a href='/LiveConcert/user/user_page.php?username=$artist'><i class='icon-heart-empty'></i > $artist</a></p>
						                        <div class='row spacer30'></div>
						                    </div> ";
									}
									
								}
								$result->close();
								$mysqli->next_result();

							}
						?>
                   	<?php 

						if($result = $mysqli->query("call new_band('$username')") or die($mysqli->error)){
							if($result->num_rows >0){
								while($row = $result->fetch_object()){
									$band = $row->baname;
									$bbio = $row->bbio;
									$postby = $row->postby;
									$bptime = $row->bptime;
									echo "<div class='span3 post'>
												<div class='image-post'>
							                            <div class='square'>
							                                <div class='img-container'>
							                                    <a href='/LiveConcert/artist_band/band_page.php?baname=$band'>
							                                    <img src='/LiveConcert/assets/images/$band.jpg'>
							                                    <div class='square-icon'></div></a>
							                                </div>
							                            </div> <a href='/LiveConcert/artist_band/band_page.php?baname=$band'>
							                            <h3>New Band POST</h3></a>
							                        </div>
							                        <p><i class='icon-calendar'></i > $bptime</p>";
							                    echo " <p> Our New Band  <a href='/LiveConcert/artist_band/band_page.php?baname=$band'>$band</a> $bbio </p>

                        						<p class='padding-5-30'><a href='/LiveConcert/artist_band/band_page.php?baname=$band'><i class='icon-heart-empty'></i > $band</a></p>
						                        <p class='padding-5-30'><a href='/LiveConcert/user/user_page.php?username=$postby'><i class=
						                        'icon-map-marker'></i > $postby</a></p>
						                        <div class='row spacer30'></div>
						                    </div> ";

								}
								
							}
							$result->close();
							$mysqli->next_result();

						}
					?>
										
                </div>
            </div><!-- Blog end -->

            <div class='row spacer30'></div>
            <div class='row spacer30'></div>
        </div>                       
        <!-- ./Container -->   


    </section>
   
    <!-- Placed at the end of the document so the pages load faster -->
    <script src='/LiveConcert/assets/new/js/jquery.js'></script> 
    <script src='/LiveConcert/assets/new/js/bootstrap.min.js'></script> 
    <!-- // <script src='/LiveConcert/assets/new/js/mobile.menu.js'></script>  -->
    <script src='/LiveConcert/assets/new/js/jquery.flexslider-min.js'></script> 
    <!-- // <script src='/LiveConcert/assets/new/js/jquery.mousewheel.js'></script>  -->
    <script src='/LiveConcert/assets/new/js/jquery.prettyPhoto.js'></script> 
    <!-- // <script src='/LiveConcert/assets/new/js/jquery.mCustomScrollbar.min.js'></script>  -->
    <script src='/LiveConcert/assets/new/js/jquery.masonry.min.js'></script>
    <script src='/LiveConcert/assets/new/js/functions.js'></script>
</body>
</html>



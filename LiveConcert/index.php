<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="en-US">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="en-US">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="en-US">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width" />
<title>Live Concert</title>

<link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,700&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="/LiveConcert/assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/LiveConcert/assets/css/bootstrap.responsive.min.css">
<link rel="stylesheet" type="text/css" href="/LiveConcert/assets/js/jquery.fancybox.css">
<link rel="stylesheet" type="text/css" href="/LiveConcert/assets/css/style.css">
<script src="/LiveConcert/assets/js/jquery-1.9.1.min.js"></script>
<script src="/LiveConcert/assets/js/jquery.fancybox.pack.js"></script>
<script src="/LiveConcert/assets/js/jquery.validate.min.js"></script>
<script src="/LiveConcert/assets/js/bootstrap.min.js"></script>
<script src="/LiveConcert/assets/js/jquery.masonry.min.js"></script>
<script src="/LiveConcert/assets/js/main.js"></script>


<body>
    <div id="page" class="container-fluid">
        <header id="about" class="site-header" role="banner">
            <div class="logo"><a href="#" title="Impuls" rel="home"><img width="480" height="302" src="/LiveConcert/assets/images/Trecord4_movie.gif" class="attachment-logo" alt="logo.png" /></a></div>
            <div id="site-navigation" role="navigation">
                <ul class="nav-menu">
                    <li><a href="#Home">Home</a></li>
                    <li><a href="#Concert">Concert</a></li>
                    <li><a href="#Band">Band</a></li>
                    <li><a href="#ConcertList">ConcertList</a></li>
                    <li><a href="#AboutUs">About Us</a></li>
                </ul>
            </div>
            <?php 
            include "includes/config.php"; 
            if (!isset($_SESSION['username'])){echo "<li style='font-size:20px'><a href='/LiveConcert/login.php'>Join Us</li>";}
            else{$username =$_SESSION['username']; echo "<li style='font-size:20px'><a href='/LiveConcert/user/user_page.php?username=$username'>YOUR PROFILE</li>";}  ?>
            <!-- #site-navigation -->
            <div class="dropdown-menu-wrapper">
                <select class="dropdown-menu-mobile">
                </select>
            </div>
            <h1>The Most Exciting Concert Events</h1>
            <div class="separator"></div>
            <div class="row-fluid">
                <div class="span4">
                    <div class="solutions-icon"></div>
                    <h2>Look</h2>
                </div>
                <div class="span4">
                    <div class="competitiveness-icon"></div>
                    <h2>Listen</h2>
                </div>
                <div class="span4">
                    <div class="confidence-icon"></div>
                    <h2>Love</h2>
                </div>
            </div>
        </header>
        <!-- #masthead -->
        <div id="main">
            <p>
            <div class="team-wrapper" id="Concert">
                <div class="team">
                    <h2><a display="none" href="/LiveConcert/concert/concert_list.php">Concert</a></h2>
                    <div class="separator-gray"></div>
                    <p>Check the upcoming concert, you can manage your concert schedule. Also, you can find similar concert according to your taste. You also can build the recommendation concert list, which other user can follow as well. After attended the concert, posting rate and review would help us to find concert match your music favor. 
                    </p>
                    <div class="slider-wrapper">
                        <div class="posts-container">
                            <!-- <a class="member" href="#lightbox">
                                <div class="hover-icon"></div>
                                <img width="375" height="185" src="assets/images/Lykke Li Night.jpg" class="attachment-member wp-post-image" alt="male3.jpg" />
                                <div class="member-meta">
                                    <h2>Lykke Li Night</h2> -->
                                    <!-- <span class="job">Head of business</span><span class="corner"></span> -->
                             <!--    </div>
                            </a> -->
                            
                                <?php 
                                if($upcoming_6_concert = $mysqli->query("call upcoming_concert_top_6()") or die($mysqli->error)){

                                	while($row = $upcoming_6_concert->fetch_object()){
                                		$cname = $row->cname;
                                		echo "<a class='member' href='#lightbox'>
			                                <div class='hover-icon'></div>
			                                <img width='375' height='185' src='assets/images/".$cname.".jpg' class='attachment-member wp-post-image' alt='male3.jpg' />
			                                <div class='member-meta'>";
			                            echo "<h2>".$cname."</h2>
                                    	<span class='corner'></span>
                               			 </div></a>";
                                	}
                                	$upcoming_6_concert->close();
                                	$mysqli->next_result();
                                }

                                ?>
                                <!-- <div class="member-meta">
                                    <h2>Explosions In The Sky Concert</h2>
                                    <span class="job">Photographer</span><span class="corner"></span>
                                </div>
                            </a> -->
               <!--              <a class="member last" href="#lightbox">
                                <div class="hover-icon"></div>
                                <img width="375" height="185" src="css/images/content/male2-375x185.jpg" class="attachment-member wp-post-image" alt="male2.jpg" />
                                <div class="member-meta">
                                    <h2>Leo Duskanski</h2>
                                    <span class="job">Art Director</span><span class="corner"></span>
                                </div>
                            </a>
                            <a class="member " href="#lightbox">
                                <div class="hover-icon"></div>
                                <img width="375" height="185" src="css/images/content/male1-375x185.jpg" class="attachment-member wp-post-image" alt="male1.jpg" />
                                <div class="member-meta">
                                    <h2>Felix Doe</h2>
                                    <span class="job">Digital overlord</span><span class="corner"></span>
                                </div>
                            </a>
                            <a class="member " href="#lightbox">
                                <div class="hover-icon"></div>
                                <img width="375" height="185" src="css/images/content/female2-375x185.jpg" class="attachment-member wp-post-image" alt="female2.jpg" />
                                <div class="member-meta">
                                    <h2>Tinna Doe</h2>
                                    <span class="job">Photographer</span><span class="corner"></span>
                                </div>
                            </a>
                            <a class="member last" href="#lightbox">
                                <div class="hover-icon"></div>
                                <img width="375" height="185" src="css/images/content/male4-375x185.jpg" class="attachment-member wp-post-image" alt="male4.jpg" />
                                <div class="member-meta">
                                    <h2>Alex Doe</h2>
                                    <span class="job">Photographer</span><span class="corner"></span>
                                </div>
                            </a> -->
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="load-more">Load more</a>
                    <div class="slider-navigation"></div>
                </div>
            </div>
            <div class="references-wrapper" id="Band">
                <div class="references">
                    <h2><a display="none" href="/LiveConcert/artist_band/band_list.php">Bands</a></h2>
                    <div class="separator-pattern"></div>
                    <p>Find your favorate bands, be fan of them, you would know more about their music and their upcoming concert.
                    </p>
                    <div class="categories">
                        <div class="category-container category-1" data-id="8" data-slug="photography">
                        <!-- <a href="/LiveConcert/artist_band/band_page.php?baname=Cat Power"> -->
                            <img width="375" height="375" src="assets/images/Cat Power.jpg" class="attachment-story" alt="Cat Power.jpg" />
                            <div class="category-meta"><span class="name">Cat Power</span><span class="category-icon"></span></div>
                        </a></div>
                        <div class="group">
                            <div class="category-container category-2" data-id="7" data-slug="motion-graphics">
                            	<!-- <a href="/LiveConcert/artist_band/band_page.php?baname=band of horses"> -->
                            	<img width="185" height="185" src="assets/images/band of horses.jpg" class="attachment-story" alt="Cat Power.jpg" />
                                <div class="category-meta"><span class="name">band of horses</span><span class="category-icon"></span></div>
                            </a></div>
                            <div class="category-container category-3" data-id="6" data-slug="consulting">
                            	<!-- <a href="/LiveConcert/artist_band/band_page.php?baname=Explosions In The Sky"> -->
                            	<img width="185" height="185" src="assets/images/Explosions In The Sky.jpg" class="attachment-story" alt="Cat Power.jpg" />
                                <div class="category-meta"><span class="name">Explosions In The Sky</span><span class="category-icon"></span></div>
                            </a></div>
                            <div class="category-container category-4" data-id="5" data-slug="it-technologies">
                            	<!-- <a href="/LiveConcert/artist_band/band_page.php?baname=Kyte"> -->
                            	<img width="185" height="185" src="assets/images/Kyte.jpg" class="attachment-story" alt="Cat Power.jpg" />
                                <div class="category-meta"><span class="name">Kyte</span><span class="category-icon"></span></div>
                            </a></div>
                            <div class="category-container category-5" data-id="4" data-slug="webdesign">
                            	<!-- <a href="/LiveConcert/artist_band/band_page.php?baname=Feist"> -->
                            	<img width="185" height="185" src="assets/images/Feist.jpg" class="attachment-story" alt="Cat Power.jpg" />
                                <div class="category-meta"><span class="name">Feist</span><span class="category-icon"></span></div>
                            </a></div>
                        </div>
                        <div class="category-container category-6" data-id="3" data-slug="mobile-interface">
                        	<!-- <a href="/LiveConcert/artist_band/band_page.php?baname=Immanu El"> -->
                            <img width="375" height="375" src="assets/images/Immanu El.jpg" class="attachment-story" alt="mobile-interface.jpg" />
                            <div class="category-meta"><span class="name">Immanu El</span><span class="category-icon"></span></div>
                        </a></div>
                        <div class="cleaner"></div>
                    </div>
                    <div class="single-category" data-slug="photography">
                        <div class="slider-wrapper">
                            <div class="posts-container" style="left: 0px;">
                                <a class="category-post" data-rel="cat"  href="#lightbox" data-slug="etiam-purus">
                                    <img width="375" height="375" src="assets/images/The Cinematic Orchestra.jpg" class="attachment-story wp-post-image" alt="ref4.jpg">
                                    <div class="category-meta"> <span class="name">The Cinematic Orchestra</span> <span class="category-icon"></span> 
                                    </div>
                                </a>
                                <a class="category-post" data-rel="cat" href="#lightbox" data-slug="in-feugiat">
                                    <img width="375" height="375" src="assets/images/Thexx.jpg" class="attachment-story wp-post-image" alt="ref3.jpg">
                                    <div class="category-meta"> <span class="name">The xx</span> <span class="category-icon"></span> 
                                    </div>
                                </a>
                                <a class="category-post last" data-rel="cat" href="#lightbox" data-slug="consectetur-adipiscing-elit">
                                    <img width="375" height="375" src="assets/images/Lykke Li.jpg" class="attachment-story wp-post-image" alt="ref2.png">
                                    <div class="category-meta"> <span class="name">Lykke Li</span> <span class="category-icon"></span> 
                                    </div>
                                </a>
                                <a class="category-post" data-rel="cat" href="#lightbox" data-slug="lorem-ipsum-dolor">
                                    <img width="375" height="375" src="assets/images/Hammock.jpg" class="attachment-story wp-post-image" alt="ref1.jpg">
                                    <div class="category-meta"> <span class="name">Hammock</span> <span class="category-icon"></span> 
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="cleaner"></div>
                    </div>
                    <a href="javascript:void(0)" class="load-more">Load more</a>
                    <div class="slider-navigation"></div>
                </div>
            </div>
            <div class="blog-wrapper" id="ConcertList">
                <div class="blog">
                    <h2><a href='/LiveConcert/concertlist/concertlist_list.php'>Check Other's Recommend List</a></h2>
                    <div class="separator-blog"></div>
                    <?php 
                    	$allListArray = array();
                    	if($allList = $mysqli->query("call get_all_list") or die($mysqli->error)){
                    		while($row = $allList->fetch_object()){
                    			$listObject = array();
                    			$listObject['listname'] = $row->listname;
                    			$listObject['username'] = $row->username;
                    			$listObject['ldescription'] = $row->ldescription;
                    			$allListArray[] = $listObject;
                    		}
                    		$allList->close();
                    		$mysqli->next_result();
                    	}
                     ?>
                    <div class="blog-posts-wrapper">
                        <div class="blog-posts-container">
                            <div class="blog-post gallery" data-slug="gallery">
                                <div class="gallery-wrapper">
                                    <div class="gallery-navigation"><a href="javascript:void(0);" class="arrow left"></a><a href="javascript:void(0);" class="arrow right"></a></div>
                                    <div class="gallery-content">
                                        <div class="image"><img src="assets/images/<?php echo $allListArray[0]['listname']; ?>.jpg"></div>
                                    </div>
                                </div>
                                <div class="blog-post-content">
                                    <h2><a href="/LiveConcert/concertlist/concertlist_page.php?listname=<?php echo $allListArray[0]['listname']; ?>" class="blog-post-link" data-rel="blog" data-slug="gallery"><?php echo $allListArray[0]['listname']; ?></a></h2>
                                    <p><?php echo $allListArray[0]['ldescription']; ?></p>
                                    <div class="date"><?php echo $allListArray[0]['username']; ?></div>
                                </div>
                            </div>
                            <div class="blog-post quote" data-slug="seattle-central-library">
                                <div class="quote"><img height='240px' width='375px' src="assets/images/<?php echo $allListArray[2]['listname']; ?>.jpg"></div>
                                <div class="blog-post-content">
                                    <h2><a href="#lightbox" class=" blog-post-link" data-rel="blog"  data-slug="seattle-central-library"><?php echo $allListArray[2]['listname']; ?></a></h2>
                                    <p><?php echo $allListArray[2]['ldescription']; ?></p>
                                    <div class="date"><?php echo $allListArray[2]['username']; ?></div>
                                </div>
                            </div>
                            <div class="blog-post gallery" data-slug="akiko">
                                <div class="gallery-wrapper">
                                    <div class="gallery-navigation"><a href="javascript:void(0);" class="arrow left"></a><a href="javascript:void(0);" class="arrow right"></a></div>
                                    <div class="gallery-content">
                                        <div class="image"><img src="assets/images/<?php echo $allListArray[1]['listname']; ?>.jpg"></div>
                                        
                                    </div>
                                </div>
                                <div class="blog-post-content">
                                    <h2><a href="/LiveConcert/concertlist/concertlist_page.php?listname=<?php echo $allListArray[1]['listname']; ?>" class=" blog-post-link" data-rel="blog"  data-slug="akiko"><?php echo $allListArray[1]['listname']; ?></a></h2>
                                    <p><?php echo $allListArray[1]['ldescription']; ?></p>
                                    <div class="date"><?php echo $allListArray[1]['username']; ?></div>
                                </div>
                            </div>
                            <div class="blog-post link" data-slug="absolutely-free-images-bank">
                                <a class="link" href="/LiveConcert/concertlist/concertlist_page.php?listname=recommendationlist" target="_blank">Our Recommendation List</a>
                                <!-- <img height='240px' width='375px' src="assets/images/recommendationlist.jpg"> -->
                                <div class="blog-post-content">
                                    <h2><a href="/LiveConcert/concertlist/concertlist_page.php?listname=recommendationlist" class=" blog-post-link" data-rel="blog"  data-slug="absolutely-free-images-bank">Check Our Team's Taste</a></h2>
                                    <p>This is our team's taste, all indie rack, post rock, mixed blues</p>
                                    <div class="date">Wen&Su</div>
                                </div>
                            </div>
                            <div class="blog-post audio" data-slug="pseudart-delirium">
                                <div class="blog-post-content">
                                    <h2><a href="/LiveConcert/concertlist/concertlist_page.php?listname=<?php echo $allListArray[3]['listname']; ?>" class=" blog-post-link"  data-rel="blog" data-slug="pseudart-delirium"><?php echo $allListArray[3]['listname']; ?></a></h2>
                                    <div class="date"><?php echo $allListArray[3]['username']; ?></div>
                                    <img height='270px' width='325px' src="assets/images/<?php echo $allListArray[3]['listname']; ?>.jpg">
                                    <div class="soundcloud">
                                    <br><br></b><center><p><?php echo $allListArray[3]['ldescription']; ?></p><?php echo $allListArray[3]['listname']; ?></center>
                                        <!-- <iframe src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F8481339&amp;color=53adb3&amp;auto_play=false&amp;show_artwork=false"></iframe> -->
                                    </div>
                                </div>
                            </div>
                            <div class="blog-post video" data-slug="the-real-thing">
                                <div class="video">
                                <img height='270px' width='325px' src="assets/images/<?php echo $allListArray[4]['listname']; ?>.jpg">
                                </div>
                                <div class="blog-post-content">
                                    <h2><a href="/LiveConcert/concertlist/concertlist_page.php?listname=<?php echo $allListArray[4]['listname']; ?>" class=" blog-post-link" data-rel="blog" data-slug="the-real-thing"><?php echo $allListArray[4]['listname']; ?></a></h2>
                                    <p><?php echo $allListArray[4]['ldescription']; ?></p>
                                    <div class="date"><?php echo $allListArray[4]['username']; ?></div>
                                </div>
                            </div>
                            <div class="blog-post standard" data-slug="lorem-ipsum">
                                <div class="thumb"><img width="375" height="240" src="assets/images/<?php echo $allListArray[5]['listname']; ?>.jpg" class="attachment-blog-thumb wp-post-image" alt="(16).jpg" /></div>
                                <div class="blog-post-content">
                                    <h2><a href="/LiveConcert/concertlist/concertlist_page.php?listname=<?php echo $allListArray[5]['listname']; ?>" class=" blog-post-link" data-rel="blog" data-slug="lorem-ipsum"><?php echo $allListArray[5]['listname']; ?></a></h2>
                                    <p><?php echo $allListArray[5]['ldescription']; ?></p>
                                    <div class="date"><?php echo $allListArray[5]['username']; ?></div>
                                </div>
                            </div>
                            <div class="cleaner"></div>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="load-more">Load more</a>
                    <div class="slider-navigation"></div>
                </div>
            </div>
           
        </div>
        <!-- #main -->
        <div class="footer-wrapper" id="AboutUs">
            <footer id="colophon" role="contentinfo">
                <h2>Join Us<img width="126" height="56" src="assets/images/admin.jpg" class="attachment-logo-footer" alt="logo-small.png" />Be Close</h2>
                <div class="separator-footer"></div>
                <div class="social-icons"> <a href="http://facebook.com/impuls" target="_blank" class="facebook"><span></span></a> <a href="http://twitter.com/impuls" target="_blank" class="twitter"><span></span></a> <a href="http://plus.google.com/impuls" target="_blank" class="google"><span></span></a> <a href="http://dribbble.com/impuls" target="_blank" class="dribbble"><span></span></a> <a href="http://www.deviantart.com/impuls" target="_blank" class="deviant-art"><span></span></a> <a href="http://www.linkedin.com/impuls" target="_blank" class="linked-in"><span></span></a> <a href="http://www.vimeo.com/impuls" target="_blank" class="vimeo"><span></span></a> <a href="http://www.flickr.com/impuls" target="_blank" class="flickr"><span></span></a> </div>
                <div class="row">
                    
                </div>
                <div class="left">
                    
                    <div class="info"> Suzie<br />
                        Email: <a href="">email@nyu.edu</a>
                    </div>
                    <div class="info"> Wendy<br />
                        Email: <a href="">xf@nyu.edu</a>
                    </div>
                </div>
            </footer>
            <!-- #colophon --> 
        </div>
        <div class="single-content" id="lightbox">
            <div class="lightbox">
                <div class="thumb">
                    <img width="880" height="600" src="css/images/content/(16)-880x600.jpg" class="attachment-lightbox" alt="(16).jpg">
                </div>
                <div class="thumb mobile">
                    <img width="280" height="330" src="css/images/content/(16)-280x330.html" class="attachment-mobile-lightbox" alt="(16).jpg">
                </div>
                <div class="content-wrapper">
                    
                    <div class="social">
                        <a href="mailto:info@example.com?subject=Lorem%20ipsum&amp;body=Check%20this%20out%20http://impuls.com" target="_blank" class="mail-icon"></a>
                        <ul class="social-icons">
                            <li class="facebook"> <a class="social-icon facebook" href="http://facebook.com/suzie.su.18"></a> </li>
                            <li class="twitter"> <a class="social-icon twitter" href="http://twitter.com" target="_blank"></a> </li>
                        </ul>
                    </div>
                    <div class="cleaner"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- #page --> 
</body>
</html>



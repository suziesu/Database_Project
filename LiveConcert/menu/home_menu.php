<header> 
<ul id='menu'>
<li><a href='/LiveConcert/home.php'>Home</a></li>
<?php 

require_once($path."/LiveConcert/includes/config.php");

if(isset($_SESSION['username']) ){
	$username=$_SESSION['username'];
	echo "<li><a href='/LiveConcert/user/user_page.php?username=$username'>Your Profile</a></li>";
}
?> 
<li><a href='/LiveConcert/artist_band/band_list.php'>Band</a></li>
<li><a href='/LiveConcert/concert/concert_list.php'>Concert</a></li>
<li><a href='/LiveConcert/concertlist/concertlist_list.php'>ConcertList</a></li>
<li><a href='/LiveConcert/genre/genre_list.php'>Music Genre</a></li>
<li><a href='/LiveConcert/recommendation.php'>You May Like</a></li>
<li><a href='/LiveConcert/logout.php'>Logout</a></li>


</ul>


<center>
<ul id='menu'>
<link rel="stylesheet" type="text/css" href="/LiveConcert/assets/css/menu.css">
<nav>
  <ul>
  <li>
      <a href='/LiveConcert/index.php'>Home</a>
    </li>
    <li>
      <a href='/LiveConcert/new.php'>News</a>
    </li>
  <li>
      <?php 

if(isset($_SESSION['username']) ){
	$username=$_SESSION['username'];
	echo "<a href='/LiveConcert/user/user_page.php?username=$username'>Your Profile</a>";
}
?> 
    </li>
    
    <li>
      <a href='/LiveConcert/artist_band/band_list.php'>Band</a>
    </li>
    <li>
      <a href='/LiveConcert/concert/concert_list.php'>Concert</a>
      </li>
      <li>
      <a href='/LiveConcert/concertlist/concertlist_list.php'>ConcertList</a>
    </li>
    <li>
      <a href='/LiveConcert/genre/genre_list.php'>Music Genre</a>
    </li>
    <li>
      <a href='/LiveConcert/recommendation.php'>You May Like</a>
    </li>
    <li>
      <a href='/LiveConcert/logout.php'>Logout</a>
    </li>
    <li>
      <!-- <a href="#">Contact</a> -->
    </li>
  </ul></nav>
  </center>
</nav>
<!-- <li><a href='/LiveConcert/artist_band/band_list.php'>Band</a></li> -->
<!-- <li><a href='/LiveConcert/concert/concert_list.php'>Concert</a></li> -->
<!-- <li><a href=>ConcertList</a></li>
<li><a href='/LiveConcert/genre/genre_list.php'>Music Genre</a></li>
<li><a href='/LiveConcert/recommendation.php'>You May Like</a></li>
<li><a href='/LiveConcert/logout.php'>Logout</a></li> -->


</ul>

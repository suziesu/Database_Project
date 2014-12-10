<!DOCTYPE html>
<html>
<html>
<head>
<?php 
include "../includes/new_head.html"; 
include "../includes/regular_page_head.php";
?>
	<title>Band Page</title>
</head>
<body>


<?php 
$username = $_SESSION['username'];
$owner = "";
$baname = "";
$bptime = "";
$fanOf = false;
$bbio="";
$bptime="";
$owner="";
	if(isset($_GET['baname'])){
		$baname = $_GET['baname'];
		if($bandinfo = $mysqli->query("call get_band_info('$baname')") or die($mysqli->error)){
			if($row = $bandinfo->fetch_object()){
				$bbio = $row->bbio;
				$owner = $row->postby;
				$bptime = $row->bptime;
			}
			$bandinfo->close();
			$mysqli->next_result();
		}
		if($isFan = $mysqli->query("call fan_of_band('$username','$baname')") or die($mysqli->error)){
			if($isFan->num_rows > 0){
				$fanOf = true;
			}
			$isFan->close();
			$mysqli->next_result();
		}
	}else if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['baname'])){
		$baname = $_POST['baname'];
		if($isFan = $mysqli->query("call fan_of_band('$username','$baname')") or die($mysqli->error)){
			if($isFan->num_rows > 0){
				$fanOf = true;
			}
			$isFan->close();
			$mysqli->next_result();
		}
		//check the submit button
		if($_POST['submit'] == 'Delete Band'){
			if($result = $mysqli->query("call delete_band('$baname')")){
				echo "delete band success";
				// $result->close();
				// $mysqli->next_result();
			}
		}

		if($_POST['submit'] == 'Fan'){
			if($result = $mysqli->query("call be_fan('$username','$baname')")){
				echo "be fan success";
				$fanOf = true;
				// $result->close();
				// $mysqli->next_result();
			}

		}
		if($_POST['submit'] == 'UnFan'){
			if($result = $mysqli->query("call un_fan('$username','$baname')")){
				echo "unfan success";
				$fanOf = false;
				// $result->close();
				// $mysqli->next_result();
			}
		}
		if($_POST['submit'] == 'Remove Concert' && isset($_POST['cname'])){
			$cname = $_POST['cname'];
			if($result = $mysqli->query("call remove_whole_concert('$cname')")){
				echo "remove concnert success";
				// $result->close();
				// $mysqli->next_result();
			}
		}
	}else{
		echo "no bandname is set";
		header("Location: band_list.php");
	}
	

?>

<section class='content'>
<div class="container white-background">

	<div id='user_info' class='row'>
<a href='/LiveConcert/artist_band/band_list.php'><button id='create_concert'>Back To Band List</button></a>

		<div  class="span7">




<h3><?php echo $baname; ?></h3><h3><img src="/LiveConcert/assets/images/<?php echo $baname; ?>.jpg"></h3>
<p id='margin-top'>

<?php 
	if($baname){
		//ifi user is the owner he can edit and delete
		if($username == $owner){
			echo "<p><a href='edit_band.php?baname=$baname'><button id='create_concert'>Edit Band</button></a></p>";
			echo "<p><form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'><input type='hidden' name='baname' value='$baname'><input id='create_concert' type='submit' name='submit' value='Delete Band'></a></p>";
		//if not he only can follow or not follow
		}else{
			if(!$fanOf){
			echo "<h3><form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'><input type='hidden' name='baname' value='$baname'><input id='submit' type='submit' name='submit' value='Fan'></form></h3>";
			//already followed but not the creator show followed button
			}else{
				echo "<h3><button color='white' type='button'>Followed</button>";
				echo "<p><form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'><input type='hidden' name='baname' value='$baname'><input id='create_concert' type='submit' name='submit' value='UnFan'></form></h3>";
			}
		}
		//get band tpe 
		echo "<p >Band Type</p>";
		if($bandtp = $mysqli->query("call get_band_type('$baname')") or die($mysqli->error)){
			if($bandtp->num_rows > 0){
				echo "<li id='margin-left'>";
				while($row = $bandtp->fetch_object()){
					$subtype = $row->subtypename;
					echo "<a href='/LiveConcert/genre/genre_type_page.php?subtype=$subtype'>$subtype</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				}
			}echo "</li>";
			$bandtp->close();
			$mysqli->next_result();
		}
		echo "</div>";
		//get band Member
		echo "<div class='span3' >

		<h3>Band Member</h3>";
		if($bandmem = $mysqli->query("call get_band_member('$baname')") or die($mysqli->error)){
			echo "<h6>";
			if($bandmem->num_rows > 0){
				while($row = $bandmem->fetch_object()){
					$member = $row->bandmember;
					echo "<li><a href='/LiveConcert/user/user_page.php?username=$member'>$member</a></li>";
				}
				
			}
			echo "</h6>";
			$bandmem->close();
			$mysqli->next_result();
			
		}

		echo "
		</p></div></div>";
		//get band concert
		echo "<div class='row' id='concert'>";
		echo "<h2>Bio</h2>";
		 echo "<p>".$bbio."</p>"; 
		 echo "</div>";
		echo "<div class='row' id='concert'>

		<h2>Upcoming Concert</h2>
		";
		if($upconing = $mysqli->query("call get_band_future_concert('$baname')") or die($mysqli->error)){
			if($upconing->num_rows > 0){
				while($row = $upconing->fetch_object()){
					$concert = $row->cname;
					$cdatetime = $row->cdatetime;
					$locname = $row->locname;
					$price = $row->price;
					$postby = $row->cpostby;
					$cdescrib = $row->cdescription;
					echo "<div class='span3' >";
					echo "<div><a href='/LiveConcert/concert/concert_page.php?cname=$concert'><img src='/LiveConcert/assets/images/$concert.jpg'>";
					echo "<h4>".$concert."</h4><h6>$cdatetime";
					echo $locname;
					echo "</a></h6></div>";
					echo "<ul>$cdescrib</ul>";
					//remove concert button
					if($username == $postby){
						echo "<p id='edit'><a href='/LiveConcert/edit_concert.php?cname=$concert'><button id='submit'>Edit</button></a></p>";
						echo "<p id='edit'><form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST' onsubmit='return confirm("."'Are you sure you want to remove?'".");'><input type='hidden' name='baname' value='$baname'><input type='hidden' name='cname' value='$concert'><input id='create_concert' type='submit' name='submit' value='Remove Concert'></form></p>";
					}
					echo "</div>";
				}
				
			}
			$upconing->close();
			$mysqli->next_result();
		}

		echo "</div>";

		echo "<div class='row' id='concert'>

		<h2>Past Concert</h2>";

		if($past = $mysqli->query("call get_band_past_concert('$baname')") or die($mysqli->error)){
			
			if($past->num_rows > 0){
				while($row = $past->fetch_object()){
					$concert = $row->cname;
					$cdatetime = $row->cdatetime;
					$locname = $row->locname;
					$price = $row->price;
					$postby = $row->cpostby;
					$cdescrib = $row->cdescription;
					echo "<div class='span3' >";
					echo "<a href='/LiveConcert/concert/concert_page.php?cname=$concert'><img src='/LiveConcert/assets/images/$concert.jpg'>";
					echo "<h4>".$concert."</h4><span>$cdatetime</span>";
					echo "<p>".$locname."</p>";
					echo "</a>";
					echo "<p><ul>$cdescrib</ul></p>";
					//remove concert button
					if($username == $postby){
						echo "<a href='/LiveConcert/edit_concert.php?cname=$concert'><button id='submit'>Edit</button></a>";
					}
					echo "</div>";
				}
				
			}
			$past->close();
			$mysqli->next_result();
		}
		echo "</div>";
		
	}else{
		echo "no band is choosen";
	}
	$mysqli->close();

?>
<div class='row' id='concert'>
<h3><a href="/LiveConcert/artist_band/band_list.php"><button id='create_concert'>Back To Band List</button></a></h3>
</div>
</body>
</html>
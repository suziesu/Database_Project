<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
include $path."/LiveConcert/menu/home_menu.php";?>
	<title>Band Page</title>
</head>
<body>
<?php 
$username = $_SESSION['username'];
$owner = "";
$baname = "";
$bptime = "";
$fanOf = false;
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
		header("Location: bandlist.php");
	}
	

?>
<a href="/LiveConcert/artist_band/band_list.php"><button>Back To Band List</button></a>
<div><img src="/LiveConcert/assets/images/<?php echo $baname; ?>.jpg"><h2><?php echo $baname; ?></h2></body></div>
<?php 
	if($baname){
		//ifi user is the owner he can edit and delete
		if($username == $owner){
			echo "<a href='edit_band.php?baname=$baname'><button>Edit Band</button></a>";
			echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'><input type='hidden' name='baname' value='$baname'><input type='submit' name='submit' value='Delete Band'></a>";
		//if not he only can follow or not follow
		}else{
			if(!$fanOf){
			echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'><input type='hidden' name='baname' value='$baname'><input type='submit' name='submit' value='Fan'></form>";
			//already followed but not the creator show followed button
			}else{
				echo "<button color='grey' type='button'>Followed</button>";
				echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'><input type='hidden' name='baname' value='$baname'><input type='submit' name='submit' value='UnFan'></form>";
			}
		}
		//get band tpe 
		echo "<div><h4>Band Type</h4><ul>";
		if($bandtp = $mysqli->query("call get_band_type('$baname')") or die($mysqli->error)){
			while($row = $bandtp->fetch_object()){
				$subtype = $row->subtypename;
				echo "<a href='/LiveConcert/genre/genre_type_page.php?subtype=$subtype'>$subtype</a>&nbsp;";
			}
			$bandtp->close();
			$mysqli->next_result();
		}
		echo "</ul></div>";

		//get band Member
		echo "<div><h3>Band Member</h3><ul>";
		if($bandmem = $mysqli->query("call get_band_member('$baname')") or die($mysqli->error)){
			while($row = $bandmem->fetch_object()){
				$member = $row->bandmember;
				echo "<a href='/LiveConcert/user/user_page.php?username=$member'>$member</a>";
			}
			$bandmem->close();
			$mysqli->next_result();
		}

		echo "</ul></div>";
		//get band concert
		echo "<div><h3>Upcoming Concert</h3>";
		if($upconing = $mysqli->query("call get_band_future_concert('$baname')") or die($mysqli->error)){
			if($upconing->num_rows > 0){
				while($row = $upconing->fetch_object()){
					$concert = $row->cname;
					$cdatetime = $row->cdatetime;
					$locname = $row->locname;
					$price = $row->price;
					$postby = $row->cpostby;
					$cdescrib = $row->cdescription;
					echo "<div><a href='/LiveConcert/concert/concert_page.php?cname=$concert'><img src='/LiveConcert/assets/images/$concert.jpg'>";
					echo "<h4>".$concert."</h4><span>$cdatetime</span>";
					echo $locname;
					echo "</a></div>";
					echo "<div><ul>$cdescrib</ul><div>";
					//remove concert button
					if($username == $postby){
						echo "<a href='/LiveConcert/edit_concert.php?cname=$concert'><button>Edit</button></a>";
						echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST' onsubmit='return confirm("."'Are you sure you want to remove?'".");'><input type='hidden' name='baname' value='$baname'><input type='hidden' name='cname' value='$concert'><input type='submit' name='submit' value='Remove Concert'></form>";
					}
				}
				$upconing->close();
				$mysqli->next_result();
			}
		}

		echo "</div>";
		echo "<div><h3>Past Concert</h3></h3>";
		if($past = $mysqli->query("call get_band_past_concert('$baname')") or die($mysqli->error)){
			if($past->num_rows > 0){
				while($row = $past->fetch_object()){
					$concert = $row->cname;
					$cdatetime = $row->cdatetime;
					$locname = $row->locname;
					$price = $row->price;
					$postby = $row->cpostby;
					$cdescrib = $row->cdescription;
					echo "<div><a href='/LiveConcert/concert/concert_page.php?cname=$concert'><img src='/LiveConcert/assets/images/$concert.jpg'>";
					echo "<h4>".$concert."</h4><span>$cdatetime</span>";
					echo $locname;
					echo "</a></div>";
					echo "<div><ul>$cdescrib</ul><div>";
					//remove concert button
					if($username == $postby){
						echo "<a href='/LiveConcert/edit_concert.php?cname=$concert'><button>Edit</button></a>";
					}
				}
				$past->close();
				$mysqli->next_result();
			}
		}
		echo "</div>";
		
	}else{
		echo "no band is choosen";
	}

?>
<a href="/LiveConcert/artist_band/band_list.php"><button>Back To Band List</button></a>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
	<?php include "../includes/new_head.html"; 
	include "../includes/regular_page_head.php";?>
	<title>My Followed List</title>
</head>
<body>
<?php 
$username = $_SESSION['username'];
$listname="";
	if(isset($_POST['listname']) && isset($_POST['submit'])){
		$listname = $_POST['listname'];
		if($unfollow = $mysqli->query("call unfollow_recommenlist('$listname')") or die($mysqli->error)){
			$unfollow->close();
			$mysqli->next_result();
		}
	}

?>
<section class='content'>
<div class="container white-background">
<!-- <h3 id="title"><?php echo $listname."</h3>";?> -->
		<h3> My Concert List</h3>

	<div id='concert_list' class='row'>
		<div class='span2'></div>
			<div class='span8'>
			
			<!-- <h3><img src="/LiveConcert/assets/images/<?php echo $listname; ?>.jpg"> </h3> -->


<?php
	if($result = $mysqli->query("call followed_recommend_list('$username')") or die($mysqli->error)){
		if($result->num_rows > 0){
			while($row = $result->fetch_object()){
				$fl = $row->listname;
				$createby = $row->username;
				$lcreatetime = $row->lcreatetime;
				$ldescription = $row->ldescription;
				echo "<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$fl' >";
				echo "<h3><img src='/LiveConcert/assets/images/$fl.jpg'></h3>";
				echo "<p>$fl</p></a>";
				echo "<p><i class='icon-edit'></i>&nbsp;$createby</p>";
				echo "<p><i class='icon-calendar'></i>&nbsp;$lcreatetime</p>";
				echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'>";
				echo "<input type='hidden' name='listname' value='$fl'>";
				echo "<p><input id='create_concert' type='button' name='submit' value='Unfollow'></form></p>";
				echo "<p>$ldescription</p>";
			}
		}
		$result->close();
	}
	$mysqli->close();

?>
</div>
	</div>
	</div>
	</section>
</body>
</html>
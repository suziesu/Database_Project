
<!DOCTYPE html>
<html>
<head>
<?php include "../includes/new_head.html"; 

include "../includes/regular_page_head.php";?>
	<title>ConcertList</title>
</head>
<body>

<?php 
$username = $_SESSION['username'];
$createdby = '';
$describ = "";
$createtime = "";
$listname="";
$followed = false;
//get list basic info
if(isset($_GET['listname'])){
	$listname = $_GET['listname'];
	if($getList = $mysqli->query("call get_recommend_list_by_name('$listname')") or die($mysqli->error)){
		if(!$row=$getList->fetch_object()){
			echo "no such list";
		}else{
			$createdby = $row->username;
			$describ = $row->ldescription;
			$createtime = $row->lcreatetime;
		}
		$getList->close();
		$mysqli->next_result();
	}
	//if already been followed
	if($followList = $mysqli->query("call is_followed('$listname','$username')") or die($mysqli->error)){
		if($followList->num_rows > 0 ){
			$followed = true;
			
		}
		$followList->close();
		$mysqli->next_result();
	}
}else if($_SERVER['REQUEST_METHOD']=='POST' && !isset($_POST['listname'])){
	$_SESSION['error'] = "listname is not choosed";
	header("Location: my_concertlist.php");
}else{
//follow method post
//follow the list
	if(isset($_POST['listname']) && $_POST['submit']=='Follow'){
		$followListName = $_POST['listname'];
		if($follow = $mysqli->query("call follow_recommend_list('$followListName','$username')") or die($mysqli->error)){
			// $follow->close();
		}

	}
	//unfollow the list
	if(isset($_POST['listname']) && $_POST['submit'] == 'UnFollow'){
		$unFollowListName = $_POST['listname'];
		if($unfollow = $mysqli->query("call unfollow_recommenlist('$username', '$unFollowListName')") or die($mysqli->error)){
			// $unfollow->close();
		}
	}
	//delete whole list
	if(isset($_POST['listname']) && $_POST['submit'] == 'Delete'){
		$deleteListName = $_POST['listname'];
		if($delete = $mysqli->query("call delete_userrecommendlist('$username', '$deleteListName')") or die($mysqli->error)){
			header('Location: my_concertlist.php');
			// $delete->close();
		} 
	}
		//remove concert
	if(isset($_POST['remove_concert']) && $_POST['submit'] == 'Remove'){
		$removeConcertName = $_POST['remove_concert'];
		$listname = $_POST['listname'];
		if($delete = $mysqli->query("call Remove('$listname', '$removeConcertName')") or die($mysqli->error)){
			// $delete->close();
		} 
	}
}
?>


<section class='content'>
<div class="container white-background">
<h3 id="title"><?php echo $listname."</h3>";?>
	<div id='user_info' class='row'>
		
		<div class="span8">
			
			<center><img src="/LiveConcert/assets/images/<?php echo $listname; ?>.jpg"> </center>
			

		





<?php 
//not follow and not created by viewer show follow button
if($listname){
	if($username != $createdby){
		if(!$followed){
			echo "<h3><form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'><input type='hidden' name='listname' value='$listname'><input type='submit' id='submit' name='submit' value='Follow'></form>";
			//already followed but not the creator show followed button
		}else{
			echo "<h3>Followed";
			echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'><input type='hidden' name='listname' value='$listname'><input type='submit' id='submit' name='submit' value='UnFollow'></form>";
		}
	//creater himself do nothing.
	}else{
		echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST' onsubmit='return confirm("."'Are you sure you want to delete?'".");'><input type='hidden' name='listname' value='$listname'><input id='submit' type='submit' name='submit' value='Delete'></form>";
	}
	echo "</h3>";
}else{
	echo "no listname is chosen";
	header("Location: concertlist_list.php");

}
echo "<p>by:&nbsp;".$createdby ."&nbsp;&nbsp;&nbsp;at:&nbsp;".$createtime."</p>"; ?>
 

			</div>
		
		<div class="span2">
			<a href="/LiveConcert/concertlist/my_concertlist.php"><button id='create_concert'>Back To My All List</button></a>
		</div>
	</div>
	<div id='user_info' class='row'>


<?php echo "<h3>Description:</h3><p></p><p>$describ</p></div>
<div id='concert_list' class='row'>
<h2>Concert</h2>
<div class='span2'></div>
<div class='span8'>";
	
	if($getConcert = $mysqli->query("call get_recommend_list_concert('$listname')") or die($mysqli->error)){
		if($getConcert->num_rows > 0){

			while($row = $getConcert->fetch_object()){
				$concert = $row->cname;
				$cdatetime = $row->cdatetime;
				$locname = $row->locname;
				$price = $row->price;
				$cdescrib = $row->cdescription;
				echo "<h3><a href='/LiveConcert/concert/concert_page.php?cname=$concert'><img src='/LiveConcert/assets/images/$concert.jpg'>";
				echo $concert."</a></h3><p><i class='icon-calendar'></i >&nbsp;$cdatetime</p>";
				echo "<p><i class='icon-map-marker'></i>&nbsp;".$locname."</p>";
				echo "<p>$cdescrib</p>";
				//remove concert button
				if($username == $createdby){
					echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST' onsubmit='return confirm("."'Are you sure you want to remove?'".");'><input type='hidden' name='listname' value='$listname'><input type='hidden' name='remove_concert' value='$concert'><input id='submit' type='submit' name='submit' value='Remove'></form>";
				}
			}
			$getConcert->close();
			$mysqli->next_result();
		}

	}
	echo "</div>
	</div>
	</div>
	</section>";


?>

</body>
</html>
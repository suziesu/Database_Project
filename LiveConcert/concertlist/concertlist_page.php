<!DOCTYPE html>
<html>
<head>
<?php include "includes/head.php"; ?>
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
	if($getList = $mysqli->query("call get_recommend_list_by_name($listname)")){
		if(!$row=$getList->fetch_object()){
			echo "no such list";
		}else{
			$createdby = $row->username;
			$describ = $row->ldescription;
			$createtime = $row->lcreatetime;
		}
		$getList->close();
	}
	//if already been followed
	if($followList = $mysqli->query("call is_followed($listname,$username)")){
		if($followList->num_rows > 0 ){
			$followed = true;
			$followList->close();
		}
	}
}
//follow method post
//follow the list
if(isset($_POST['follow']) && $_POST['submit']=='Follow'){
	$followListName = $_POST['follow'];
	if($follow = $mysqli->query("call follow_recommend_list($followListName,$username)")){
		$follow->close();
	}

}
//unfollow the list
if(isset($_POST['unfollow']) && $_POST['submit'] == 'UnFollow'){
	$unFollowListName = $_POST['unfollow'];
	if($unfollow = $mysqli->query("call unfollow_recommenlist($username, $unFollowListName)")){
		$unfollow->close();
	}
}
//delete whole list
if(isset($POST['delete']) && $_POST['submit'] == 'Delete'){
	$deleteListName = $_POST['delete'];
	if($delete = $mysqli->query("call delete_userrecommendlist($username, $deleteListName)")){
		$delete->close();
	} 
	//remove concert
if(isset($_POST['remove_concert']) && $_POST['submit'] == 'Remove'){
	$removeConcertName = $_POST['remove_concert'];
	$listname = $_POST['listname'];
	if($delete = $mysqli->query("call Remove($listname, $removeConcertName,)")){
		$delete->close();
	} 
}

?>

<img src="/LiveConcert/assets/images/<?php echo $listname; ?>.jpg"> <?php echo $listname."<br>".$createdby."<br>".$createtime; ?>


<?php 
//not follow and not created by viewer show follow button
if($username != $createdby){
	if(!$followed){
		echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'><input type='hidden' name='follow' value='$listname'><input type='button' name='submit' value='Follow'></form>";
		//already followed but not the creator show followed button
	}else{
		echo "<button color='grey' type='button'>Followed</button>";
		echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'><input type='hidden' name='unfollow' value='$listname'><input type='button' name='submit' value='UnFollow'></form>";
//creater himself do nothing.
}else{

	echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST' onsubmit='return confirm("."'Are you sure you want to delete?'".");'><input type='hidden' name='delete' value='$listname'><input type='button' name='submit' value='Delete'></form>";
}

echo "<p>$describ</p>";

	if($getConcert = $mysqli->query("call get_recommend_list_concert($listname)")){
		if($getConcert->num_rows > 0){
			while($row = $getConcert->fetch_object()){
				$concert = $row->cname;
				$cdatetime = $row->cdatetime;
				$locname = $row->locname;
				$price = $row->price;
				$cdescrib = $row->cdescription;
				echo "<div><a href='/LiveConcert/assets/images/$concert.jpg'>" ;
				echo $concert."<span>$cdatetime</span>";
				echo $locname;
				echo "</a></div>";
				echo "<div>$cdescrib<div>";
				//remove concert button
				if($username == $createdby){
					echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST' onsubmit='return confirm("."'Are you sure you want to remove?'".");'><input type='hidden' name='listname' value='$listname'><input type='hidden' name='remove_concert' value='$concert'><input type='button' name='submit' value='Remove'></form>";
				}
			}
			$getConcert->close();
		}

	}


?>

</body>
</html>
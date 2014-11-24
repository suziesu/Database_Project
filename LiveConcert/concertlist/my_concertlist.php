<!DOCTYPE html>
<html>
<head>
<?php include "includes/head.php"; ?>
	<title>My Concert List</title>
</head>
<body>
<?php 
$concertname = "";
$username = $_SESSION['username'];
if(isset($_GET['cname'])){
	$concertname = $_GET['cname']);
}

if($_SERVER["REQUEST_METHOD"]=='POST'){
	if(isset($_POST['listname']) && $_POST['submit'] == 'Add'){
		if($addToList = $mysqli->query("call add_to_recommendlist($ml,$concertname)")){
			$addToList->close();
			echo "add successed";
			header("Location: concertlist_page.php?listname=".$ml);
		}
	}
	if(isset($POST['delete']) && $_POST['submit'] == 'Delete'){
		$deleteListName = $_POST['delete'];
		if($delete = $mysqli->query("call delete_userrecommendlist($username, $deleteListName)")){
			$delete->close();
		} 
	}
}

echo "<span><a href='/LiveConcert/concertlist/create_new_list.php'>Create a New List</a></span>";

if($result = $mysqli->query("call my_recommend_list($username)")){
	if($result->num_rows > 0){
		while($row = $result->fetch_object()){
			$ml = $row->listname;
			echo "<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$ml' >";
			if(file_exists("/LiveConcert/assets/images/$ml.jpg")){
				echo "<img src='/LiveConcert/assets/images/$ml.jpg'>";
			}
			echo "$ml</a>";
			$descrip = $row->ldescription;
			echo "<div>$descrip</div>";
			if(isset($_GET['cname'])){
				echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'>";
				echo "<input type='hidden' name='listname' value='$ml'>"
				echo"<input type='button' name='submit' value='Add'></form>";
			}else{
				echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST' onsubmit='return confirm("."'Are you sure you want to delete?'".");'><input type='hidden' name='delete' value='$ml'><input type='button' name='submit' value='Delete'></form>";
			}
		}
	}
	$result->close();
}





?>
</body>
</html>
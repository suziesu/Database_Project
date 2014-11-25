<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
include $path."/LiveConcert/menu/home_menu.php";?>
	<title>My Concert List</title>
</head>
<body>
<?php 
if(isset($_SESSION['error'])){
	echo $_SESSION['error'];
}
unset($_SESSION['error']);
$concertname = "";
$username = $_SESSION['username'];
if(isset($_GET['cname'])){
	$concertname = $_GET['cname'];
}

if($_SERVER["REQUEST_METHOD"]=='POST'){
	if(isset($_POST['listname']) && $_POST['submit'] == 'Add'){
		if($addToList = $mysqli->query("call add_to_recommendlist('$ml','$concertname')") or die($mysqli->error)){
			// $addToList->close();
			// $mysqli->next_result();
			echo "add successed";
			header("Location: concertlist_page.php?listname=".$ml);
		}
	}
	if(isset($POST['delete']) && $_POST['submit'] == 'Delete'){
		$deleteListName = $_POST['delete'];
		if($delete = $mysqli->query("call delete_userrecommendlist('$username', '$deleteListName')") or die($mysqli->error)){
			// $delete->close();
			// $mysqli->next_result();
		} 
	}
}

echo "<div><span><a href='/LiveConcert/concertlist/create_new_list.php'>Create a New List</a></span></div>";

if($result = $mysqli->query("call my_recommend_list('$username')") or die($mysqli->error)){
	if($result->num_rows > 0){
		while($row = $result->fetch_object()){
			$ml = $row->listname;
			echo "<div><ul><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$ml' >";
			if(file_exists("/LiveConcert/assets/images/$ml.jpg")){
				echo "<img src='/LiveConcert/assets/images/$ml.jpg'>";
			}
			echo "$ml</a>";
			$descrip = $row->ldescription;
			echo "<div>$descrip</div>";
			if(isset($_GET['cname'])){
				echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'>";
				echo "<input type='hidden' name='listname' value='$ml'>";
				echo "<input type='submit' name='submit' value='Add'></form></div>";
			}else{
				echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST' onsubmit='return confirm("."'Are you sure you want to delete?'".");'><input type='hidden' name='delete' value='$ml'><input type='submit' name='submit' value='Delete'></form></div>";
			}
		}
	}
	$result->close();
	$mysqli->next_result();
}





?>
</body>
</html>
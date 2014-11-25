<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
include $path."/LiveConcert/menu/home_menu.php";?>
	<title>Edit Band Info</title>
</head>
<body>
<?php 
$username = $_SESSION['username'];
$owner = "";
$baname = "";
$bptime = "";
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
	}
	if($username != $owner){
		echo "you cannot edit";
		header("Location:band_page.php?baname=".$baname);
	}




?>

</body>
</html>
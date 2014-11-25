
<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
include $path."/LiveConcert/menu/home_menu.php";?>
	<title>All Concert List</title>
</head>
<body>
<!-- this will show all the future concert

also has genre type when people click is will show that type of concert

The system recommendation concert will be at explore page -->

<?php
$username = $_SESSION['username'];
//get all typelist
if($alltype = $mysqli->prepare("select typename from Type")){
	$alltype->execute();
	$alltype->bind_result($typename);
	$getAllType = array();
	while($alltype->fetch()){
		array_push($getAllType,$typename);
	}
	$alltype->close();
	echo "<table>";
	foreach ($getAllType as $key) {
		echo "<tr>";
		echo "<td><a href='/LiveConcert/concert/concert_list.php?type=$key'>$key</a><td>";
		if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')")){
			while($row = $allsubtype->fetch_object()){
				$subtypename = $row->subtypename;
				echo "<td><a href='/LiveConcert/concert/concert_list.php?type=$key&subtype=$subtypename'>$subtypename</td>";
			}
			echo "</tr>";
			$allsubtype->close();
			$mysqli->next_result();
		}
	}
	echo "</table>";
}
echo "<div><h3>Concert</h3>";
//get subtype concert
if(isset($_GET['type']) && isset($_GET['subtype'])){
	echo "<div>";
	$subtype = $_GET['subtype'];
	if($subtypeConcert = $mysqli->query("call get_subtype_future_concert('$subtype')") or die($mysqli->error)){
		while($row = $subtypeConcert->fetch_object()){

			$cname = $row->cname;
			$cdatetime = $row->cdatetime;
			$locname = $row->locname;
			$price = $row->price;
			$cdescription = $row->cdescription;

			echo "<ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
			if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
			}
			

			echo "<h4>$cname</h4></a>";
			echo "<span>$locname</span>";
			echo "<span>$cdatetime</span>";
			echo "<span>$price</span>";
			echo "<div>$cdescription</div></ul>";
		}
		$subtypeConcert->close();
		$mysqli->next_result();

	}
	echo "</div>";
//get type concert
}else if(isset($_GET['type'])){
	echo "<div>";
	$type = $_GET['type'];
	if($typeConcert = $mysqli->query("call get_type_future_concert('$type')")){
		while($row = $typeConcert->fetch_object()){
			$cname = $row->cname;
			$cdatetime = $row->cdatetime;
			$locname = $row->locname;
			$price = $row->price;
			$cdescription = $row->cdescription;

			echo "<ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
			if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
			}
			echo "<h4>$cname</h4></a>";
			echo "<span>$locname</span>";
			echo "<span>$cdatetime</span>";
			echo "<span>$price</span>";
			echo "<div>$cdescription</div></ul>";
		}
		$typeConcert->close();
		$mysqli->next_result();
	}
	echo "</div>";
//get all concert
}else{
	echo "<div>";
	if($allConcert = $mysqli->query("call get_all_future_concert()")){
		while($row = $allConcert->fetch_object()){
			$cname = $row->cname;
			$cdatetime = $row->cdatetime;
			$locname = $row->locname;
			$price = $row->price;
			$cdescription = $row->cdescription;

			echo "<ul><a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
			if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
			}
			echo "<h4>$cname</h4></a>";
			echo "<span>$locname</span>";
			echo "<span>$cdatetime</span>";
			echo "<span>$price</span>";
			echo "<div>$cdescription</div></ul>";
		}
		$allConcert->close();
		$mysqli->next_result();
//get
	}
	$mysqli->close();
	echo "</div>";
}
echo "</div>";
?>
</body>
</html>

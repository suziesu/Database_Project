
<!DOCTYPE html>
<html>
<head>
<?php include "includes/head.php"; ?>
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
	foreach ($getAllType as $key) {
		echo "<tr>";
		echo "<a href='/LiveConcert/concert/concert_list.php?type=$key'>$key</a></tr><tr>";
		if($allsubtype = $mysqli->query("call onetypeallsubtype($key)")){
			while($row = $allsubtype->fetch_object()){
				$subtypename = $row->subtypename;
				echo "<td><a href='/LiveConcert/concert/concert_list.php?type=$key&subtype=$subtypename'>$subtypename</td>";
			}
			echo "</tr>";
			$allsubtype->close();
		}
	}	
}
//get subtype concert
if(isset($_GET['type']) && isset($_GET['subtype'])){
	$subtype = $_GET['subtype'];
	if($subtypeConcert = $mysqli->query("call get_subtype_future_concert($subtype)")){
		while($row = $subtypeConcert->fetch_object()){
			$cname = $row->cname;
			$cdatetime = $row->cdatetime;
			$locname = $row->locname;
			$price = $row->price;
			$cdescription = $row->cdescription;

			echo "<a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
			if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
			}
			echo "$cname</a>";
			echo "<span>$locname</span>";
			echo "<span>$cdatetime</span>";
			echo "<span>$price</span>";
			echo "<div>$cdescription</div>";
		}
		$subtypeConcert->close();
	}
//get type concert
}else if(isset($_GET['type'])){
	
	$type = $_GET['type'];
	if($typeConcert = $mysqli->query("call get_type_future_concert($type)")){
		while($row = $typeConcert->fetch_object()){
			$cname = $row->cname;
			$cdatetime = $row->cdatetime;
			$locname = $row->locname;
			$price = $row->price;
			$cdescription = $row->cdescription;

			echo "<a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
			if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
			}
			echo "$cname</a>";
			echo "<span>$locname</span>";
			echo "<span>$cdatetime</span>";
			echo "<span>$price</span>";
			echo "<div>$cdescription</div>";
		}
		$typeConcert->close();
	}
//get all concert
}else{
	if($allConcert = $mysqli->query("call get_all_future_concert()")){
		while($row = $allConcert->fetch_object()){
			$cname = $row->cname;
			$cdatetime = $row->cdatetime;
			$locname = $row->locname;
			$price = $row->price;
			$cdescription = $row->cdescription;

			echo "<a href='/LiveConcert/concert/concert_page.php?cname=$cname' >";
			if(file_exists("/LiveConcert/assets/images/$cname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$cname.jpg'>";
			}
			echo "$cname</a>";
			echo "<span>$locname</span>";
			echo "<span>$cdatetime</span>";
			echo "<span>$price</span>";
			echo "<div>$cdescription</div>";
		}
		$allConcert->close();
		
//get
	}
	$mysqli->close();
}

?>
</body>
</html>

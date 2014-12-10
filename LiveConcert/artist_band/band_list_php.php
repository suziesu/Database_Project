
<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
include "../includes/checklogin.php";
include $path."/LiveConcert/menu/home_menu.php";?>
	<title>Band List</title>
</head>
<body>
<!-- it will show all the band and artist list
also have type people click the that type of band
and also show some recommendation band by system -->

<?php
$username = $_SESSION['username'];
//get all typelist
if($alltype = $mysqli->prepare("select typename from Type") or die($mysqli->error)){
	$alltype->execute();
	$alltype->bind_result($typename);
	$getAllType = array();
	while($alltype->fetch()){
		array_push($getAllType,$typename);
	}
	$alltype->close();
	// $mysqli->next_result();
	echo "<table>";
	foreach ($getAllType as $key) {
		echo "<tr>";
		echo "<td><a href='band_list.php?type=$key'>$key</a></td>";
		if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')") or die($mysqli->error)){
			while($row = $allsubtype->fetch_object()){
				$subtypename = $row->subtypename;
				echo "<td><a href='band_list.php?type=$key&subtype=$subtypename'>$subtypename</td>";
			}
			echo "</tr>";
			$allsubtype->close();
			$mysqli->next_result();
		}
	}	
	echo "</table>";
}

//get subtype band
if(isset($_GET['type']) && isset($_GET['subtype'])){
	echo "<div>";
	$subtype = $_GET['subtype'];
	if($subtypeband = $mysqli->query("call get_subtype_band('$subtype')") or die($mysqli->error)){
		while($row = $subtypeband->fetch_object()){
			$baname = $row->baname;
			$bbio = $row->bbio;
			echo "<ul><a href='/LiveConcert/artist_band/band_page.php?baname=$baname' >";
			if(file_exists("/LiveConcert/assets/images/$baname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$baname.jpg'>";
			}
			echo "<h4>$baname</h4></a>";
			echo "<div>$bbio</div></ul>";
		}
		$subtypeband->close();
		$mysqli->next_result();
	}
	echo "</div>";
//get type band
}else if(isset($_GET['type'])){
	echo "<div>";
	$type = $_GET['type'];
	if($typeband = $mysqli->query("call get_type_band('$type')") or die($mysqli->error)){
		while($row = $typeband->fetch_object()){
			$baname = $row->baname;
			$bbio = $row->bbio;
			echo "<ul><a href='/LiveConcert/artist_band/band_page.php?baname=$baname' >";
			if(file_exists("/LiveConcert/assets/images/$baname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$baname.jpg'>";
			}
			echo "<h4>$baname</h4></a>";
			echo "<div>$bbio</div></ul>";
		}
		$typeband->close();
		$mysqli->next_result();
	}
	echo "</div>";
//get all band
}else{
	echo "<div>";
	if($allband = $mysqli->query("call get_all_band()") or die($mysqli->error)){
		while($row = $allband->fetch_object()){
			$baname = $row->baname;
			$bbio = $row->bbio;
			echo "<ul><a href='/LiveConcert/artist_band/band_page.php?baname=$baname' >";
			if(file_exists("/LiveConcert/assets/images/$baname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$baname.jpg'>";
			}
			echo "<h4>$baname</h4></a>";
			echo "<div>$bbio</div></ul>";
		}
		$allband->close();
		$mysqli->next_result();
	}
	echo "</div>";
//get
}

?>
<div>
	<h2>Recommend Band</h2>
<!-- recommend the band based on the other user, who has similar taste to user, highly rated the bands' concert 
and not a fan of userhimself -->

<?php 
	echo "<div>";
	if($recommendBand = $mysqli->query("call recommend_band_highrated_by_simitaste('$username')") or die($mysqli->error)){
		while($row = $recommendBand->fetch_object()){
			$baname = $row->baname;
			$bbio = $row->bbio;
			echo "<ul><a href='/LiveConcert/artist_band/band_page.php?baname=$baname' >";
			if(file_exists("/LiveConcert/assets/images/$baname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$baname.jpg'>";
			}
			echo "<h4>$baname</h4></a>";
			echo "<div>$bbio</div></ul>";
		}
		$recommendBand->close();
		$mysqli->next_result();
	}
	$mysqli->close();
	echo "</div>";
?>



</div>
</body>
</html>
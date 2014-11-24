
<!DOCTYPE html>
<html>
<head>
<?php include "includes/head.php"; ?>
	<title>Band List</title>
</head>
<body>
<!-- it will show all the band and artist list
also have type people click the that type of band
and also show some recommendation band by system -->

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
		echo "<a href='band_list.php?type=$key'>$key</a></tr><tr>";
		if($allsubtype = $mysqli->query("call onetypeallsubtype($key)")){
			while($row = $allsubtype->fetch_object()){
				$subtypename = $row->subtypename;
				echo "<td><a href='band_list.php?type=$key&subtype=$subtypename'>$subtypename</td>";
			}
			echo "</tr>";
			$allsubtype->close();
		}
	}	
}

//get subtype band
if(isset($_GET['type']) && isset($_GET['subtype'])){
	$subtype = $_GET['subtype'];
	if($subtypeband = $mysqli->query("call get_subtype_band($subtype)")){
		while($row = $subtypeband->fetch_object()){
			$baname = $row->baname;
			$babio = $row->babio;
			echo "<a href='/LiveConcert/artist_band/band_page.php?baname=$baname' >";
			if(file_exists("/LiveConcert/assets/images/$baname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$baname.jpg'>";
			}
			echo "$baname</a>";
			echo "<div>$babio</div>";
		}
		$subtypeband->close();
	}
//get type band
}else if(isset($_GET['type'])){
	
	$type = $_GET['type'];
	if($typeband = $mysqli->query("call get_type_band($type)")){
		while($row = $typeband->fetch_object()){
			$baname = $row->baname;
			$babio = $row->babio;
			echo "<a href='/LiveConcert/artist_band/band_page.php?baname=$baname' >";
			if(file_exists("/LiveConcert/assets/images/$baname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$baname.jpg'>";
			}
			echo "$baname</a>";
			echo "<div>$babio</div>";
		}
		$typeband->close();
	}
//get all band
}else{
	if($allband = $mysqli->query("call get_all_band()")){
		while($row = $allband->fetch_object()){
			$baname = $row->baname;
			$babio = $row->babio;
			echo "<a href='/LiveConcert/artist_band/band_page.php?baname=$baname' >";
			if(file_exists("/LiveConcert/assets/images/$baname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$baname.jpg'>";
			}
			echo "$baname</a>";
			echo "<div>$babio</div>";
		}
		$allband->close();
	}
//get
}

?>
<div>
	<h2>Recommend Band</h2>
<!-- recommend the band based on the other user, who has similar taste to user, highly rated the bands' concert 
and not a fan of userhimself -->

<?php 
	if($recommendBand = $mysqli->query("call recommend_band_highrated_by_simitaste($username)")){
		while($row = $recommendBand->fetch_object()){
			echo $baname = $row->baname;
			echo $babio = $row->babio;
			echo "<a href='/LiveConcert/artist_band/band_page.php?baname=$baname' >";
			if(file_exists("/LiveConcert/assets/images/$baname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$baname.jpg'>";
			}
			echo "$baname</a>";
			echo "<div>$babio</div>";
		}
		$recommendBand->close();
	}
	$mysqli->close();

?>



</div>
</body>
</html>
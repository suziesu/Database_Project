
<!DOCTYPE html>
<html>
<head>
	<?php include "includes/head.php"; ?>
	<title>All Recommendation List</title>
</head>
<body>
<!-- This will show the all the concertrecommend list separate by page
and the system recommend recommedlist with similar taste
then if user click one taste it will show this kind of taste recommendlist  -->
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
		echo "<a href='/LiveConcert/concertlist/concertlist_list.php?type=$key'>$key</a></tr><tr>";
		if($allsubtype = $mysqli->query("call onetypeallsubtype($key)")){
			while($row = $allsubtype->fetch_object()){
				$subtypename = $row->subtypename;
				echo "<td><a href='/LiveConcert/concertlist/concertlist_list.php?type=$key&subtype=$subtypename'>$subtypename</td>";
			}
			echo "</tr>";
			$allsubtype->close();
		}
	}	
}
//get subtype conncertlist
if(isset($_GET['type']) && isset($_GET['subtype'])){
	$subtype = $_GET['subtype'];
	if($subtypeList = $mysqli->query("call get_subtype_list($subtype)")){
		while($row = $subtypeList->fetch_object()){
			$listname = $row->listname;
			$ldiscrip = $row->ldescription;
			$createby = $row->username;
			$createtime = $row->lcreatetime;
			echo "<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname' >";
			if(file_exists("/LiveConcert/assets/images/$listname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$listname.jpg'>";
			}
			echo "$listname</a>";
			echo "<span>$createby</span>";
			echo "<span>$createtime</span>";
			echo "<div>$ldiscrip</div>";
		}
		$subtypeList->close();
	}
//get type concertlist
}else if(isset($_GET['type'])){
	
	$type = $_GET['type'];
	if($typeList = $mysqli->query("call get_type_list($type)")){
		while($row = $typeList->fetch_object()){
			$listname = $row->listname;
			$ldiscrip = $row->ldescription;
			$createby = $row->username;
			$createtime = $row->lcreatetime;
			echo "<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname' >";
			if(file_exists("/LiveConcert/assets/images/$listname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$listname.jpg'>";
			}
			echo "$listname</a>";
			echo "<span>$createby</span>";
			echo "<span>$createtime</span>";
			echo "<div>$ldiscrip</div>";
		}
		$typeList->close();
	}
//get all concertlist
}else{
	if($allList = $mysqli->query("call get_all_list()")){
		while($row = $allList->fetch_object()){
			$listname = $row->listname;
			$ldiscrip = $row->ldescription;
			$createby = $row->username;
			$createtime = $row->lcreatetime;
			echo "<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname' >";
			if(file_exists("/LiveConcert/assets/images/$listname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$listname.jpg'>";
			}
			echo "$listname</a>";
			echo "<span>$createby</span>";
			echo "<span>$createtime</span>";
			echo "<div>$ldiscrip</div>";
		}
		$allList->close();
		
//get
	}
}

?>
<div>
<!-- get user with similar taste's followed recommendlist order by it is followed count -->	
	<h2>Recommend To You</h2>
	<?php
	if($systemRecommend = $mysqli->query("call recommend_list_most_follower_similar_taste($username)")){
		while($row = $systemRecommend->fetch_object()){
			$listname = $row->listname;
			$ldiscrip = $row->ldescription;
			$createby = $row->username;
			$createtime = $row->lcreatetime;
			echo "<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname' >";
			if(file_exists("/LiveConcert/assets/images/$listname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$listname.jpg'>";
			}
			echo "$listname</a>";
			echo "<span>$createby</span>";
			echo "<span>$createtime</span>";
			echo "<div>$ldiscrip</div>";
		}
		$systemRecommend->close();
	}

	$mysqli->close();

	?>

</body>
</html>
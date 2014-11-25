
<!DOCTYPE html>
<html>
<head>
	<?php include "../includes/head.php"; 
	include $path."/LiveConcert/menu/home_menu.php";?>
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
	echo "<table>";
	$alltype->close();
	foreach ($getAllType as $key) {
		echo "<tr>";
		echo "<td><a href='/LiveConcert/concertlist/concertlist_list.php?type=$key'>$key</a></td>";
		if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')")){
			while($row = $allsubtype->fetch_object()){
				$subtypename = $row->subtypename;
				echo "<td><a href='/LiveConcert/concertlist/concertlist_list.php?type=$key&subtype=$subtypename'>$subtypename</td>";
			}
			echo "</tr>";
			$allsubtype->close();
			$mysqli->next_result();
		}
	}
	echo "</table>";	
}
echo "<div><h3>User's Recommendation List</h3>";
//get subtype conncertlist
if(isset($_GET['type']) && isset($_GET['subtype'])){
	echo "<div>";
	$subtype = $_GET['subtype'];
	echo $subtype;
	if($subtypeList = $mysqli->query("call get_subtype_list('$subtype')") or die($mysqli->error)){
		while($row = $subtypeList->fetch_object()){
			$listname = $row->listname;
			$ldiscrip = $row->ldescription;
			$createby = $row->username;
			$createtime = $row->lcreatetime;
			echo "<ul><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname' >";
			if(file_exists("/LiveConcert/assets/images/$listname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$listname.jpg'>";
			}
			echo "<h4>$listname</h4></a>";
			echo "<span>$createby</span>";
			echo "<span>$createtime</span>";
			echo "<div>$ldiscrip</div></ul>";
		}
		$subtypeList->close();
		$mysqli->next_result();
	}
	echo "</div>";
//get type concertlist
}else if(isset($_GET['type'])){
	echo "<div>";
	$type = $_GET['type'];
	if($typeList = $mysqli->query("call get_type_list('$type')")){
		while($row = $typeList->fetch_object()){
			$listname = $row->listname;
			$ldiscrip = $row->ldescription;
			$createby = $row->username;
			$createtime = $row->lcreatetime;
			echo "<ul><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname' >";
			if(file_exists("/LiveConcert/assets/images/$listname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$listname.jpg'>";
			}
			echo "<h4>$listname</h4></a>";
			echo "<span>$createby</span>";
			echo "<span>$createtime</span>";
			echo "<div>$ldiscrip</div></ul>";
		}
		$typeList->close();
		$mysqli->next_result();
	}
	echo "</div>";
//get all concertlist
}else{
	if($allList = $mysqli->query("call get_all_list()")){
		echo "<div>";
		while($row = $allList->fetch_object()){
			$listname = $row->listname;
			$ldiscrip = $row->ldescription;
			$createby = $row->username;
			$createtime = $row->lcreatetime;
			echo "<ul><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname' >";
			if(file_exists("/LiveConcert/assets/images/$listname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$listname.jpg'>";
			}
			echo "<h4>$listname</h4></a>";
			echo "<span>$createby</span>";
			echo "<span>$createtime</span>";
			echo "<div>$ldiscrip</div></ul>";
		}
		$allList->close();
		$mysqli->next_result();
//get
	}
	echo "</div>";
}

?>
</div>
<div>
<!-- get user with similar taste's followed recommendlist order by it is followed count -->	
	<h2>Recommend To You</h2>
	<?php
	if($systemRecommend = $mysqli->query("call recommend_list_most_follower_similar_taste('$username')") or die($mysqli->error)){
		echo "<div>";
		while($row = $systemRecommend->fetch_object()){
			$listname = $row->listname;
			$ldiscrip = $row->ldescription;
			$createby = $row->username;
			$createtime = $row->lcreatetime;
			echo "<ul><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname' >";
			if(file_exists("/LiveConcert/assets/images/$listname.jpg")){
				echo "<img src='/LiveConcert/assets/images/$listname.jpg'>";
			}
			echo "<h4>$listname</h4></a>";
			echo "<span>$createby</span>";
			echo "<span>$createtime</span>";
			echo "<div>$ldiscrip</div></ul>";
		}
		$systemRecommend->close();
	}

	$mysqli->close();
	echo "</div>";
	?>

</body>
</html>
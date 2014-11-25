<!DOCTYPE html>
<html>
<head>
	<?php include "../includes/head.php"; 
	include $path."/LiveConcert/menu/home_menu.php";?>
	<title>Music Genre List</title>
</head>
<body>
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
		echo "<td><a href='/LiveConcert/genre/genre_type_page.php?type=$key'>$key</a></td>";
		if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')")){
			while($row = $allsubtype->fetch_object()){
				$subtypename = $row->subtypename;
				echo "<td><a href='/LiveConcert/genre/genre_type_page.php?subtype=$subtypename'>$subtypename</td>";
			}
			echo "</tr>";
			$allsubtype->close();
			$mysqli->next_result();
		}
	}
	echo "</table>";	
}
?>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
	include $path."/LiveConcert/menu/home_menu.php";?>
	<title>Music Genre</title>
</head>
<body>
<center><h1>Music Genre</h1></center>
<?php 
	if(isset($_GET['subtype'])){
		$subtype = $_GET['subtype'];
		
		if($sub = $mysqli->query("call get_subtype_describ('$subtype')") or die($mysqli->error)){
			echo "<h2>$subtype</h2>";
			if($row = $sub->fetch_object()){
				echo "<div>".$row->subtypedescrip."</div>";
			}
			$sub->close();
			// $mysqli->next_result();
		}
	}
	if(isset($_GET['type'])){
		$type = $_GET['type'];
		if($tp = $mysqli->query("call get_type_describ('$type')") or die($mysqli->error)){
			echo "<h2>$type</h2>";
			if ($row = $tp->fetch_object()){
				echo "<div>".$row->typedecrip."</div>";

			}
			

			$tp->close();
		}
			
			// $mysqli->next_result();
	}

$mysqli->close();
?>
<a href='/LiveConcert/genre/genre_list.php'><button>Go Back To Type List</button></a>
</body>
</html>
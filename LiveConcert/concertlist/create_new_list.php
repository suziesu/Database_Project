<!DOCTYPE html>
<html>
<head>
<?php include "../includes/head.php"; 
	include "../functions/input_text_function.php";
	include "../functions/recommendation_input_check.php";
	include $path."/LiveConcert/menu/home_menu.php";
?>
	<title>Create New List</title>
</head>
<body>
<h1>Create New List</h1>

<div>
<?php
$msg = "";
$descrip = "";
$listname = "";
$username = $_SESSION['username'];
if($_SERVER["REQUEST_METHOD"]=='POST'){
	if($_POST['submit'] == 'Cancel'){
		header("Location:my_concertlist.php");
	}else{
		if(isset($_POST['listname']) && list_name_check($_POST['listname'])){
			$listname = $_POST['listname'];
			
			if(isset($_POST['descrip'])){
				$descrip = $_POST['descrip'];
			}
			if($insertNewList = $mysqli->query("call create_userrecommendlist('$listname','$username','$descrip')") or die ($mysqli->error)){
				header("Location:my_concertlist.php");
			}
		}
	}
	$mysqli->close();
}

?>

<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='POST'>
	<span class="error"><?php echo $msg; ?><br>* Required Field</span><br>
	RecommendList Name:<span class="error">*<?php echo $listnameERR; ?></span><input type='text' name='listname' value='<?php echo htmlentities($listname); ?>'>
	Short Description: <textarea name='descrip' cols='40'rows='5' value='<?php echo htmlentities($descrip); ?>'></textarea>
	<input type='submit' name='submit' value='Submit'>
	<input type='submit' name='submit' value='Cancel'>
</form>
</div>
</body>
</html>
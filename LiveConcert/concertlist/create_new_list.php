<!DOCTYPE html>
<html>
<head>
<?php 
include "../includes/regular_page_head.php";
	include "../functions/input_text_function.php";
	include "../functions/recommendation_input_check.php"; 
   include "../includes/new_head.html"; 
    include "../functions/login_inputcheck.php";
	
?>
	
	<title>Create New List</title>
</head>

<body>

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
			$plus_score = 0.1;
			if($update_user_score = $mysqli->query("call update_user_score_by_review('$username','$plus_score')") or die($mysqli->error)){
				// echo "userscore success";
				$mysqli->next_result();
			}
			if($insertNewList = $mysqli->query("call create_userrecommendlist('$listname','$username','$descrip')") or die ($mysqli->error)){
				header("Location:my_concertlist.php");
			}
		}
	}
	$mysqli->close();
}

?>
<section class='content'>
	<div class="container white-background">
	<h2>Create New List</h2>
	

	<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='POST'>
	<div id='post_concert' class='row'>
		<span class="error"><?php echo $msg; ?><br>* Required Field</span>
		<!-- <table> -->
		<h3>RecommendList Name:</td><td><span class="error">*<?php echo $listnameERR; ?></span></h3>
		<p><input type='text' name='listname' value='<?php echo htmlentities($listname); ?>'></p>

	<!-- </div>		
	<div id='post_concert' class='row'> -->
		<h3>Short Description: </h3>
		<p><textarea name='descrip' cols='40'rows='5' value='<?php echo htmlentities($descrip); ?>'></textarea></p>
		<center><input  id="submit" type='submit' name='submit' value='Submit'>
		<input  id="submit" type='submit' name='submit' value='Cancel'></center>
		<!-- </table> -->
	</div>
	</form>

</div>
</section>
</body>
</html>
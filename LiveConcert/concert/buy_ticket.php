<!DOCTYPE html>
<html>
<head>
<?php 
include "../includes/regular_page_head.php";
include "../includes/new_head.html";
	include "../functions/input_text_function.php";

include "../functions/login_inputcheck.php";?>
	<title>Edit Band Info</title>
</head>
<body>

<?php
$cname = ""; $price=0;
$username = $_SESSION['username'];
$availability = 0;
	if(isset($_GET['cname']) && isset($_GET['price']) && isset($_GET['availability'])){
		$cname = $_GET['cname'];
		$price = $_GET['price'];
		$availability = $_GET['availability'];
	}
if($_SERVER['REQUEST_METHOD']=='POST'){

	$cname = $_POST['cname'];
	$availability = $_POST['availability'];
	if(isset($_POST['quantity']) && $_POST['quantity'] > 0 ){
		$quantity = $_POST['quantity'];
		if($availability < $quantity){
			echo "<h3>Sorry, not enough space";
		}else{
			if($insert_ticket = $mysqli->query("call insert_ticket('$username','$cname','$quantity')")or die($mysqli->error)){
				// echo "ticket sucess";
				$mysqli->next_result();
			}
			//update availability
			if($updateConcertAvai = $mysqli->query("call update_availability('$cname','$quantity')") or die($mysqli->error)){
				// echo "update succes";
				$mysqli->close();
			}
		}
	}
	echo "<h3>Sucess</h3>";
}

?>
<section class='content'>
	<div class="container white-background">

		<div id='user_info' class='row'>
			<a href='/LiveConcert/concert/concert_page.php?cname=<?php echo $cname ?>'><button id='create_concert'>Back To Concert List</button></a>

			<div  class="span7">
				<h3><img src="/LiveConcert/assets/images/<?php echo $cname; ?>.jpg"></h3>
					<center><h3><?php echo $cname; ?></h3></center>

			</div>
		</div>
	<div id='bandmember_info' class='row'>
		<form id="" method="POST" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?cname=".$cname;?>'>
		<p><?php echo $price; ?>
		<input name='quantity' value=''>
		<input name='availability' value='<?php echo $availability; ?>' type='hidden'>
		<input name='cname' value = '<?php echo $cname; ?>' type='hidden'>
		<input id='submit' type='submit' name='submit' value='Buy'></p>
		</form>


</body>
</html>
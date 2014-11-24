<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<?php 
$review =false;
$username = $_SESSION['username'];
$cname="";
$userscore = $_SESSION['score'];
//if concert has not started then no review and star
//if past show "past"
if($_SERVER["REQUEST_METHOD"]=='GET'){
	if(isset($_GET['cname'])){
		$cname = $_GET['cname'];
		if(isset($_GET['review'])){
			$review = true;
		}
		if($userscore > 8)
	}
}else{
	if(isset($_GET['cname'])){
		$cname = $_GET['cname'];
		if(isset($_POST['review'])){
			$reviewSubmit = $_POST['review'];
			if($insertReview = $mysqli->query("call insert_review($username,$cname,$reviewSubmit)")){
				$insertReview->close();
				$review = false;
			}
		}
		if(isset($_POST['rating-input-1'])){
			$stars = count($_POST['rating-input-1']);
			if($insertRating = $mysqli->query("call insert_rating($username,$cname,$stars)")){
				$insertRating->close();
			}

		}
	}else{
		echo "no cname";
	}
	

}


?>
<div id='review'>

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method='POST'>
	<img src='/LiveConcert/assets/images/<?php echo $cname;?>.jpg'>
<!-- if it is a future concert add going plan to option	 -->
<!-- add concert to list -->
	<a href='/LiveConcert/concertlist/my_concertlist.php?cname=<?php echo $cname; ?>'><button>Add To Recommend List</button></a>
<!-- location -->
<!-- ticket -->
	<span class="rating">
	<?php 
	if($getRate = $mysqli->query("call rating_by_user($username,$cname)")){
		if($row = $getRate->fetch_object()){
			$star = $row->rating;
		}else{
			$star = 0;
		}
		$getRate->close();
	}
	for()



	?>
        <input type="radio" class="rating-input"
            id="rating-input-1-5" name="rating-input-1[]">
        <label for="rating-input-1-5" class="rating-star"></label>
        <input type="radio" class="rating-input"
            id="rating-input-1-4" name="rating-input-1[]">
        <label for="rating-input-1-4" class="rating-star"></label>
        <input type="radio" class="rating-input"
            id="rating-input-1-3" name="rating-input-1[]">
        <label for="rating-input-1-3" class="rating-star"></label>
        <input type="radio" class="rating-input"
            id="rating-input-1-2" name="rating-input-1[]">
        <label for="rating-input-1-2" class="rating-star"></label>
        <input type="radio" class="rating-input"
            id="rating-input-1-1" name="rating-input-1[]">
        <label for="rating-input-1-1" class="rating-star"></label>
    </span>
    </form>
    
	<?php  
	if($getReview = $mysqli->query("call concert_review($cname)")){
		if($getReview->num_rows > 0){
			while($row = $getReview->fetch_object()){
				echo $row->username.":".$row->reviewtime."<br>";
				echo $row->review;
			}
		}
		$getReview->close();
	}
	if($review){
		echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'>";
		echo "<textarea id='review' name='review' cols='40'rows='5' ></textarea>
	<input type='button' value='Submit'></form>";

	}
	?>

	
	
</div>
</body>
</html>
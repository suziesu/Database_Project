<!DOCTYPE html>
<html>
<head>
<?php include "includes/head.php";?>
	<title>Admin Artist Verification</title>
</head>
<body>
<?php 
if($_SERVER["REQUEST_METHOD"]=='POST'){
	$verifiedname = $_POST['username'];
	if($_POST['verifyArtist'] == 'approve'){
		
		if($verify = $mysqli->query("call verify_artist($verifiedname)")){
			$verify->close();
		}
	}else if($_POST['verifyArtist'] =='disapprove'){
		if($disverify = $mysqli->query("call dis_verify_artist($verifiedname)")){
			$disverify->close();
		}
	}else{
		$approveERR = "no choice";
	}
	
}

if($unverify = $mysqli->prepare("select username,verifyID from Artist where verifystatus=0")){
	$unverify->execute();
	$unverify->bind_result($username,$verifyID);
	while($unverify->fetch()){
		echo "<form action='verify_artist.php' method='POST'>";
		echo "<img scr=/LiveConcert/assets/img/".$username.".jpg/ height='42' width='42'> $username:$verifyID";
		echo "<input type='hidden' name='username' value='$username'>";
		echo "<input type='button' name='verifyArtist' value='approve'>";
		echo "<input type='button' name='verifyArtist' value='disapprove'>";
		echo "</form>";
		echo $approveERR;
	}
	$unverify->close();
}
$mysqli->close();
?>

</body>
</html>


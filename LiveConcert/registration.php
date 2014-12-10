<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="assets/js/jquery/jquery.js"></script>
	<?php include "includes/new_head.html";
	include "includes/config.php";
	include "functions/input_text_function.php";
	include "functions/login_inputcheck.php";
?>
	<link rel="stylesheet" type="text/css" href="/LiveConcert/assets/css/main1.css">
</head>
<body>
<!-- <style type="background:url(/LiveConcert/assets/images/background_2.jpg)"></style> -->
<center><h2 id='registitle'>LIVE <img src="/LiveConcert/assets/images/Trecord1.gif"> CONCERT</h2>
<h3>Registration</h3>
<?php 
$nameinput = "";
$dobinput = "";
$emailinput = "";
$cityinput = "";
$usernameinput = "";


if($_SERVER['REQUEST_METHOD']=='POST'){
	$usernameinput = username_entered($_POST['username']);
	$nameinput =name_entered($_POST['name']); 
	$dobinput = dob_entered($_POST['dob']); 
	$emailinput = email_entered($_POST['email']);
	$cityinput = city_entered($_POST['city']);
	$passwordinput = password_valid($_POST['password']);
		//insert to user table
//check user infomation
	if($usernameinput && $nameinput && $passwordinput && $dobinput && $emailinput && $cityinput){
		//insert to user table
		
		if(!find_user_by_username($_POST['username'])){
			echo $dobinput;
			if($insertUser = $mysqli->query("call insert_user('$usernameinput','$nameinput','$passwordinput','$dobinput','$emailinput','$cityinput')") or die($mysqli->error)){
				$_SESSION['username'] = $usernameinput;
				$_SESSION['loggedin'] = true;
				$_SESSION['score'] = 0;
				$_SESSION['city'] = $cityinput;
				// $insertUser->close();
				if($loginrecord = $mysqli->query("call loginrecord('$usernameinput')")){
					// $loginrecord->close();
				}
				if(!empty($_POST['subtype'])){
					foreach($_POST['subtype'] as $value) {
						$type_subtype = explode('|', $value);
						if($insertUserTaste = $mysqli->query("call insert_usertaste('$usernameinput','$type_subtype[0]','$type_subtype[1]')")){
							echo "insert user taste success";
							// $insertUserTaste->close();
						}
					}
				}

				//if user is artist insert to artist table
				if(!empty($_POST['verifyID'])){
					$pass =  $_POST['verifyID'];
					if($idinput = verifyID($pass)){
						echo "234";
						echo $idinput;
						$banameinput = "";
						$allowpost = 0;
						if(!empty($_POST['banameInDB']) && $_POST['banameInDB']!='Find Your Band'){
								$banameinput = $_POST['banameInDB'];
						}else if(!empty($_POST['baname'])){
								$banameinput = $_POST['baname'];
						}else{
							$banameinput = "";
						}
						if(!empty($_POST['allowpost'])){
							$allowpost = 1;
						}
						if($insertArtist = $mysqli->query("call insert_artist('$usernameinput','$idinput','$banameinput',$allowpost)") or die($mysqli->error)){
							// $insertArtist->close();
							$mysqli->next_result();
							header("Location: index.php");
						}
					}
				}else{
					echo $verifyIDERR;
					header("Location: index.php");
				}
				
				
			}else{
				echo "insert error";
			}
		}

		//insert to user state table
			
	}
}

?>
<!-- <div class='registration'></div> -->
<section class='content'>
	<div class="container white-background " id='registration'>
	<div id="registitle"></div>

		<div  class='row'> 
<form  method="POST" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>

			<div class="span5">

				<span >* Required Field</span>
				<p>Username: * <span><?php echo $usernameERR; ?></span></p>
				<p><input type="text" name="username" value="<?php echo htmlentities($usernameinput); ?>" placeholder="less than 30 chars"></p>
				<p>Name: <span >* <?php echo $nameERR; ?></span></p>
				<p><input type="text" name="name" value="<?php echo $nameinput; ?>" placeholder="Real Name"></p>
				<p>Password: <span >* <?php echo $passwordERR; ?></span></p>
				<p><input type="password" name="password" placeholder="only letters and numbers"></p>
				<p>DOB: <span >* <?php echo $dobERR; ?></span></p>
				<p><input type="text" name="dob" value='<?php echo htmlentities($dobinput); ?>' placeholder="2014-11-11"></p>

				<p>Email: <span>* <?php echo $emailERR; ?></span></p>
				<p><input type="text" name="email" value="<?php echo htmlentities($emailinput); ?>" placeholder="Email Address"></p>
				<p>City: <span>* <?php echo $cityERR; ?></span></p>
				<p><input type="text" name="city" value="<?php echo htmlentities($cityinput); ?>" placeholder="city name"></p>
			</div>
			<div class="span5">
				<p>Music Genre You like:</p>
	
	
	
	<?php 
	if($alltype = $mysqli->prepare("select typename from Type")){
		$alltype->execute();
		$alltype->bind_result($typename);
		$getAllType = array();
		while($alltype->fetch()){
			array_push($getAllType,$typename);
		}
		$alltype->close();
		// for($x = 0; $x < count($getAllType); $x++){
		foreach($getAllType as $key){
			// $key = $getAllType[$x];
			echo "<ul><p><span id='type'><input id='typename' type='checkbox' name='typename[]' value='$key'>$key:</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

			if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')") or die($mysqli->error)){
				// echo "<p>";
				while($row = $allsubtype->fetch_object()){
					$subtypename = $row->subtypename;
					echo "<input id='$key' type='checkbox' name='subtype[]' value='$key".'|'."$subtypename'>&nbsp;$subtypename&nbsp;&nbsp;";
				}
				echo "</p></ul>";
				$allsubtype->close();
				$mysqli->next_result();
			}
		}
		// echo "</table>";		
	}

	?>
	</div>
	<div  class='row'> 
	<p><input id="artistcheck" type="checkbox" name="artist" value="">If you are an artist	</p>
	<div id='artist'>
	<p>	VerifyID: <span>* <?php echo $verifyIDERR; ?></span>
	<input type="text" name="verifyID" placeholder="10 Chars"/><p>

		<p>Band name: 
		<select name ='banameInDB'>
		<option>Find Your Band</option>
		<?php 
			if($allBand = $mysqli->prepare("select baname from Band")){
				$allBand->execute();
				$allBand->bind_result($baname);
				while($allBand->fetch()){
					if(htmlentities($banameinput) == $baname){
						echo "<option value ='$baname' selected>$baname</option>";
					}else{
						echo "<option value='$baname' >$baname</option>";
					}
				}
			}
			$mysqli->close();
		?></p>
		</select><br>
		<p>If not exist, please type your bandname:</p>
		<p><input type="text" name="baname" placeholder=""></p>
	<p>	<input type="checkbox" name="allowpost" value='allow' checked='checked'>Allow Us to Post Concert</p>
</div>
		</div>
		</div>
	<input class="login_button" type="submit" name="registration" value="registration">

	</form>
	</div>
</section>


	
	<script type="text/javascript">
	$(document).ready(function(){
		$('#artist').hide();
		$('#artistcheck').click(function(){
			if($('#artistcheck').prop('checked')){
				alert($(this).prop('checked'));
				$('#artist').fadeIn();
			}else{
				$('#artist').hide();
			}
		});
		
		$("input[id='typename']").each(function(){
			$(this).click(function(){
				var checked = $(this).prop('checked');
				var name = $(this).val();
				$("input[id='"+name+"']").each(function(i,o){
					$(this).prop('checked',checked);
				});
			});
		});
	});
	</script>

</form>

</body>
</html>



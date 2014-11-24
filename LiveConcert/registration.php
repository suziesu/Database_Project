<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="assets/js/jquery/jquery.js"></script>
	<?php include "includes/head.php";
	include "functions/input_text_function.php";
	include "functions/login_inputcheck.php";
	// include "function.php";
?>
</head>
<body>
<center><h1>LiveConcert</h1></center>
<h2>Registration</h2>
<?php 
if($_SERVER['REQUEST_METHOD']=='POST'){

	if($usernameinput = username_entered($_POST['username']) && !find_user_by_username($_POST['username']) && $nameinput =name_entered($_POST['name']) && $passwordinput = password_valid($_POST['password']) && $dobinput = dob_enetered($_POST['dob']) && $emailinput = email($_POST['email']) && $cityinput = city_entered($_POST['city']) ){
		if($insertUser = $mysqli->query("call insert_user($usernameinput,$nameinput,$passwordinput,$dobinput,$emailinput,$cityinput)")){
			echo "base user info success";
			$insertUser->close();
		}

		if(!empty($_POST['subtype'])){
			foreach ($_POST['subtype'] as $value) {
				$type_subtype = explode('|', $value);
				if($insertUserTaste = $mysqli->query("call insert_usertaste($usernameinput,$type_subtype[0],$type_subtype[1])")){
					echo "insert user taste success";
					$insertUserTaste->close();
				}
			}
		}

		if(!empty($_POST['artist'])){
			if($idinput = verifyID($_POST['verifyID'])){
				$banameinput = "";
				$allowpost = 0;
				if(!empty($_POST['banameInDB'])){
						$banameinput = $_POST['banameInDB'];
				}else if(!empty($_POST['baname'])){
						$banameinput = $_POST['baname'];
				}else{
					$banameinput = "";
				}
				if(!empty($_POST['allowpost'])){
					$allowpost = 1;
				}
				if($insertArtist = $mysqli->query("call insert_artist($usernameinput,$idinput,$banameinput,$allowpost)")){
					$insertArtist->close();
				}
			}
		}
		
	}
}

?>
<form id="login-register" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<span class="error"><?php echo $msg; ?><br>* Required Field</span><br>
	Username: <span class="error">* <?php echo $usernameERR; ?></span><input type="text" name="username" value="<?php echo htmlentities($usernameinput) ?>" placeholder="less than 30 chars">
	Name: <span class="error">* <?php echo $nameERR; ?></span><input type="text" name="name" value="<?php echo htmlentities($nameinput) ?>" placeholder="Real Name">
	Password: <span class="error">* <?php echo $passwordERR; ?></span><input type="password" name="password" placeholder="only letters and numbers">
	DOB: <span class="error">* <?php echo $dobERR; ?></span><input type="text" name="dob" value="<?php echo htmlentities($dobinput) ?>" placeholder="11/11/2014">

	Email: <span class="error">* <?php echo $emailERR; ?></span><input type="text" name="email" value="<?php echo htmlentities($emailinput) ?>" placeholder="Email Address">
	City: <span class="error">* <?php echo $cityERR; ?></span><input type="text" name="city" value="<?php echo htmlentities($cityinput) ?>" placeholder="city name">
	Music Genre You like:<br>
	<table>
	<?php 
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
			echo "<input id='typename' type='checkbox' name='typename[]' value='$key'>$key</tr><tr>";
			if($allsubtype = $mysqli->query("call onetypeallsubtype($key)")){
				while($row = $allsubtype->fetch_object()){
					$subtypename = $row->subtypename;
					echo "<td><input id='$key' type='checkbox' name='subtype[]' value='$key".'|'."$subtypename'>$subtypename</td>";
				}
				echo "</tr>";
				$allsubtype->close();
			}
		}
		$mysqli->close();
		
	}


	 ?>
	 </table>
	<input id="artistcheck" type="checkbox" name="artist" value="">If you are an artist<br>
	<div id='artist'>
		VerifyID: <span class="error">* <?php echo $verifyIDERR; ?></span><input type="text" name="verifyID" placeholder="10 digit">

		Band name: 
		<select name = 'banameInDB'>
		<?php 
			if($allBand = mysqli->prepare("select baname from Band")){
				$allBand->execute();
				$allBand->bind_result($baname);
				while($allBand->fetch()){
					if(htmlentities($banameinput) == $baname){
						echo "<option value ='$baname' selected>$baname</option>";
					}else{
						echo "<option value ='$baname >$baname</option>";
					}
				}
			}

		?>
		</select><br>
		If not exist, please type your bandname:
		<input type="text" name="baname" placeholder="">
		<input type="checkbox" name="allowpost" value='allow' checked='checked'>Allow Us to Post Concert
	</div>
	<input class="login_button" type="submit" name="registration" value="registration">

	<script type="text/javascript">
	$(document).ready(function(){
		$('artist').hide();
		if ($('#artistcheck').prop('checked')){
			$('#artist').fadeIn();
		}else{
			$('#artist').fadeOut();
		}
		$('#typename').click(function(){
			$('#'+$(this).val()).prop('checked',$(this).prop(checked));
		});
	}
	</script>
</form>

</body>
</html>



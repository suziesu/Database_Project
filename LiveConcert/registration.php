<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="assets/js/jquery/jquery.js"></script>
	<?php include "includes/head.php";
	include "functions/input_text_function.php";
	include "functions/login_inputcheck.php";
?>
</head>
<body>
<center><h1>LiveConcert</h1></center>
<h2>Registration</h2>
<?php 
$nameinput = "";
$dobinput = "";
$emailinput = "";
$cityinput = "";
$usernameinput = "";


if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['username'])){
		$usernameinput = $_POST['username'];
	}
	if(isset($_POST['name'])){
		$nameinput =$_POST['name']; 
	}
	if(isset($_POST['dob'])){
		$dobinput = $_POST['dob']; 
	}
	if(isset($_POST['email'])){
		$emailinput = $_POST['email'];
	}
	if(isset($_POST['city'])){
		$cityinput = $_POST['city'];
	}
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
						if($insertArtist = $mysqli->query("call insert_artist('$usernameinput','$idinput','$banameinput',$allowpost)")){
							$insertArtist->close();
						}
					}
				}
				
				header("Location: home.php");
			}else{
				echo "insert error";
			}
		}

		//insert to user state table
			
	}
}

?>
<form id="login-register" method="POST" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
	<span class="error"><br>* Required Field</span><br>
	Username: <span class="error">* <?php echo $usernameERR; ?></span><input type="text" name="username" value="<?php echo htmlentities($usernameinput); ?>" placeholder="less than 30 chars">
	Name: <span class="error">* <?php echo $nameERR; ?></span><input type="text" name="name" value="<?php echo $nameinput; ?>" placeholder="Real Name">
	Password: <span class="error">* <?php echo $passwordERR; ?></span><input type="password" name="password" placeholder="only letters and numbers">
	DOB: <span class="error">* <?php echo $dobERR; ?></span><input type="text" name="dob" value='<?php echo htmlentities($dobinput); ?>' placeholder="2014-11-11">

	Email: <span class="error">* <?php echo $emailERR; ?></span><input type="text" name="email" value="<?php echo htmlentities($emailinput); ?>" placeholder="Email Address">
	City: <span class="error">* <?php echo $cityERR; ?></span><input type="text" name="city" value="<?php echo htmlentities($cityinput); ?>" placeholder="city name">
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
		// for($x = 0; $x < count($getAllType); $x++){
		foreach($getAllType as $key){
			// $key = $getAllType[$x];
			echo "<table><col align='left'><tr><td><input id='typename' type='checkbox' name='typename[]' value='$key'>$key: &nbsp;</td>";

			if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')") or die($mysqli->error)){
				// echo "<tr><td>&nbsp;</td>";
				while($row = $allsubtype->fetch_object()){
					$subtypename = $row->subtypename;
					echo "<td><input id='$key' type='checkbox' name='subtype[]' value='$key".'|'."$subtypename'>$subtypename</td>";
				}
				echo "</tr>";
				$allsubtype->close();
				$mysqli->next_result();
			}
		}
		echo "</table>";		
	}

	?>
	 </table>
	<input id="artistcheck" type="checkbox" name="artist" value="">If you are an artist<br>
	<div id='artist'>
		VerifyID: <span class="error">* <?php echo $verifyIDERR; ?></span><input type="text" name="verifyID" placeholder="10 Chars">

		Band name: 
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
		?>
		</select><br>
		If not exist, please type your bandname:
		<input type="text" name="baname" placeholder="">
		<input type="checkbox" name="allowpost" value='allow' checked='checked'>Allow Us to Post Concert
	</div>
	<input class="login_button" type="submit" name="registration" value="registration">

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



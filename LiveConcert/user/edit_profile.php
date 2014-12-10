<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="assets/js/jquery/jquery.js"></script>
<?php 
include "../includes/regular_page_head.php";
include "../includes/new_head.html";
include "../functions/input_text_function.php";

include "../functions/login_inputcheck.php";?>
	<!-- <link rel="stylesheet" type="text/css" href="/LiveConcert/assets/css/main1.css"> -->
</head>
<body>

<?php 
$username = "";
$name = "";
$dob = "";
$email= "";
$city= "";
function find_user_by_uname($username){
	global $nameERR,$mysqli;
	
	if($getuser = $mysqli->query("call find_user_byname('$username')") or die($mysqli->error)){
		if($getuser->num_rows > 0){
			$getuser->close();
			$mysqli->next_result();
			$nameERR = "User name exists, please change a new one";
			return true;
		}else{
			$getuser->close();
			$mysqli->next_result();
			return false;
		}
	}
}

if(isset($_GET['username'])){
		$username = $_GET['username'];
		if($userinfo = $mysqli->query("call find_user_byname('$username')") or die($mysqli->error)){
			if($row = $userinfo->fetch_object()){
				$name = $row->name;
				$dob = $row->dob;
				$email = $row->email;
				$city=$row->city;
			}
			$userinfo->close();
			$mysqli->next_result();
		}
	}
if(isset($_GET['name'])){
		 $name = $_GET['name'];
		 if($userinfo2 = $mysqli->query("call find_user_byname('$username')") or die($mysqli->error)){
		 	if($row = $userinfo2->fetch_object()){
		 		$name = $row->name;
		 	}
		 	$userinfo2->close();
		 	$mysqli->next_result();
		 }
}
if(isset($_GET['dob'])){
		 $dob = $_GET['dob'];
		 if($userinfo3 = $mysqli->query("call find_user_byname('$username')") or die($mysqli->error)){
		 	if($row = $userinfo3->fetch_object()){
		 		$dob= $row->dob;
		 	}
		 	$userinfo3->close();
		 	$mysqli->next_result();
		 }
}
if(isset($_GET['email'])){
		 $email = $_GET['email'];
		 if($userinfo4 = $mysqli->query("call find_user_byname('$username')") or die($mysqli->error)){
		 	if($row = $userinfo4->fetch_object()){
		 		$email= $row->email;
		 	}
		 	$userinfo4->close();
		 	$mysqli->next_result();
		 }
}
if(isset($_GET['city'])){
		 $city = $_GET['city'];
		 if($userinfo4 = $mysqli->query("call find_user_byname('$username')") or die($mysqli->error)){
		 	if($row = $userinfo4->fetch_object()){
		 		$city= $row->city;
		 	}
		 	$userinfo4->close();
		 	$mysqli->next_result();
		 }
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['username'])){
		$username = $_GET['username'];
		$new_username=$_POST['username'];
		$name = null_allowed_input($_POST['name']);
		$dob = null_allowed_input($_POST['dob']);
		$email= null_allowed_input($_POST['email']);
		$city= null_allowed_input($_POST['city']);
		echo $city;
	$subtypenamearray = array();
	if(isset($_POST['subtype'])){
		$subtypenamearray = $_POST['subtype'];
		echo ",".join($subtypenamearray);
	}
	if($username!=$new_username && find_user_by_uname($new_username)){
		$berr= "username alreayexists!";
	}else{
	
	//delete first
		if($delete_BT = $mysqli->query("call delete_user_bandtype('$username')") or die($mysqli->error)){
			echo "success";
			$mysqli->next_result();
		}
		//updsate
		if($update_desc = $mysqli->query("call update_user_info('$username','$new_username','$name','$dob','$email','$city')") or die($mysqli->error)){
			echo "upsuccess";
		}

		if(!empty($_POST['subtype'])){
			foreach($_POST['subtype'] as $value) {
				$type_subtype = explode('|', $value);
				if($insertUserTaste = $mysqli->query("call insert_usertest('$new_username','$type_subtype[0]','$type_subtype[1]')")){
					// $insertUserTaste->close();
					$mysqli->next_result();
				}
			}
		}
		header("Location:/LiveConcert/user/user_page.php?username=".$username);
	}
}
?>


<section class='content'>
	<div class="container white-background">
		<h2>Edit Profile</h2>
		<div id='user_info' class='row'>
			<a href='/LiveConcert/user/user_page.php?username=<?php echo $username; ?>'><button id='create_concert'>Back To Profile</button></a>

			<div  class="span7">
				<h3><img src="/LiveConcert/assets/images/<?php echo $username; ?>.jpg"></h3>
					<center><h3><?php echo $username; ?></h3></center>

			</div>
		</div>



<div id='bandmember_info' class='row'>
<form id="login-register" method="POST" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?username=".$username;?>'>


		<div  class="span3">
			<p><!-- <span class="error"> -->* Required Field</span></p>
			<h3>Username::</h3>
			<h3>Name:</h3>
			<h3>DOB:</h3>
			<h3>Email:</h3>
			<h3>City:</h3>
			<h3>Music Genre You like:</h3>
			<!-- <h3></h3>
			<h3></h3>
			<h3></h3>
			<h3></h3>
			<h3></h3>
			<h3></h3> -->
		</div>
		<div class="span7">
		<p></p>	
	
	<h3><?php echo "$username"?></h3>
	<h3><input type="text" name="name" value="<?php echo $name; ?>" ><span class="error">* <?php echo $name; ?></span></h3>
	<h3><input type="text" name="dob" value='<?php echo $dob; ?>' ><span class="error">* <?php echo $dob; ?></span></h3>
	<h3><input type="text" name="email" value="<?php echo $email; ?>" ><span class="error">* <?php echo $email; ?></span></h3>
	<h3><input type="text" name="city" value="<?php echo $city; ?>" ><span class="error">* <?php echo $city; ?></span></h3>
	<table>
			<?php 
			$band_pre_type = array();
		if($bandtp = $mysqli->query("call get_user_taste('$username')") or die($mysqli->error)){
			while($row = $bandtp->fetch_object()){
				$band_pre_type[] = $row->subtypename;
			}
			$bandtp->close();
			$mysqli->next_result();
		}

	if($allBandType = $mysqli->prepare("select typename from Type")){
		$allBandType->execute();
		$allBandType->bind_result($typename);
		$Bandtype= array();
		while($allBandType->fetch()){
			array_push($Bandtype,$typename);
		}
		$allBandType->close();
		foreach($Bandtype as $key){
			echo "<p>$key: &nbsp;</p>";

			if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')") or die($mysqli->error)){
				// echo "<tr><td>&nbsp;</td>";
				// echo "<p>";
				echo "<li>";
				while($row = $allsubtype->fetch_object()){
					$subtypename = $row->subtypename;
					
					if(in_array($subtypename, $band_pre_type)){
						echo "<input id='$key' type='checkbox' name='subtype[]' value='$key".'|'."$subtypename' checked/>$subtypename&nbsp;&nbsp;&nbsp;";

					}else{
						echo "<input  id='$key' type='checkbox' name='subtype[]' value='$key".'|'."$subtypename'/>$subtypename&nbsp;&nbsp;&nbsp;";

					}
				}
				echo "</li>";
				$allsubtype->close();
				$mysqli->next_result();
			}
		}
		echo "</table>";
	}

	?>
	</div>
	<input type='hidden' name='username' value='<?php echo $username;?>'>
	<input style="float:right" id='submit' class="login_button" type="submit" name="registration" value="Update" >

</form>

	</div>
</div>


</section>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#artist').hide();
		$('#artistcheck').click(function(){
			if($('#artistcheck').prop('checked')){
				// alert($(this).prop('checked'));
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



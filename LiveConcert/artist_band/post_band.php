<!DOCTYPE html>
<html>
<head>
<?php 
include "../includes/regular_page_head.php";
include "../includes/new_head.html";
	include "../functions/input_text_function.php";

include "../functions/login_inputcheck.php";
?>
 	
	<title>Post a new Band</title>
</head>
<body>
<?php 
$username = $_SESSION['username'];
$baname="";
$bbio = "";
$score = $_SESSION['score'];
$option="";
function find_band_by_baname($baname){
	global $nameERR,$mysqli;
	
	if($getband = $mysqli->query("call get_band_info('$baname')") or die($mysqli->error)){
		if($getband->num_rows > 0){
			$getband->close();
			$mysqli->next_result();
			$nameERR = "Band name exists, please change a new one";
			return true;
		}else{
			$getband->close();
			$mysqli->next_result();
			return false;
		}
	}
}


if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['baname'])){

	$baname = name_entered($_POST['baname']);
	$bbio = null_allowed_input($_POST['bbio']);
	$bandmemberarray = array();
		if(isset($_POST['bandmember'])){
		$bandmemberarray = $_POST['bandmember'];

	}
	$typenamearray = array();
		if(isset($_POST['bandtype'])){
		$typenamearray = $_POST['bandtype'];
	}
	$subtypenamearray = array();
		if(isset($_POST['subtype'])){
		$subtypenamearray = $_POST['subtype'];
	}

	if($baname  && !find_band_by_baname($baname)){
		if($score >= 10){
		//insert concert into Concert
			if($insertBand = $mysqli->query("call insert_band('$baname','$bbio','$username')") or die($mysqli->error)){
				echo "<h3>You have already post a new band successfully!</h3>";

				// $insertBand->close();
				// $mysqli->next_result();
				foreach ($bandmemberarray as $key) {
					# code...
					if($insertBM = $mysqli->query("call insert_bandmember('$baname','$key')") or die($mysqli->error)){
						// $insertBM->close();
						// $mysqli->next_result();

					}
				}
				if(!empty($_POST['subtype'])){
					foreach($_POST['subtype'] as $value) {
						$type_subtype = explode('|', $value);
						if($insertUserTaste = $mysqli->query("call insert_bandtype('$baname','$type_subtype[0]','$type_subtype[1]')")){
							// $insertUserTaste->close();
						}
					}
				}
				header("Location:/LiveConcert/user/user_page.php?username=".$username);
			}
				
		}else	{
			echo "You donot have permission to post a band！";
		}	
	}
}


?>


<section class='content'>
	<div class="container white-background">
	<h2>Post a Band</h2>
<!-- <div id='user_info' class='row'> -->
		<div id='post_concert' class='row'>
			<center>	
				<form id="" method="POST" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
				<table>
				<tr><th></th></tr>
				<tr><td>Band Name:</td><td><input type='text' name='baname' value='<?php echo $baname; ?>'><span class="error">* <?php echo $nameERR; ?></td></tr>
				<tr><td>Band Description:</td><td><textarea name='bbio' rows="8" cols="50" value='<?php echo $bbio; ?>'></textarea></td></tr>


				<tr><td>Band Memeber
					<img id='addNewOne' src='/LiveConcert/assets/images/add_black_button.png' width='20' height='20'></td><td>

				<div id='bandmember_div'>
				<span id='band_member'>
					<input type='text' name='bandmember[]'>
					<br>
					<br>
				</span>
				</div>
				</td></tr>


				<tr><td>Band Type</td><td>
				<div id='bandtype_div'>
				<span id='band_type'>
					<table>
					<?php 

					if($allBandType = $mysqli->prepare("select typename from Type")){
						$allBandType->execute();
						$allBandType->bind_result($typename);
						$Bandtype= array();
						while($allBandType->fetch()){
							array_push($Bandtype,$typename);
						}
						$allBandType->close();
						foreach($Bandtype as $key){
							echo "<ul><col align='left'><tr><td><label id='typename' name='typename[]' value='$key'>$key: &nbsp;</td></ul>";

							if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')") or die($mysqli->error)){
								// echo "<tr><td>&nbsp;</td>";
								// echo "<li>";
								while($row = $allsubtype->fetch_object()){
									$subtypename = $row->subtypename;
									echo "<td><input id='$key' type='checkbox' name='subtype[]' value='$key".'|'."$subtypename'>$subtypename</td>";
								}
								// echo "</li>";
								$allsubtype->close();
								$mysqli->next_result();
							}
						}
						// echo "</table>";
					}
					?>
					</table>

				<!-- 	<select id='bandtypeoption' name='bandtypeoption'>
					<option value='' >Choose A Band Type</option>
					<?php 
					$Bandtype= array();

					if($allBandType = $mysqli->prepare("select typename from Type")){
						$allBandType->execute();
						$allBandType->bind_result($typename);
						while($allBandType->fetch()){
							echo "<option value='$typename' >$typename</option>";
						}
					}
					?>
					</select>
					<?php
				    $option= $_POST['bandtypeoption'];
				    echo $option;
				    ?>
					<select id='subtypeoption' name='subtypeoption'>
					<option value='' >Choose A Subtype</option>
					<?php 
					$Bandsubtype = array();
					if($allSubtype = $mysqli->prepare("select subtypename from Subtype where typename = $option ")){
						$allSubtype->execute();
						$allSubtype->bind_result($subtypename);
						
						while($allSubtype->fetch()){
							echo "<option value='$subtypename' >$subtypename</option>";
						}
					}	
					?>
					</select>  -->
					<br>
					<br>
				</span>
				</div>
					<!-- <img id='addNewTwo' src='/LiveConcert/assets/images/add_black_button.png' width='20' height='20'>
				</td></tr> -->

				<tr><td></td><td><input  style="float:right"  id='submit' type='submit' name='submit' value='Post'></td></tr>
				</table>
				</form></center>
				</div>
	</div>
</section>
<script src="../assets/js/jquery/jquery.js"></script> 
	<script src="../assets/js/moment.js"></script> 
	<script src="../assets/js/combodate.js"></script> 
<script type="text/javascript">

	$("img[id='addNewOne']").each(function(){
		$(this).click(function(){
			$('#bandmember_div').append($("#band_member").clone());
			// $(this).hide();
		});
	});
	// $("img[id='addNewTwo']").each(function(){
	// 	$(this).click(function(){
	// 		$('#bandtype_div').append($("#band_type").clone());
	// 	});
	// });

</script>
</body>
</html>
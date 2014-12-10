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
$username = $_SESSION['username'];
$score = $_SESSION['score'];
$owner = "";
$baname = "";
$bptime = "";
$bbio= "";
$berr="";
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
if(isset($_GET['baname'])){
		$baname = $_GET['baname'];
		if($bandinfo = $mysqli->query("call get_band_info('$baname')") or die($mysqli->error)){
			if($row = $bandinfo->fetch_object()){
				$bbio = $row->bbio;
				$owner = $row->postby;
				$bptime = $row->bptime;
			}
			$bandinfo->close();
			$mysqli->next_result();
		}
	}
	if($username != $owner){
		// echo "you cannot edit";
		header("Location:band_page.php?baname=".$baname);
}
if(isset($_GET['bbio'])){
		 $bbio = $_GET['bbio'];
		 if($bandinfo2 = $mysqli->query("call get_band_bio('$baname')") or die($mysqli->error)){
		 	if($row = $bandinfo2->fetch_object()){
		 		$bbio = $row->bbio;
		 	}
		 	$bandinfo2->close();
		 	$mysqli->next_result();
		 }
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['baname'])){
	$baname = $_GET['baname'];
    $new_baname = $_POST['baname'];
	$bbio = null_allowed_input($_POST['bbio']);
	$bandmemberarray = array();
	if(isset($_POST['bandmember'])){
		$bandmemberarray = $_POST['bandmember'];

	}
	$subtypenamearray = array();
	if(isset($_POST['subtype'])){
		$subtypenamearray = $_POST['subtype'];
	}
	if($baname !=$new_baname && find_band_by_baname($new_baname)){
		$berr= "bandname alreayexists!";
	}else{
	// $typenamearray = array();
	// 	if(isset($_POST['bandtype'])){
	// 	$typenamearray[] = $_POST['bandtype'];
	// }
	
	//delete first
		if($delete_BM = $mysqli->query("call delete_band_member('$baname')") or die($mysqli->error)){
			// echo "success";
			$mysqli->next_result();
		}
		if($delete_BT = $mysqli->query("call delete_band_type('$baname')") or die($mysqli->error)){
			// echo "success";
			$mysqli->next_result();
		}
		//update
		if($update_desc = $mysqli->query("call update_band_info('$baname','$new_baname','$bbio','$username')") or die($mysqli->error)){
			// echo "upsuccess";
		}

	//insert
		foreach ($bandmemberarray as $key) {
						# code...
			if($insertBM = $mysqli->query("call insert_bandmember('$new_baname','$key')") or die($mysqli->error)){
				// echo "success";
				$mysqli->next_result();
			}
		}
		if(!empty($_POST['subtype'])){
			foreach($_POST['subtype'] as $value) {
				$type_subtype = explode('|', $value);
				if($insertUserTaste = $mysqli->query("call insert_bandtype('$new_baname','$type_subtype[0]','$type_subtype[1]')")){
					// $insertUserTaste->close();
					$mysqli->next_result();
				}
			}
		}
		header("Location:/LiveConcert/artist_band/band_page.php?baname=".$new_baname);
	}
	
}

	

	
	
?>

<section class='content'>
	<div class="container white-background">

		<div id='user_info' class='row'>
			<a href='/LiveConcert/artist_band/band_list.php'><button id='create_concert'>Back To Band List</button></a>

			<div  class="span7">
				<h3><img src="/LiveConcert/assets/images/<?php echo $baname; ?>.jpg"></h3>
					<center><h3><?php echo $baname; ?></h3></center>

			</div>
		</div>
	<div id='bandmember_info' class='row'>
		<form id="" method="POST" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?baname=".$baname;?>'>

		<div  class="span3">

			<h3>Band Name:</h3>
			<h3>Description:</h3>
			<h3></h3>
			<h3></h3>
			<h3></h3>
			<h3>Band Member:</h3>
			<center><img id='addNewOne' src='/LiveConcert/assets/images/add_black_button.png' width='20' height='20'></center>
			<h3></h3>
			<h3></h3>
			<h3></h3>
			<h3></h3>
			<h3></h3>
			<h3></h3>
			<h3>Band Type:</h3>
		</div>
		<div class="span7">
			<h3><input type='text' name='baname' value='<?php echo $baname; ?>'><span class="error"><?php echo $berr; ?></span></ul></h3>

			<h3><textarea name='bbio' rows="8" cols="50" value='<?php echo $bbio; ?>'><?php echo $bbio; ?></textarea></h3>
			<div id='bandmember_div'>
			
				<?php 
				   
			 		if($bandmem = $mysqli->query("call get_band_member('$baname')") or die($mysqli->error)){
					while($row = $bandmem->fetch_object()){
						$member = $row->bandmember;
						echo "<span id='band_member'>";
					echo "<p><input type='text' name='bandmember[]' value='$member'>&nbsp;&nbsp;&nbsp;</p></span>";
					}
					$bandmem->close();
					$mysqli->next_result();
				}
			?>
				
			</span>
			
			</div>
			<div>
			<?php 
			$band_pre_type = array();
		if($bandtp = $mysqli->query("call get_band_type('$baname')") or die($mysqli->error)){
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
			echo "<h4><label >$key: &nbsp;</h4>";

			if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')") or die($mysqli->error)){
				// echo "<tr><td>&nbsp;</td>";
				// echo "<p>";
				while($row = $allsubtype->fetch_object()){
					$subtypename = $row->subtypename;

					if(in_array($subtypename, $band_pre_type)){
						echo "<input id='$key' type='checkbox' name='subtype[]' value='$key".'|'."$subtypename' checked/>$subtypename&nbsp;&nbsp;&nbsp;";

					}else{
						echo "<input  id='$key' type='checkbox' name='subtype[]' value='$key".'|'."$subtypename'/>$subtypename&nbsp;&nbsp;&nbsp;";

					}
				}
				// echo "</p>";
				$allsubtype->close();
				$mysqli->next_result();
			}
		}
		echo "</table>";
	}

	?>

		</div>
</div>

<input style="float:right" id='submit' type='submit' name='submit' value='Submit'>

</form>

	</div>
</div>


</section>


	

	

<div id='bandtype_div'>

<span id='band_type'>
	<table>
	
	
	<script src="../assets/js/jquery/jquery.js"></script> 
	<script src="../assets/js/moment.js"></script> 
	<script src="../assets/js/combodate.js"></script> 
<script type="text/javascript">
	$("#addNewOne").each(function(){
		$(this).click(function(){
			$('#bandmember_div').append($("#band_member").clone());
			// $(this).hide();
		});
	});
	// $("input[id='typename']").each(function(){
	// 		$(this).click(function(){
	// 			var checked = $(this).prop('checked');
	// 			var name = $(this).val();
	// 			$("input[id='"+name+"']").each(function(i,o){
	// 				$(this).prop('checked',checked);
	// 			});
	// 		});
	// 	});

</script>
</body>
</html>
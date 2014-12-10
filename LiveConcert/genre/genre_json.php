<?php 
require_once ("../includes/config.php");
if(isset($_GET['subtype'])){
		$subtype = $_GET['subtype'];
	if($sub = $mysqli->query("call get_subtype_describ('$subtype')") or die($mysqli->error)){
		if($row = $sub->fetch_object()){
			echo json_encode($row->subtypedescrip);
		}else{
			$sub->close();
			$mysqli->next_result();
			if($tp = $mysqli->query("call get_type_describ('$subtype')") or die($mysqli->error)){
				if ($row = $tp->fetch_object()){
					echo json_encode($row->typedecrip);
				}
				$tp->close();
			}
		}
	}
$mysqli->close();
}
?>
<?php 
$listnameERR="";
function list_name_check($listname){
	global $mysqli,$listnameERR;
	$listname = clean_text($listname);
	if(empty($listname)){
		$listnameERR = "listname cannot be empty";
		return false;
	}else{
		if($isExist = $mysqli->query("call get_recommend_list_by_name('$listname')") or die($mysqli->error)){
			if($isExist->num_rows > 0){
				$listnameERR = "listname is already exists, please change to a new one";
				$isExist->close();
				$mysqli->next_result();
				return false;
			}else{
				$isExist->close();
				$mysqli->next_result();
				return true;
			}
		}
	}
}



?>
<?php 
$listnameERR="";
function list_name_check($listname){
	$listname = clean_text($listname);
	if(!empty($listname)){
		$listnameERR = "listname cannot be empty";
		return false;
	}else{
		if($isExist = $mysqli->query("call get_recommend_list_by_name($listname)")){
			if($isExist->num_rows > 0){
				$listnameERR = "listname is already exists, please change to a new one";
				return false;
			}
		}else{
			return true;
		}
	}
}



?>
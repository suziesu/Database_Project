<?php 

function recommendConcert($Tusername){
	$sql = "select cname,baname from furureconcert natural join PlayBand";
	if($predictscore = $mysqli->prepare($sql)){
		$predictscore->execute();
		$predictscore->bind_result($cname,$baname);
		$recommendConcert = array();
		while($$predictscore->fetch()){
}

?>
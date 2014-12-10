<?php 
// header("Content-type: text/javascript");
require_once("../includes/config.php") ;
include "../includes/checklogin.php";
$username = $_SESSION['username'];
$d = array();
$type = $_GET['type'];
if($typeband = $mysqli->query("call get_from_bandtype_type('$type')") or die($mysqli->error)){
    while($row = $typeband->fetch_object()){
        $d[] = $row;
    }
    echo json_encode($d);   
    $typeband->close();
    $mysqli->next_result();
}
// if(isset($_GET['type'])){
// 	$qqq = array();
// 	$qqq[] =  $_GET['type'];
// 	echo json_encode($qqq);
// }
$mysqli->close();

?>

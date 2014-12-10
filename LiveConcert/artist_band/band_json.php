<?php 
// header("Content-type: text/javascript");
require_once("../includes/config.php") ;
include "../includes/checklogin.php";
$username = $_SESSION['username'];
$d = array();
if($alltypeband = $mysqli->query("call get_all_band_all_type()") or die($mysqli->error)){
    while($row = $alltypeband->fetch_object()){
        $d[] = $row;
    }
    echo json_encode($d);

    $alltypeband->close();
}
// if(isset($_GET['type'])){
// 	$qqq = array();
// 	$qqq[] =  $_GET['type'];
// 	echo json_encode($qqq);
// }
$mysqli->close();

?>

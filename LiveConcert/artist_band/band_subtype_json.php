<?php 
// header("Content-type: text/javascript");
require_once("../includes/config.php") ; 
include "../includes/checklogin.php";
$username = $_SESSION['username'];
$d = array();
$subtype = $_GET['subtype'];


if($subtypeband = $mysqli->query("call get_from_bandtype_subtype('$subtype')") or die($mysqli->error)){
    while($row = $subtypeband->fetch_object()){
        $d[] = $row;
        // if(file_exists("/LiveConcert/assets/images/$baname.jpg")){
        //     echo "<img src='/LiveConcert/assets/images/$baname.jpg'>";
        // }
        // echo "<h4>$baname</h4></a>";
        // echo "<div>$bbio</div></ul>";
    }
    echo json_encode($d); 
    $subtypeband->close();
    $mysqli->next_result();
}
?>
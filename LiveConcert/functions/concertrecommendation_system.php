<?php
//after insert the ConcertRating call this function
function updatePearsonSimilarity($Tusername,$Tcname, $Trating){
	
	//first check if the cname exists in the pearsionSimilarity,yes: update, no insert
	if($existcname = $mysqli->prepare("select * from PearsonSimilarity where cname2 = ?")){
		$existcname ->bind_param('ss',$Tcname,$Tcname);
		$existcname->execute();
		$existcname->bind_result($cname1,$cname2,$sumR1,$sumR2,$sumR1square, $sumR2square, $sumR1multiR2,$count,$pearsonsimi);
		if($existcname->fetch()){
			$existcname->close();
			if($searchuserrating = $mysqli->prepare("select * from ConcertRating where username = ? and cname <> ?")){
				$searchuserrating->bind_param('ss',$Tusername,$Tcname);
				$searchuserrating->execute();
				$searchuserrating->bind_result($username,$cname,$rating,$ratetime);
				$userrating = array();
				while($searchuserrating->fetch()){
					$userrating[$cname] = $rating;
				}
				$searchuserrating->close();
				//update the pearsonsimilarity table
				
				if($insertPS = $mysqli->prepare("update PearsonSimilarity set sumR1=sumR1+?,sumR2=sumR2+?,sumR1square=sumR1square+?,sumR2square=sumR2square+?,sumR1multiR2=sumR1multiR2+?,count=count+?,pearsonsimi=? where cname1 = ? and cname2 = ? ")){
					foreach ($userrating as $key => $value) {
						$insertPS->bind_param('iiiiiidss',$value,$Trating,$value * $value, $Trating * $Trating,$value * $Trating,1,0,$key,$Tcname);
						$insertPS->execute();
						$insertPS->bind_param('iiiiiidss',$Trating,$value, $Trating * $Trating,$value * $value,$value * $Trating,1,0,$Tcname,$key);
						$insertPS->execute();
					# code...
					}
					$insertPS->close();

				}
			}

		}else{
			//cname not exist in the similarity table so needs to add the 
			//value of each return tuple into pearson one
			$existcname->close();
			if($searchuserrating = $mysqli->prepare("select * from ConcertRating where username = ? and cname <> ?")){
				$searchuserrating->bind_param('ss',$Tusername, $Tcname);
				$searchuserrating->execute();
				$searchuserrating->bind_result($username,$cname,$rating,$ratetime);
				$userrating = array();
				while($searchuserrating->fetch()){
					$userrating[$cname] = $rating;
				}
				$searchuserrating->close();
				//insert into the pearsonsimilarity table
				
				if($insertPS = $mysqli->prepare("insert into PearsonSimilarity(cname1,cname2,sumR1,sumR2,sumR1square,sumR2square,sumR1multiR2,count,pearsonsimi) values(?,?,?,?,?,?,?,?,?) ")){
					foreach ($userrating as $key => $value) {
						if($key != $Tcname){
							$insertPS->bind_param('ssiiiiiid',$key,$Tcname,$value,$Trating,$value * $value, $Trating * $Trating,$value * $Trating,1,0);
							$insertPS->execute();
							$insertPS->bind_param('ssiiiiiid',$Tcname,$key,$Trating,$value, $Trating * $Trating,$value * $value,$value * $Trating,1,0);
							$insertPS->execute();
						}
					}
					$insertPS->close();

				}
			}

		}
	}
}
//function to calculate the similarity
function pearsonfunction($x,$y,$xx,$yy,$xy,$count){
	$numerator = $xy - ($x * $y / $count);
	$denominator = sqrt($xx - pow($x, 2)) * sqrt($yy - pow($y, 2));
	return $numerator/$denominator;
}

// for already inserted value, call this function at once:
// function CalculatePSInitial(){
// 	if($initialCalcuPS = $mysqli->prepare("update PearsonSimilarity set pearsonsimi = ? where ")){

// 		$initialCalcuPS->bind_param('d',)

// 	}
// }

function CalculatePS($Tusername, $Tcname,$Trating){
	//call function to update the PearsonSimilarity table
	updatePearsonSimilarity($Tusername,$Tcname, $Trating);
	//get the user rated concert name, then search pearsionsimilarity tuple by concertname then calculate
	if($calcuPS = $mysqli->prepare("select * from PearsonSimilarity where cname1 = ? or cname2 = ?")){
		$calcuPS->bind_param('ss',$Tcname,$Tcname);
		$calcuPS->execute();
		$calcuPS->bind_result($cname1,$cname2,$sumR1,$sumR2,$sumR1square, $sumR2square, $sumR1multiR2,$count,$pearsonsimi);
		$similarity = array();
		while($calcuPS->fetch()){
			$similarity[$cnam1.'|'.$cname2] = pearsonfunction($sumR1,$sumR2,$sumR1square, $sumR2square, $sumR1multiR2,$count);

		}
		$calcuPS->close();
		if($updatePS = $mysqli->prepare("update PearsonSimilarity set pearsonsimi=? where cname1=? and cname2=?")){
			foreach ($similarity as $key => $value) {
				$key_array = explode('|', $key);
				$updatePS->bind_param('dss',$value,$key_array[0],$key_array[1]);
				$updatePS->execute();
				# code...
			}
			$updatePS->close();


		}
	}

}
function PredictAllScore($Tusername){
	$sql = "select NR.cname,sum(PS.pearsonsimi*CR.rating)/sum(PS.pearsonsimi) as guessscore
from (select * from futureconcert) as NR 
inner join PearsonSimilarity as PS on NR.cname = PS.cname1,ConcertRating CR
where PS.pearsonsimi > 0 and CR.username = ? and PS.cname2 = CR.cname
group by NR.cname
order by guessscore
";
	if($predictscore = $mysqli->prepare($sql)){
		$predictscore->bind_param('s',$Tusername);
		$predictscore->execute();
		$predictscore->bind_result($cname,$guessscore);
		$recommendConcert = array();
		while($$predictscore->fetch()){
			$recommendConcert[$cname] = $guessscore;
		}
		$predictscore->close();
		return $recommendConcert;
	}
}

?>
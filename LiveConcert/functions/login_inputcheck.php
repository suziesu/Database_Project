<?php 
$cityERR =$dobERR = $nameERR= $usernameERR = $emailERR =$passwordERR =$msg= "";
$verifyIDERR = "";
$numberERR = "";
// $username = $email = $password = "";
// check if the email is entered and if the email is valid
function email_entered($email){
	global $emailERR, $passwordERR, $msg;
	if(empty($email)){
		$emailERR = "Email cannot be empty";
		return false;
	}else{
		$email = clean_text($email);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$emailERR = "Invalid email address";
			return False;
		}else{
			return $email;
		}
	}
}
function username_entered($username){
	global $usernameERR;
	if(empty($username)){

		$usernameERR = "username cannot be empty";
		return false;
	}else{
		$username = clean_text($username);
		return $username;
	}

}
function name_entered($name){
	global $nameERR;
	if(empty($name)){
		$nameERR = "name cannot be empty";
		return false;
	}else{
		$name = clean_text($name);
		return $name;
	}
}
function dob_entered($dob){
	global $dobERR;
	if(empty($dob)){

		$dobERR = "dob is prefered for discount";
		return false;
	}else{
		if($date = DateTime::createFromFormat('Y-m-d', $dob)){
			$birth = $date->format('Y-m-d');
			return $birth;
		}else{
			$dobERR = "the format is not correct";
			return false;
		}
	}
}
function date_time_check($datetime){
	if(empty($datetime)){
		return null;
	}else{
		if($date = DateTime::createFromFormat('d-m-Y H:i', $datetime)){
			$output = $date->format('Y-m-d H:i:00');
			return $output;
		}
	}
}
function null_allowed_input($input){
	if(empty($input)){
		return null;
	}else{
		return clean_text($input);
	}
}
function city_entered($city){
	global $cityERR;
	if(empty($city)){
		$cityERR = "city cannot be empty";
		return false;
	}else{
		return clean_text($city);
	}
}
function verifyID($id){
	global $verifyIDERR ;
	if(empty($id)){
		$verifyIDERR  = "verify Id needed for artist";
		return false;
	}else{
		if(!preg_match('/^[a-zA-Z0-9]{10}$/', $id)){
			$verifyIDERR  = "ID is not valid, 10 character";
			return false;
		}else{
			return $id;
		}
	}
}

//for registration
// function username_exist($Tusername){
// 	if($usersearch = $mysqli->prepare("select username from User where username =?")){
// 		$usersearch->bind_param('s',$Tusername);
// 		$usersearch->execute();
// 		$usersearch->bind_result($username);
// 		if($usersearch->fetch){
// 			$usernameERR = "username is already exists, please choose a new one";
// 			$usersearch->close();
// 			return True;
// 		}
// 	}
// }

//varify the password only use number and letters
function password_valid($password){
	global $passwordERR;
	if(empty($password)){
			$passwordERR = "Password cannot be empty";
			return false;
		}else{
			$password = clean_text($password);
			if (!preg_match('/^[a-zA-Z0-9]+$/', $password)){
				$passwordERR = "Only contain Letter and Numbers!";
				return False;

			}else{
				$password = password_hash($password,PASSWORD_BCRYPT);
				return $password;
			}
		}
}

//login user paswordcheck
function validate_user($Tusername,$Tpassword){
	global $passwordERR, $msg, $mysqli;
	if($userlogin = $mysqli->query("call find_user_byname('$Tusername')")){
		echo "!23";
		if($row = $userlogin->fetch_object()){
			echo $row->password;
			if(password_verify($Tpassword,$row->password)){
				$userArray = array('username'=>$row->username,'score'=>$row->score,'city'=>$row->city);
				$userlogin->close();
				$mysqli->next_result();
				return $userArray;
			}else{
				$passwordERR = "password is not correct, please try again";
				$userlogin->close();
				$mysqli->next_result();
				return false;
			}
		}
		else{
			$msg = "User name cannot found, please sign up first";
			$userlogin->close();
			$mysqli->next_result();
			return false;
		}
		
	}	
}

//for registration username chack and other find user score info
function find_user_by_username($Tusername){
	global $usernameExist,$mysqli, $usernameERR;
	if($usersearch = $mysqli->query("call find_user_byname('$Tusername')") or die($mysqli->error)){
		if($row = $usersearch->fetch_object()){
			//register, echo the usernameExist
			$usernameERR = "username is already exists, <a href='index.php?username=$Tusername'>try login</a> ";
			$userArray = array('username'=>$row->username,'city'=>$row->city,'score'=>$row->score);
			$usersearch->close();
			$mysqli->next_result();
			return $userArray;
		}else{
			$usersearch->close();
			$mysqli->next_result();
			return false;
		}
		
	}else{
		echo "cannot find user";
		return false;
	}
}

function number_check($number){
	global $numberERR;
	if(!empty($number)){
		if (!preg_match('/^[0-9]+$/', $number)){
			$numberERR = "Price only have numbers!";
			return -1;
		}else{
			return $number;
		}
	}else{
		return 0;
	}
}
?>
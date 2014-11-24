<?php 
$cityERR =$dobERR = $nameERR= $usernameERR = $emailERR =$passwordERR =$msg= "";
$verifyIDERR = "";
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
		$username = "username cannot be empty";
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
		return true;
	}
}
function dob_entered($dob){
	global $dobERR;
	if(empty($dob)){

		$dobERR = "dob is prefered for discount";
		return false;
	}else{
		if($date = DateTime::createFromFormat('m/d/Y', $dob)){
			$birth = $date->format('Y-m-d');
			return $birth;
		}else{
			$dobERR = "the format is not correct";
			return false;
		}
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
		if(!preg_match('/^[a-zA-Z0-9]{10}$/', $password){
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
				$password = password_hash($password,PASSWORD_DEFAULT);
				return $password;
			}
		}
}

//login user paswordcheck
function validate_user($Tusername,$Tpassword){
	global $passwordERR,$msg;

	if($userlogin = $mysqli->query("call find_user_byname($username)")){
		if($row = $userlogin->fetch_object()){
			if($Tpassword == $row->password){
				$userArray = array('username'=>$row->username,'score'=>$row->score,'city'=>$row->city);
				$userlogin->close();
				$mysqli->close();
				return $userArray;
			}else{
				$passwordERR = "password is not correct, please try again";
				$userlogin->close();
				$mysqli->close();
				return false;
			}
		}
		else{
			$msg = "User name cannot found, please sign up first";
			$userlogin->close();
			$mysqli->close();
			return false;
		}
		
	}	
}

//for registration username chack and other find user score info
function find_user_by_username($Tusername){
	global $usernameExist;
	if($usersearch = $mysqli->query("call find_user_byname($username)")){
		if($row = $usersearch->fetch_object()){
			//register, echo the usernameExist
			$usernameERR = "username is already exists, please change a new one";
			$userArray = array('username'=>$row->username,'city'=>$row->city,'score'=>$row->score);
			$usersearch->close();
			$mysqli->close();
			return $userArray;
		}else{
			return false;
		}
		
	}	
}
?>
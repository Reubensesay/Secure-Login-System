<?php 

session_start();

function signup($data)
{
	$errors = array();
 
	//validate 
	//"preg_match" is used to match the username to a specific set of characters
	if(!preg_match('/^[a-zA-Z]+$/', $data['username'])){
		$errors[] = "Please enter a valid username";
	}

	if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
		$errors[] = "Please enter a valid email";
	}

	if(strlen(trim($data['password'])) < 4){
		$errors[] = "Password must be atleast 4 chars long";
	}

	if($data['password'] != $data['password2']){
		$errors[] = "Passwords must match";
	}

	$check = database_run("select * from users where email = :email limit 1",['email'=>$data['email']]);
	if(is_array($check)){
		$errors[] = "That email already exists";
	}

	//save
	if(count($errors) == 0){

		$arr['username'] = $data['username'];
		$arr['email'] = $data['email'];
		$arr['password'] = hash('sha256',$data['password']);
		$arr['date'] = date("Y-m-d H:i:s");

		$query = "insert into users (username,email,password,date) values 
		(:username,:email,:password,:date)";

		database_run($query,$arr);
	}
	return $errors;
}

function login($data)
{
	$errors = array();
 
	//validate 
	if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
		$errors[] = "Please enter a valid email";
	}

	if(strlen(trim($data['password'])) < 4){
		$errors[] = "Password must be atleast 4 chars long";
	}
 
	//check
	if(count($errors) == 0){

		$arr['email'] = $data['email'];
		$password = hash('sha256', $data['password']);

		$query = "select * from users where email = :email limit 1";

		$row = database_run($query,$arr);

		if(is_array($row)){
			$row = $row[0];

			if($password === $row->password){
				
				$_SESSION['USER'] = $row;
				$_SESSION['LOGGED_IN'] = true;
			}else{
				$errors[] = "wrong email or password";
			}

		}else{
			$errors[] = "wrong email or password";
		}
	}
	return $errors;
}

function database_run($query, $vars = array()) {
    $string = "mysql:host=localhost;dbname=verify_db";
    $con = new PDO($string, 'root', '');

    if (!$con) {
        return false;
    }

    $stm = $con->prepare($query);
    
    if (!$stm) {
        return false;
    }

    $check = $stm->execute($vars);

    if ($check) {
        $data = $stm->fetchAll(PDO::FETCH_OBJ);

        if (count($data) > 0) {
            return $data;
        }
    }

    return false;
}


function check_login($redirect = true){

	if(isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])){

		return true;
	}

	if($redirect){
		header("Location: login.php");
		die;
	}else{
		return false;
	}
	
}

function check_verified(){
    if (isset($_SESSION['USER']) && isset($_SESSION['USER']->id)) {
        $id = $_SESSION['USER']->id;
        $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $vars = array(':id' => $id);
        $row = database_run($query, $vars);

        if (is_array($row)) {
            $row = $row[0];

            if ($row->email == $row->email_verified) {
                return true;
            }
        }
    }

    return false;
}




<?php



header('Content-Type: application/json');



if($_SERVER['REQUEST_METHOD']=='GET'){

//GET header "AppToken" sent by client
$authHeader = $_SERVER['HTTP_APPTOKEN'];
//echo $authHeader;
	if ($authHeader != null){
		//token is set

		//set token as session id
		session_id($authHeader);
		session_start();
		
		
		
			
		session_destroy();
		echo "You are now logged out!";
			
			
			
	}else{
		//token is not set
		echo "Error! Private key was not provided";
		header('HTTP/1.0 401 Unauthorized');		
	}
}else{
	//method not allowed
	header('HTTP/1.0 400 Bad Request');
}
?>
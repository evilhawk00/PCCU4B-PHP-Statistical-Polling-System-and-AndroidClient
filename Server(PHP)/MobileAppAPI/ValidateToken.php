<?php

require_once 'dbconfig.php';
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
		
		
		if(empty($_SESSION['CurrentUserName'])){
			
			$json_response = array();
			$row_array['Token'] = "expired";
			array_push($json_response,$row_array);
			echo json_encode($json_response);
			
		} else{
			
			
			
			$json_response = array();
			$row_array['Token'] = "valid";
			array_push($json_response,$row_array);
			echo json_encode($json_response);
			
			

			
			
		}	
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
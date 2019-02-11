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
			echo "Error! You're not logged in.";
			header('HTTP/1.0 401 Unauthorized');
		}else{
				try {
				$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
				$conn->exec("SET NAMES utf8");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$GetAllJobRecord = $conn->prepare("SELECT * FROM jobstatistics ORDER BY JobDate DESC");
				$GetAllJobRecord->execute();
				} catch(PDOException $e) {
						echo '系統錯誤: ' . $e->getMessage();
						header("location:error.html");
				}
				$json_response = array();
				//loop through all table rows
				while ($row=$GetAllJobRecord->fetch()){

				$row_array['DisplayName'] = $row['member_name'];
				$row_array['JobDetail'] = $row['JobDetail'];
				$row_array['Minutes'] = $row['Minutes'];
				$row_array['JobDate'] = $row['JobDate'];
				array_push($json_response,$row_array);
				}
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

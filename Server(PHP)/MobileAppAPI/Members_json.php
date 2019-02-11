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
		} else{

			try {
				$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
				$conn->exec("SET NAMES utf8");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$GetUserListStatistics = $conn->prepare('SELECT members.UID, members.DisplayName, members.Nickname, SUM(jobstatistics.Minutes) AS Total, members.LastLoginTime FROM members LEFT JOIN jobstatistics ON members.DisplayName = jobstatistics.member_name GROUP BY members.DisplayName, members.UID ORDER BY UID ASC');
				$GetUserListStatistics->execute();
			} catch(PDOException $e) {
					echo '系統錯誤: ' . $e->getMessage();
					//header("location:error.html");
			}
			//Create an array
			$json_response = array();
		//loop through all table rows
			while ($row=$GetUserListStatistics->fetch()){
				$row_array['DisplayName'] = $row['DisplayName'];
				if ($row['Nickname']== "NoRecord"){
					$row_array['Nickname'] = "!!此人尚未加入系統!!";
				}else{
					$row_array['Nickname'] = $row['Nickname'];
				}	
				if ($row['Total']== NULL){
					$row_array['Total'] = "0";
				}else{
					$row_array['Total'] = $row['Total'];
				}	
				if ($row['LastLoginTime']== NULL){
					$row_array['LastLoginTime'] = "從未登入";
				}else{
					$row_array['LastLoginTime'] = $row['LastLoginTime'];
				}		
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











//session_start();





?>

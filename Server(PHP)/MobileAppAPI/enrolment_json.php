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
				//retrive the jobs I done
				$CurrentUser = addslashes($_SESSION['CurrentUserName'] );
				$GetMyJobHistory = $conn->prepare("SELECT * FROM `jobstatistics` WHERE member_name=\"$CurrentUser\" ORDER BY JobDate DESC");
				$GetMyJobHistory->execute();
				} catch(PDOException $e) {
						echo '系統錯誤: ' . $e->getMessage();
						//header("location:error.html");
				}
				$json_response = array();
				while ($row=$GetMyJobHistory->fetch()){
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
}elseif($_SERVER['REQUEST_METHOD']=='POST'){
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
			
			$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
			$conn->exec("SET NAMES utf8");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$newJobItem = addslashes( $_POST['WorkDetails'] ); //prevents types of SQL injection
			$newJobWorkTime = addslashes( $_POST['WorkTime'] ); //prevents types of SQL injection
			$CurrentUser = addslashes($_SESSION['CurrentUserName'] );
			$CurrentUserNick = addslashes($_SESSION['CurrentNickName'] );

			if ( !empty($_POST['AdditionalDetails'])){
			  $JobRemarks = addslashes( $_POST['AdditionalDetails'] );
			  $JobRemarks_Avaliable = addslashes( '1' );
			}else{
			  $JobRemarks = NULL;	
			  $JobRemarks_Avaliable = addslashes( '0' );
			}

			$JobFinishedTime = addslashes( $_POST['JobDate'] );


			try {
			$InsertJobData = $conn->prepare("INSERT INTO `jobstatistics` (`JobID`, `member_name`, `NickName`, `JobDetail`, `Remarks_Avaliable`, `Remarks`, `Minutes`, `JobDate`, `AddedTime`) VALUES (NULL, '$CurrentUser', '$CurrentUserNick', '$newJobItem', '$JobRemarks_Avaliable', '$JobRemarks', '$newJobWorkTime', '$JobFinishedTime', NOW());");
			$process = $InsertJobData->execute();

			} catch(PDOException $e) {
					echo '系統錯誤: ' . $e->getMessage();
					//header("location:error.html");
				}

					

			if($process){
			//insert success redirect back 
				header('HTTP/1.0 200 OK');
			}else{
				//failed response 500
				header('HTTP/1.0 500 Internal Server Error');
			}
		
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
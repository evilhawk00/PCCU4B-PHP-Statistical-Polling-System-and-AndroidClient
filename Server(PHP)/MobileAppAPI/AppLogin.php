<?php

require_once 'dbconfig.php';

 session_start();
 
 $currentSID = session_id();
 
header('Content-Type: application/json'); 
 
 if($_SERVER['REQUEST_METHOD']=='POST'){
$myusername=$_POST['username'];
$mypassword=$_POST['password'];
$encrypted_mypassword=md5($mypassword);
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword); 




$tbl_name="members"; // Table name


$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
$conn->exec("SET NAMES utf8");

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$LoginAction = $conn->prepare("SELECT * FROM $tbl_name WHERE Email='$myusername' and Password='$encrypted_mypassword'");
$LoginAction->execute();


$result=$LoginAction->fetch(PDO::FETCH_ASSOC);

$json_response = array();
 
 if($result){
	 
	 $row_array['Login'] = "success";
	 $row_array['DisplayName'] = $result['DisplayName']; 
	 $row_array['AppToken'] = $currentSID;
	 array_push($json_response,$row_array);

 $_SESSION['CurrentUserName'] = $result['DisplayName']; 
 $_SESSION['CurrentNickName'] = $result['NickName']; 
 

 }else{
	 
	 $row_array['Login'] = "failure";
	 $row_array['DisplayName'] = "unauthorized";
	 $row_array['AppToken'] = "unauthorized";
	 array_push($json_response,$row_array);

 
 }
 
 echo json_encode($json_response);
 }
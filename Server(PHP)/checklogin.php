<?php

require_once 'dbconfig.php';

ini_set ("display_errors", "1");
error_reporting(E_ALL);

ob_start();
session_start();


try {


$tbl_name="members"; // Table name



$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
$conn->exec("SET NAMES utf8");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// Defining your login details into variables
$myusername=$_POST['myusername'];
$mypassword=$_POST['mypassword'];
$encrypted_mypassword=md5($mypassword); //MD5 Hash for security
// MySQL injection protections
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);


$LoginAction = $conn->prepare("SELECT * FROM $tbl_name WHERE Email='$myusername' and Password='$encrypted_mypassword'");
$LoginAction->execute();



$result=$LoginAction->fetch(PDO::FETCH_ASSOC);


if($result){



 $_SESSION['CurrentUserName'] = $result['DisplayName']; 
 $_SESSION['CurrentNickName'] = $result['NickName']; 
 $UpdateLastLoginTime = $conn->prepare("UPDATE $tbl_name SET `LastLoginTime` = NOW() WHERE $tbl_name.Email='$myusername' AND $tbl_name.Password='$encrypted_mypassword'");
 $UpdateLastLoginTime->execute();
header("location:home.php");
}

else {

}

ob_end_flush();

} catch(PDOException $e) {
        echo '系統錯誤: ' . $e->getMessage();
		header("location:error.html");
    }

?> 




<html>
<head>
	<title>請先登入</title>
	<link href="css/default_styles.css" rel="stylesheet" type="text/css" />
	<link rel='icon' href='favicon.ico' type='image/x-icon'/ >
	<script language="JavaScript" src="js/LoginInput.js"></script>
	
	


</head>

<body>    


<div class="wrapper">
<section class="main-header nav-down">
	<div class="row">
		<div class="col-md-12">
			<div class="tag-line">團隊、合作、感動紀錄你的每一刻</div>
			<a class="logo" href="http://pccu4b.gq/"><img src="images/PCCU4B.png"></a>
			
			
		
	</div>
	</div>
</section>





<section class="login">
	<div class="row margin padding">
		<div class="container">
			<div class="col-md-12 textCenter">
				<h1 class="splash">登入</h1>
			</div>
			<div class="col-md-4 max-width" style="position:relative;z-index:15;">
	
				<form name="form1" method="post" action="checklogin.php" onsubmit="return loginValidate(this)">
				
				    <fieldset class="control-group">
				
					    <div class="control-group">
						    <label class="control-label" for="username">!!!帳號密碼錯誤!!!<br>Email Address:</label>
							<div class="controls">
							    <input class="input-xlarge" name="myusername" id="myusername" type="text">
							</div>
						</div>
				
						<div class="control-group">
						    <label class="control-label" for="password">密碼:</label>
							<div class="controls">
							    <input name="mypassword" id="mypassword" type="password">
							</div>
						</div>
				
				     <div>
			  		<div><input class="largeBtn primaryColor pullRight" name="Submit" value="登入" type="submit"></div>
				          
				         
				          <span class="passwordReset"><a href="forgetpassword.html" style="color:#666;font-size:14px;">忘記密碼</a></span>
	                      
				     </div>
				     
					</fieldset>
	
				</form>
				
			</div>
		</div>
	</div>
</section>	





</div>
<section class="COPYRIGHT-footer">
	<div class="container">
		<div class="row">
			<div class="col-md-12">	
				<p class="copyright">
				版權所有 - &copy; 2016 PCCU4B 財法4B畢典籌備規劃系統</p>
			</div>
		</div>
	</div>
</section>

</body></html>
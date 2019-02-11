<?php




session_start();

//prevent iframe hijack
header("X-Frame-Options: SAMEORIGIN");
//If your session isn't valid, it returns you to the login screen for protection
if(!empty($_SESSION['CurrentUserName'])){
 header("location:home.php");
}




?>




<html>
<head>
	<title>財法4b畢典籌備規劃系統</title>
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
						    <label class="control-label" for="username">Email Address:</label>
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
				     <div class="MobileDownload">
					<p>立即下載手機版專用App!!</p>
					<a class="googleplay" href="https://play.google.com/store/apps/details?id=com.evilhawk00.pccu4b" target="_blank"><img src="images/GooglePlay.png" style="width:180px;"></a>
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
				<p class="copyright">版權所有 - &copy; 2016 PCCU4B 財法4B畢典籌備規劃系統</p>
			</div>
		</div>
	</div>
</section>

</body></html>
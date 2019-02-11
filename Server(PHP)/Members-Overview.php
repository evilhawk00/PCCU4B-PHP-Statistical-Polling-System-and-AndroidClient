<?php

require_once 'dbconfig.php';

session_start();

if(empty($_SESSION['CurrentUserName'])){
 header("location:access-denied.php");
} 



try {
// connection details
$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
$conn->exec("SET NAMES utf8");

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



//retrive Members names and nick from members


$GetUserListStatistics = $conn->prepare('SELECT members.UID, members.DisplayName, members.Nickname, SUM(jobstatistics.Minutes) AS Total, members.LastLoginTime FROM members LEFT JOIN jobstatistics ON members.DisplayName = jobstatistics.member_name GROUP BY members.DisplayName, members.UID ORDER BY UID ASC');
$GetUserListStatistics->execute();



} catch(PDOException $e) {
        echo '系統錯誤: ' . $e->getMessage();
		header("location:error.html");
    }


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>成員明細</title>
<link href="css/default_styles.css" rel="stylesheet" type="text/css" />

</head>
<body>    


<div class="wrapper">
<section class="main-header nav-down">
	<div class="row">
		<div class="col-md-12">
			<div class="tag-line">團隊、合作、感動紀錄你的每一刻</div>
			<a class="logo" href="http://pccu4b.gq/"><img src="images/PCCU4B.png"></a>
			<div class="main-menu">
			<div class="hidden-xs divider">&nbsp;</div>
			<div class="topBtn">
<?php
echo "<font size=\"3\" style=\"text-align:left\">" . $_SESSION['CurrentUserName'] . "[" . $_SESSION['CurrentNickName'] . "]，歡迎您</font>";

?>
			
			</div>
			<div class="hidden-xs divider">&nbsp;</div>
			<div class="topBtn">
			        <a href="logout.php" style="font-size:17px;color:#0a6da3;">登出系統</a><font>&nbsp;&nbsp;&nbsp;</font>
			
			</div>
			
			
			
			
			
			</div>
			
		
	</div>
	</div>
	
	
</section>


<div id="header">

<a href="home.php">資料統計</a><font style="font-size:20px;color:white;">&nbsp;|&nbsp;</font><a href="enrolment.php">登錄個人工作時數</a><font style="font-size:20px;color:white;">&nbsp;|&nbsp;</font><a href="overview.php">全體已登錄項目</a><font style="font-size:20px;color:white;">&nbsp;|&nbsp;</font><a href="Personal-Ranking.php">貢獻排行榜</a><font style="font-size:20px;color:white;">&nbsp;|&nbsp;</font><a href="Members-Overview.php">成員明細</a>
</div>
<div id="container">






<table class="WithBorder" border="0" width="70%" align="center">
<CAPTION><h3>已註冊使用者</h3></CAPTION>
<tr>
<th>大名(點擊查看明細)</th>
<th>a.k.a</th>
<th>總時數(分鐘)</th>
<th>最後登入時間</th>
</tr>

<?php


while ($row=$GetUserListStatistics->fetch()){
echo "<tr>";

if ($row['Total']== NULL){
	if ($row['Nickname']== "NoRecord"){
		echo "<td class=\"NoRecord\"><font style=\"color:red;cursor:not-allowed;\">" . $row['DisplayName']."</td>";
	}
	else{
	echo "<td><font style=\"color:#603600;\">" . $row['DisplayName']."</td>";
	}
}	
else{
	
	echo '<td><a href="UserProfile.php?UserID=' . $row['UID'] . '" style="font-size:14px;color:#0a6da3;">' . $row['DisplayName'].'</a></td>';
	
}




if ($row['Nickname']== "NoRecord"){
		echo "<td class=\"NoRecord\"><font style=\"color:red;cursor:not-allowed;\">!!此人尚未加入系統!!</td>";
}
else{
		echo "<td>" . $row['Nickname']."</td>";
}


if ($row['Total']== NULL){
	if ($row['Nickname']== "NoRecord"){
		echo "<td class=\"NoRecord\"><font style=\"color:red;cursor:not-allowed;\">!!-99999!!</td>";
	}
	else{
		echo "<td><font style=\"color:#d46000;\">0</font></td>";
	}
}
else{
	echo "<td><font style=\"color:#0d4f15\">" . $row['Total']."</font></td>";
}



if ($row['LastLoginTime']== NULL){
	if ($row['Nickname']== "NoRecord"){
		echo "<td class=\"NoRecord\"><font style=\"color:red;cursor:not-allowed;\">!!此人尚未加入系統!!</td>";
	}
	else{
    echo "<td><font style=\"color:#77452c;\">從未登入</font></td>";
	}
}
else{
	echo "<td><font style=\"color:#001538;\">" . $row['LastLoginTime']."</font></td>";
}


echo "</tr>";
}




?>
</table>
<br>
</div>

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

</body>
</html>
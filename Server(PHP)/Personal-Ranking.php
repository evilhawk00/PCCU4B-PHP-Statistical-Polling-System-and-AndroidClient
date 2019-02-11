<?php

require_once 'dbconfig.php';

session_start();
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['CurrentUserName'])){
 header("location:access-denied.php");
} 

try {
// connection details
$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);

$conn->exec("SET NAMES utf8");

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



//retrive Members names and nick from members


$GetUserMinutesSUM = $conn->prepare('SELECT members.UID, jobstatistics.member_name, jobstatistics.Nickname, SUM(jobstatistics.Minutes) AS Total FROM jobstatistics INNER JOIN members ON jobstatistics.member_name = members.DisplayName GROUP BY jobstatistics.member_name, members.UID, jobstatistics.Nickname ORDER BY Total DESC');
$GetUserMinutesSUM->execute();



} catch(PDOException $e) {
        echo '系統錯誤: ' . $e->getMessage();
		header("location:error.html");
    }


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>貢獻排行</title>
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
<CAPTION><h3>貢獻排行</h3></CAPTION>
<tr>
<th>大名(點擊查看明細)</th>
<th>a.k.a</th>
<th>總時數(分鐘)[⇓由多至少]</th>
</tr>

<?php
//loop through all table rows

while ($row=$GetUserMinutesSUM->fetch()){
echo "<tr>";

echo '<td><a href="User-Statistics.php?UserID=' . $row['UID'] . '" style="font-size:14px;color:#0a6da3;">' . $row['member_name'].'</a></td>';


echo "<td>" . $row['Nickname']."</td>";
echo "<td>" . $row['Total']."</td>";

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
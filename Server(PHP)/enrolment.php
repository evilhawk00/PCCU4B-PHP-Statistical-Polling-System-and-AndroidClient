<?php

require_once 'dbconfig.php';

session_start();
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['CurrentUserName'])){
 header("location:access-denied.php");
}

try {

$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
$conn->exec("SET NAMES utf8");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);




$CurrentUser = addslashes($_SESSION['CurrentUserName'] );


$GetMyJobHistory = $conn->prepare("SELECT * FROM `jobstatistics` WHERE member_name=\"$CurrentUser\" ORDER BY JobDate DESC");
$GetMyJobHistory->execute();


} catch(PDOException $e) {
        echo '系統錯誤: ' . $e->getMessage();
		header("location:error.html");
    }



// inserting sql query
if (isset($_POST['Submit']))
{

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
 header("Location: enrolment.php");
}else{
	//failed show error page
	header("location:error.html");
}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>登錄工作時數</title>
<link href="css/default_styles.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

  
  <script>
  $(document).ready(function() {
    $("#datepicker").datepicker({
	dateFormat: "yy-mm-dd",
	changeMonth: true,
    changeYear: true,
    firstDay: 1,
    minDate: Date.parse("2016-01-01"),
    maxDate: Date.parse("2021-01-01"),
    yearRange: "c:c+1"
	});
  });

  </script>


	
<script language="JavaScript" src="js/jquery.blockUI.js"></script>	

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
<table width="90%" align="center">
<CAPTION><h3>登錄工作時數</h3></CAPTION>
<form name="PostWorkDetails" id="PostWorkForm" action="enrolment.php" method="post">
<tr>
    <td width="45%">*工作內容、理由</td>
	<td width="25%">(非必填)額外備註事項:</td>
	<td width="8%">*時數(分鐘)</td>
	<td width="15%">*工作日期(ex:2016-01-01)</td>
	<td width="7%">&nbsp;</td>
</tr>
<tr>
 <td width="45%"><textarea id="enr1" name="WorkDetails" rows="5" style="width:90%"></textarea></td>
 <td width="25%"><textarea id="enr2" name="AdditionalDetails" rows="3" style="width:85%"></textarea></td>
 <td width="8%"><input id="enr3" type="number" name="WorkTime" min="5" max="99999" style="width:55%"/></td>
 <td width="15%"><input id="datepicker" name="JobDate" style="width:80%"/></td>
 <td width="7%"><input class="enrBtn primaryColor"type="submit" name="Submit" value="送出"/></td>
</tr>
</table>
<hr>
<table class="WithBorder" border="0" width="85%" align="center">
<CAPTION><h3>我的貢獻紀錄</h3></CAPTION>
<tr>
<th>編號</th>
<th class="JobDetails">工作內容、理由(點擊*查看備註)</th>
<th>時數(分鐘)</th>
<th>工作日期(⇓新到舊)</th>
<th>系統建檔日期</th>
</tr>

<?php
//loop through all table rows
$i= 1;
while ($row=$GetMyJobHistory->fetch()){
echo "<tr>";
echo "<td>" . $row['JobID']."</td>";
if ($row['Remarks_Avaliable']== 1){
	
	$PreOutPutScript =  '<script>$(document).ready(function() { $("#Remark'.$i.'").click(function() { $.blockUI({ theme:true, title:"'. $row['JobDetail'] . '", message:\'<p>'. $row['Remarks'] . '</p>\', onOverlayClick: $.unblockUI  }); }); }); </script>';
	echo preg_replace("/[\n\r]/","",$PreOutPutScript);
	echo '<td class="JobDetails"><div id="Remark' .$i. '" style="color:blue; cursor: pointer;">' . $row['JobDetail'] . '*</div></td>';
	$i++;
	
}
else {
	echo "<td class=\"JobDetails\">" . $row['JobDetail']."</td>";
}
echo "<td>" . $row['Minutes']."</td>";
echo "<td>" . $row['JobDate']."</td>";
echo "<td>" . $row['AddedTime']."</td>";


//echo '<td><a href="positions.php?id=' . $row['position_id'] . '">Delete Position</a></td>';
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
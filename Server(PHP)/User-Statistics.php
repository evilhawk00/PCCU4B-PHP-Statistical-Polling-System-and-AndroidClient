<?php


require_once 'dbconfig.php';


$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die(mysqli_error());


// connection details

mysqli_query($link,"SET NAMES utf8");


session_start();
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['CurrentUserName'])){
 header("location:access-denied.php");
} 







?>

<?php
// deleting sql query
// check if the 'id' variable is set in URL
 if (isset($_GET['UserID']))
 {
 // get id value
 $UID = $_GET['UserID'];
 
 $UID_JobLists = mysqli_query($link,"SELECT members.UID, jobstatistics.* FROM jobstatistics INNER JOIN members ON jobstatistics.member_name = members.DisplayName WHERE UID=\"$UID\" ORDER BY JobDate DESC")or die("The position does not exist ... \n"); 
 if (mysqli_num_rows($UID_JobLists)<1){
    $UID_JobLists = null;
	header("Location: Personal-Ranking.php");
}
 
 

 }
 else
 header("Location: Personal-Ranking.php");
    
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>貢獻排行</title>
<link href="css/default_styles.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script language="JavaScript" src="js/jquery.blockUI.js"></script>	

</head>
<body>    


<div class="wrapper">
<section class="main-header nav-down">
	<div class="row">
		<div class="col-md-12">
		<div class="menu-toggle visible-xs">
				<div class="toggleContainer">
					<div class="bar topBar"></div>
					<div class="bar middleBar"></div>
					<div class="bar bottomBar"></div>
				</div>
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
	</div>
	
	
</section>


<div id="header">



   <a href="Personal-Ranking.php">返回貢獻排行榜</a>
</div>
<div id="container">






<table class="WithBorder" border="0" width="85%" align="center">

<?php
$UserDisplayName = mysqli_fetch_array($UID_JobLists);
mysqli_data_seek($UID_JobLists, 0);
$UserDisplayName_Title = mysqli_fetch_array($UID_JobLists);
echo "<CAPTION><h3>" . $UserDisplayName_Title['member_name']. "[". $UserDisplayName['NickName']. "]的貢獻紀錄</h3></CAPTION>";

?>


<tr>
<th>編號</th>
<th class="JobDetails">工作內容、理由(點擊*查看備註)</th>
<th>時數(分鐘)</th>
<th>工作日期(⇓新到舊)</th>
<th>系統建檔日期</th>
</tr>

<?php
//loop through all table rows

mysqli_data_seek($UID_JobLists, 0);
$i= 1;
while ($row=mysqli_fetch_array($UID_JobLists)){
echo "<tr>";
//echo "<td>" . $row['UID']."</td>";
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

echo "</tr>";
}
mysqli_free_result($UID_JobLists);
mysqli_close($link);



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
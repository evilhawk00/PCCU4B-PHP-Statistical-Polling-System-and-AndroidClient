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





$HonoredNum = $conn->prepare('SELECT count(*) from (SELECT member_name FROM jobstatistics GROUP BY member_name) HonoredMemberNum');
$HonoredNum->execute();

$AllMemberNum = $conn->prepare('SELECT count(*) from members');
$AllMemberNum->execute();

$ChartQuery = $conn->query('SELECT member_name, SUM(Minutes) AS Total From jobstatistics GROUP BY member_name ORDER BY Total DESC');

$row1=$HonoredNum->fetch();
$row2=$AllMemberNum->fetch();
$NotHonoredNum=(int)$row2['count(*)']-(int)$row1['count(*)'];  

 $rows = array();
      $table = array();
      $table['cols'] = array(


        array('label' => '使用者名稱', 'type' => 'string'),
        array('label' => '時數(分鐘)', 'type' => 'number')

    );
        /* Extract the information from $result */
        foreach($ChartQuery as $r) {

          $temp = array();

          // the following line will be used to slice the Pie chart

          $temp[] = array('v' => (string) $r['member_name']); 

          // Values of each slice

          $temp[] = array('v' => (int) $r['Total']); 
          $rows[] = array('c' => $temp);
        }

    $table['rows'] = $rows;

    // convert data into JSON format
    $jsonTable = json_encode($table);
	
	
	$Participatingrows = array();
      $Participatingtable = array();
      $Participatingtable['cols'] = array(  array('label' => '參與/未參與', 'type' => 'string'),array('label' => '人數', 'type' => 'number'));
       

          $tempa = array();

          // the following line will be used to slice the Pie chart

          $tempa[] = array('v' => (string) "已參與貢獻人數"); 

          // Values of each slice

          $tempa[] = array('v' => (int) $row1['count(*)']); 
          $Participatingrows[] = array('c' => $tempa);
		  $tempa = array();

          // the following line will be used to slice the Pie chart

          $tempa[] = array('v' => (string) "未參與貢獻人數"); 

           //Values of each slice

          $tempa[] = array('v' => (int) $NotHonoredNum); 
          $Participatingrows[] = array('c' => $tempa);
		  
		  
		  
     

    $Participatingtable['rows'] = $Participatingrows;

    // convert data into JSON format
    $jsonTable2 = json_encode($Participatingtable);
	
	
	
	
} catch(PDOException $e) {
        echo '系統錯誤: ' . $e->getMessage();
		header("location:error.html");
    }


?>
<html>
<head>
<title>統計資料總覽</title>
<link href="css/default_styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
 
        <script type="text/javascript">

        // Load the Visualization API and the piechart package.
        google.load('visualization', '1', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.setOnLoadCallback(drawChart1);
		

        function drawChart1() {

          // Create our data table out of JSON data loaded from server.
          var data = new google.visualization.DataTable(<?=$jsonTable?>);
		  
          var options = {
               title: '總貢獻時數(分鐘)',
			  backgroundColor: 'transparent',
			  colors:['#0a6da3','white','orange','#5e6570','#34a0db','#d9effc','#fccea4','#a9abad','#3fbbff','#f2f2f2','#71adbf','#f7ca71','#548464','#72d2ff','#876552','#716196'],
              is3D: 'true',
			  fontSize:18,

              width: "100%",
              height: "100%"
            };
		var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
		  chart.draw(data, options);
		}  
		google.setOnLoadCallback(drawChart2);
		function drawChart2() {	
		var data2 = new google.visualization.DataTable(<?=$jsonTable2?>);	
		var options2 = {
               title: '整體參與情形(人數)',
			   backgroundColor: 'transparent',
			   colors:['#0a6da3','grey'],
              is3D: 'true',
			  fontSize:15,
              width: "100%",
              height: "100%"
            };	
       
          
		  var chart2 = new google.visualization.PieChart(document.getElementById('chart_div2'));
		  chart2.draw(data2, options2);
          
        }
		
		
		
        </script>
		
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


<div id="page">
<div id="header">

<a href="home.php">資料統計</a><font style="font-size:20px;color:white;">&nbsp;|&nbsp;</font><a href="enrolment.php">登錄個人工作時數</a><font style="font-size:20px;color:white;">&nbsp;|&nbsp;</font><a href="overview.php">全體已登錄項目</a><font style="font-size:20px;color:white;">&nbsp;|&nbsp;</font><a href="Personal-Ranking.php">貢獻排行榜</a><font style="font-size:20px;color:white;">&nbsp;|&nbsp;</font><a href="Members-Overview.php">成員明細</a>
</div>

<?php


echo "<p><font style=\"font-size:16px;color:green;\">&nbsp;&nbsp;已有貢獻之成員:&nbsp;" . $row1['count(*)']. "位</font>";


echo "<font style=\"font-size:16px;color:red;\">&nbsp;&nbsp;未有貢獻之成員:&nbsp;" . $NotHonoredNum ."位</font>";
echo "<font style=\"font-size:16px;color:#5e6570;\">&nbsp;&nbsp;註冊會員總數:&nbsp;" . $row2['count(*)'] . "位</font>";

$ParticipatingRatio=round((int)$row1['count(*)']/(int)$row2['count(*)'],3)*100;

echo "<font style=\"font-size:15px;color:black;\">&nbsp;&nbsp;整體參與度評級:&nbsp;</font>";

if ($ParticipatingRatio>=0 && $ParticipatingRatio<=20){
	echo "<font style=\"font-size:15px;color:red;\">極低落(" .  $ParticipatingRatio ."%)</font></p>";	
} elseif($ParticipatingRatio>20 && $ParticipatingRatio<=40){
	echo "<font style=\"font-size:15px;color:#d64022;\">需再加強(" .  $ParticipatingRatio ."%)</font></p>";
} elseif($ParticipatingRatio>40 && $ParticipatingRatio<=50){
	echo "<font style=\"font-size:15px;color:#c46a25;\">尚可(" .  $ParticipatingRatio ."%)</font></p>";
} elseif($ParticipatingRatio>50 && $ParticipatingRatio<=60){
	echo "<font style=\"font-size:15px;color:black;\">普通(" .  $ParticipatingRatio ."%)</font></p>";
} elseif($ParticipatingRatio>60 && $ParticipatingRatio<=80){
	echo "<font style=\"font-size:15px;color:#537021;\">良好(" .  $ParticipatingRatio ."%)</font></p>";
} elseif($ParticipatingRatio>80 && $ParticipatingRatio<=100){
	echo "<font style=\"font-size:15px;color:green;\">優良(" .  $ParticipatingRatio ."%)</font></p>";
} else{
	echo "<font style=\"font-size:15px;color:black;\">暫無資料</font></p>";
}



?>




</div>

<div id="container">
 <table class="columns" style="margin:auto;height:55%;width:100%;">
<tr>
        <td style="margin:auto;width:50%;height:100%;"><div class="chart" id="chart_div" style="border: 1px solid #ccc;width:100%;height:100%;"></div></td>
        <td style="margin:auto;width:50%;height:100%;"><div class="chart" id="chart_div2" style="border: 1px solid #ccc;width:100%;height:100%;"></div></td>
</tr>
</table>


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
</body></html>
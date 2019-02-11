<?php

require_once 'dbconfig.php';

try {

$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
$conn->exec("SET NAMES utf8");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


session_start();



$GetUserMinutesSUM = $conn->prepare('SELECT members.UID, jobstatistics.member_name, jobstatistics.Nickname, SUM(jobstatistics.Minutes) AS Total FROM jobstatistics INNER JOIN members ON jobstatistics.member_name = members.DisplayName GROUP BY jobstatistics.member_name, members.UID, jobstatistics.Nickname ORDER BY Total DESC');
$GetUserMinutesSUM->execute();


} catch(PDOException $e) {
        echo '系統錯誤: ' . $e->getMessage();
		header("location:error.html");
    }




$json_response = array();



while ($row=$GetUserMinutesSUM->fetch()){

$row_array['DisplayName'] = $row['member_name'];
$row_array['Nickname'] = $row['Nickname'];
$row_array['Total'] = $row['Total'];

array_push($json_response,$row_array);

}

echo json_encode($json_response);


?>
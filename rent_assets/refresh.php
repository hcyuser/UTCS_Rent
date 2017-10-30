<?php
if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){
	header("location:/rent_assets/index.php");
	exit();
}
require_once('Connections/connect_mysql.php');

$searchstr = 
"SELECT 
	`rent_acc`, 
	`rent_realname`, 
	`pro_name`, 
	`pro_nickname` 
FROM 
	`rent_list` 
WHERE 
	`state` = '4' 
	AND `return_date` < CURDATE()";

$link_ID = connect_mysql(); // 連線資料庫
$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢
$max_index = mysql_num_rows($searchlist); // 取得資料筆數(rows)
mysql_close($link_ID); // 資料庫斷開連結

for($i = 0; $i < $max_index; $i++){
	// 取出查詢結果，每執行一次就將一組row的值放入$record
	$record = mysql_fetch_array($searchlist);
	
	$link_ID = connect_mysql(); // 連線資料庫
	// 查詢租借者的email
	$searchstr = "SELECT `email` FROM `account_list` WHERE `account` = '".$record['rent_acc']."'";
	$searchlist2 = mysql_query($searchstr, $link_ID); // 送出查詢
	$email = mysql_fetch_row($searchlist2)[0]; // 租借者的email
	mysql_close($link_ID); // 資料庫斷開連結
	
	// inculde寄郵件的參數設定
	require_once('../Connections/send_email_setting.php');
	
	$mail->Subject = "財物租借系統 租借逾期通知"; // 設定郵件標題
	//設定郵件內容
	$mail->Body = "<H3><font color='red'>注意，此信件由系統寄出，請勿直接回信。</font></H3><b>".
		$record['rent_realname']."</b> 您好，您的租借 <b>財產名稱: ".$record['pro_name'].
		"</b><br><font color='red'>已經逾期</font>了! 請盡快帶著物品至公誠樓3樓，資訊科學系辦公室，找助教歸還";
	$mail->AddAddress($email, $record['rent_realname']); // 設定收件者郵件及名稱
	$mail->Send(); // 寄出信件
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>DB自動檢查</title>
</head>

<script type="text/javascript">
var timeDate;
var h, m, s;

function getNowTime(){
	timeDate = new Date();
	h = timeDate.getHours() > 9 ? timeDate.getHours() : '0'+timeDate.getHours();
	m = timeDate.getMinutes() > 9 ? timeDate.getMinutes() : '0'+timeDate.getMinutes();
	s = timeDate.getSeconds() > 9 ? timeDate.getSeconds() : '0'+timeDate.getSeconds();
	document.getElementById("feedback").innerHTML = h +':'+ m +':'+ s;
	setTimeout('getNowTime()',1000); //指定1秒刷新一次
	if (h+m+s == 0) // 當0時0分0秒，重新整理頁面
		location.reload();
}
</script>

<body style="background:#FFFFF4;" onload="getNowTime()">
<div align="center">
<H1>財物租借管理系統 自動檢查租約</H1>
<H1>注意，<font color="#AA0000">請不要關掉此頁面</font>，若要暫時關閉，請一定要記得開回來</H1>
<H2>此頁面將會於<font color="#AA0000">每日0時0分0秒</font>，檢查有無逾期的租約，並寄信給租借人，提醒歸還</H2>
</div>
<div align="center">
<H1>目前時間: <div id="feedback"></div></H1>
</div>
</body>

</html>
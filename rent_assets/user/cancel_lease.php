<?php require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回我的租借頁面
if ( empty($_POST['rentID'])){
	header("location:myLease.php");
	exit();
}
else{
	$rentID=check_input($_POST['rentID']);
	// 刪除租借資料
	$searchstr = "DELETE FROM `rent_list` WHERE `rent_id` = ".$rentID." AND `state` < 4";
	$link_ID = connect_mysql(); // 連線資料庫
	mysql_query($searchstr, $link_ID); // 送出刪除
	mysql_close($link_ID); // 資料庫斷開連結

	header("location:myLease.php");
	exit();
}
?>
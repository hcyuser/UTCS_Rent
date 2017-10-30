<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回上一頁
if ( empty($_POST['proMainID']) || empty($_POST['proSubID']) || empty($_POST['proList'])){
	echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
	exit();
}

else{
	$proMainID = $_POST['proMainID'];
	$proSubID = $_POST['proSubID'];
	$proList = $_POST['proList'];
	
	// 刪除財產資料
	if ($proList == 1) // 屬於萬元以上財產
	$searchstr = "DELETE FROM `10000以上財產` WHERE `財產編號`='$proMainID' 
		AND `財產序號`='$proSubID'";

	else{ // 財產
	$searchstr = "DELETE FROM `非消耗性物品` WHERE `物品編號`='$proMainID' 
		AND `物品序號`='$proSubID'";
	}
	$link_ID = connect_mysql(); // 連線資料庫
	mysql_query($searchstr, $link_ID); // 送出刪除
	
	// 如果有照片，刪除照片
	$searchstr = "DELETE FROM `photo_list` WHERE `pro_mainID`='$proMainID' 
		AND `pro_subID`='$proSubID' AND pro_list='$proList'";
	mysql_query($searchstr, $link_ID); // 送出刪除
	
	mysql_close($link_ID); // 資料庫斷開連結
	
	// 返回上一頁+刷新
	echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>"; 
	exit();
}
?>
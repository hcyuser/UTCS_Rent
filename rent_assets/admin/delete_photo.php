<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回管理財產頁面
if ( empty($_POST['proMainID']) || empty($_POST['proSubID']) || empty($_POST['proList'])){
	header("location:manageProduct1.php");
	exit();
}

else{
	$proMainID = $_POST['proMainID'];
	$proSubID = $_POST['proSubID'];
	$proList = $_POST['proList'];
	
	// 更新財產資料
	if ($proList == 1) // 屬於萬元以上財產
	$searchstr = "UPDATE `10000以上財產` SET `照片` = '0' 
		WHERE `財產編號`='$proMainID' AND `財產序號`='$proSubID'";

	else{ // 財產
	$searchstr = "UPDATE `非消耗性物品` SET `照片` = '0' 
		WHERE `物品編號`='$proMainID' AND `物品序號`='$proSubID'";
	}
	$link_ID = connect_mysql(); // 連線資料庫
	mysql_query($searchstr, $link_ID); // 送出更新
	
	// 刪除照片
	$searchstr = "DELETE FROM `photo_list` WHERE `pro_mainID`='$proMainID' 
		AND `pro_subID`='$proSubID' AND pro_list='$proList'";
	mysql_query($searchstr, $link_ID); // 送出刪除

	mysql_close($link_ID); // 資料庫斷開連結
	echo "<script>history.back();</script>";
	exit();
}
?>
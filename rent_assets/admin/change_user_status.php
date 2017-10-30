<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回我的租借頁面
if ( empty($_POST['userID']) || empty($_POST['actionType']) ){
	header("location:/rent_assets/admin/manageUser.php");
	exit();
}
else{
	$account=check_input($_POST['userID']);
	$actionType = $_POST['actionType'];
	$link_ID = connect_mysql(); // 連線資料庫
	
	// 刪除使用者
	if ($actionType == 1){
		// 刪除此用戶的租借申請
		$searchstr = "DELETE FROM `rent_list` WHERE `state` < 4 AND `rent_acc`='$account'";
		mysql_query($searchstr, $link_ID); // 送出要求
		$searchstr = "DELETE FROM `account_list` WHERE `account`='$account'";
	}
	
	// 升級為管理員
	elseif ($actionType == 2)
		$searchstr = "UPDATE `account_list` SET `level`='2' WHERE `account`='$account'";
		
	// 降級為租借者
	else
		$searchstr = "UPDATE `account_list` SET `level`='1' WHERE `account`='$account'";
	
	mysql_query($searchstr, $link_ID); // 送出要求
	mysql_close($link_ID);

	header("location:/rent_assets/admin/manageUser.php");
	exit();
}
?>
<?php require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回各人資料頁面
if ( empty($_POST['realname']) || empty($_POST['e-mail']) ){
	header("location:userData.php");
	exit();
}

else{
	$userID = $_SESSION['account'];
	$realname = check_input($_POST['realname']);
	$newpassword = check_input($_POST['newpassword']);
	$email = check_input($_POST['e-mail']);
	$phone = check_input($_POST['phone']);
	
	// 更新使用者資料
	$searchstr = 
	"UPDATE 
		`account_list` 
	SET 
		`realname` = '$realname', 
		`department` = '" .$_POST['department'] ."', 
		`grade` = '" .$_POST['grade'] ."', 
		`phone` = '$phone', 
		`email` = '$email'";
	
	if ( !empty($newpassword) ){
		$searchstr .= ", `password`='$newpassword'";
	}
	
	$searchstr .= " WHERE `account`='$userID'";
	$link_ID = connect_mysql(); // 連線資料庫
	mysql_query($searchstr, $link_ID); // 送出更新
	mysql_close($link_ID); // 資料庫斷開連結
	
	echo "<script>alert('您的資料已更新成功');
	document.location.href = 'userData.php';</script>";
}
?>
<?php require_once('Connections/connect_mysql.php'); ?>
<?php
// 若直接以網址進入此頁，返回註冊頁面
if ( empty($_POST['username']) || empty($_POST['realname']) || empty($_POST['password'])
	|| empty($_POST['password2']) || empty($_POST['e-mail']) ){
	header("location:/rent_assets/register.php");
	exit();
}

else{
	session_start();
	$account=check_input($_POST['username']);
	$realname=check_input($_POST['realname']);
	$password=check_input($_POST['password']);
	$dep=$_POST['department'];
	$grade=$_POST['grade'];
	$email=check_input($_POST['e-mail']);
	$phone=check_input($_POST['phone']);
	
	// 檢查使用者帳號是否已存在
	$searchStr = "SELECT * FROM `account_list` WHERE `account`='$account'";
	$link_ID = connect_mysql(); // 連線資料庫
	$searchList = mysql_query($searchStr, $link_ID); // 送出查詢，結果放入$searchList
	mysql_close($link_ID); // 資料庫斷開連結
	$search_num = mysql_num_rows($searchList); // 查詢結果的資料筆數(rows)
	
	// 使用者已存在，註冊失敗
	if($search_num >= 1){
		header("location:/rent_assets/register_fail.php");
		exit();
	}
	
	// 使用者不存在，新增使用者到資料庫
	else{
		// 新使用者，預設是最低權限
		$searchStr = 
		"INSERT INTO `account_list` (
			`account`, `password`, `realname`, `phone`, 
			`email`, `department`, `grade`, `level`) 
		VALUES (
				'$account', '$password', '$realname', '$phone', 
				'$email', '$dep', '$grade', '1')";
		$link_ID = connect_mysql(); // 連線資料庫
		mysql_query($searchStr, $link_ID); // 送出新增
		mysql_close($link_ID); // 資料庫斷開連結
		header("location:/rent_assets/register_success.php");
		exit();
	}
}
?>
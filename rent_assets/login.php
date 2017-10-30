<?php require_once('Connections/connect_mysql.php');?>
<?php
session_start();

// 登入函數
function login($username, $password){
	// 檢查帳號密碼是否正確
	$searchstr = "SELECT * FROM `account_list` 
		WHERE `account`='$username' and `password`='$password'";
	$link_ID = connect_mysql(); // 連線資料庫
	$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢，結果放入$searchlist
	mysql_close($link_ID); // 資料庫斷開連結
	$search_num = mysql_num_rows($searchlist); // 查詢結果的資料筆數(rows)
	
	// 帳號密碼符合
	if($search_num >= 1){
		
		// 如果勾選了 記住我，將資訊存入cookie
		if ( !empty($_POST['remember_me']) ){
			// cookie期效6個月
			setcookie("account", $username, time()+15768000);
			setcookie("password", $password, time()+15768000);
		}
		// 取出查詢結果，將一組row的值放入$search_record中
		$search_record = mysql_fetch_array($searchlist);
		$_SESSION['ch_OK'] = "true"; // 表示已登入
		$_SESSION['account'] = $search_record['account']; // 儲存使用者學號
		$_SESSION['user_level'] = $search_record['level']; // 儲存使用者權限等級
		unset($_SESSION['login_error']);
		
		if($search_record['level'] > 1)
			header("location:/rent_assets/admin/manageLease.php");
		else
			header("location:/rent_assets/user/productList1.php");
	}
	
	// 帳號密碼不符合
	else{
		$_SESSION['login_error'] += 1;
		header("location:/rent_assets/index.php");
	}
}

// 若有COOKIE，自動登入
if ( !empty($_COOKIE['account']) && !empty($_COOKIE['password']) ){
	login($_COOKIE['account'], $_COOKIE['password']);
}

// 若直接以網址進入此頁，又無COOKIE，頁面返回登入頁面
elseif ( empty($_POST['username']) || empty($_POST['password']) ){
	header("location:/rent_assets/index.php");
}

// 一般登入的情況
else{
	$username=check_input($_POST['username']);
	$password=check_input($_POST['password']);
	
	login($username, $password);
}
?>
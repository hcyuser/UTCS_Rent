<?php require_once('Connections/connect_mysql.php'); ?>
<?php
// 若直接以網址進入此頁，返回忘記密碼頁面
if ( empty($_POST['username']) || empty($_POST['e-mail']) ){
	header("location:/rent_assets/forget.php");
	exit();
}

else{
	session_start();
	$username=check_input($_POST['username']);
	$email=check_input($_POST['e-mail']);
	
	// 檢查使用者帳號與信箱是否符合
	$searchStr = "SELECT * FROM `account_list` 
		WHERE `account`='$username' AND `email`='$email'";
	$link_ID = connect_mysql(); // 連線資料庫
	$searchList = mysql_query($searchStr, $link_ID); // 送出查詢，結果放入$searchList
	mysql_close($link_ID); // 資料庫斷開連結
	$search_num = mysql_num_rows($searchList); // 查詢結果的資料筆數(rows)
	
	// 符合，寄送密碼至信箱
	if($search_num >= 1){
		$record = mysql_fetch_array($searchList);
		
		// inculde寄郵件的參數設定
		require_once('Connections/send_email_setting.php');
		
		$mail->Subject = "財物租借系統 找回密碼"; // 設定郵件標題
		//設定郵件內容
		$mail->Body = "<H3><font color='red'>注意，此信件由系統寄出，請勿直接回信。</font></H3><b>".
			$record['realname']."</b> 您好，您的密碼是 <b>".
			$record['password']."</b><br>下次別再忘記密碼囉!";
		$mail->AddAddress($email, $username); // 設定收件者郵件及名稱
		$mail->Send(); // 寄出信件
		
		header("location:/rent_assets/forget_success.php");
		exit();
	}
	
	// 不符合，轉入錯誤頁面
	else{
		header("location:/rent_assets/forget_fail.php");
		exit();
	}
}
?>
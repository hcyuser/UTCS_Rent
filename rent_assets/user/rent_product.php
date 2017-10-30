<?php require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回財產清單頁面
if ( empty($_POST['account']) || empty($_POST['proMainID'])
	|| empty($_POST['proSubID']) ){
	header("location:productList1.php");
	exit();
}
else{
	$account=check_input($_POST['account']);
	$realname=check_input($_POST['realname']);
	$proList=$_POST['pro_list'];
	
	// 新增租借資料
	$searchstr = 
	"INSERT INTO `rent_list`(
	`rent_acc`, `rent_realname`, `pro_list`, 
	`pro_mainID`, `pro_subID`, `pro_name`, 
	`pro_nickname`, `pro_brand`, `pro_place`, 
	`pro_amount`, `state`, `rent_date`, 
	`return_date`
	) 
	VALUES (
	'$account', '$realname', '$proList', '".$_POST['proMainID']."', '" 
	.$_POST['proSubID']."', '".$_POST['pro_name']."', '".$_POST['pro_nickname']."', '"
	.$_POST['pro_brand']."', '".$_POST['pro_place']."', '".$_POST['pro_amount']."', 
	1, '".$_POST['dtp_input1']."', '".$_POST['dtp_input2']."'
	)";
	
	$link_ID = connect_mysql(); // 連線資料庫
	mysql_query($searchstr, $link_ID); // 送出新增
	mysql_close($link_ID); // 資料庫斷開連結
	
	// 如果是預約租借，才寄信通知管理員
	if ( $_POST['dtp_input1'] > date("Y-m-d") ){
		// inculde寄郵件的參數設定
		require_once('../Connections/send_email_setting.php');
		
		$mail->Subject = "財物租借系統 租借申請通知"; // 設定郵件標題
		//設定郵件內容
		$mail->Body = "系統通知您，<br><b>租借人: ".$account." ".$realname.
			"</b><br>剛剛申請了新的租借<br><b>財產編號: ".$_POST['proMainID'].
			"<br>財產序號: ".$_POST['proSubID']."<br>財產名稱: ".$_POST['pro_name'].
			"<br>財產別名: ".$_POST['pro_nickname']."</b>";
		$mail->AddAddress("bob821114@gmail.com", "資科系助教"); // 設定收件者郵件及名稱
		$mail->Send(); // 寄出信件
	}
	
	echo "<script>alert('您的租借申請已經送出，請等待審核');
	window.close();</script>";
	
	exit();
}
?>
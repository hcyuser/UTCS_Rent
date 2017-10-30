<?php require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回多筆租借頁面
if ( empty($_POST['account']) || empty($_POST['proMainID'])
	|| !isset($_POST['proSubID1']) || empty($_POST['proSubID2']) ){
	header("location:rentMultiProduct.php");
	exit();
}
else{
	$account = $_POST['account'];
	$realname = $_POST['realname'];
	$proMainID = $_POST['proMainID'];
	$proSubID1 = $_POST['proSubID1'];
	$proSubID2 = $_POST['proSubID2'];
	$amountOfPro = $proSubID2 - $proSubID1 + 1; // 要租借的財產總數
	$proList = $_POST['proList']; // 財產屬於的清單
	
	// 檢查租借財產是否已存在
	if ($proList == 1)
		$searchStr = "SELECT * FROM `10000以上財產` 
		WHERE `財產編號` = '$proMainID' AND `財產序號` BETWEEN '$proSubID1' AND '$proSubID2'";
	else // $proList == 2
		$searchStr = "SELECT * FROM `非消耗性物品` 
		WHERE `物品編號` = '$proMainID' AND `物品序號` BETWEEN '$proSubID1' AND '$proSubID2'";
	
	$link_ID = connect_mysql(); // 連線資料庫
	$searchList = mysql_query($searchStr, $link_ID); // 送出查詢，結果放入$searchList
	mysql_close($link_ID); // 資料庫斷開連結
	$search_num = mysql_num_rows($searchList); // 查詢結果的資料筆數(rows)
	
	if ($search_num != $amountOfPro){
		echo "<script>alert('您輸入的財產序號區間中\\n有不存在的財產，請檢查');
		history.back();</script>";
		exit();
	}
	
	$recordP = mysql_fetch_array($searchList); // 第一筆財產的資料
	
	// 新增租借資料
	if ($proList == 1){
		$searchstr = 
		"INSERT INTO `rent_list`(
		`rent_acc`, `rent_realname`, `pro_list`, 
		`pro_mainID`, `pro_subID`, `pro_name`, 
		`pro_nickname`, `pro_brand`, `pro_place`, 
		`pro_amount`, `state`, `rent_date`, 
		`return_date`
		) 
		VALUES (
		'$account', '$realname', '$proList', 
		'$proMainID', '$proSubID1"."~"."$proSubID2', '".$recordP['財產名稱']."', '".
		$recordP['財產別名']."', '".$recordP['廠牌型式']."', '".$recordP['存置地點'].
		"', '$amountOfPro', 1, '".date("Y-m-d")."', '".date("Y-m-d")."'
		)";
		$proName = $recordP['財產名稱'];
		$proNickname = $recordP['財產別名'];
	}
	else { // $proList == 2
		$searchstr = 
		"INSERT INTO `rent_list`(
		`rent_acc`, `rent_realname`, `pro_list`, 
		`pro_mainID`, `pro_subID`, `pro_name`, 
		`pro_nickname`, `pro_brand`, `pro_place`, 
		`pro_amount`, `state`, `rent_date`, 
		`return_date`
		) 
		VALUES (
		'$account', '$realname', '$proList', 
		'$proMainID', '$proSubID1"."~"."$proSubID2', '".$recordP['物品名稱']."', '".
		$recordP['物品別名']."', '".$recordP['廠牌']."╱".$recordP['型式']."', '".$recordP['存置地點'].
		"', '$amountOfPro', 1, '".date("Y-m-d")."', '".date("Y-m-d")."'
		)";
		$proName = $recordP['物品名稱'];
		$proNickname = $recordP['物品別名'];
	}
	
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
			"</b><br>剛剛申請了一組多筆租借<br><b>財產編號: $proMainID<br>
			財產序號: $proSubID1 ~ $proSubID2<br>財產名稱: $proName<br>
			財產別名: $proNickname</b>";
		$mail->AddAddress("bob821114@gmail.com", "資科系助教"); // 設定收件者郵件及名稱
		$mail->Send(); // 寄出信件
	}

	echo "<script>alert('您的租借申請已經送出，請等待審核');</script>";
	header("myLease.php");
	exit();
}
?>
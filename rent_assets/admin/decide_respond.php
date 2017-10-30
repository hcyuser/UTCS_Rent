<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回我的租借頁面
if ( empty($_POST['allRentID']) || empty($_POST['allRentState']) ){
	header("location:/rent_assets/admin/manageLease.php");
	exit();
}
else{
	$allRentState = $_POST['allRentState'];
	
	// 搜尋rent_id在陣列中的租借
	$searchstr = 
	"SELECT 
		r.*, 
		a.email 
	FROM 
		`rent_list` AS r, 
		`account_list` AS a 
	WHERE 
		r.`rent_acc` = a.`account` COLLATE utf8_unicode_ci 
		AND r.`rent_id` IN (";
		
	foreach($_POST['allRentID'] as $RentID){
		$searchstr .= "'".$RentID."', ";
	}
	// substr用來刪掉最後一個逗號
	$searchstr = substr($searchstr,0,-2).") ORDER BY `rent_id` DESC"; // 當初回應的陣列是由倒序排列

	$link_ID = connect_mysql(); // 連線資料庫
	$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢，結果放入$searchlist
	$max_index = mysql_num_rows($searchlist); // 查詢結果的資料筆數(rows)

	for($i = 0; $i < $max_index; $i++){
		// 取出查詢結果，每執行一次就將一組row的值放入$record中
		$record = mysql_fetch_array($searchlist);
		if ($record['state'] != $allRentState[$i]){
			// 更新租借申請狀態
			$searchstr = "UPDATE `rent_list` SET `state`=".$allRentState[$i].
				" WHERE `rent_id`=".$record['rent_id'];
			mysql_query($searchstr, $link_ID); // 送出更新
			
			// 如果此租借是多筆租借，跳過更新在庫狀態的動作
			if (preg_match("/~/" ,$record['pro_subID'])){
				continue;
			}
			// 更新財產的在庫狀態
			if ($allRentState[$i] == 4 || $allRentState[$i] == 6){
				// 財產為已出借
				if ($record['pro_list'] == 1)
					$searchstr = "UPDATE `10000以上財產` SET `出租狀態`='1' WHERE `財產編號`='".$record['pro_mainID']."' AND `財產序號`='".$record['pro_subID']."'";
				else
					$searchstr = "UPDATE `非消耗性物品` SET `出租狀態`='1' WHERE `物品編號`='".$record['pro_mainID']."' AND `物品序號`='".$record['pro_subID']."'";
				mysql_query($searchstr, $link_ID); // 送出更新
			}
			elseif ($allRentState[$i] == 5){
				// 財產為未出借
				if ($record['pro_list'] == 1)
					$searchstr = "UPDATE `10000以上財產` SET `出租狀態`='0' WHERE `財產編號`='".$record['pro_mainID']."' AND `財產序號`='".$record['pro_subID']."'";
				else
					$searchstr = "UPDATE `非消耗性物品` SET `出租狀態`='0' WHERE `物品編號`='".$record['pro_mainID']."' AND `物品序號`='".$record['pro_subID']."'";
				mysql_query($searchstr, $link_ID); // 送出更新
			}
		}
		
		// 判斷是否寄信通知租借人
		if ( ($record['state'] == 1 || $record['state'] == 3) && $allRentState[$i] == 2){
			// inculde寄郵件的參數設定
			require_once('../Connections/send_email_setting.php');
			
			$mail->Subject = "財物租借系統 允許租借通知"; // 設定郵件標題
			//設定郵件內容
			$mail->Body = "<H3><font color='red'>注意，此信件由系統寄出，請勿直接回信。</font></H3><b>".
				$record['rent_realname']."</b> 您好，您申請的租借 <b>財產名稱: ".$record['pro_name'].
				"</b><br>已經取得同意了! 請至公誠樓3樓，資訊科學系辦公室，找助教領取";
			$mail->AddAddress($record['email'], $record['rent_realname']); // 設定收件者郵件及名稱
			$mail->Send(); // 寄出信件
		}
	}

	mysql_close($link_ID); // 資料庫斷開連結
	echo "<script>alert('回應已更新');
	history.back();</script>";
	exit();
}
?>
<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回管理物品頁面
if ( empty($_POST['input1']) || empty($_POST['input2']) ){
	header("location:manageProduct2.php");
	exit();
}

else{
	$proMainID = $_POST['input1'];
	$proSubID = $_POST['input2'];
	$OldProMainID = $_POST['OldInput1'];
	$OldProSubID = $_POST['OldInput2'];
	
	// 更新物品資料
	$searchstr =
	"UPDATE 
		`非消耗性物品` 
	SET 
		`物品編號` = '$proMainID', 
		`物品序號` = '$proSubID', 
		`物品名稱` = '".$_POST['input3']."', 
		`物品別名` = '".$_POST['input4']."', 
		`廠牌` = '".$_POST['input5']."', 
		`型式` = '".$_POST['input6']."', 
		`單位` = '".$_POST['input7']."', 
		`數量` = '".$_POST['input8']."', 
		`單價` = '".$_POST['input9']."', 
		`總價` = '".$_POST['input10']."', 
		`保管單位` = '".$_POST['input11']."', 
		`保管人` = '".$_POST['input12']."', 
		`使用單位` = '".$_POST['input13']."', 
		`使用人` = '".$_POST['input14']."', 
		`存置地點` = '".$_POST['input15']."', 
		`取得日期` = '".$_POST['input16']."', 
		`入帳日期` = '".$_POST['input17']."', 
		`申請日期` = '".$_POST['input18']."', 
		`增置方式` = '".$_POST['input19']."',
		`使用年限` = '".$_POST['input20']."',
		`經費來源` = '".$_POST['input21']."',
		`會計科目` = '".$_POST['input22']."',
		`物品區分` = '".$_POST['input23']."',
		`廠商` = '".$_POST['input24']."',
		`備註` = '".$_POST['input25']."',
		`出租狀態` = '".$_POST['input26']."'";
	
	// 如果有上傳照片
	if ( $_FILES['uploadFile']['size'] > 0 ) 
		$searchstr .= ", `照片` = '1'";
	
	$searchstr .= " WHERE `物品編號`='$OldProMainID' AND `物品序號`='$OldProSubID'";
	$link_ID = connect_mysql(); // 連線資料庫
	mysql_query($searchstr, $link_ID); // 送出更新
	
	// 如果proID有更變，更新此財產照片對應的proID
	if ( $proMainID != $_POST['OldInput1'] || $proSubID != $_POST['OldInput2'])
	{
		$searchstr = "UPDATE `photo_list` 
		SET `pro_mainID` = '$proMainID', `pro_subID` = '$proSubID'
		WHERE `pro_mainID`='$OldProMainID' AND `pro_subID`='$OldProSubID' 
		AND `pro_list`='2'";
		mysql_query($searchstr, $link_ID); // 送出更新
	}
	
	
	// 如果有照片，上傳照片
	if ( $_FILES['uploadFile']['size'] > 0 ){
		// 開啟圖片檔
		$file = fopen($_FILES['uploadFile']['tmp_name'], "rb");
		// 讀入圖片檔資料
		$fileContents = fread($file, filesize($_FILES['uploadFile']['tmp_name'])); 
		// 關閉圖片檔
		fclose($file);
		
		// 圖片檔案資料編碼
		$fileContents = base64_encode($fileContents);
		
		// 照片格式
		$fileType = $_FILES["uploadFile"]["type"];
		
		// 連線資料庫
		$link_ID = connect_mysql();

		// 組合查詢字串，如果資料不存在就 INSERT，存在就 UPDATE
		$SQLstr=
		"INSERT INTO `photo_list`(
			`pro_mainID`, `pro_subID`, `pro_list`, 
			`pic`, `type`
		) 
		VALUES (
				'$proMainID', '$proSubID', '2', '$fileContents', 
				'$fileType'
			) ON DUPLICATE KEY 
		UPDATE 
			`pic` = '$fileContents', 
			`type` = '$fileType'";
		

		//將圖片檔案資料寫入資料庫
		mysql_query($SQLstr, $link_ID) or die(mysql_error()); 
	}
	
	mysql_close($link_ID); // 資料庫斷開連結
	// 不能用history.back()，因為proID有可能會改
	echo "<script>alert('資料已更新');
	location.href='manageProductData2.php?pro_mainID=".$proMainID."&pro_subID=".$proSubID."';</script>";
	exit();
}
?>
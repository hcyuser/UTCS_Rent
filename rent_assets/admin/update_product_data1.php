<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回管理財產頁面
if ( empty($_POST['input1']) || empty($_POST['input2']) ){
	header("location:manageProduct1.php");
	exit();
}

else{
	$proMainID = $_POST['input1'];
	$proSubID = $_POST['input2'];
	$OldProMainID = $_POST['OldInput1'];
	$OldProSubID = $_POST['OldInput2'];
	
	// 更新財產資料
	$searchstr =
	"UPDATE 
		`10000以上財產` 
	SET 
		`財產編號` = '$proMainID', 
		`財產序號` = '$proSubID', 
		`財產名稱` = '".$_POST['input3']."', 
		`財產別名` = '".$_POST['input4']."', 
		`財產性質` = '".$_POST['input5']."', 
		`廠牌型式` = '".$_POST['input6']."', 
		`購置日期` = '".$_POST['input7']."', 
		`移動日期` = '".$_POST['input8']."', 
		`單位` = '".$_POST['input9']."', 
		`數量` = '".$_POST['input10']."', 
		`單價` = '".$_POST['input11']."', 
		`使用年限` = '".$_POST['input12']."', 
		`保管單位` = '".$_POST['input13']."', 
		`保管人` = '".$_POST['input14']."', 
		`使用單位` = '".$_POST['input15']."', 
		`使用人` = '".$_POST['input16']."', 
		`存置地點` = '".$_POST['input17']."', 
		`原登錄號` = '".$_POST['input18']."', 
		`出租狀態` = '".$_POST['input19']."'";
	
	// 如果有上傳照片
	if ( $_FILES['uploadFile']['size'] > 0 ) 
		$searchstr .= ", `照片` = '1'";

	$searchstr .= " WHERE `財產編號`='$OldProMainID' AND `財產序號`='$OldProSubID'";
	$link_ID = connect_mysql(); // 連線資料庫
	mysql_query($searchstr, $link_ID); // 送出更新
	
	// 如果proID有更變，更新此財產照片對應的proID
	if ( $proMainID != $_POST['OldInput1'] || $proSubID != $_POST['OldInput2'])
	{
		$searchstr = "UPDATE `photo_list` 
		SET `pro_mainID` = '$proMainID', `pro_subID` = '$proSubID'
		WHERE `pro_mainID`='$OldProMainID' AND `pro_subID`='$OldProSubID' 
		AND `pro_list`='1'";
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
				'$proMainID', '$proSubID', '1', '$fileContents', 
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
	location.href='manageProductData1.php?pro_mainID=".$proMainID."&pro_subID=".$proSubID."';</script>"; 
	exit();

}
?>
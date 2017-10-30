<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 若直接以網址進入此頁，返回管理物品頁面
if ( empty($_POST['input1']) || empty($_POST['input2-1Post']) ){
	header("location:addNewProduct2.php");
	exit();
}

else{
	$proMainID = $_POST['input1'];
	$proSubIDPre = $_POST['input2Pre']; // 新式序號的前半段
	$proSubID = $_POST['input2-1Post']; // 新式序號的後半段(起始)
	$proSubID2 = $_POST['input2-2Post']; // 新式序號的後半段(結尾)
	$proSubIDLength = strlen($proSubID); // 新式序號的後半段長度
	$amountOfPro = $proSubID2 - $proSubID + 1; // 要新增物品的總數
	$proSubIDArray = array($amountOfPro); // 所有要新增物品的proSubID
	
	// 檢查物品是否已存在
	$searchStr = "SELECT `物品編號`,`物品序號` FROM `非消耗性物品` 
		WHERE `物品編號`='$proMainID' AND `物品序號` IN (";
	
	// sprintf("%'07d")補0到7位數
	// 這裡把7改成$proSubIDLength實現動態長度，原本多長，就補到多長
	for($i = 0; $i < $amountOfPro; $i++){
		// 將所有要新增物品的proSubID儲存起來
		$proSubIDArray[$i] = sprintf("%s%'0".$proSubIDLength."d",$proSubIDPre,($proSubID+$i));
		$searchStr .= "'".$proSubIDArray[$i]."', ";
	}
	// substr用來刪掉最後一個逗號
	$searchStr = substr($searchStr,0,-2).")";

	$link_ID = connect_mysql(); // 連線資料庫
	$searchList = mysql_query($searchStr, $link_ID) or die(mysql_error());  // 送出查詢，結果放入$searchList
	mysql_close($link_ID); // 資料庫斷開連結
	$search_num = mysql_num_rows($searchList); // 查詢結果的資料筆數(rows)

	// 物品已存在，新增失敗
	if($search_num >= 1){
		$message = "";
		for($i = 0; $i < $search_num; $i++){
			// 取出查詢結果，每執行一次就將一組row的值放入$record中
			$record = mysql_fetch_array($searchList);
			
			// 打/n會被php變成換行，要打//n，讓//被php的判斷成/，js就可以讀到/n
			// 解決文章 http://stackoverflow.com/questions/6146835/how-do-i-let-php-echo-n-as-plain-text-for-javascript-and-not-have-the-n-cr
			$message .= "物品編號: " .$record['物品編號']. "\\n物品序號: " .$record['物品序號']."\\n";
		}
		echo "<script>alert('".$message."資料已存在，整組資料未新增'); history.back();</script>";
		exit();
	}
	
	// 檢查有無選擇照片
	if ( $_FILES['uploadFile']['size'] > 0 ) 
		$hasPhoto = 1;
	else
		$hasPhoto = 0;
	
	$link_ID = connect_mysql(); // 連線資料庫
	
	// 依序新增物品
	for($i = 0; $i < $amountOfPro; $i++){
		// 新增物品
		$searchStr =
		"INSERT INTO `非消耗性物品` (
		`物品編號`, `物品序號`, `物品名稱`, 
		`物品別名`, `廠牌`, `型式`, 
		`單位`, `數量`, `單價`, `總價`, 
		`保管單位`, `保管人`, `使用單位`, 
		`使用人`, `存置地點`, `取得日期`, 
		`入帳日期`, `申請日期`, 
		`增置方式`, `使用年限`, 
		`經費來源`, `會計科目`, 
		`物品區分`, `廠商`, `備註`, 
		`出租狀態`, `照片`
		) 
		VALUES (
			'$proMainID', '".$proSubIDArray[$i]."', '".$_POST['input3']."', '".$_POST['input4']."', 
			'".$_POST['input5']."', '".$_POST['input6']."', '".$_POST['input7']."', 
			'".$_POST['input8']."', '".$_POST['input9']."', '".$_POST['input10']."', 
			'".$_POST['input11']."', '".$_POST['input12']."', '".$_POST['input13']."', 
			'".$_POST['input14']."', '".$_POST['input15']."', '".$_POST['input16']."', 
			'".$_POST['input17']."', '".$_POST['input18']."', '".$_POST['input19']."', 
			'".$_POST['input20']."', '".$_POST['input21']."', '".$_POST['input22']."', 
			'".$_POST['input23']."', '".$_POST['input24']."', '".$_POST['input25']."', 
			'0', '$hasPhoto'
		)";
		
		mysql_query($searchStr, $link_ID); // 送出更新
	}
	
	// 如果有照片，上傳照片
	if ( $hasPhoto ){
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
		
		for($i = 0; $i < $amountOfPro; $i++){
			// 組合查詢字串
			$SQLstr=
			"INSERT INTO `photo_list`(
				`pro_mainID`, `pro_subID`, `pro_list`, 
				`pic`, `type`
			) 
			VALUES (
					'$proMainID', '".$proSubIDArray[$i]."', '2', '$fileContents', 
					'$fileType'
			)";
			
			// 將圖片檔案資料寫入資料庫
			mysql_query($SQLstr, $link_ID) or die(mysql_error());
		}
	}

	mysql_close($link_ID); // 資料庫斷開連結
	echo "<script>alert('資料已新增'); location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
	exit();
}
?>
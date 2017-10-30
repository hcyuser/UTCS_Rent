<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 搜尋財產的資料
$searchstr = "SELECT * FROM `10000以上財產` WHERE `財產編號`='"
	.$_GET['pro_mainID']."' AND `財產序號`='".$_GET['pro_subID']."'";

$link_ID = connect_mysql(); // 連線資料庫
$searchlist = mysql_query($searchstr, $link_ID);
$recordP = mysql_fetch_array($searchlist); // 財產的資料

mysql_close($link_ID); // 資料庫斷開連結
?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>管理財產: <?php echo $recordP['財產名稱']; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- 引入 Bootstrap -->
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
	<script src="../bootstrap/js/jquery.min.js"></script>
	<!-- 包括所有已編譯的插件 -->
	<script src="../bootstrap/js/bootstrap.min.js"></script>
</head>

<script type="text/javascript">
// $(window).load()頁面讀取後執行內容
$(window).load(function(){
	
	// 圖片縮圖
	$("img").each(function(i){
		// 移除目前設定的影像長寬
		$(this).removeAttr('width');
		$(this).removeAttr('height');
	
		// 取得影像實際的長寬
		var imgW = $(this).width();
		var imgH = $(this).height();
		
		// 計算縮放比例
		var pre=1;
		if ( $(this).attr("_w") != 0)
			pre = $(this).attr("_w")/imgW;
		else
			pre = $(this).attr("_h")/imgH;
		
		// 設定目前的縮放比例
		$(this).width(imgW*pre);
		$(this).height(imgH*pre);
	});
});

// 確定刪除照片
function show_confirm(proMainID ,proSubID)
{
	var ans = confirm("確定要刪除此照片?");; 	
	
	if (ans)
		document.getElementById("formProID").submit();
	else
		return;
}
</script>

<style type="text/css">
#content_body {
	font-size:21px;
	font-weight:bold;
	margin:100px 50px 50px auto;
}
</style>

<body style="background:#FFFFF4;">
<div class="container">
	<form method="POST" action="update_product_data1.php" Enctype="multipart/form-data">
		<div class="row" id="content_body">
		<div class="col-lg-12">
			<table class="table table-bordered">
			<tbody>
				<tr>
					<td colspan="2" align="center">
					<?php
					if($recordP['照片'])
						echo "<img src=\"show_photo.php?pro_list=1&pro_mainID=".$recordP['財產編號']."&pro_subID=".$recordP['財產序號']."\" _w='800'>";
					else echo '暫無照片';?>
					<br>
					<table class="table-bordered">
						<tr>
							<td>選擇新照片(容量需8MB以下):<BR>上傳後舊照片會被刪除
							<input type="file" name="uploadFile"></td>
							<td><input type="button" class="btn btn-danger" value="刪除目前照片" onclick="show_confirm();"></td>
						</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td width="50%">
					<label>財產編號</label>
					<input type="text" class="form-control input-lg" name="input1" placeholder="財產編號" value="<?php echo $recordP['財產編號']; ?>" required>
					<input type="hidden" name="OldInput1" value="<?php echo $recordP['財產編號']; ?>">
					</td>
					<td>
					<label>財產序號</label>
					<input type="text" class="form-control input-lg" name="input2" placeholder="財產序號" value="<?php echo $recordP['財產序號']; ?>" required>
					<input type="hidden" name="OldInput2" value="<?php echo $recordP['財產序號']; ?>">
					</td>
				</tr>
				<tr>
					<td>
					<label>財產名稱</label>
					<input type="text" class="form-control input-lg" name="input3" placeholder="財產名稱" value="<?php echo $recordP['財產名稱']; ?>">
					</td>
					<td>
					<label>財產別名</label>
					<input type="text" class="form-control input-lg" name="input4" placeholder="財產別名" value="<?php echo $recordP['財產別名']; ?>">
					</td>
				</tr>
				<tr>
					<td>
					<label>財產性質</label>
					<input type="text" class="form-control input-lg" name="input5" placeholder="財產性質" value="<?php echo $recordP['財產性質']; ?>">
					</td>
					<td>
					<label>廠牌╱型式</label>
					<input type="text" class="form-control input-lg" name="input6" placeholder="廠牌╱型式" 
					value="<?php if( empty($recordP['廠牌型式']) ) echo "╱"; else echo $recordP['廠牌型式']; ?>">
					</td>
				</tr>
				<tr>
					<td>
					<label>購置日期</label>
					<input type="text" class="form-control input-lg" name="input7" placeholder="購置日期" value="<?php echo $recordP['購置日期']; ?>">
					</td>
					<td>
					<label>移動日期</label>
					<input type="text" class="form-control input-lg" name="input8" placeholder="移動日期" value="<?php echo $recordP['移動日期']; ?>">
					</td>
				</tr>
				<tr>
					<td>
					<label>單位</label>
					<input type="text" class="form-control input-lg" name="input9" placeholder="單位" value="<?php echo $recordP['單位']; ?>">
					</td>
					<td>
					<label>數量</label>
					<input type="text" class="form-control input-lg" name="input10" placeholder="數量" value="<?php echo $recordP['數量']; ?>">
					</td>
				</tr>
				<tr>
					<td>
					<label>單價</label>
					<input type="text" class="form-control input-lg" name="input11" placeholder="單價" value="<?php echo $recordP['單價']; ?>">
					</td>
					<td>
					<label>使用年限</label>
					<input type="text" class="form-control input-lg" name="input12" placeholder="使用年限" value="<?php echo $recordP['使用年限']; ?>">
					</td>
				</tr>
				<tr>
					<td>
					<label>保管單位</label>
					<input type="text" class="form-control input-lg" name="input13" placeholder="保管單位" value="<?php echo $recordP['保管單位']; ?>">
					</td>
					<td>
					<label>保管人</label>
					<input type="text" class="form-control input-lg" name="input14" placeholder="保管人" value="<?php echo $recordP['保管人']; ?>">
					</td>
				</tr>
				<tr>
					<td>
					<label>使用單位</label>
					<input type="text" class="form-control input-lg" name="input15" placeholder="使用單位" value="<?php echo $recordP['使用單位']; ?>">
					</td>
					<td>
					<label>使用人</label>
					<input type="text" class="form-control input-lg" name="input16" placeholder="使用人" value="<?php echo $recordP['使用人']; ?>">
					</td>
				</tr>
				<tr>
					<td>
					<label>存置地點</label>
					<input type="text" class="form-control input-lg" name="input17" placeholder="存置地點" value="<?php echo $recordP['存置地點']; ?>">
					</td>
					<td>
					<label>原登錄號</label>
					<input type="text" class="form-control input-lg" name="input18" placeholder="原登錄號" value="<?php echo $recordP['原登錄號']; ?>">
					</td>
				</tr>
				<tr>
					<td>
					<label>出租狀態 (正常情況下，不須手動更改)</label>
					<select class="form-control input-lg" name="input19">
						<option value="0" <?php if(!$recordP['出租狀態']) echo "select";?>>未出借</option>
						<option value="1" <?php if($recordP['出租狀態']) echo "select";?>>已出借</option>
					</select>
					</td>
				</tr>
			</tbody>
			</table>
			
			<div class="form-group">
				<input type="submit" class="btn btn-lg btn-success btn-block" value="確定更新資料 (有選擇照片會上傳)">
			</div>
		</div>
		</div>
	</form>
	<!-- 隱藏的表單，確定刪除照片時，傳值到另一個頁面 -->
	<form id="formProID" method="POST" action="delete_photo.php">
		<input type="hidden" name="proMainID" value="<?php echo $recordP['財產編號']; ?>">
		<input type="hidden" name="proSubID" value="<?php echo $recordP['財產序號']; ?>">
		<input type="hidden" name="proList" value="1">
	</form>
</div>

</body>

</html>

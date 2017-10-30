<?php require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php');
header("Cache-control: private"); // 避免回上一頁時，表單被清空資料 ?>

<?php
$userID = $_SESSION['account'];
// 搜尋使用者的資料
$searchstr = "SELECT * FROM `account_list` WHERE `account`='$userID'";
$link_ID = connect_mysql(); // 連線資料庫
$searchlist = mysql_query($searchstr, $link_ID);
$recordU = mysql_fetch_array($searchlist); // 使用者的資料

// 搜尋財產的資料
if ($_GET['pro_list']==1){
	$searchstr = "SELECT * FROM `10000以上財產` WHERE `財產編號`='"
		.$_GET['pro_mainID']."' AND `財產序號`='".$_GET['pro_subID']."'";
}
else if ($_GET['pro_list']==2){
	$searchstr = "SELECT * FROM `非消耗性物品` WHERE `物品編號`='"
		.$_GET['pro_mainID']."' AND `物品序號`='".$_GET['pro_subID']."'";
}

$searchlist = mysql_query($searchstr, $link_ID);
$recordP = mysql_fetch_array($searchlist); // 財產的資料

// 搜尋租借申請
$searchstr = "SELECT * FROM `rent_list` WHERE `pro_mainID`='".$_GET['pro_mainID']."' AND `pro_subID`='"
	.$_GET['pro_subID']."' AND `return_date` >= CURDATE() AND 
	(`state` < '3' || `state` = '4') ORDER BY `rent_date` ";
$searchlist = mysql_query($searchstr, $link_ID);
$max_index = mysql_num_rows($searchlist); // 取得資料筆數(rows)
for($i = 0; $i < $max_index; $i++){
	// 取出查詢結果，每執行一次就將一組row的值放入陣列$recordR
	$recordR[$i] = mysql_fetch_array($searchlist);
}

mysql_close($link_ID); // 資料庫斷開連結
?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>租借財產: <?php if($_GET['pro_list']==1) echo $recordP['財產名稱']; else echo $recordP['物品名稱']; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- 引入 Bootstrap -->
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
	<script src="../bootstrap/js/jquery.min.js"></script>
	<!-- 包括所有已編譯的插件 -->
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	
    <!-- Custom CSS -->
    <link href="../css/simple-sidebar.css" rel="stylesheet">
	
	<!-- Custom 時間選擇器插鍵 -->
	<link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
	<script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
	<script type="text/javascript" src="../js/locales/bootstrap-datetimepicker.zh-TW.js" charset="UTF-8"></script>
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

	// 自動選取目前的年級
	$("#grade").val("<?php echo $recordU['grade']; ?>");
});

// 此財產已被預約的日期段
var reservedDate = [<?php
	if($max_index > 0){
		// 最後一筆日期前，要多印','
		for($i = 0; $i < $max_index-1; $i++){
			echo str_replace("-","",$recordR[$i]['rent_date']).",".
			str_replace("-","",$recordR[$i]['return_date']).",";
		} 
		echo str_replace("-","",$recordR[$max_index-1]['rent_date']).",".
			str_replace("-","",$recordR[$max_index-1]['return_date']);
	} ?>
];

// 檢查輸入的資料，是否符合預期
function checkInputData(){
	
	// 日期未輸入
	if( document.getElementById("dtp_input1").value == "" ||
		document.getElementById("dtp_input2").value == ""){
		alert("請選擇租借日期");
		return false; // false:阻止交提表單
	}
	
	// 日期格式為2013-12-06，使用正規表示法將 '-' 去掉，變成20131206
	var startDate = document.getElementById("dtp_input1").value.replace(/-/g, "");
	var endDate = document.getElementById("dtp_input2").value.replace(/-/g, "");
	
	// 歸還日期要在起始日期之後
	if(endDate < startDate){
		alert("歸還日期 要在 起始日期 之後");
		return false;
	}
	
	// 檢查租借時間有無重複
	if (reservedDate.length > 0){
		for (var i = 0; i < reservedDate.length; i = i+2){
			if( reservedDate[i] <= startDate && startDate <= reservedDate[i+1] ){
				alert("\"起始日期\" 在別人的租借時段內\n請檢查 已被預約的日期");
				return false;
			}
			if( reservedDate[i] <= endDate && endDate <= reservedDate[i+1] ){
				alert("\"歸還日期\" 在別人的租借時段內\n請檢查 已被預約的日期");
				return false;
			}
		}
	}
	
	// 在交提表單前，把年級的disabled清掉，disabled的欄位不會傳遞
	$("#grade").removeAttr("disabled");
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
	<form method="POST" action="rent_product.php" onsubmit="return checkInputData()">
		<div class="row" id="content_body">
		<div class="col-lg-12">
			<table class="table table-bordered">
			<tbody>
				<?php if($_GET['pro_list']==1){ // 屬於萬元財產 ?>
				<tr>
					<td rowspan=10 width="50%">
					<?php if($recordP['照片'])
							echo "<img src=\"show_photo.php?pro_list=1&pro_mainID=".$recordP['財產編號']."&pro_subID=".$recordP['財產序號']."\" _w='650'>";
						  else echo '暫無照片';?>
					</td>
				</tr>
				<tr>
					<td>財產編號</td>
					<td><?php echo $recordP['財產編號']; ?>
					<input type="hidden" name="proMainID" value="<?php echo $recordP['財產編號']; ?>">
					</td>
				</tr>
				<tr>
					<td>財產序號</td>
					<td><?php echo $recordP['財產序號']; ?>
					<input type="hidden" name="proSubID" value="<?php echo $recordP['財產序號']; ?>">
					</td>
				</tr>
				<tr>
					<td>財產名稱</td>
					<td><?php echo $recordP['財產名稱']; ?>
					<input type="hidden" name="pro_name" value="<?php echo $recordP['財產名稱']; ?>">
					</td>
				</tr>
				<tr>
					<td>財產別名</td>
					<td><?php echo $recordP['財產別名']; ?>
					<input type="hidden" name="pro_nickname" value="<?php echo $recordP['財產別名']; ?>">
					</td>
				</tr>
				<tr>
					<td>廠牌╱型式</td>
					<td><?php echo $recordP['廠牌型式']; ?>
					<input type="hidden" name="pro_brand" value="<?php echo $recordP['廠牌型式']; ?>">
					</td>
				</tr>
				<?php } ?>
				
				<?php if($_GET['pro_list']==2){ // 屬於財產 ?>
				<tr>
					<td rowspan=10 width="50%">
					<?php if($recordP['照片'])
							echo "<img src=\"show_photo.php?pro_list=2&pro_mainID=".$recordP['物品編號']."&pro_subID=".$recordP['物品序號']."\" _w='650'>";
						  else echo '暫無照片';?>
					</td>
				</tr>
				<tr>
					<td>物品編號</td>
					<td><?php echo $recordP['物品編號']; ?>
					<input type="hidden" name="proMainID" value="<?php echo $recordP['物品編號']; ?>">
					</td>
				</tr>
				<tr>
					<td>物品序號</td>
					<td><?php echo $recordP['物品序號']; ?>
					<input type="hidden" name="proSubID" value="<?php echo $recordP['物品序號']; ?>">
					</td>
				</tr>
				<tr>
					<td>物品名稱</td>
					<td><?php echo $recordP['物品名稱']; ?>
					<input type="hidden" name="pro_name" value="<?php echo $recordP['物品名稱']; ?>">
					</td>
				</tr>
				<tr>
					<td>物品別名</td>
					<td><?php echo $recordP['物品別名']; ?>
					<input type="hidden" name="pro_nickname" value="<?php echo $recordP['物品別名']; ?>">
					</td>
				</tr>
				<tr>
					<td>廠牌╱型式</td>
					<td><?php echo $recordP['廠牌']."╱".$recordP['型式']; ?>
					<input type="hidden" name="pro_brand" value="<?php echo $recordP['廠牌']; ?>">
					</td>
				</tr>
				<?php } ?>
				
				<tr>
					<td>存置地點</td>
					<td><?php echo $recordP['存置地點']; ?>
					<input type="hidden" name="pro_place" value="<?php echo $recordP['存置地點']; ?>">
					</td>
				</tr>
				<tr>
					<td>數量╱單位
					<td><?php echo $recordP['數量']." ".$recordP['單位']; ?></td>
					<input type="hidden" name="pro_amount" value="<?php echo $recordP['數量']; ?>">
					</td>
				</tr>
				<tr>
					<td>目前租借狀態</td>
					<td><?php if($recordP['出租狀態']) echo "<span class='text-danger'>已出借</span>";
						else echo "未出借"; ?>
					</td>
				</tr>
				<tr>
					<td>已被預約的日期</td>
					<td width=280px><?php 
						for($i = 0; $i < $max_index; $i++){
							echo "由".$recordR[$i]['rent_acc'].'租借:<br>'.$recordR[$i]['rent_date']." ~ ".$recordR[$i]['return_date']."<br><br>";
						}; ?>
					</td>
				</tr>
				<input type="hidden" name="pro_list" value="<?php echo $_GET['pro_list']; ?>">
			</tbody>
			</table>
			
			<table class="table table-bordered">
			<tbody>
				<tr>
					<td>
					<p>租借日期選擇</p>
					<div class="form-group">
						<label for="dtp_input1" class="col-md-2 control-label">起始日期</label>
						<div class="input-group date form_date col-md-8" data-date="" data-date-format="yyyy MM dd" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
							<input class="form-control" size="16" type="text" value="" id="TESTI" readonly>
							<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
						<input type="hidden" name="dtp_input1" id="dtp_input1" class="form-control" /><br/>
					</div>
					<div class="form-group">
						<label for="dtp_input2" class="col-md-2 control-label">歸還日期</label>
						<div class="input-group date form_date col-md-8" data-date="" data-date-format="yyyy MM dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
							<input class="form-control" size="16" type="text" value="" readonly>
							<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
						<input type="hidden" name="dtp_input2" id="dtp_input2" class="form-control" /><br/>
					</div>
					</td>
					<td style="vertical-align: middle">
					<div class="form-group">
						<input type="submit" class="btn btn-lg btn-success btn-block" value="確定租借">
					</div>
					</td>
				</tr>
			</tbody>
			</table>

			<p>租借人資料 (有誤請至<a href="userData.php" target="_blank">個人檔案</a>修改)</p>
			<div class="row">
				<div class="col-md-6">
				<div class="form-group">
				<label>學號</label>
				<input type="text" name="account" class="form-control input-lg" 
				value="<?php echo $recordU['account'];?>" placeholder="學號" readonly>
				</div>
				</div>
				
				<div class="col-md-6">
				<div class="form-group">
				<label>姓名</label>
				<input type="text" name="realname" class="form-control input-lg" 
				value="<?php echo $recordU['realname'];?>" placeholder="姓名" readonly>
				</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6">
				<div class="form-group">
				<label>科系</label>
				<input type="text" class="form-control input-lg" 
				value="<?php echo $recordU['department'];?>" placeholder="科系" readonly>
				</div>
				</div>
				
				<div class="col-md-6">
				<div class="form-group">
				<label>年級</label>
				<select class="form-control input-lg" id="grade" disabled>
					<option value="1">大一</option>
					<option value="2">大二</option>
					<option value="3">大三</option>
					<option value="4">大四</option>
					<option value="5">碩一</option>
					<option value="6">碩二</option>
				</select>
				</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
				<div class="form-group">
				<label>e-mail (通知信件會寄送到此信箱)</label>
				<input type="email" class="form-control input-lg" 
				value="<?php echo $recordU['email'];?>" placeholder="e-mail" readonly>
				</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
				<div class="form-group" style="text-align:left">
				<label>手機號碼 (若物品逾期未還，會打電話通知您)</label>
				<input type="text" class="form-control input-lg" 
				value="<?php echo $recordU['phone'];?>" placeholder="手機號碼" readonly>
				</div>
				</div>
			</div>
		</div>
		</div>
	</form>
</div>
	
<!-- 時間選擇器細節設定，include位置不可換，原因未知 -->
<script type="text/javascript">
$('.form_date').datetimepicker({
	language:  'zh-TW',
	weekStart: 0,
	todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	forceParse: 0,
	startDate: <?php echo "'".date("Y/m/d")."'"; ?> // 今天以前的日期不能選擇
});
</script>

</body>

</html>

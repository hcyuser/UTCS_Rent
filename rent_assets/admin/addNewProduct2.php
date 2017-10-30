<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); 
header("Cache-control: private"); // 避免回上一頁時，表單被清空資料 ?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>新增非消耗性物品</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- 引入 Bootstrap -->
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
	<script src="../bootstrap/js/jquery.min.js"></script>
	<!-- 包括所有已編譯的插件 -->
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	
	<!-- Custom CSS -->
    <link href="../css/simple-sidebar.css" rel="stylesheet">
</head>

<script type="text/javascript">
// $(window).load()頁面讀取後執行內容
$(window).load(function(){
	// 將menuNum14-2 HighLight
	$("#menuNum14-2").addClass("active");
	
	// 當input2-1失去焦點時，將值複製到input2-2
	$("#input2-1").blur(function(){
		// 如果input2-2跟input2-1長度不同
		if ($("#input2-2").val().length != $("#input2-1").val().length)
			$("#input2-2").val($("#input2-1").val());
	});
});

// 點擊 使用說明 時
function openNewWindow(theURL,winName,win_width,win_height) { 
	var PosX = (screen.width-win_width)/2;
	var PosY = (screen.height-win_height)/2;
	features = "width="+win_width+",height="+win_height+",top="+PosY+",left="+PosX;
	window.open(theURL,winName,features);
} 

// 檢查輸入的資料，是否符合預期
function checkInputData(){
	var startNum = document.getElementById("input2-1").value;
	var endNum = document.getElementById("input2-2").value;
	var origSN = startNum, origEN = endNum; // 未切前後半的號碼
	
	// 舊式的物品序號，純數字且長度只有8碼，不需轉換
	if (startNum.length > 8)
	{
		var regex = new RegExp("([0-9]*[^0-9])([0-9]+)", ""); // 正規表示法，篩選條件
		startNum.match(regex); // match起始號碼
		startNum = RegExp.$2; // 新式序號的後半段(起始)
		endNum.match(regex); // match結尾號碼
		endNum = RegExp.$2; // 新式序號的後半段(結尾)
		document.getElementById("input2Pre").value = RegExp.$1; // 新式序號的前半段
	}
	
	// 起始號碼 比 結尾號碼 大
	if(startNum > endNum){
		alert("\"起始號碼\" 應該要小於 \"結尾號碼\" \n請檢查");
		document.getElementById("input2-1").focus();
		scroll(0,0); // 頁面回頂部
		return false; // false:阻止交提表單
	}
	
	// 號碼長度不同
	if(startNum.length != endNum.length){
		alert("\"起始號碼\" 長度與 \"結尾號碼\" 不同\n請檢查");
		document.getElementById("input2-1").focus();
		scroll(0,0);
		return false;
	}
	
	document.getElementById("input2-1Post").value = startNum;
	document.getElementById("input2-2Post").value = endNum;
	
	// 多筆新增時，跟使用者確認筆數
	if(startNum < endNum){
		return confirm("物品序號"+origSN+"~"+origEN+"\n共新增"+(endNum-startNum+1)+"筆資料\n確定嗎?");
	}
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

<div id="wrapper">

	<!-- Sidebar -->
	<?php require_once('sidebar.php'); ?>
	<!-- /#sidebar-wrapper -->

	<!-- Page Content -->
	<div id="page-content-wrapper">
		<div class="container-fluid" id="content_body">
			<div class="col-lg-10 col-lg-offset-1">
			<form method="POST" action="add_new_product2.php" Enctype="multipart/form-data" onsubmit="return checkInputData()">
				<table class="table table-bordered" style="min-width: 860px;">
				<tbody>
					<tr><a href="#top"></a>
						<td colspan="2" align="center">
						選擇照片(容量需8MB以下):<input type="file" name="uploadFile">
						</td>
					</tr>
					<tr>
						<td>
						<label>物品編號</label>
						<input type="text" class="form-control input-lg" name="input1" placeholder="物品編號" required>
						</td>
					</tr>
					<tr>
						<td width="50%">
						<label>物品序號 (多筆新增 起始號碼)</label> <input type="button" class="btn btn-info" value="使用說明" onclick="openNewWindow('HowToUse.png','使用說明',1460,670)">
						<input type="text" class="form-control input-lg" id="input2-1" name="input2-1" placeholder="物品序號 起始號碼" required>
						</td>
						<td>
						<label>物品序號 (多筆新增 結尾號碼)</label>
						<input type="text" class="form-control input-lg" id="input2-2" name="input2-2" placeholder="物品序號 結尾號碼" required>
						<!-- 隱藏的欄位，用於存新式序號的前後半段 -->
						<input type="hidden" id="input2-1Post" name="input2-1Post" value="">
						<input type="hidden" id="input2-2Post" name="input2-2Post" value="">
						<input type="hidden" id="input2Pre" name="input2Pre" value="">
						</td>
					</tr>
					<tr>
						<td>
						<label>物品名稱</label>
						<input type="text" class="form-control input-lg" name="input3" placeholder="物品名稱">
						</td>
						<td>
						<label>物品別名</label>
						<input type="text" class="form-control input-lg" name="input4" placeholder="物品別名">
						</td>
					</tr>
					<tr>
						<td>
						<label>廠牌</label>
						<input type="text" class="form-control input-lg" name="input5" placeholder="廠牌">
						</td>
						<td>
						<label>型式</label>
						<input type="text" class="form-control input-lg" name="input6" placeholder="型式">
						</td>
					</tr>
					<tr>
						<td>
						<label>單位</label>
						<input type="text" class="form-control input-lg" name="input7" placeholder="單位">
						</td>
						<td>
						<label>數量</label>
						<input type="number" class="form-control input-lg" name="input8" placeholder="數量" value="1">
						</td>
					</tr>
					<tr>
						<td>
						<label>單價</label>
						<input type="text" class="form-control input-lg" name="input9" placeholder="單價">
						</td>
						<td>
						<label>總價</label>
						<input type="text" class="form-control input-lg" name="input10" placeholder="總價">
						</td>
					</tr>
					<tr>
						<td>
						<label>保管單位</label>
						<input type="text" class="form-control input-lg" name="input11" placeholder="保管單位">
						</td>
						<td>
						<label>保管人</label>
						<input type="text" class="form-control input-lg" name="input12" placeholder="保管人">
						</td>
					</tr>
					<tr>
						<td>
						<label>使用單位</label>
						<input type="text" class="form-control input-lg" name="input13" placeholder="使用單位">
						</td>
						<td>
						<label>使用人</label>
						<input type="text" class="form-control input-lg" name="input14" placeholder="使用人">
						</td>
					</tr>
					<tr>
						<td>
						<label>存置地點</label>
						<input type="text" class="form-control input-lg" name="input15" placeholder="存置地點">
						</td>
						<td>
						<label>取得日期</label>
						<input type="text" class="form-control input-lg" name="input16" placeholder="取得日期">
						</td>
					</tr>
					<tr>
						<td>
						<label>入帳日期</label>
						<input type="text" class="form-control input-lg" name="input17" placeholder="入帳日期">
						</td>
						<td>
						<label>申請日期</label>
						<input type="text" class="form-control input-lg" name="input18" placeholder="申請日期">
						</td>
					</tr>
					<tr>
						<td>
						<label>增置方式</label>
						<input type="text" class="form-control input-lg" name="input19" placeholder="增置方式">
						</td>
						<td>
						<label>使用年限</label>
						<input type="text" class="form-control input-lg" name="input20" placeholder="使用年限">
						</td>
					</tr>
					<tr>
						<td>
						<label>經費來源</label>
						<input type="text" class="form-control input-lg" name="input21" placeholder="經費來源">
						</td>
						<td>
						<label>會計科目</label>
						<input type="text" class="form-control input-lg" name="input22" placeholder="會計科目">
						</td>
					</tr>
					<tr>
						<td>
						<label>物品區分</label>
						<input type="text" class="form-control input-lg" name="input23" placeholder="物品區分">
						</td>
						<td>
						<label>廠商</label>
						<input type="text" class="form-control input-lg" name="input24" placeholder="廠商">
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<label>備註</label>
						<input type="text" class="form-control input-lg" name="input25" placeholder="備註">
						</td>
					</tr>
				</tbody>
				</table>

				<div class="col-lg-6 col-lg-offset-3">
					<input type="submit" class="btn btn-lg btn-success btn-block" value="確定新增物品 (有選擇照片會上傳)">
				</div>
			</form>
			</div>
		</div>
	</div>
	<!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->

</body>

</html>

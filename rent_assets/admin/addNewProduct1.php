<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); 
header("Cache-control: private"); // 避免回上一頁時，表單被清空資料 ?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>新增萬元財產</title>
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
	// 將menuNum14-1 HighLight
	$("#menuNum14-1").addClass("active");
	
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
	
	// 多筆新增時，跟使用者確認筆數
	if(startNum < endNum)
		return confirm("財產序號"+startNum+"~"+endNum+"\n共新增"+(endNum-startNum+1)+"筆資料\n確定嗎?");
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
			<form method="POST" action="add_new_product1.php" Enctype="multipart/form-data" onsubmit="return checkInputData()">
				<table class="table table-bordered" style="min-width: 860px;">
				<tbody>
					<tr><a href="#top"></a>
						<td colspan="2" align="center">
						選擇照片(容量需8MB以下):<input type="file" name="uploadFile">
						</td>
					</tr>
					<tr>
						<td>
						<label>財產編號</label>
						<input type="text" class="form-control input-lg" name="input1" placeholder="財產編號" required>
						</td>
					</tr>
					<tr>
						<td width="50%">
						<label>財產序號 (多筆新增 起始號碼)</label> <input type="button" class="btn btn-info" value="使用說明" onclick="openNewWindow('HowToUse.png','使用說明',1460,670)">
						<input type="text" class="form-control input-lg" id="input2-1" name="input2-1" placeholder="財產序號 起始號碼" required>
						</td>
						<td>
						<label>財產序號 (多筆新增 結尾號碼)</label>
						<input type="text" class="form-control input-lg" id="input2-2" name="input2-2" placeholder="財產序號 結尾號碼" required>
						</td>
					</tr>
					<tr>
						<td>
						<label>財產名稱</label>
						<input type="text" class="form-control input-lg" name="input3" placeholder="財產名稱">
						</td>
						<td>
						<label>財產別名</label>
						<input type="text" class="form-control input-lg" name="input4" placeholder="財產別名">
						</td>
					</tr>
					<tr>
						<td>
						<label>財產性質</label>
						<input type="text" class="form-control input-lg" name="input5" placeholder="財產性質">
						</td>
						<td>
						<label>廠牌╱型式</label>
						<input type="text" class="form-control input-lg" name="input6" placeholder="廠牌╱型式" value="╱">
						</td>
					</tr>
					<tr>
						<td>
						<label>購置日期</label>
						<input type="text" class="form-control input-lg" name="input7" placeholder="購置日期">
						</td>
						<td>
						<label>移動日期</label>
						<input type="text" class="form-control input-lg" name="input8" placeholder="移動日期">
						</td>
					</tr>
					<tr>
						<td>
						<label>單位</label>
						<input type="text" class="form-control input-lg" name="input9" placeholder="單位">
						</td>
						<td>
						<label>數量</label>
						<input type="number" class="form-control input-lg" name="input10" placeholder="數量" value="1">
						</td>
					</tr>
					<tr>
						<td>
						<label>單價</label>
						<input type="text" class="form-control input-lg" name="input11" placeholder="單價">
						</td>
						<td>
						<label>使用年限</label>
						<input type="text" class="form-control input-lg" name="input12" placeholder="使用年限">
						</td>
					</tr>
					<tr>
						<td>
						<label>保管單位</label>
						<input type="text" class="form-control input-lg" name="input13" placeholder="保管單位">
						</td>
						<td>
						<label>保管人</label>
						<input type="text" class="form-control input-lg" name="input14" placeholder="保管人">
						</td>
					</tr>
					<tr>
						<td>
						<label>使用單位</label>
						<input type="text" class="form-control input-lg" name="input15" placeholder="使用單位">
						</td>
						<td>
						<label>使用人</label>
						<input type="text" class="form-control input-lg" name="input16" placeholder="使用人">
						</td>
					</tr>
					<tr>
						<td>
						<label>存置地點</label>
						<input type="text" class="form-control input-lg" name="input17" placeholder="存置地點">
						</td>
						<td>
						<label>原登錄號 (多筆新增時，會自動依序增加)</label>
						<input type="text" class="form-control input-lg" name="input18" placeholder="原登錄號">
						</td>
					</tr>
				</tbody>
				</table>

				<div class="col-lg-6 col-lg-offset-3">
					<input type="submit" class="btn btn-lg btn-success btn-block" value="確定新增財產 (有選擇照片會上傳)">
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

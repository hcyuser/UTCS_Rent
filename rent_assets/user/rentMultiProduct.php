<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); 
header("Cache-control: private"); // 避免回上一頁時，表單被清空資料 ?>

<?php
$userID = $_SESSION['account'];
// 搜尋使用者的資料
$searchstr = "SELECT * FROM `account_list` WHERE `account`='$userID'";
$link_ID = connect_mysql();
$searchlist = mysql_query($searchstr, $link_ID);
mysql_close($link_ID);
$record = mysql_fetch_array($searchlist);
?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>多筆租借模式</title>
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
	// 將menuNum5 HighLight
	$("#menuNum5").addClass("active");
	
	// proSubID1失去焦點時，將值複製到proSubID2
	$("#proSubID1").blur(function(){
		// 如果input2-2跟input2-1長度不同
		if ($("#proSubID1").val().length != $("#proSubID2").val().length)
			$("#proSubID2").val($("#proSubID1").val());
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
	var startNum = document.getElementById("proSubID1").value;
	var endNum = document.getElementById("proSubID2").value;
	
	// 起始號碼 比 結尾號碼 大
	if(startNum >= endNum){
		alert("\"起始號碼\" 應該要小於 \"結尾號碼\" \n請檢查");
		document.getElementById("proSubID1").focus();
		return false; // false:阻止交提表單
	}
	
	// 號碼長度不同
	if(startNum.length != endNum.length){
		alert("\"起始號碼\" 長度與 \"結尾號碼\" 不同\n請檢查");
		document.getElementById("proSubID1").focus();
		return false;
	}

	// 跟使用者確認筆數
	return confirm("財產序號"+startNum+"~"+endNum+"\n共租借"+(endNum-startNum+1)+"筆財產\n確定嗎?");
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
				<div class="row">
					<div class="col-md-12">
					<p class="text-center text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> 
					注意！此功能只能租借多筆「財產(物品)編號」相同的財產<br>
					而且只能今天借今天還，即「租借日期」與「歸還日期」皆為今天</p>
					</div>
				</div>
				
				<form method="POST" action="rent_multi_product.php" onsubmit="return checkInputData()">
				<div class="row">
					<div class="col-md-6">
					<div class="form-group">
					<label>財產屬於 (注意，選錯會造成租借失敗)</label>
					<select class="form-control input-lg" name="proList">
						<option value="1">萬元財產清單</option>
						<option value="2">非消耗性物品清單</option>
					</select>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group">
					<label>財產(物品) 編號</label>
					<input type="text" name="proMainID" class="form-control input-lg" 
					value="" placeholder="財產(物品) 編號" required>
					</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
					<div class="form-group">
					<label>財產(物品) 序號　(起始號碼)</label>
					<input type="text" name="proSubID1" id="proSubID1" class="form-control input-lg" value="" placeholder="財產(物品) 序號　(起始號碼)" required>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group">
					<label>財產(物品) 序號　(結尾號碼)</label>
					<input type="text" name="proSubID2" id="proSubID2" class="form-control input-lg" value="" placeholder="財產(物品) 序號　(結尾號碼)" required>
					</div>
					</div>
				</div>
					<input type="hidden" name="account" value="<?php echo $record['account']; ?>">
					<input type="hidden" name="realname" value="<?php echo $record['realname']; ?>">
					
				<div class="col-lg-6 col-lg-offset-3">
					<input type="submit" class="btn btn-lg btn-success btn-block" value="確定租借">
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

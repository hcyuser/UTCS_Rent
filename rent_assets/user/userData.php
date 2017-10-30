<?php require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

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
	<title>個人資料</title>
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
	// 將menuNum1 HighLight
	$("#menuNum1").addClass("active");
	
	// 自動選取目前的科系與年級
	$("#department").val("<?php echo $record['department']; ?>");
	$("#grade").val("<?php echo $record['grade']; ?>");
});

// 檢查輸入的資料，是否符合預期
function checkUserData(){
	// 如果輸入的兩次密碼不相同
	if( document.getElementById("newpassword").value != 
		document.getElementById("newpassword2").value){
		alert("您輸入的兩次密碼不相符，請檢查輸入");
		return false; // false:阻止交提表單
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
		<div class="container-fluid">
		
		<form method="POST" action="update_user_data.php" onsubmit="return checkUserData()">
			<div class="row" id="content_body">
				<div class="col-lg-10 col-lg-offset-1">
					<div class="row">
						<div class="col-md-12">
						<p class="text-center text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> 個人資料請確實填寫，若經查證非屬實，我們將會取消您的租借資格</p>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
						<div class="form-group" style="text-align:left">
						<label>學號</label>
						<input type="text" class="form-control input-lg" 
						value="<?php echo $record['account'];?>" placeholder="學號" readonly>
						</div>
						</div>
						
						<div class="col-md-6">
						<div class="form-group" style="text-align:left">
						<label>姓名</label>
						<input type="text" name="realname" class="form-control input-lg" 
						value="<?php echo $record['realname'];?>" placeholder="姓名" required>
						</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
						<div class="form-group" style="text-align:left">
						<label>更改新密碼 (空白即不更改)</label>
						<input type="password" name="newpassword" id="newpassword" class="form-control input-lg" value="" placeholder="密碼">
						</div>
						</div>
						
						<div class="col-md-6">
						<div class="form-group" style="text-align:left">
						<label>確認新密碼</label>
						<input type="password" name="newpassword2" id="newpassword2" class="form-control input-lg" value="" placeholder="再次確認密碼" >
						</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
						<div class="form-group" style="text-align:left">
						<label>科系</label>
						<select class="form-control input-lg" name="department" id="department">
							<option disabled style="color: #a94442">---------教育學院---------</option>
							<option value="教育學系">教育學系</option>
							<option value="特殊教育學系">特殊教育學系</option>
							<option value="幼兒教育學系">幼兒教育學系</option>
							<option value="心理與諮商學系">心理與諮商學系</option>
							<option value="學習與媒材設計學系">學習與媒材設計學系</option>
							<option disabled style="color: #a94442">------人文藝術學院------</option>
							<option value="中國語文學系">中國語文學系</option>
							<option value="歷史與地理學系">歷史與地理學系</option>
							<option value="社會暨公共事務學系">社會暨公共事務學系</option>
							<option value="音樂學系">音樂學系</option>
							<option value="視覺藝術學系">視覺藝術學系</option>
							<option value="英語教學系">英語教學系</option>
							<option value="舞蹈學系">舞蹈學系</option>
							<option disabled style="color: #a94442">----------理學院----------</option>
							<option value="體育學系">體育學系</option>
							<option value="資訊科學系">資訊科學系</option>
							<option value="應用物理暨化學系">應用物理暨化學系</option>
							<option value="數學系">數學系</option>
							<option value="地球環境暨生物資源學系">地球環境暨生物資源學系</option>
							<option disabled style="color: #a94442">------市政管理學院------</option>
							<option value="城市發展學系">城市發展學系</option>
							<option value="衛生福利學系">衛生福利學系</option>
							<option value="都會產業經營與行銷學系">都會產業經營與行銷學系</option>
						</select>
						</div>
						</div>
						
						<div class="col-md-6">
						<div class="form-group" style="text-align:left">
						<label>年級</label>
						<select class="form-control input-lg" name="grade" id="grade">
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
						<div class="form-group" style="text-align:left">
						<label>e-mail (通知信件會寄送到此信箱，請確實填寫)</label>
						<input type="email" name="e-mail" class="form-control input-lg" 
						value="<?php echo $record['email'];?>" placeholder="e-mail" required>
						</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
						<div class="form-group" style="text-align:left">
						<label>手機號碼 (若物品逾期未還，會打電話通知您，請確實填寫)</label>
						<input type="text" name="phone" class="form-control input-lg" 
						value="<?php echo $record['phone'];?>" placeholder="手機號碼" required>
						</div>
						</div>
					</div>
					<p></p>
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
						<input type="submit" class="btn btn-lg btn-success btn-block" value="確定更改">
						</div>
					</div>
				</div>
			</div>
		</form>
		
		</div>
	</div>
	<!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->

</body>

</html>

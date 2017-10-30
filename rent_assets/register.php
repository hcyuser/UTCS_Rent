<?php
session_start();
// 若已經登入，先把使用者登出
if( !empty($_SESSION['ch_OK']) ){
	session_unset();
	session_destroy();
}
header("Cache-control: private"); // 避免回上一頁時，表單被清空資料
?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>加入會員</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- 引入 Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
	<script src="bootstrap/js/jquery.min.js"></script>
	<!-- 包括所有已編譯的插件 -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
</head>

<script type="text/javascript">
// 檢查輸入的資料，是否符合預期
function checkUserData(){
	// 如果輸入的兩次密碼不相同
	if( document.getElementById("password").value != 
		document.getElementById("password2").value){
		alert("您輸入的兩次密碼不相符，請檢查輸入");
		return false; // false:阻止交提表單
	}
}
</script>

<style type="text/css">
#box_title {
	font-size:26px;
	font-weight:bold;
	text-align:center;
}
#box_body {
	font-size:21px;
	font-weight:bold;
}
</style>

<body style="background:#FFFFF4;">
<div class="container-fluid">

<div class="row">
<div class="col-md-6 col-md-offset-3">
	<form method="POST" action="/rent_assets/add_new_user.php" onsubmit="return checkUserData()">
		<div class="panel panel-primary" style="margin:100px auto 50px auto;">
			<div id="box_title" class="panel-heading">
				加入會員
			</div>
			<div id="box_body" class="panel-body">
				<div class="row">
					<div class="col-md-12">
					<p class="text-center text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> 個人資料請確實填寫，若經查證非屬實，我們將會取消您的租借資格</p>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
					<div class="form-group" style="text-align:left">
					<label>學號 (開頭的U，不分大小寫)</label>
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="學號" required autofocus>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group" style="text-align:left">
					<label>姓名</label>
					<input type="text" name="realname" class="form-control input-lg" placeholder="姓名" required>
					</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
					<div class="form-group" style="text-align:left">
					<label>密碼</label>
					<input type="password" name="password" id="password" class="form-control input-lg" placeholder="密碼" required>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group" style="text-align:left">
					<label>確認密碼</label>
					<input type="password" name="password2" id="password2" class="form-control input-lg" placeholder="再次確認密碼" required>
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
					<select class="form-control input-lg" name="grade">
						<option value="1" selected>大一</option>
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
					<input type="email" name="e-mail" class="form-control input-lg" placeholder="e-mail" required>
					</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
					<div class="form-group" style="text-align:left">
					<label>手機號碼 (若物品逾期未還，會打電話通知您，請確實填寫)</label>
					<input type="text" name="phone" class="form-control input-lg" placeholder="手機號碼" required>
					</div>
					</div>
				</div>
				<input type="submit" class="btn btn-lg btn-success btn-block" value="確定註冊">
			</div>
		</div> 
	</form>
</div>
</div>
<p align="center" style="font-size:26px;"><a href="/rent_assets/index.php"><b>回首頁</b></a></p>
</div>
</body>

</html>

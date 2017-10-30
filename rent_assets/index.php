<?php
session_start();

// 若已經登入，頁面轉向選單頁面
if( !empty($_SESSION['ch_OK']) ){
	if ($_SESSION['user_level'] > 1)
		header("location:/rent_assets/admin/manageLease.php");
	else
		header("location:/rent_assets/user/productList1.php");
}

// 若有COOKIE，自動登入
elseif ( !empty($_COOKIE['username']) && !empty($_COOKIE['password']) ){
	header("location:/rent_assets/login.php");
}

else{
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>財物租借管理系統</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- 引入 Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
	<script src="bootstrap/js/jquery.min.js"></script>
	<!-- 包括所有已編譯的插件 -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
	
	<!-- Custom JavaScript .button-checkbox的js -->
	<script src="js/index.js"></script>
</head>

<style type="text/css">
#box_title {
	font-size:26px;
	font-weight:bold;
	text-align:center;
}
#box_body {
	font-size:21px;
	font-weight:bold;
	text-align:center;
}
</style>

<body style="background:#FFFFF4;">
<div class="container-fluid">

<div class="row">
<div class="col-md-4 col-md-offset-4">
	<form method="POST" action="/rent_assets/login.php">
		<div class="panel panel-primary" style="margin:100px auto 50px auto;">
			<div id="box_title" class="panel-heading">
				財物租借管理系統
			</div>
			<div id="box_body" class="panel-body">
				<div class="form-group" style="text-align:left">
					<label>學號</label>
					<input type="text" name="username" class="form-control input-lg" placeholder="學號" required autofocus>
				</div>
				<div class="form-group" style="text-align:left">
					<label>密碼</label>
					<input type="password" name="password" class="form-control input-lg" placeholder="密碼" required>
				</div>
						
				<div class="row">
				<div class="col-md-6">
					<input type="submit" class="btn btn-lg btn-success btn-block" value="登入">
				</div>
				<div class="col-md-6">
					<a href="/rent_assets/register.php" class="btn btn-lg btn-warning btn-block">加入會員</a>
				</div>
				</div>
				
				<p></p>
				
				<div class="row">
				<div class="col-md-6">
					<span class="button-checkbox">
					<button type="button" class="btn btn-block" data-color="info">記住我</button>
                    <input type="checkbox" name="remember_me" id="remember_me" class="hidden">
					</span>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-block btn-default" onclick="location.href='/rent_assets/forget.php'">
					<a href="#">忘記密碼</a>
					</button>
				</div>
				</div>
				
				<?php
					if( !empty($_SESSION['login_error']) ){
				?>
						<h3 class="text-danger"><b>帳號或密碼錯誤</b></h3>
				<?php
						unset($_SESSION['login_error']);
					}
				?>
			</div>
		</div> 
	</form>
</div>
</div>


</div>
</body>

</html>
<?php } ?>
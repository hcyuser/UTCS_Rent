<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>忘記密碼</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- 引入 Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
	<script src="bootstrap/js/jquery.min.js"></script>
	<!-- 包括所有已編譯的插件 -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
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
	<form method="POST" action="/rent_assets/get_password_back.php">
		<div class="panel panel-danger" style="margin:100px auto 50px auto;">
			<div id="box_title" class="panel-heading">
				忘記密碼
			</div>
			<div id="box_body" class="panel-body">
				<div>
					噗噗，太大意惹。您忘記密碼了嗎?<br>
					請在下面輸入，您的學號和註冊時所填的信箱。<br>
					若兩者相符，我們將會把密碼寄信到您的信箱中。<br>
					下次別再忘記密碼囉。（<ゝω・）☆ kira
				</div>
				<div class="form-group" style="text-align:left">
					<label>學號</label>
					<input type="text" name="username" class="form-control input-lg" placeholder="學號" required autofocus>
				</div>
				<div class="form-group" style="text-align:left">
					<label>e-mail (您註冊時所填寫的信箱)</label>
					<input type="email" name="e-mail" class="form-control input-lg" placeholder="e-mail" required>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-lg btn-success btn-block" value="確定">
				</div>
			</div>
		</div> 
	</form>
</div>
</div>
<p align="center" style="font-size:26px;"><a href="/rent_assets/index.php"><b>回首頁</b></a></p>
</div>
</body>

</html>
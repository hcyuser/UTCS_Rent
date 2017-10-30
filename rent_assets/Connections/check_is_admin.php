<?php
if(!isset($_SESSION)){
	session_start();
}

// 若不是管理員，直接以網址進入此頁，跳回首頁
if($_SESSION['user_level'] < 1){
	header("location:/rent_assets/index.php");
	exit();
}
?>
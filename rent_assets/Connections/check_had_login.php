<?php
if(!isset($_SESSION)){
	session_start();
}

// 若尚未登入，直接以網址進入此頁，跳回首頁
if( empty($_SESSION['ch_OK']) ){
	header("location:/rent_assets/index.php");
	exit();
}
?>
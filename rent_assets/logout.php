
<?php

// 若有COOKIE，刪除相關資訊
if ( !empty($_COOKIE['account']) || !empty($_COOKIE['password']) ){
	setcookie("account", "", time()-3600);
	setcookie("password", "", time()-3600);
}


// 使用者登出
session_start();
session_unset();
session_destroy();
header("location:/rent_assets/index.php");
?>
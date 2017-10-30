<?php
// 連接MySQL資料庫
function connect_mysql(){
	$link = mysql_connect("localhost","root","Cs12345.");
	mysql_query("SET NAMES utf8");
	mysql_select_db("rent_assetsDB",$link);
	date_default_timezone_set('Asia/Taipei');
	return $link;
}

// 轉換使用者的輸入，使其符合SQL的搜尋字串，避免鑽漏洞
function check_input($sqlStr)
{
	// 去除斜槓
	if (get_magic_quotes_gpc())
	{
		$sqlStr = stripslashes($sqlStr);
	}
	// 如果不是數字則加引號
	if (!is_numeric($sqlStr))
	{
		$sqlStr = mysql_real_escape_string($sqlStr);
	}
	return $sqlStr;
}
?>

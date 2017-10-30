<?php require_once('../Connections/connect_mysql.php');?>

<?php
// 從資料庫中讀取圖片
$proMainID = check_input($_GET['pro_mainID']);
$proSubID = check_input($_GET['pro_subID']);
$proList = check_input($_GET['pro_list']);

// 搜尋財產的資料
$searchstr = "SELECT * FROM `photo_list` 
WHERE `pro_mainID`='$proMainID' AND `pro_subID`='$proSubID' AND `pro_list`='$proList'";

$link_ID = connect_mysql();
$searchlist = mysql_query($searchstr, $link_ID);
mysql_close($link_ID);
$search_num = mysql_num_rows($searchlist);

if ($search_num < 1)
    die("暫無圖片");

$record = mysql_fetch_array($searchlist);

ob_clean(); // 清除先前輸出，必要，勿刪
// 來源http://blog.yam.com/csylvia/article/72522150

header("Content-type: ".$record['type']);
echo base64_decode($record['pic']); // Base64解碼

?>
<?php
// 匯入PHPMailer類別
// dirname(__FILE__) 取得此檔案的絕對路徑
include(dirname(__FILE__)."\..\phpmailer\class.phpmailer.php");

$mail= new PHPMailer(); // 建立新物件
$mail->IsSMTP(); // 設定使用SMTP方式寄信
$mail->SMTPAuth = true; // 設定SMTP需要驗證
$mail->SMTPSecure = "ssl"; // Gmail的SMTP主機需要使用SSL連線
$mail->Host = "smtp.gmail.com"; // Gamil的SMTP主機
$mail->Port = 465;  // Gamil的SMTP主機的SMTP埠位為465埠。
$mail->CharSet = "UTF-8"; // 設定郵件編碼
$mail->IsHTML(true); // 設定郵件內容為HTML

$mail->Username = "csitemrentalsystem@gmail.com"; // 設定驗證帳號
$mail->Password = "duyeizzzxfidkenp"; // 設定應用程式密碼

$mail->From = "csitemrentalsystem@gmail.com"; // 設定寄件者信箱        
$mail->FromName = "台北市立大學 資訊科學系 財物租借系統"; // 設定寄件者姓名
?>
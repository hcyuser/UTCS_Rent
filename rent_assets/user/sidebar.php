<?php
$link_ID = connect_mysql(); // 連線資料庫
// 計算允許與未歸還的租借數
$searchstr = 
"SELECT COUNT(*) FROM `rent_list` WHERE (`state`=2 OR `state`=6) 
AND `rent_acc` = '".$_SESSION['account']."'";
$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢
$newMsgNum = mysql_fetch_array($searchlist)[0]; // 取得資料總數
mysql_close($link_ID); // 資料庫斷開連結
?>

<div id="sidebar-wrapper">
	<ul class="sidebar-nav">
		<li class="sidebar-brand">
			<a href="#">
				資科系財物租借系統
			</a>
		</li>
		<hr width="90%">
		<li id="menuNum1" class="">
			<a href="userData.php"><span class="glyphicon glyphicon-user"></span> 個人資料</a>
		</li>
		<li id="menuNum2">
			<a href="myLease.php"><span class="glyphicon glyphicon-list-alt"></span> 我的租借
			<?php if($newMsgNum != 0) echo '<span class="label label-warning">'.$newMsgNum.'</span>'; ?></a>
		</li>
		<hr width="90%">
		<li id="menuNum3">
			<a href="productList1.php"><span class="glyphicon glyphicon-usd"></span> 萬元財產清單</a>
		</li>
		<li id="menuNum4">
			<a href="productList2.php"><span class="glyphicon glyphicon-usd"></span> 非消耗性物品清單</a>
		</li>
		<li id="menuNum5">
			<a href="rentMultiProduct.php"><span class="glyphicon glyphicon-sort-by-alphabet"></span> 多筆租借模式</a>
		</li>
		<hr width="90%">
		<li>
			<a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> 登出</a>
		</li>
	</ul>
</div>

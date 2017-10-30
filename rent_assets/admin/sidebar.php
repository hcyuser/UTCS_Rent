<?php
$link_ID = connect_mysql(); // 連線資料庫
$searchstr = "SELECT COUNT(`state`) FROM `rent_list` WHERE `state`=1"; // 計算未審核的租借數
$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢
$applyNum = mysql_fetch_array($searchlist)[0]; // 取得資料總數
$searchstr = "SELECT COUNT(`state`) FROM `rent_list` WHERE `state`=4"; // 計算出借中的租借數
$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢
$lendNum = mysql_fetch_array($searchlist)[0]; // 取得資料總數
$searchstr = "SELECT COUNT(`state`) FROM `rent_list` WHERE `state`=6"; // 計算未歸還的租借數
$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢
$noReturnNum = mysql_fetch_array($searchlist)[0]; // 取得資料總數

mysql_close($link_ID); // 資料庫斷開連結
?>

<div id="sidebar-wrapper">
	<ul class="sidebar-nav">
		<li class="sidebar-brand">
			<a href="#">
				資科系財物管理系統
			</a>
		</li>
		<hr width="90%">
		<li id="menuNum1" class="">
			<a href="userData.php"><span class="glyphicon glyphicon-user"></span> 個人資料</a>
		</li>
		<li id="menuNum2">
			<a href="myLease.php"><span class="glyphicon glyphicon-list-alt"></span> 我的租借</a>
		</li>
		<hr width="90%">
		<li id="menuNum11"> <!-- 為了 財產清單 下面，未來的擴充性，編號從11開始 -->
			<a href="manageUser.php"><span class="glyphicon glyphicon-tower"></span> 管理帳號</a>
		</li>
		<li id="menuNum12-1">
			<a href="manageLease.php"><span class="glyphicon glyphicon-file"></span> 管理租借</a>
		</li>
		<li id="menuNum12-2">
			<a href="manageLease.php?state=1">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right"></span> 未審核
			<?php if($applyNum != 0) echo '<span class="label label-warning">'.$applyNum.'</span>'; ?></a>
		</li>
		<li id="menuNum12-3">
			<a href="manageLease.php?state=4">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right"></span> 出借中
			<?php if($noReturnNum != 0) echo '<span class="label label-warning">'.$lendNum.'</span>'; ?></a>
		</li>
		<li id="menuNum12-4">
			<a href="manageLease.php?state=6">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right"></span> 逾期未還
			<?php if($noReturnNum != 0) echo '<span class="label label-warning">'.$noReturnNum.'</span>'; ?></a>
		</li>
		<li id="menuNum12-5">
			<a href="manageLease.php?state=5">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right"></span> 過去紀錄</a>
		</li>
		<li id="menuNum13-1">
			<a href="manageProduct1.php"><span class="glyphicon glyphicon-lock"></span> 管理萬元財產</a>
		</li>
		<li id="menuNum13-2">
			<a href="manageProduct2.php"><span class="glyphicon glyphicon-lock"></span> 管理非消耗性物品</a>
		</li>
		<li id="menuNum14-1">
			<a href="addNewProduct1.php"><span class="glyphicon glyphicon-plus"></span> 新增萬元財產</a>
		</li>
		<li id="menuNum14-2">
			<a href="addNewProduct2.php"><span class="glyphicon glyphicon-plus"></span> 新增非消耗性物品</a>
		</li>
		<hr width="90%">
		<li id="menuNum3">
			<a href="productList1.php"><span class="glyphicon glyphicon-usd"></span> 萬元財產清單</a>
		</li>
		<li id="menuNum4">
			<a href="productList2.php"><span class="glyphicon glyphicon-usd"></span> 非消耗性物品清單</a>
		</li>
		<hr width="90%">
		<li>
			<a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> 登出</a>
		</li>
	</ul>
</div>

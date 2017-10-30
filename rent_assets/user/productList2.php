<?php require_once('../Connections/connect_mysql.php');
require_once('../Connections/check_had_login.php'); ?>

<?php
// 預設每頁顯示20筆
if ( empty($_GET['displayNum'])){
	$displayNum=20;
}
// 有輸入，每頁顯示n筆
else{
	$displayNum=$_GET['displayNum'];
}

// 預設顯示第1頁
if ( empty($_GET['page'])){
	$page=1;
}
// 有輸入，顯示第m頁
else{
	$page=$_GET['page'];
}

// 預設搜尋所有物品
$searchFor1="";
$searchFor2="";
$searchFor3="";
$seacherTemp=""; // 儲存SQL查詢，WHERE的篩選條件
$searchstr = "SELECT * FROM `非消耗性物品` WHERE 1";

// 有輸入，搜尋目標物品
if ( !empty($_GET['searchFor1']) || !empty($_GET['searchFor2']) || !empty($_GET['searchFor3']) ){
	if ( !empty($_GET['searchFor1']) ){
		$searchFor1=check_input(trim($_GET['searchFor1'])); // trim()去空白
		$seacherTemp .= " AND `物品編號` LIKE '%$searchFor1%'";
	}
	if ( !empty($_GET['searchFor2']) ){
		$searchFor2=check_input(trim($_GET['searchFor2'])); // trim()去空白
		$seacherTemp .= " AND `物品序號` LIKE '%$searchFor2%'";
	}
	if ( !empty($_GET['searchFor3']) ){
		$searchFor3=check_input(trim($_GET['searchFor3'])); // trim()去空白
		$seacherTemp .= " AND (`物品名稱` LIKE '%$searchFor3%' OR `物品別名` LIKE '%$searchFor3%')";
	}
}

$searchstr .= $seacherTemp." ORDER BY `物品編號`, `物品序號`"; // 由id排列物品

$startNum = $displayNum * ($page-1);
$searchstr .= " LIMIT $startNum, $displayNum"; // 從第n*m筆資料開始，取n筆資料

$link_ID = connect_mysql(); // 連線資料庫
$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢，結果放入$searchlist

$max_index = mysql_num_rows($searchlist); // 查詢結果的資料筆數(rows)
// 不可改，即使上面用LIMIT，取得的資料可能比預期少(已取完)

for($i = 0; $i < $max_index; $i++){
	// 取出查詢結果，每執行一次就將一組row的值放入陣列$recordArray中
	$recordArray[$i] = mysql_fetch_array($searchlist);
}

// 計算物品數
$searchstr = "SELECT COUNT(*) FROM `非消耗性物品` WHERE 1".$seacherTemp; 

$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢
$dataTotal = mysql_fetch_array($searchlist)[0]; // 取得資料總數
$pageTotal = ceil($dataTotal/$displayNum); // 計算總頁數

mysql_close($link_ID); // 資料庫斷開連結

// 目前頁面的網址，後面會使用
$URL = "productList2.php?";
if ( !empty($_GET['searchFor1']) )
	$URL = $URL."searchFor1=".$searchFor1."&";
if ( !empty($_GET['searchFor2']) )
	$URL = $URL."searchFor2=".$searchFor2."&";
if ( !empty($_GET['searchFor3']) )
	$URL = $URL."searchFor3=".$searchFor3."&";
?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>非消耗性物品清單</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- 引入 Bootstrap -->
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
	<script src="../bootstrap/js/jquery.min.js"></script>
	<!-- 包括所有已編譯的插件 -->
	<script src="../bootstrap/js/bootstrap.min.js"></script>

    <!-- Custom CSS -->
    <link href="../css/simple-sidebar.css" rel="stylesheet">
</head>

<script>
// $(window).load()頁面讀取後執行內容
$(window).load(function(){
	
	// 圖片縮圖
	$("img").each(function(i){
		// 移除目前設定的影像長寬
		$(this).removeAttr('width');
		$(this).removeAttr('height');
	
		// 取得影像實際的長寬
		var imgW = $(this).width();
		var imgH = $(this).height();
		
		// 計算縮放比例
		var pre=1;
		if ( $(this).attr("_w") != 0)
			pre = $(this).attr("_w")/imgW;
		else
			pre = $(this).attr("_h")/imgH;
		
		// 設定目前的縮放比例
		$(this).width(imgW*pre);
		$(this).height(imgH*pre);
	});
	
	// 將menuNum4 HighLight
	$("#menuNum4").addClass("active");
});

// 由於submit後，其他參數會消失，故用此方法保留參數
function search(){
	document.location.href = "productList2.php?displayNum=<?php echo $displayNum;?>"
	+"&searchFor1="+ document.getElementById("searchFor1").value
	+"&searchFor2="+ document.getElementById("searchFor2").value
	+"&searchFor3="+ document.getElementById("searchFor3").value;
}
</script>

<style type="text/css">
#content_body {
	font-size:21px;
	font-weight:bold;
	margin:100px 50px 50px auto;
}
</style>

<body style="background:#FFFFF4;">

<div id="wrapper">

	<!-- Sidebar -->
	<?php require_once('sidebar.php'); ?>
	<!-- /#sidebar-wrapper -->

	<!-- Page Content -->
	<div id="page-content-wrapper">
		<div class="container-fluid" id="content_body">
			<!-- 搜尋欄+目前資料的編號+每頁顯示 -->
			<div class="row">
				<div class="col-lg-8">
					<label>搜尋 (留空的欄位不會進行比對)</label><br>
					<span class="form-inline">
						<input type="text" id="searchFor1" class="form-control input-lg" placeholder='物品編號' value="<?php echo $searchFor1; ?>">
						<input type="text" id="searchFor2" class="form-control input-lg" placeholder='物品序號' value="<?php echo $searchFor2; ?>">
						<input type="text" id="searchFor3" class="form-control input-lg" placeholder='物品名稱 or 物品別名' value="<?php echo $searchFor3; ?>">
						<button type="buttom" class="btn btn-default input-lg" onclick="search()"><span class="glyphicon glyphicon-search"></span></button>
					</span>
				</div>
				<div class="col-lg-4">
					<?php if($dataTotal==0) echo "查無資料";
						  else echo "總共".$dataTotal."筆中的".($startNum+1)."~".($startNum+$max_index)."筆";
						  if($searchFor1!="" || $searchFor2!="" || $searchFor3!="")
							echo "<br><a href=\"productList2.php?displayNum=".$displayNum."\">返回查看所有物品</a>";
					?>
				</div>
				<div class="col-lg-12">
					<span class="pull-right">
					每頁顯示：		
					<button type="button" class="btn btn-default <?php if($displayNum==10) echo "active"; ?>"
					onclick="location.href='<?php echo $URL."displayNum=10";?>'">10筆</button>

					<button type="button" class="btn btn-default <?php if($displayNum==20) echo "active"; ?>"
					onclick="location.href='<?php echo $URL."displayNum=20";?>'">20筆</button>
					
					<button type="button" class="btn btn-default <?php if($displayNum==30) echo "active"; ?>"
					onclick="location.href='<?php echo $URL."displayNum=30";?>'">30筆</button>
					</span>
				</div>
			</div>
			<!-- 頁數鈕 -->
			<div class="row">
				<div class="col-lg-12">
					<ul class="pagination pull-right">
						<?php if($dataTotal==0) exit(); // 若無物品，直接結束
						// 物品頁數少於21頁
						if($pageTotal < 21){ 
							for($i = 1; $i <= $pageTotal; $i++){
								if($i == $page)
									echo '<li class="active">';
								else
									echo '<li>';
								echo "<a href=\"".$URL."displayNum=".$displayNum."&page=".$i."\">".$i."</a></li>";
							}
						}
						// 物品頁數多於21頁
						else{
							// 在1~11頁時
							if($page < 12){
								for($i = 1; $i <= 21; $i++){
									if($i == $page)
										echo '<li class="active">';
									else
										echo '<li>';
									echo "<a href=\"".$URL."displayNum=".$displayNum."&page=".$i."\">".$i."</a></li>";
								}
								echo "<li><a href=\"".$URL."displayNum=".$displayNum."&page=".$pageTotal."\">...".$pageTotal."</a></li>";
							}
							// 在倒數12頁時
							else if($page > $pageTotal-11){
								echo "<li><a href=\"".$URL."displayNum=".$displayNum.'&page=1">1...</a></li>';
								for($i = $pageTotal-20; $i <= $pageTotal; $i++){
									if($i == $page)
										echo '<li class="active">';
									else
										echo '<li>';
									echo "<a href=\"".$URL."displayNum=".$displayNum."&page=".$i."\">".$i."</a></li>";
								}
							}
							// 在中間頁時
							else{
								echo "<li><a href=\"".$URL."displayNum=".$displayNum.'&page=1">1...</a></li>';
								for($i = $page-10; $i <= $page+10; $i++){
									if($i == $page)
										echo '<li class="active">';
									else
										echo '<li>';
									echo "<a href=\"".$URL."displayNum=".$displayNum."&page=".$i."\">".$i."</a></li>";
								}
								echo "<li><a href=\"".$URL."displayNum=".$displayNum."&page=".$pageTotal."\">...".$pageTotal."</a></li>";
							}
						}
						?>
					</ul>
				</div>
			</div>
			<!-- 顯示物品資料 -->
			<div class="row">
				<div class="col-lg-12">
					<table class="table table-hover table-bordered">
						<thead>
							<tr class="info">
								<th width="300px">照片</th>
								<th style="min-width:150px;">物品編號<br>物品序號</th>
								<th style="min-width:200px;">物品名稱<br>物品別名</th>
								<th style="min-width:120px;">廠牌╱型式</th>
								<th style="min-width:80px;">存置地點</th>
								<th style="min-width:50px;">數量</th>
								<th style="min-width:60px;">出租狀態</th>
								<th>預約<br>租借</th>
							</tr>
						</thead>
						<tbody>
							<?php
							// 印出查詢結果
							for($i = 0; $i < $max_index; $i++){
							?>
							<tr>
								<td><?php if($recordArray[$i]['照片'])
											echo "<img src=\"show_photo.php?pro_list=2&pro_mainID=".$recordArray[$i]['物品編號']."&pro_subID=".$recordArray[$i]['物品序號']."\" _w='300'>";
										  else echo '暫無照片'; ?>
								</td>
								<td><a href="<?php echo "productList2.php?displayNum=".$displayNum."&searchFor1=".$recordArray[$i]['物品編號'];?>"><?php echo $recordArray[$i]['物品編號'];?></a><br><?php echo $recordArray[$i]['物品序號'];?></td>
								<td><?php echo $recordArray[$i]['物品名稱']."<br>".$recordArray[$i]['物品別名']; ?></td>
								<td><?php echo $recordArray[$i]['廠牌']."╱".$recordArray[$i]['型式']; ?></td>
								<td><?php echo $recordArray[$i]['存置地點']; ?></td>
								<td><?php echo $recordArray[$i]['數量']; ?></td>
								<td><?php if($recordArray[$i]['出租狀態']) echo "<span class='text-danger'>已出借</span>";
										  else echo "未出借"; ?></td>
								<td><button type="button" class="btn btn-success" 
									onclick="window.open('<?php echo "productState.php?pro_list=2&pro_mainID=".$recordArray[$i]['物品編號']."&pro_subID=".$recordArray[$i]['物品序號']; ?>')">預約</button>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- 頁數鈕 -->
			<div class="row">
				<div class="col-lg-12">
					<ul class="pagination pull-right">
						<?php if($dataTotal==0) exit(); // 若無物品，直接結束
						// 物品頁數少於21頁
						if($pageTotal < 21){ 
							for($i = 1; $i <= $pageTotal; $i++){
								if($i == $page)
									echo '<li class="active">';
								else
									echo '<li>';
								echo "<a href=\"".$URL."displayNum=".$displayNum."&page=".$i."\">".$i."</a></li>";
							}
						}
						// 物品頁數多於21頁
						else{
							// 在1~11頁時
							if($page < 12){
								for($i = 1; $i <= 21; $i++){
									if($i == $page)
										echo '<li class="active">';
									else
										echo '<li>';
									echo "<a href=\"".$URL."displayNum=".$displayNum."&page=".$i."\">".$i."</a></li>";
								}
								echo "<li><a href=\"".$URL."displayNum=".$displayNum."&page=".$pageTotal."\">...".$pageTotal."</a></li>";
							}
							// 在倒數12頁時
							else if($page > $pageTotal-11){
								echo "<li><a href=\"".$URL."displayNum=".$displayNum.'&page=1">1...</a></li>';
								for($i = $pageTotal-20; $i <= $pageTotal; $i++){
									if($i == $page)
										echo '<li class="active">';
									else
										echo '<li>';
									echo "<a href=\"".$URL."displayNum=".$displayNum."&page=".$i."\">".$i."</a></li>";
								}
							}
							// 在中間頁時
							else{
								echo "<li><a href=\"".$URL."displayNum=".$displayNum.'&page=1">1...</a></li>';
								for($i = $page-10; $i <= $page+10; $i++){
									if($i == $page)
										echo '<li class="active">';
									else
										echo '<li>';
									echo "<a href=\"".$URL."displayNum=".$displayNum."&page=".$i."\">".$i."</a></li>";
								}
								echo "<li><a href=\"".$URL."displayNum=".$displayNum."&page=".$pageTotal."\">...".$pageTotal."</a></li>";
							}
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->

</body>

</html>

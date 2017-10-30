<?php require_once('../Connections/check_is_admin.php');
require_once('../Connections/connect_mysql.php');
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

// 預設搜尋所有使用者
$searchFor1="";
$searchFor2="";
$seacherTemp=""; // 儲存SQL查詢，WHERE的篩選條件
$searchstr = "SELECT * FROM `account_list` WHERE 1";

// 有輸入，搜尋目標使用者
if ( !empty($_GET['searchFor1']) || !empty($_GET['searchFor2']) ){
	if ( !empty($_GET['searchFor1']) ){
		$searchFor1=check_input(trim($_GET['searchFor1'])); // trim()去空白
		$seacherTemp .= " AND `account` LIKE '%$searchFor1%'";
	}
	if ( !empty($_GET['searchFor2']) ){
		$searchFor2=check_input(trim($_GET['searchFor2'])); // trim()去空白
		$seacherTemp .= " AND `realname` LIKE '%$searchFor2%'";
	}
}

$searchstr .= $seacherTemp." ORDER BY `account`"; // 由account排列財產

$startNum = $displayNum * ($page-1); // 從第n*m筆資料開始，取n筆資料

$searchstr = $searchstr . " LIMIT $startNum, $displayNum";

$link_ID = connect_mysql(); // 連線資料庫
$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢，結果放入$searchlist

$max_index = mysql_num_rows($searchlist); // 查詢結果的資料筆數(rows)
// 不可改，即使上面用LIMIT，取得的資料可能比預期少(已取完)

for($i = 0; $i < $max_index; $i++){
	// 取出查詢結果，每執行一次就將一組row的值放入陣列$recordArray中
	$recordArray[$i] = mysql_fetch_array($searchlist);
}

// 計算使用者數
$searchstr = "SELECT COUNT(*) FROM `account_list` WHERE 1".$seacherTemp;

$searchlist = mysql_query($searchstr, $link_ID); // 送出查詢
$dataTotal = mysql_fetch_array($searchlist)[0]; // 取得資料總數
$pageTotal = ceil($dataTotal/$displayNum); // 計算總頁數

mysql_close($link_ID); // 資料庫斷開連結

// 目前頁面的網址，後面會使用
$URL = "manageUser.php?";
if ( !empty($_GET['searchFor1']) )
	$URL = $URL."searchFor1=".$searchFor1."&";
if ( !empty($_GET['searchFor2']) )
	$URL = $URL."searchFor2=".$searchFor2."&";
?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>管理帳號</title>
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
	// 將menuNum11 HighLight
	$("#menuNum11").addClass("active");
});

// 由於submit後，其他參數會消失，故用此方法保留參數
function search(){
	document.location.href = "manageUser.php?displayNum=<?php echo $displayNum;?>"
	+"&searchFor1="+ document.getElementById("searchFor1").value
	+"&searchFor2="+ document.getElementById("searchFor2").value;
}

// 確定對帳號執行動作
function show_confirm(account ,realname, actionType)
{
	var ans; 
	switch(actionType){
		case 1: ans = confirm("使用者"+account+" "+realname+"\n確定要刪除此帳號?"); break;
		case 2: ans = confirm("使用者"+account+" "+realname+"\n確定升級為管理員?"); break;
		case 3: ans = confirm("使用者"+account+" "+realname+"\n確定降級為使用者?"); break;
	}
	
	if (ans){
		document.getElementById("userID").value = account;
		document.getElementById("actionType").value = actionType;
		document.getElementById("formUserID").submit();
	}
	else
		return;
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
						<input type="text" id="searchFor1" class="form-control input-lg" placeholder='學號' value="<?php echo $searchFor1; ?>">
						<input type="text" id="searchFor2" class="form-control input-lg" placeholder='姓名' value="<?php echo $searchFor2; ?>">
						<button type="buttom" class="btn btn-default input-lg" onclick="search()"><span class="glyphicon glyphicon-search"></span></button>
					</span>
				</div>
				<div class="col-lg-4">
					<?php if($dataTotal==0) echo "查無使用者";
						  else echo "總共".$dataTotal."筆中的".($startNum+1)."~".($startNum+$max_index)."筆";
						  if($searchFor1!="" || $searchFor2!="")
							echo "<br><a href=\"manageUser.php?displayNum=".$displayNum."\">返回查看所有財產</a>";
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
						<?php if($dataTotal==0) exit(); // 若無財產，直接結束
						// 財產頁數少於21頁
						if($pageTotal < 21){ 
							for($i = 1; $i <= $pageTotal; $i++){
								if($i == $page)
									echo '<li class="active">';
								else
									echo '<li>';
								echo "<a href=\"".$URL."displayNum=".$displayNum."&page=".$i."\">".$i."</a></li>";
							}
						}
						// 財產頁數多於21頁
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
			<!-- 顯示申請資料 -->
			<div class="row">
				<div class="col-lg-12">
					<table class="table table-hover table-bordered">
						<thead>
							<tr class="info">
								<th width="15%">學號</th>
								<th width="13%">姓名</th>
								<th width="12%">電話號碼</th>
								<th width="20%">e-mail</th>
								<th width="20%">科系 / 年級</th>
								<th width="10%">權限等級</th>
								<th width="8%">刪除帳號</th>
							</tr>
						</thead>
						<tbody>
							<?php
							// 印出查詢結果
							for($i = 0; $i < $max_index; $i++){
							?>
							<tr>
								<td><?php echo $recordArray[$i]['account']; ?></td>
								<td><?php echo $recordArray[$i]['realname']; ?></td>
								<td><?php echo $recordArray[$i]['phone']; ?></td>
								<td><?php echo $recordArray[$i]['email']; ?></td>
								<td><?php echo $recordArray[$i]['department']." / ";
									switch($recordArray[$i]['grade']){
										case 1: echo "大一"; break;
										case 2: echo "大二"; break;
										case 3: echo "大三"; break;
										case 4: echo "大四"; break;
										case 5: echo "碩一"; break;
										case 6: echo "碩二"; break;
									};?>
								</td>
								<td><?php
									if($recordArray[$i]['level'] == 1){
										echo "租借者";?>
										<button type="button" class="btn btn-success" 
										onclick="show_confirm('<?php echo $recordArray[$i]['account'] ?>','<?php echo $recordArray[$i]['realname']; ?>',2)">
										升級管理員</button>
									<?php }
									elseif($recordArray[$i]['level'] == 2){
										echo "管理員";?>
										<button type="button" class="btn btn-danger" 
										onclick="show_confirm('<?php echo $recordArray[$i]['account'] ?>','<?php echo $recordArray[$i]['realname']; ?>',3)">
										降級租借者</button>
									<?php }
									else echo "最高管理員";
									?>
								</td>
								<td><?php if($recordArray[$i]['level'] < 3){ ?>
									<button type="button" class="btn btn-danger btn-block" 
									onclick="show_confirm('<?php echo $recordArray[$i]['account'] ?>','<?php echo $recordArray[$i]['realname']; ?>',1)">刪除</button>
									<?php } ?>
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
						<?php
						// 財產頁數少於21頁
						if($pageTotal < 21){ 
							for($i = 1; $i <= $pageTotal; $i++){
								if($i == $page)
									echo '<li class="active">';
								else
									echo '<li>';
								echo "<a href=\"".$URL."displayNum=".$displayNum."&page=".$i."\">".$i."</a></li>";
							}
						}
						// 財產頁數多於21頁
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
			
			<!-- 隱藏的表單，確定回應時，傳值到另一個頁面 -->
			<form id="formUserID" method="POST" action="change_user_status.php">
				<!-- 儲存使用者的帳號 -->
				<input type="hidden" name="userID" id="userID" value="">
				<!-- 儲存要執行的動作 1刪除帳號 2升級管理員 3降級租借者 -->
				<input type="hidden" name="actionType" id="actionType" value="">
			</form>
		</div>
	</div>
	<!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->

</body>

</html>

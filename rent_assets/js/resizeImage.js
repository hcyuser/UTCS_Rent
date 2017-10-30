// copy from http://www.minwt.com/webdesign-dev/js/2758.html
// 此為原本，系統中是修改過的版本

<script>
// 圖片縮圖
$(window).load(function(){
	$("img").each(function(i){
		if($(this).attr("alumb")=="true"){
			//移除目前設定的影像長寬
			$(this).removeAttr('width');
			$(this).removeAttr('height');
 
			//取得影像實際的長寬
			var imgW = $(this).width();
			var imgH = $(this).height();
 
			//計算縮放比例
			var w=$(this).attr("_w")/imgW;
			var h=$(this).attr("_h")/imgH;
			var pre=1;
			if(w>h){
				pre=h;
			}else{
				pre=w;
			}
 
			//設定目前的縮放比例
			$(this).width(imgW*pre);
			$(this).height(imgH*pre);
		}
	});
});
</script>
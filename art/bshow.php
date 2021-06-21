<?php
/*	整站系统 - 文章大页面显示模块 bshow.php
	最后修改: 2014.4.20
*/
require ('../admin/class/artclass.php');
$art = new art();

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title><?php echo $art->webname; ?></title>
<link href="<?php echo $art->skin_file; ?>" rel="stylesheet" />
</head>
<body>

<div id="container">
	<div id="top"><?php echo $art->mk_head(); ?></div>
	<div id="nav"><?php echo $art->mk_nav(1,$art->idx); ?></div>
	<div id="main" class="border" style="padding:10px 0px;"><?php echo "\n".$art->mk_cont()."\n\t\t"; ?></div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; ?></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="../pic/up.png" /></a></div>

<script language="javascript">

window.onscroll = function(){
	var sTop = 0;
	if(typeof window.pageYOffset != 'undefined'){
		sTop = window.pageYOffset;
	}else if(typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat'){
		sTop = document.documentElement.scrollTop;
	}else if(typeof document.body != 'undefined'){
		sTop = document.body.scrollTop;
	}
	document.getElementById('sDiv').style.display = sTop > 0 ? 'block' : 'none';
}

window.onload = function(){
	var oMain = document.getElementById('main');
	var oCon = document.getElementById('container');
	var wMain = oMain.scrollWidth;
	if(wMain > 1200){
		oCon.style.maxWidth = wMain + 30 + 'px';
		oCon.style.width =  wMain + 30 + 'px';
	}
}

var sub_num = 0;
function isub(){
	if(sub_num == 0){
		document.getElementById('okey').disabled = true;
		sub_num ++;
	}
}

</script>

</body>
</html>
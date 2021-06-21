<?php
/*	整站系统 - 文章显示模块 show.php for wap
	最后修改: 2015.5.22
*/
require ('class/artclass.php');
$art = new art();

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<meta name="viewport"content="width=device-width, initial-scale=1"/>
<title><?php echo $art->webname; ?></title>
<link href="style/wap.css" rel="stylesheet" />
</head>
<body>

<div id="container">
	<div id="nav"><?php echo $art->mk_nav(1,$art->idx); ?></div>
	<div id="main">
		<div id="maincont" style="padding:5px 0px;"><?php echo "\n".$art->mk_cont()."\n\t\t"; ?></div>
	</div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; $art->online(); ?></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="../pic/up.png" /></a></div>

<script src="style/dg.js"></script>
<script language="javascript">
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
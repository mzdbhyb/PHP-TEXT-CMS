<?php

/*	黑马整站系统	栏目导航文件 - index.php for wap
	修改时间 - 2015年4月25日
*/

require ('class/st_idxclass.php');
$web = new st_idx();
$id = isset($_GET['id']) ? $_GET['id'] : 0;

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<meta name="viewport"content="width=device-width, initial-scale=1"/>
<title><?php echo $web->webname; ?></title>
<link href="style/wap.css" rel="stylesheet" />
<style type="text/css">
	th { background: #aaa; color: #fff; font-weight:bold; padding: 8px;}
	td { padding: 4px; }
	.tab_mid { margin: 0px auto; width: 95%; }

</style>
</head>
<body>

<div id="container">
	<div id="nav"><?php echo $web->mk_nav(1,$id); ?></div>
	<div id="main">
		<div id="maincont" class="border" style="padding-top:10px;padding-bottom:10px;"><?php echo "\n".$web->mk_cont()."\n\t\t"; ?></div>
	</div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; $web->online(); ?></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="../pic/up.png" /></a></div>

<script src="style/dg.js"></script>

</body>
</html>
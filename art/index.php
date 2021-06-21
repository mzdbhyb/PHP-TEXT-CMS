<?php

/*	黑马整站系统	栏目导航文件 - index.php
	修改时间 - 2014年5月3日
*/

require ('../admin/class/st_idxclass.php');
$web = new st_idx();
$id = isset($_GET['id']) ? $_GET['id'] : 0;

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title><?php echo $web->webname; ?></title>
<link href="<?php print $web->skin_file; ?>" rel="stylesheet" />
<style type="text/css">
	th { background: #aaa; color: #fff; font-weight:bold; padding: 8px;}
	td { padding: 4px; }
	.tab_mid { margin: 0px auto; width: 95%; }

</style>
</head>
<body>

<div id="container">
	<div id="top"><?php echo $web->mk_head(); ?></div>
	<div id="nav"><?php echo $web->mk_nav(1,$id); ?></div>
	<div id="main">
		<div id="mainside" class="border">
			<?php echo $web->new_comm("·").$web->mk_lnk("◇"); $web->web_count(); ?>
		</div>
		<div id="maincont" class="border" style="padding-top:10px;padding-bottom:10px;"><?php echo "\n".$web->mk_cont()."\n\t\t"; ?></div>
	</div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; ?></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="../pic/up.png" /></a></div>

<script src="../skin/dg.js"></script>

</body>
</html>
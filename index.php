<?php

/*	黑马整站系统	首页文件 - index.php
	修改时间 - 2014年4月10日
*/

require ('admin/class/idxclass.php');
$web = new idx();

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title><?php echo $web->webname; ?></title>
<link href="<?php print $web->skin_file; ?>" rel="stylesheet" />
</head>
<body>

<div id="container">
	<div id="top"><?php echo $web->mk_head(); ?></div>
	<div id="nav"><?php echo $web->mk_nav(0); ?></div>
	<div id="main">
		<div id="mainside">
			<?php echo $web->new_comm("·").$web->mk_lnk("◇"); $web->web_count(); ?>
		</div>
		<div id="maincont">
			<div id="mmleft" class="cont"><?php echo $web->new_art(); ?></div>
			<div id="mmright" class="cont shadow">
				<strong>网站公告</strong><a title=" 关于系统 " href="./source/about.html"><img style="width:16px;height:16px;float:right;" src="pic/about.png" /></a>
				<?php echo $web->web_news("./source/webnews.txt"); ?>
			</div>
			<div id="mmbottom">
				<?php echo $web->web_news("./source/index.txt"); ?>
			</div>
		</div>
	</div>
	<div id="bottom"><?php include("source/foot.php"); echo $bottom; ?><br /><a href="./wap/">手机版</a></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="./pic/up.png" /></a></div>

<script src="./skin/dg.js"></script>

</body>
</html>
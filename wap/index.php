<?php

/*	马黑整站系统	首页文件 - index.php for wap
	修改时间 - 2015年4月22日
*/

require ('class/idxclass.php');
$web = new idx();

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<meta name="viewport"content="width=device-width, initial-scale=1"/>
<title><?php echo $web->webname; ?></title>
<link href="style/wap.css" rel="stylesheet" />
</head>
<body>

<div id="container">
	<div id="nav"><?php echo $web->mk_nav(0); ?></div>
	<div id="main">
		<div id="maincont" style="padding:0 5px;">
			<?php echo "<div class=\"title\">".$web->webname."</div>\t\t\t\n".$web->new_art();$web->web_count(); ?>
		</div>
	</div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; ?><br /><a href="../">电脑版</a></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="../pic/up.png" /></a></div>

<script src="style/dg.js"></script>

</body>
</html>
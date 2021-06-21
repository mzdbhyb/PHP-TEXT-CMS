<?php

/*	黑马整站系统	留言薄 - index.php
	修改时间 - 2014年5月3日
*/

require ('../admin/class/npadclass.php');
$npad = new npad();

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title><?php echo $npad->webname."留言薄"; ?></title>
<link href="<?php print $npad->skin_file; ?>" rel="stylesheet" />
</head>
<body>
<div id="container">
	<div id="top"><?php echo $npad->mk_head(); ?></div>
	<div id="nav"><?php echo $npad->mk_nav(5); ?></div>
	<div id="main">
		<div id="mainside" class="border">
<?php

echo $npad->mk_lnk("◇");
$npad->web_count();

?>
		</div>
		<div id="maincont" class="border">
<?php

$npad->show();

?>
		 </div>
	</div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; ?></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="../pic/up.png" /></a></div>

<script src="../skin/dg.js"></script>

</body>
</html>
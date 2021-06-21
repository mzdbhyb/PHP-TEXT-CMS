<?php

/*	黑马整站系统	留言薄 - index.php for wap
	修改时间 - 2015年4月25日
*/

require ('class/npadclass.php');
$npad = new npad();

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<meta name="viewport"content="width=device-width, initial-scale=1"/>
<title><?php echo $npad->webname."留言薄"; ?></title>
<link href="style/wap.css" rel="stylesheet" />
</head>
<body>
<div id="container">
	<div id="nav"><?php echo $npad->mk_nav(2); ?></div>
	<div id="main">
		<div id="maincont" class="border">
<?php

$npad->show();

?>
		 </div>
	</div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; $npad->online(); ?></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="../pic/up.png" /></a></div>

<script src="style/dg.js"></script>

</body>
</html>
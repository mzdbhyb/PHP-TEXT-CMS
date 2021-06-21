<?php
/*	整站系统 - 文章显示模块 show.php
	最后修改: 2014.5.3
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
	<div id="main">
		<div id="mainside" class="border">
			<?php echo $art->new_comm("·").$art->mk_lnk("◇"); $art->web_count(); ?>
		</div>
		<div id="maincont" class="border" style="padding:10px 0px;"><?php echo "\n".$art->mk_cont()."\n\t\t"; ?></div>
	</div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; ?></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="../pic/up.png" /></a></div>

<script src="../skin/dg.js"></script>
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
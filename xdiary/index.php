<?php
/*	黑马整站系统	xdiary - index.php
	修改时间 - 2014年5月3日
*/
require ("../admin/class/xdclass.php");
$xd=new xd();

$find=@$_GET['find'];
if($find!='') $find_flag=@$_SESSION['SGL_XDIARY'];

?>
<!doctype html>
<html lang="zh">
<head>
<meta charset="utf-8">
<title><?php echo $xd->webname; ?>日记</title>
<link href="<?php print $xd->skin_file; ?>" rel="stylesheet" />
<style type="text/css">
	#xdcont { width: 100%;border-bottom: 1px solid #aaa;}
	#xdcont td { padding: 4px 10px;background:#fff;border-left:1px solid #aaa;border-top:1px solid #aaa; }
</style>
</head>
<body>

<div id="container">
	<div id="top"><?php echo $xd->mk_head(); ?> </div>
	<div id="nav"><?php echo $xd->mk_nav(4); ?></div>
	<div id="main">
		<div id="mainside" class="border mid">

<?php

echo $xd->show_cal();
echo $xd->show_new_cmm();
echo "<p class=\"position\"><b>网站统计</b><br />".$xd->online()."</p>";
?>
		</div>
		<div id="maincont" class="border" style="padding: 0px 10px;">
<?php

echo "<p><span class=\"title\">".$xd->show_day()."</span><span class=\"fright\">[阅读 ".$xd->counter()."]</span></p>\n";
echo "<div class=\"cont\" style=\"padding:0px 20px;font-size:12pt;min-height:300px;\">$xd->content</div>";
echo "<p class=\"right position\">".$xd->xdnav()." &nbsp; &nbsp;</p>\n";
echo $xd->show_cmm();

?>
		</div>
	</div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; ?></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="../pic/up.png" /></a></div>

<script src="../skin/dg.js"></script>
<script language="javascript">

<?php
if(isset($find_flag)){
	echo "
function highlight(){
	var str=document.getElementById('show_diary').innerHTML;
	var pattern = new RegExp(\"$find_flag\", \"img\");
	var pool=[];
	var i=0;
	document.getElementById('show_diary').innerHTML=str.replace(/<[\/]{0,1}[a-z]+[^>]*>/img,function(){pool[pool.length]=arguments[0];return \"\%%%%\"}).replace(pattern,\"<span style='background-color:#ff9632;'>\"+\"$find_flag\"+\"</span>\").replace(/\%%%%/img,function(){return pool[i++]});
}
highlight();
";
}
?>

</script>

</body>
</html>
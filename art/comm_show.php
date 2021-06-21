<?php

require ('../admin/class/artclass.php');
$comm = new art();

$cmm_file = $comm->st."c/".$comm->art.".txt";
if(!is_file($comm->art_file) && !is_file($cmm_file)) die('file not found');

$ar = array_reverse(file($cmm_file));
$tt = count($ar);
$pnum = 15;
$pg = isset($_GET['pg']) ? (int)$_GET['pg'] : 0;
if($pg > $tt - 1) $pg = $tt - 1;
if($pg < 0) $pg = 0;
$comm->get_ip();

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<title>文章评论阅读</title>
<style type="text/css" rel="stylesheet">
@charset "utf-8";
body	{ margin:10px;padding:0px;background-color:#ffffff;color:#000;font-size:10pt; }
a:link, a:visited, a:active	{ text-decoration:none;color:#336AA3; }
a:hover	{ text-decoration:underline; }
table,td	{ padding:6px;font-family:'宋体','新宋体';color:#000;;font-size:10pt; }
img	{ border:0px; }
h2,h3	{ text-align: center; }
form { margin: 0; }
.editor { margin-top: 5px; margin-bottom: 5px; }
.mid	{ text-align:center; }
.right	{ text-align:right; }
</style>
</head>
<body>
<h2>文章评论阅读</h2>
<table style="margin:auto;width:1024px;padding:0px;"><tr>
	<td style="width:50%;">
<?php

include ($comm->art_file);
$art_url = "./".(isset($bs) ? "bshow.php" : "show.php")."?st=$comm->st&art=$comm->art";
echo "\t文章: <a href=\"$art_url\">$ftitle</a> [ 作者: ".$author." ]\n";

?>
	</td>
	<td style="width:50%;text-align:right;"><?php echo $comm->mkpage($tt,$pnum,$pg,"&st=$comm->st&art=$comm->art"); ?></td>
</tr></table>
<table style="margin:auto;width:1024px;">
	<tr style="background-color:#aaa;"><td style="width:80px;text-align:center;"><b>评论人</b></td><td style="width:120px;text-align:center;"><b>评论日期</b></td><td class="mid"><b>评论内容</b></td><td style="width:30px;text-align:center;"><b>操作</b></td></tr>
<?php

for($i=$pg*$pnum;$i<$pg*$pnum+$pnum;$i++){
	if($i<$tt){
		$idx = $tt - $i - 1;
		$del_str = $comm->is_user() > 1 ? "../admin/?id=".$comm->is_user()."&st=$comm->st&art=$comm->art&act=del&idx=$idx" : "";
		$del_str = $del_str != "" ? "<span class=\"fright\"><a href=\"".$del_str."\">删除</a></span>" : "删除";
		list($name,$cont,$time) = explode("#",$ar[$i]);
		echo "\t<tr style=\"background-color:#ddd;\"><td>".$name."</td><td>".trim($time)."</td><td>".$cont."</td><td>".$del_str."</td></tr>\n";
	}
}


?>

</table>
<table style="margin:auto;width:1024px;padding:0px;text-align:right;"><tr><td><?php echo $comm->mkpage($tt,$pnum,$pg,"&st=$comm->st&art=$comm->art"); ?></td></tr></table>
<p class="mid"><a href="../">返回首页</a></p>

</body>
</html>
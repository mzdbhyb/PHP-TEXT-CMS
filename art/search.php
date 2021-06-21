<?php
/*	整站系统文档 - search.php
	作用: 站内搜索
	修改时间: 2014.4.22
*/
require ('../admin/class/webclass.php');
$sfind = new web('..');

$state = isset($state) ? $state : 0;//0=动态
$sfind->sort_ar[$sfind->sort_all]=array("全部");

function find_key($ar,$kword,$jing=0) {
	$res = "\n<script language=\"javascript\">\n\nvar findstr=new Array();\n\n";
	$j = 0;
	for($i=0;$i<count($ar);$i++) {
		if(strstr(strtolower($ar[$i]),strtolower($kword))) {
			$GLOBALS["total"]++;
			@list($st,$a_fn,$a_title,$auth,$show_fn,$create_tm,$sd) = explode('#',trim($ar[$i]));
			$art_file = $show_fn == "b" ? "bshow.php" : "show.php";
			$sd = trim($sd);
			$uri = $jing == 0 ? "$art_file?st=$st&sd=$sd&art=$a_fn" : $sd."/".$a_fn.".html";
			$res .= "findstr[$j]='<a target=\"_blank\" title=\" 作者: ".$auth." \" href=\"./".$uri."\">".$a_title."</a><br />';\n";
			$j+=1;
		}
	}
	$res.="\n</script>\n";
	return $res;
}

function mkjs(){  //JS搜索分页引擎
	$js_str="
<script language=\"javascript\">

var total=findstr.length; //总条目
var pnum=15;
total%pnum==0 ? tt=total/pnum : tt=Math.ceil(total/pnum);
document.write(\"<p align='center'>页码: \");
for(i=0;i<tt;i++){
	k=i+1;
	document.write('<a href=\"#\" onclick=javascript:output(' + i + ');this.style.color=\"#888\";>' + k + '</a> ');
}
document.write(\"</p>\");

function output(id){
	mystr=\"\";
	k=0;
	for(i=id*pnum;i<id*pnum+pnum;i++){
		k=i+1;
		if(findstr[i]) mystr+=k + \" \" + findstr[i];
	}
	document.getElementById('msgshow').innerHTML=mystr;
}

output(0);

</script>\n";
	return $js_str;

}

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title>整站系统·搜索</title>
<link href="<?php print $sfind->skin_file; ?>" rel="stylesheet" />
<style type="text/css">
#mcont { margin: auto; width: 80%;padding: 10px; min-height: 560px; }
#tips { margin: 10px auto; width: 80%; padding: 20px; font-size: 13px; background: #eee; border: 1px solid #aaa; border-radius: 6px; box-shadow: 3px 3px 3px #aaa; }
</style>
</head>
<body>

<div id="container">
	<div id="top"><?php echo $sfind->mk_head(); ?></div>
	<div id="nav"><?php echo $sfind->mk_nav(6,0); ?></div>
	<div id="main" class="border">
<?php

$idx = isset($_POST['rd']) ? $_POST['rd'] : (isset($_GET['idx']) ? $_GET['idx'] : 0);//类别
if($idx < 0) $idx = 0;
if($idx > $sfind->sort_all) $idx = $sfind->sort_all;

if(isset($_GET['keyword'])){//URL关键词
	$keyword = @$_GET['keyword'];
	if($keyword != mb_convert_encoding(mb_convert_encoding($keyword,"GB2312","UTF-8"),"UTF-8","GB2312")) $keyword = mb_convert_encoding($keyword,'UTF-8','GB2312'); //编码处理
}else{
	$keyword = trim(@$_POST['as_q']);
}

echo "\t\t<div id=\"mcont\">\n\t\t\t<p class=\"title mid\">".$sfind->webname."·搜索</p>\n\t\t\t<form id=\"find\" name=\"find\" method=\"post\">\n\t\t\t\t<p>\n\t\t\t\t\t<input name=\"sitesearch\" value=\"".$_SERVER['HTTP_HOST']."\" type=\"hidden\" />\n";
for($i=0;$i<$sfind->sort_all+1;$i++) {
	echo "\t\t\t\t\t<input type=\"radio\" name=\"rd\" value=\"$i\"";
	if($idx == $i) echo " checked=\"true\"";
	echo " />".$sfind->sort_ar[$i][0]."\n";
}
echo "\t\t\t\t</p>\n\t\t\t\t<p>\n\t\t\t\t<label for=\"keyword\">请输入关键词：</label><input style=\"width: 230px;padding: 2px 4px 2px 4px;\" type=\"text\" name=\"as_q\" id=\"searched_content\" value=\"$keyword\" maxlength=\"20\" /> &nbsp;\n\t\t\t\t\t<input type=\"button\" value=\" 站内搜索 \" onclick=\"iSub('search.php');\" />&nbsp; \n\t\t\t\t\t<input type=\"button\" value=\" 谷歌搜索 \" onclick=\"iSub('http://www.google.com.hk/search');\" />\n\t\t\t\t</p>\n";
echo "\t\t\t</form>\n";

echo "\t\t\t<div id=\"tips\">\n";

if(!empty($keyword)){
	$total = 0;
	switch ($idx) {
		case $sfind->sort_all://搜索全部
			$temp_str = "";
			for($i=0;$i<count($sfind->sort_ar)-1;$i++) {
				$sf = $sfind->sort_ar[$i][1].".txt";
				if(file_exists($sf)) {
					$temp_str .= trim(implode('%%',file($sf))).'%%';
				}
			}
			$s_ar = explode('%%',$temp_str);
			break;
		default:  //搜索子项
			$sf = $sfind->sort_ar[$idx][1].".txt";
			if(file_exists($sf)) $s_ar = file($sf);
			break;
	}
	$msg = @find_key($s_ar,$keyword,$state);
	echo "\t<p>与关键词 <u>$keyword</u> 匹配的条目：</p>\n\t<blockquote id=\"msgshow\">\n";
	empty($total) ? $msg = "\t<p>在 <u>".$sfind->sort_ar[$idx][0]. "</u> 未找到任何与 <u>".$keyword."</u> 相匹配的条目\n\t</blockquote>\n" : $msg .= "\t</blockquote>\n\t<p>在 <u>".$sfind->sort_ar[$idx][0]."</u> 栏目共找到 <span style=\"color:red;\">".$total."</span> 条与 <u>".$keyword."</u> 相匹配的条目</p>\n". mkjs();
}else{
	$msg="\t\t\t\t<p>尚未开始查找</p>\n";
}

echo $msg."\t\t\t</div>\n\t\t</div>";

?>

	</div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; ?></div>
</div>

<script language="javascript">
function iSub(target){
	var oForm = document.getElementById('find');
	if(target != 'search.php') oForm.method = 'get';
	oForm.action = target;
	oForm.submit();
}
</script>

</body>
</html>
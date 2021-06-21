<?php

/*	XDiary单用户版 - 日记查找	find.php
	修改时间: 2011.10.13
*/

if(function_exists('date_default_timezone_set')) date_default_timezone_set('PRC');

$id=@$_GET['id'];
$year=@$_POST['year'];

if(empty($year)) $year=date("Y");
if(empty($year) or $year>date("Y")) $year=date("Y");
include ("../source/webconf.php");

function mkdate($y,$num,$index) {
	$milliseconds=mktime(0,0,0,1,1,0+$y) + $num * 86400;
	$msg="<a target=\"_blank\" href=\"./?year=$y&idx=$index&find=find\">".date('Y年n月j日',$milliseconds)."</a><br />";
	return $msg;
}

$keyword = strip_tags(@$_POST['keyword']);
if($keyword){
	session_start();
	$_SESSION['SGL_XDIARY']=$keyword;
}

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title>XDiary - <?php print $webname; ?>日记</title>
<style type="text/css">
	body { margin: 10px; background-color: #fff; color: #000; font-family: '新宋体', '宋体', Arial, Sans-Serif; font-size: 10pt; }
	a { color: #2065a4; background-color: #fff; text-decoration: none; }
	a:visited { color:#555;  }
	a:hover { text-decoration: underline; }
	#main { margin: 0 auto; line-height: 2; width: 900px; clear:both; }
	#log { margin: 0 auto; width:500px; padding: 10px; border: 1px solid #bbb; box-shadow: 4px 4px 4px #aaa; }
	.mid { text-align: center; }
</style>
</head>
<body>

<div id="main">
	<br />
	<h2 class="mid">X-Diary·日记搜索</h2>

<?php

print "\t<form name=\"find\" method=\"post\" action=\"find.php?id=search\" onsubmit=\"return check()\">\n\t\t<p style=\"text-align:center;\">请输入关键词：<input type=\"text\" name=\"keyword\" size=\"30\" value=\"$keyword\"> &nbsp;";
print "\t\t选择日记：<select size=\"1\" name=\"year\">";

for($i=$ybegin;$i<=date("Y");$i++){
	$i==$year ? print "\t\t\t<option value=\"$i\" selected>$i</option>\n" : print "\t\t\t<option value=\"$i\">$i</option>\n";
}

print "\t\t</select>&nbsp; &nbsp;<input type=\"submit\" value=\" 开始搜索 \" name=\"b1\"></p>\n\t</form>\n";

print "\t<div id=\"log\">\n";

if(empty($id)) {
	print "\t<p>尚未开始搜索。注意：<span style=\"color:red\">关键词区别大小写</span><br /><br /><br /></p>";
}elseif($id=='search') {
	$year=@$_POST['year'];
	$keyword=@$_POST['keyword'];
	$xdFile="data/".$year.".txt";
	is_file($xdFile) ? $xdconts=file($xdFile) : die("Error: 日记不存在");
	$k=0;
	for($i=0;$i<count($xdconts);$i++){
		if(strstr($xdconts[$i],$keyword)){
			$show[]=array($i,$xdconts[$i]);
			$k=$k+1;
		}
	}
	if(empty($k)) $k=0;
	print "\t\t<p>在 $year 年日记中共搜索到 <span style=\"color:red\">$k</span> 条记录：</p>\n\t\t<blockquote>\n";
	for($i=0;$i<$k;$i++){
		list($date,$other)=explode("%%",$show[$i][1]);
		$echstr=mkdate($year,$date,$show[$i][0]);
		print "\t\t\t·<span style=\"color:red\">$keyword</span> - ".$echstr;
	}
	if(empty($k)) print "\t\t\t<p>没有找到与 <span style=\"color:red\">$keyword</span> 相匹配的任何记录！</p>";
	print "\n\t\t</blockquote>\n";
}

print "\t</div>\n";


print "<p class=\"mid\"><a href=\"./\">返回日记</a></p>";

?>
</div>

<script language="JavaScript">
function check() {
if(document.find.keyword.value==""){alert("搜索内容不能为空!");return false;}
}
</script>

</body>
</html>
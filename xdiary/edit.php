<?php
/*	整站系统文档 - edit.php
	作用:  XDiary - 修改日记
	最后修改: 2014.4.27
*/
require ("../admin/class/xdclass.php");
$xd=new xd();

if($xd->log_in !="master" ) die("login error");
$id=@$_GET['id'];

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title>XDiary - <?php print $diary_name; ?>日记</title>
<style rel="stylesheet">
@charset "utf-8";
body	{ margin: 10px; font-size: 10pt;font-family: '新宋体', '宋体', Sans-Serif; }
a	{ color:blue;text-decoration: none; }
a:hover	{ color:#blue;text-decoration: underline; }
form	{ margin:0;text-align:left; }
h2	{ text-align:center; }
.okey	{ padding: 12px; border:1px solid #333;box-shadow: 3px 3px 3px #aaa; }
</style>
</head>
<body>

<table style="margin:auto;"><tr><td>
<?php

if(!isset($id)) { //修改日記

	print "
<script language=\"javascript\" src=\"../admin/xheditor/jquery.js\"></script>
<script language=\"javascript\" src=\"../admin/xheditor/xheditor.js\"></script>
<script language=\"javascript\">
$(pageInit);
function pageInit(){
	$('#elm1').xheditor({linkTag:true, internalScript:true,tools:'Source,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,Align,List,Outdent,Indent,Link,Unlink,Anchor,Img,Flash,Media,Hr,Emot,Table,Preview,About', upImgUrl:\"../admin/upload.php\",upImgExt:\"jpg,jpeg,gif,png\",onUpload:insertUpload});
}
function insertUpload(arrMsg){
	var i,msg;
	for(i=0;i<arrMsg.length;i++){
		msg=arrMsg[i];
		$(\"#uploadList\").append('<option value=\"'+msg.id+'\">'+msg.localname+'</option>');
	}
}
</script>
";

	$cont=htmlspecialchars($xd->content);
	echo "
	<h2>== 修改日记 ==</h2>
	<form name=\"xdEdt\" action=\"?id=save&year=$xd->year&idx=$xd->idx\" method=\"post\">
		<textarea id=\"elm1\" name=\"content\" style=\"width:800px;height:450px;\">$cont</textarea>
		<p style=\"text-align: right\"><input type=\"submit\" value=\" 修改完毕 \" name=\"artsub\" /> &nbsp;<a href=\"./\">返回日记</a></p>
	</form>
";

}elseif($id=='save') { //保存
	$newstr=$xd->txt2html(@$_POST['content'])."\n";
	if($newstr=="") $newstr="声明：此日记为空\n";
	$xd->ar[$xd->idx]=$xd->zday."%%".$newstr;
	$fn="data/".$xd->year.".txt";
	$fp=fopen($fn,"w");
	fwrite ($fp,implode("",$xd->ar));
	fclose ($fp);
	echo "
	<h2>保存修改</h2>
	<p class=\"okey\">日记已经提交!<br />系统将自动返回日记首页，您也可以点击以下链接立刻返回：<br /><br />&nbsp; &nbsp; [ <a title=\" 单击返回 \" href=\"./?year=$xd->year&idx=$xd->idx\">返回日记</a> ]</p>\n<script language=\"javascript\" type=\"text/javascript\">\nvar timerID=setTimeout(\"window.location='./?year=$xd->year&idx=$xd->idx'\",2000);\n</script>\n
";
}

?>
</td></tr></table>

</body>
</html>
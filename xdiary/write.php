<?php
/*	整站系统文档 - write.php
	作用:  XDiary - 写日记
	最后修改: 2014.4.27
*/

require ("../admin/class/xdclass.php");
$xd=new xd();

if($xd->log_in!="master") die("login error");
if($xd->write()!=1) die("a diary was written today! -> <a href=\"./\">back</a>");
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
a	{ color:blue; text-decoration: none; }
a:hover	{ color:#blue; text-decoration: underline; }
h2	{ text-align:center; }
form	{ margin: 0; text-align: left; }
.okey	{ padding: 12px; border:1px solid #333;box-shadow: 3px 3px 3px #aaa; }
</style>
<?php

if($id!='save'){
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

}
?>
</head>
<body>

<table style="margin:auto;"><tr><td>
<?php

if(!isset($id)) { //写日记

	$da=date("z");
	echo "
	<h2>== 写新日记 ==</h2>
	<form name=\"xdEdt\" action=\"?id=save\" method=\"post\">
		<input value=\"$da\" name=\"itime\" size=\"24\" type=\"hidden\" />
		<textarea id=\"elm1\" name=\"content\" style=\"width:800px;height:450px;\"></textarea>
		<p style=\"text-align: right;\"><input type=\"submit\" value=\" 发布日记 \" name=\"artsub\" /> &nbsp;<a href=\".\">返回日记</a></p>
	</form>
";

}elseif($id=="save"){ //保存
	$da=trim(@$_POST['itime']);
	$xd=$xd->txt2html(trim(@$_POST['content']));
	if(empty($xd)) $xd="声明：此日记为空";
	$dbFile="data/".date("Y").".txt";
	$xd=$da."%%".$xd."\n";
	$fp=fopen($dbFile,"a");
	fwrite($fp,$xd);
	fclose($fp);
	echo "
	<h2>保存日记</h2>
	<p class=\"okey\">日记已经提交!<br /><br />系统将自动返回日记首页，您也可以点击以下链接立刻返回：<br /><br />[ <a title=\" 点击返回 \" href=\"./\">返回日记</a> ]<br /></p>\n<script language=\"javascript\" type=\"text/javascript\">\nvar timerID=setTimeout(\"window.location='./'\",2000);\n</script>
";
}

?>
</td></tr></table>

</body>
</html><noscript><!--
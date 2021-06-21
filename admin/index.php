<?php
/*	整站系统 后台管理首页 index.php
	修改: 2014.4.17
*/
require ('class/adminclass.php');
$admin = new admin();

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<title>网站综合设置</title>
<style type="text/css" rel="stylesheet">
@charset "utf-8";
body { margin:10px;padding:0px;line-height:1.5;background-color:#ffffff;color:#000;font-size:13px; }
a:link, a:visited, a:active	{ text-decoration:none;color:#336AA3; }
a:hover { text-decoration:underline; }
table,td { padding:10px;font-family:'宋体','新宋体';color:#000;;font-size:10pt;line-height:1.5; }
img { border:0px; }
h2,h3 { text-align: center; }
form { margin: 0; }
fieldset { padding: 10px;width:360px;border:1px solid #ccc;box-shadow:3px 3px 3px #aaa; }
fieldset legend { text-align: left; }
fieldset table select { border:1px solid #ccc;width:200px; }
fieldset table input { width: 60px; }
textarea { font-size: 12px;}
.mid { text-align:center; }
.right { text-align:right; }
.red { color: red; }
#etop	 { padding:10px 4px ;width:890px; border:1px solid #ccc;border-bottom:0px;background:#f0f0f0; }
</style>
</head>
<body>

<h2>网站后台管理</h2>
<hr />
<table style="margin:auto;width:85%;min-width:1024px;"><tr>
	<td style="width:200px;background-color:#eee;color:#666;vertical-align:top;">
<?php

echo $admin->mk_items();

?>
	</td>
	<td style="vertical-align:top;">
<?php

$admin->mk_cont();

?>
	</td>
</tr></table>
<hr />
<p class="mid"><?php echo $admin->version; ?></p>

</body>
</html><
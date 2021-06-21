<?php
/*	整站系统 - 用户退出 logout.php
	修改时间: 2014.4.15
*/
include("../source/webconf.php");
session_start();
unset($_SESSION['SYS_admin']); //指定销毁
$head_file="../";


?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<title>登出管理</title>
<link href="../skin/default.css" rel="stylesheet" type="text/css" />
</head>
<body>

<p class="mid title" style="color:#ddd;">用户登录</p>
<div id="main" class="shadow" style="margin:auto;width:300px;padding:20px;">
	<p>已经成功退出!<br />系统将自动返回此前操作的页面，您也可以点击以下链接立刻返回：</p>
	<p class="mid">[ <a title=" 单击返回 " href="<?php echo $head_file; ?>">立即返回</a> ]</p>
</div>

<script language="javascript" type="text/javascript">

var timerID=setTimeout("window.location='<?php echo $head_file; ?>'",2000);

</script>

</body>
</html>

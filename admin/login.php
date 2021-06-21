<?php
/*	整站系统 - 用户登录 login.php
	修改时间: 2014.4.15
*/

$u_list_file = "../source/u_list.txt";

if(isset($_GET['do']) && $_GET['do'] == 'in'){;
	$identity = @$_POST['identity'];
	$pws = @$_POST['pws'];
	$conf_file = "../source/user/".($identity=="master" ? "master.php" : $identity.".php");
	include ($conf_file);
	session_start();
	if($pws == $logpass){
		$_SESSION['SYS_admin'] = $identity;
		$head_file="../";
		header("Location: $head_file");
	}else{	 	die ("<p align=\"center\">Error: user's name or password error!<script language=\"javascript\">setTimeout('history.go(-1)()',2000);</script></p>");
	}
}

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<title>登录后台</title>
<link href="../skin/default.css" rel="stylesheet" />
</head>
<body>
<p class="mid title" style="color:#ddd;">用户登录</p>
<div id="main" class="shadow" style="margin:auto;width:300px;padding:20px;">

<?php

if(!isset($_GET['do']) || $_GET['do'] != 'in'){
	include ("../source/user/master.php");
	$user_msg = "<option value=\"master\" selected=\"selected\"> ".$logname." </option>\n";
	if(is_file($u_list_file)){
		$u_msg_ar = file($u_list_file);
		for($i=0;$i<count($u_msg_ar);$i++){
			$tmp_ar = explode(" = ",$u_msg_ar[$i]);
			$user_msg .= "\t\t\t<option value=\"".trim($tmp_ar[0])."\"> ".trim($tmp_ar[1])." </option>\n";
		}
	}
	echo "
	<form method=\"post\" action=\"login.php?do=in\">
		<p>账号：<select name=\"identity\">
			$user_msg
		</select></p>
		<p><label for=\"pws\">密码：</label><input type=\"password\" required=\"true\" name=\"pws\" id=\"pws\" size=\"20\"></p>
		<p class=\"mid\"><input type=\"submit\" value=\" 登录 \" name=\"b1\"> &nbsp;<a href=\"../\">首页</a></p>
	</form>\n";
}

?>

</div>

</body>
</html>
<?php
/*	XDiary单用户版 - 删除评论 delcmm.php
	修改时间: 2011.10.10
*/

require ("../admin/class/xdclass.php");
$xd=new xd();

$id = @$_GET['id'];
if($id=='') die('Error: id is missing 1');

$xd_url = "./?year=$xd->year&idx=$xd->idx";

$cmm_file = "data/".$xd->year."cmm.txt";
if(is_file($cmm_file)) {
	$commar = file($cmm_file);
	$chk_new=trim($commar[$id]); //最新评论依据
	$commar[$id]="";
	$sav_str=implode("",$commar);
	if($sav_str=="") { //内容为空删除文件
		unlink($cmm_file);
	}else{ //否则保存记录文件
		$fp=fopen($cmm_file,'w');
		flock($fp,LOCK_EX);
		fwrite($fp,$sav_str);
		flock($fp,LOCK_UN);
		fclose($fp);
	}
}

$newcmm_file = "data/newcmm.txt"; //最新评论记录文档
if(is_file($newcmm_file)){
	$sav_str="";
	$new_cmm_ar=file($newcmm_file);
	for($i=0;$i<count($new_cmm_ar);$i++) {
		$tmp_cmm_ar=explode("%%",$new_cmm_ar[$i]);
		if(trim($tmp_cmm_ar[1]) != trim($chk_new)) $sav_str.=trim($new_cmm_ar[$i])."\n";
	}
	if($sav_str=="") { //内容为空删除之
		unlink($newcmm_file);
	}else{ //不不空则保存数据
		$fp=fopen($newcmm_file,'w');
		flock($fp,LOCK_EX);
		fwrite($fp,$sav_str);
		flock($fp,LOCK_UN);
		fclose($fp);
	}
}

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<?php echo "<meta http-equiv=refresh Content='2;url=".$xd_url."' />"; ?>
<title>删除评论</title>
<link href="xd.css" rel="stylesheet" />
</head>
<body>

<h2 class="mid">删除评论</h2>
<div id="log">
<?php

echo "\t<p>需要删除的评论已经操作完毕，请 <br /><br />&nbsp;&nbsp;&nbsp; >> <a href=\"$xd_url\">返回XDiary</a></p>";

?>

</div>

</body>
</html>

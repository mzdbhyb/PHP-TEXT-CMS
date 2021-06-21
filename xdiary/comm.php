<?php

/*	XDiary单用户版 - 评论模块: 处理提交来的评论 comm.php
	修改时间: 2011.10.10
*/

require ("../admin/class/xdclass.php");
$xd=new xd();

if($xd->xdiary == 0) die("Error: diary missing");
if($xd->art_pl == 0) die("Error: cmm denied");

$uid = session_id();
list($gid,$vnum) = explode("%%",@$_POST['gid']);
$vnum = trim($vnum);
$subtime = @$_SESSION['subtime'];
$val = @$_POST['val'];
$back="./?year=$xd->year&month=$xd->month&idx=$xd->idx#pl";
$back = "<script language=\"javascript\"> window.location.href='$back'; </script>";

if(time() - $subtime < 30 || $uid != $gid || $vnum != $val) die ($back);
$_SESSION['subtime'] = time();

$cmm_time=date("Y-n-j H:i");

$guest=trim(@$_POST['guest']);
$guest=strip_tags($guest);
$guest=str_replace("#","＃",$guest);
$guest=str_replace("%%","％％",$guest);
if(empty($guest)) $guest="匿名";

$comm_words=@$_POST['comm_words'];
$comm_words=strip_tags($comm_words); //去除HTML标签
$comm_words=trim($comm_words);
if(strlen($comm_words)>200) die('400 bytes limited !');
if(empty($comm_words)) die("Error - empty string");
$comm_words=str_replace("\n","",$comm_words);
$comm_words=str_replace("\r","",$comm_words);
$comm_words=str_replace("#","＃",$comm_words);
$comm_words=str_replace("%%","％％",$comm_words);
$comm_words=stripslashes($comm_words);
$comm_words=$xd->zday."#".$guest."[".$cmm_time."] &nbsp;&nbsp;".$comm_words."\n";

$commFile="data/".$xd->year."cmm.txt"; //评论记录文档
$fp=fopen($commFile,'a');
if(flock($fp,LOCK_EX)) {
	fwrite($fp,$comm_words);
	flock($fp,LOCK_UN);
}
fclose($fp);

$newest_file="data/newcmm.txt"; //最新评论记录文档
$sav_str="<a title=\"$cmm_time\" href=\"./?year=$xd->year&idx=$xd->idx\">".$guest."</a>%%".$comm_words;
if(is_file($newest_file)) {
	$old_ar=file($newest_file);
	for($i=0;$i<30;$i++) {
		if(isset($old_ar[$i])) $sav_str.=trim($old_ar[$i])."\n";
	}
}
$fp=fopen($newest_file,'w');
if(flock($fp,LOCK_EX)) {
	fwrite($fp,$sav_str);
	flock($fp,LOCK_UN);
}
fclose($fp);

echo $back;

?>

<?php
/*	整站系统文档 修改图片说明 edit.php
	修改时间: 2014.4.16
*/
@session_start();
if(!isset($_SESSION['SYS_admin'])) die('');
$id = (int)@$_POST['id'];
$msg = @$_POST['msg'];

if($id>=0 && $msg) {
	$msg = addslashes(str_replace('#','＃',$msg));
	$fn = '../upload/upadmin.txt';
	if(is_file($fn)) {
		$ar = file($fn);
		if($id >= 0 || $id < count($ar)) {
			$line_ar = explode('#',$ar[$id]);
			$ar[$id] = $line_ar[0]."#".$msg."\n";
			$fp = fopen($fn,'w');
			fwrite($fp,implode('',$ar));
			fclose($fp);
			$res = $msg;
		}
	}
}

echo (isset($res) ? $res : '');

?>
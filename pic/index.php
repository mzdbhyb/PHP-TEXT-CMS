<?php
/*	整站系统 图片展区 index.php
	作用: 集中展现站点图片
	修改: 2014.5.5
*/

require ('../admin/class/webclass.php');

class pic extends web{

	public $ar, $id,$pic_fn = "../upload/upadmin.txt", $candel = 0;

	public function __construct(){
		parent::__construct("..");
		$this->ar = is_file($this->pic_fn) ? file($this->pic_fn) : array('about.png','up.png','logo.gif');
		$this->id = isset($_GET['id']) ? (int)$_GET['id'] : mt_rand(0,count($this->ar)-1);
		if($this->id < 0) $this->id = 0;
		if($this->id > count($this->ar) - 1) $this->id = count($this->ar) - 1;
		if($this->log_in == 'master'){
			$this->candel = 1;
		}else{
			if($this->log_in != ""){
				$u_fn = "../source/user/".$this->log_in.".php";
				include ($u_fn);
				$this->candel = strstr($this->ar[$this->id],$this->log_in."_") ? 1 : 0;
			} 
		}
	}
}

$pic = new pic();

?>
<!doctype html>
<html lang="zh">
<head>
<meta charset="utf-8">
<title><?php print $pic->webname."·图片展区"; ?></title>
<link rel="stylesheet" type="text/css" href="<?php print $pic->skin_file; ?>" />
</head>
<body>

<div id="container">
	<div id="top"><?php echo $pic->mk_head(); ?></div>
	<div id="nav"><?php echo $pic->mk_nav(2,0); ?></div>
	<div id="main" class="mid">
<?php

$do = @$_GET['do'];
@list($pic_url,$pic_msg) = explode('#',trim($pic->ar[$pic->id]));
$pic_msg = isset($pic_msg) ? stripslashes(trim($pic_msg)) : end(explode('/',$pic_url));
if($do == 'del' && $pic->candel == 1){
	$pic->ar[$pic->id] = "";
	$pic->make_file($pic->pic_fn,implode("",$pic->ar));
	unlink($pic_url);
	echo "\n<script language=\"javascript\">window.location.href = './?id=$pic->id';</script>\n";
}else{
	$next=$pic->id+1;
	$pre=$pic->id-1;
	$pic_pre = ($pic->id > 0 ? "<a title=\"前一张\" href=\"?id=$pre\"> ← </a>&nbsp;" : "");
	$pic_next = ($pic->id < count($pic->ar) - 1 ? "<a title=\"下一张\" href=\"?id=$next\"> → </a>" : "");
	$delstr = $pic->candel == 1 ? "&nbsp;[&nbsp;<a href=\"./?id=$pic->id&do=del\">删除</a> <a href=\"javascript:edit_msg($pic->id)\">编辑</a>&nbsp;]" : "";
	echo "\t\t<p>".$pic_pre."共 ".count($pic->ar)." 张 当前第 ".($pic->id + 1)."　张&nbsp;".$pic_next.$delstr. "</p>\n";
	echo "\t\t<p><img id=\"pic\" style=\"position:relative;\" alt=\"Image\" title=\"$pic_msg\" src=\"$pic_url\" onmousemove=\"load_pg(this);\" /></p>\n\t\t<p id=\"pic_msg\">== $pic_msg ==</p>\n";
	$pic->online();
}

?>
	</div>
	<div id="bottom"><?php include("../source/foot.php"); echo $bottom; ?></div>
</div>

<div id="sDiv"><a href="javascript:scroll(0,0);"><img title=" 返回顶部 " src="../pic/up.png" /></a></div>

<script language="javascript">
<?php echo "\nvar total = ".count($pic->ar).";\nvar pre_pg = ".$pre.";\nvar next_pg = ".$next.";\n\n"; ?>
function load_pg(obj){
	var pWidth = obj.width;
	var idx = 2;
	obj.onmousemove = function(e){
		e = e||event;
		var offX = e.offsetX||e.layerX ;
		if(offX < pWidth/2){
			if(pre_pg >= 0){
				obj.style.cursor = 'url(pre.cur),auto';
				obj.title = '前一张';
			}else{
				obj.style.cursor = '';
				obj.title = '没有了';
			}
			idx = 0;
		}else{
			if(next_pg < total){
				obj.style.cursor = 'url(next.cur),auto';
				obj.title = '下一张';
			}else{
				obj.style.cursor = '';
				obj.title = '没有了';
			}
			idx = 1;
		}
	}
	obj.onmouseup = function(){
		if(pre_pg < 0) pre_pg = 0;
		if(next_pg > total-1) next_pg = total - 1;
		window.location.href = "?id=" + (idx == 0 ? pre_pg : next_pg);
	}
}

function edit_msg(picId){
	var picMsg = prompt('请输入图片说明: ','<?php echo addslashes($pic_msg); ?>');
	if(picMsg == null || picMsg == '') return;
	var sPic = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	sPic.open("POST","edit.php",true);
	sPic.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	sPic.send("id=" + picId + "&msg="  + picMsg + "");
	sPic.onreadystatechange = function(){
		if (sPic.readyState==4 && sPic.status==200){
			if(sPic.responseText) document.getElementById('pic_msg').innerHTML = '== ' + picMsg + ' ==';
		}
	}
}

window.onscroll = function(){
	var sTop = 0;
	if(typeof window.pageYOffset != 'undefined'){
		sTop = window.pageYOffset;
	}else if(typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat'){
		sTop = document.documentElement.scrollTop;
	}else if(typeof document.body != 'undefined'){
		sTop = document.body.scrollTop;
	}
	document.getElementById('sDiv').style.display = sTop > 0 ? 'block' : 'none';
}

window.onresize = window.onload = function(){
	var cDiv = document.getElementById('container');
	var pDiv = document.getElementById('pic');
	var pW = pDiv.scrollWidth;
	if(pW > 1200) {
		cDiv.style.maxWidth = pW + 'px';
		cDiv.style.width = pW + 'px';
	}
	document.getElementById('main').style.minHeight = 550 + 'px';
}

</script>

</body>
</html>
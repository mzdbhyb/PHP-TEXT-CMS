<?php
/*	整站系统类文档 - npadclass.php
	作用: 留言薄相关
	修改时间：2015.4.25
*/
require ('class/webclass.php');

class npad extends web{

	public $do = 0, $dbFile = "../npad/data.dat";

	function __construct($id=0){
		parent::__construct('..');
		$this->do = (int)@$_GET['do'];//0-显示留言；1-写留言；2-回复留言；3-删除留言
		if($this->do < 0 || $this->do > 3) $this->do = 0;
	}

	public function show(){//操作项目
		switch($this->do){
				case 1: echo $this->sav_npad(); break;//保存留言
				case 2: echo $this->reply_npad(); break;//回复留言
				case 3: echo $this->del_npad(); break;//删除留言
				default: echo $this->show_npad(); break;//显示留言
		}
	}

	public function mk_npad(){//签写留言
		$fst_num = rand(0,20);//验证算术题
		$sec_num = rand(0,20);
		$cal_ar = array("+","-");
		$cal_do = $fst_num > $sec_num ? $cal_ar[rand(0,1)] : "+";
		$gid = session_id()."%%".($cal_do == "+" ? $fst_num + $sec_num : $fst_num - $sec_num);//访客标识及骓码
		$val_str = $fst_num." ".$cal_do." ".$sec_num." = ？";
		return "
			<div id=\"comm\">&nbsp;&nbsp;&nbsp;<span class=\"red\">签写留言:</span><a name=\"npad\"></a>
				<form action=\"npad.php?do=1\" method=\"post\" name=\"npad_form\" id=\"npad_form\" onsubmit=\"return isub();\">
					<p class=\"mid\"><textarea style=\"width:80%;height:60px;padding:6px;border:1px solid #aaa;box-shadow:3px 3px 3px #aaa;\" onpropertychange=\"if(value.length>500)value=value.substr(0,500)\" name=\"words\" id=\"words\" placeholder=\"欢迎留言: 30秒间隔限制\" required=\"required\" ></textarea></p>
					<p><label for=\"guest\">姓名: </label><input name=\"guest\" id=\"guest\" size=\"8\" value=\"\" maxlength=\"8\" placeholder=\"匿名\" required=\"required\"  />&nbsp;&nbsp;<input type=\"submit\" value=\" 发布 \" name=\"btn\" id=\"btn\" /><br />
					<label for=\"vnum\">验证码: </label>&nbsp;<input id=\"vnum\" name=\"val\" size=\"4\" value =\"\" required=\"true\"  onfocus=\"javascript:document.getElementById('val').innerHTML='".$val_str."';\"  />&nbsp;<span id=\"val\"></span>&nbsp;<input type=\"hidden\" id=\"gid\" name=\"gid\" value=\"$gid\" /></p>
				</form>
			</div>
<script language=\"javascript\">
var sub_num = 0;
function isub(){
	if(sub_num == 0){
		document.getElementById('btn').disabled = true;
		sub_num ++;
	}
}
</script>\n";
	}

	public function sav_npad(){//保存留言
		$uid = session_id();
		list($gid,$vnum) = explode("%%",$_POST['gid']);
		$vnum =trim($vnum);
		$val = @$_POST['val'];
		$subtime = @$_SESSION['subtime'];
		if(time() - $subtime < 30 || $uid != $gid || $vnum != $val){
			return $this->back();
		}else{
			$guest = $this->rep_str(@$_POST['guest']);
			$words = $this->rep_str(@$_POST['words']);
			if($guest == "") $guest = "匿名";
			if($words == "") die ("<blockquote>留言内容不能为空，请返回 <a href=\"javascript:history.go(-1);\">重新操作</a></blockquote>");
			$savstr = $guest."%%".date('Y.n.j H:i')."%%".$words."%%%%".$this->get_ip()."\n";
			$this->make_file($this->dbFile,$savstr,'a');
			$this->new_pad();
			$_SESSION['subtime'] = time();
			return $this->back();
		}
	}

	public function del_npad(){//删除留言
		if($this->log_in != ""){
			$id = (int)@$_GET['id'];
			$pg = (int)@$_GET['pg'];
			$back = empty($pg) ? "npad.php" : "npad.php?pg=$pg";
			if(is_file($this->dbFile)){
				$ar = file($this->dbFile);
				$savstr = "";
				for($i=0;$i<count($ar);$i++){
					if($i != $id ) $savstr .= $ar[$i];
				}
			}
			$savstr != "" ? $this->make_file($this->dbFile,$savstr) : unlink($this->dbFile);
			$this->new_pad();
			return $this->back($back);
		}else{
			return $this->back('npad.php');
		}
	}

	public function reply_npad(){//回复留言
		if($this->log_in != ""){
			$act = @$_GET['act'];
			$pg = (int)@$_GET['pg'];
			$id = (int)@$_GET['id'];
			$back = empty($pg) ? "npad.php" : "npad.php?pg=$pg";
			$res = "\t\t\r<h3 class=\"mid\">回复留言</h3>\n";
			$ar = file($this->dbFile);
			if($id > count($ar)) die("错误参数");
			for($i=0;$i<count($ar);$i++){
				if($i == $id){
					list($name,$time,$cont,$r,$ip) = explode("%%",$ar[$id]);
					break;
				}
			}
			if($act == 'save'){
				$ar[$id] = $name."%%".$time."%%".$cont."%%".$this->rep_str(@$_POST['reply'],1)."%%".trim($ip)."\n";
				$this->make_file($this->dbFile,implode("",$ar));
				$this->new_pad();
				$res .= $this->back($back);
			}else{
				$res .="
			<table style=\"margin:auto;width:95%;background:#fefefe;border:1px solid #eee;border-radius: 5px;box-shadow:3px 3px 3px #ddd;\"><tr><td style=\"background:#ddd;padding:5px;\"># $id | 姓名: $name | ip: ".trim($ip)." 留言时间: ".$time."</td></tr>
				<tr><td style=\"padding:5px;\">$cont</td></tr>
			</table>
			<table style=\"margin:auto;width:95%;padding:10px;\"><tr><td>
				<form name=\"iform\" action=\"npad.php?pg=$pg&do=2&act=save&id=$id\" method=\"post\" onsubmit='return formCheck()'>
					<p class=\"mid\"><textarea style=\"margin:auto;padding:6px;width:90%;height:100px;\" name=\"reply\" id=\"reply\">$r</textarea></p>
					<p class=\"fright\"> <span style=\"font-size:10px;color:#888;\">[ 支持HTML回复 ]</span> <input type=\"submit\" value=\" 提交回复 \"> &nbsp;<a href=\"npad.php\">放弃回复</a>&nbsp;</p>
				</form>
			</td></tr></table>\n";
			}
		}else{
			$res = $this->back('npad.php');
		}
		return $res;
	}

	public function show_npad(){//显示留言
		is_file($this->dbFile) ? $ar = array_reverse(file($this->dbFile)) : $ar[0] = "黑马%%".date("Y.n.j")."%%欢迎使用黑马整站系统留言薄！%%%%%%";
		$tt = count($ar);
		$pg = (int)@$_GET['pg'];
		$un_reply = 0;
		if(!isset($pg) || $pg < 0) $pg = 0;
		$back = $pg == 0 ? "" : "&pg=$pg";
		$pnum = 8;
		$res = "\t\t\t<div class=\"position right\">".$this->mkpage($tt,$pnum,$pg)."</div>\n";
		for($i=$pg*$pnum;$i<$pg*$pnum+$pnum;$i++){
			if($i<$tt){
				$idx = $tt - $i;
				$lnk = $this->log_in != "" ? "<span class=\"fright\"><a href=\"npad.php?do=3&id=".($idx-1).$back."\">删除</a>&nbsp;<a href=\"npad.php?do=2&id=".($idx-1).$back."\">回复</a>" : "";
				list($name,$time,$cont,$reply,$ip) = explode("%%",$ar[$i]);
				$res .= "<table style=\"margin:auto;width:95%;background:#fefefe;border:1px solid #ddd;border-radius: 5px;box-shadow: 3px 3px 3px #ddd;\"><tr><td style=\"background:#ddd;padding:5px;\"># $idx | 姓名: $name | ip: ".(isset($this->sog_in) ? trim($ip) : "***")." 留言时间: ".$time.$lnk."</td></tr>\n<tr><td style=\"padding:5px;\">$cont<blockquote style=\"color:#0000ff;\"><b>回复</b>:".(isset($reply) ? $reply : "")."</blockquote></td></tr></table><br />\n";
			}
		}
		$res .= "\t\t\t<div class=\"position right\">".$this->mkpage($tt,$pnum,$pg)."</div>\n";
		return $res.$this->mk_npad();
	}

	public function new_pad(){//统计新留言
		$fn = "../npad/newpad.php";
		$num =0;
		if(is_file($this->dbFile)){
			$ar =file($this->dbFile);
			for($i=0;$i<count($ar);$i++){
				list(,,,$r,) = explode("%%",$ar[$i]);
				if($r == "") $num += 1;
			}
		}
		$savstr = '<?php $new = '.$num.'; ?>';
		$this->make_file($fn,$savstr);
	}

	public function back($id="npad.php") {//返回前页
		return "
			<script language=\"javascript\" type=\"text/javascript\">\nwindow.location.href='$id';\n</script>
			<blockquote><br />&nbsp; &nbsp; 操作完毕！请 <a href=\"$id\">立即返回</a></blockquote>\n";
	}

	public function rep_str($str,$id=0){//替换函数
		$str = $id == 0 ? strip_tags(trim($str)) : trim($str);
		$str = str_replace("\n","",$str);
		$str = str_replace("%%","％％",$str);
		$str = stripslashes($str);
		return $str;
	}

}

?>
